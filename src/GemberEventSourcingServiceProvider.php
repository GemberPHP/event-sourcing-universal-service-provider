<?php

declare(strict_types=1);

namespace Gember\EventSourcingUniversalServiceProvider;

use Doctrine\DBAL\Connection;
use Gember\EventSourcing\EventStore\DomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\EventStore;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsDomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventStore;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventStoreRepository;
use Gember\EventSourcing\Registry\CommandHandler\Cached\CachedCommandHandlerRegistryDecorator;
use Gember\EventSourcing\Registry\CommandHandler\CommandHandlerRegistry;
use Gember\EventSourcing\Registry\CommandHandler\Reflector\ReflectorCommandHandlerRegistry;
use Gember\EventSourcing\Registry\Event\Cached\CachedEventRegistryDecorator;
use Gember\EventSourcing\Registry\Event\EventRegistry;
use Gember\EventSourcing\Registry\Event\Reflector\ReflectorEventRegistry;
use Gember\EventSourcing\Repository\UseCaseRepository;
use Gember\EventSourcing\Repository\EventSourced\EventSourcedUseCaseRepository;
use Gember\EventSourcing\Resolver\UseCase\CommandHandlers\Attribute\AttributeCommandHandlersResolver;
use Gember\EventSourcing\Resolver\UseCase\CommandHandlers\CommandHandlersResolver;
use Gember\EventSourcing\Resolver\UseCase\DomainTagProperties\Attribute\AttributeDomainTagsPropertiesResolver;
use Gember\EventSourcing\Resolver\UseCase\DomainTagProperties\DomainTagsPropertiesResolver;
use Gember\EventSourcing\Resolver\UseCase\SubscribedEvents\Attribute\AttributeSubscribedEventsResolver;
use Gember\EventSourcing\Resolver\UseCase\SubscribedEvents\SubscribedEventsResolver;
use Gember\EventSourcing\Resolver\UseCase\SubscriberMethodForEvent\Attribute\AttributeSubscriberMethodForEventResolver;
use Gember\EventSourcing\Resolver\UseCase\SubscriberMethodForEvent\SubscriberMethodForEventResolver;
use Gember\EventSourcing\Resolver\DomainMessage\DomainTags\Attribute\AttributeDomainTagsResolver;
use Gember\EventSourcing\Resolver\DomainMessage\DomainTags\DomainTagsResolver;
use Gember\EventSourcing\Resolver\DomainMessage\DomainTags\Interface\InterfaceDomainTagsResolver;
use Gember\EventSourcing\Resolver\DomainMessage\DomainTags\Stacked\StackedDomainTagsResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Attribute\AttributeNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\ClassName\ClassNameNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Interface\InterfaceNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\NormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Stacked\StackedNormalizedEventNameResolver;
use Gember\EventSourcing\UseCase\CommandHandler\UseCaseCommandHandler;
use Gember\EventSourcing\Util\Attribute\Resolver\AttributeResolver;
use Gember\EventSourcing\Util\Attribute\Resolver\Cached\CachedAttributeResolverDecorator;
use Gember\EventSourcing\Util\Attribute\Resolver\Reflector\ReflectorAttributeResolver;
use Gember\EventSourcing\Util\File\Finder\Finder;
use Gember\EventSourcing\Util\File\Finder\Native\NativeFinder;
use Gember\EventSourcing\Util\File\Reflector\Native\NativeReflector;
use Gember\EventSourcing\Util\File\Reflector\Reflector;
use Gember\EventSourcing\Util\Generator\Identity\IdentityGenerator;
use Gember\EventSourcing\Util\Messaging\MessageBus\EventBus;
use Gember\EventSourcing\Util\Serialization\Serializer\Serializer;
use Gember\EventSourcing\Util\String\FriendlyClassNamer\FriendlyClassNamer;
use Gember\EventSourcing\Util\String\FriendlyClassNamer\Native\NativeFriendlyClassNamer;
use Gember\EventSourcing\Util\String\Inflector\Inflector;
use Gember\EventSourcing\Util\String\Inflector\Native\NativeInflector;
use Gember\EventSourcing\Util\Time\Clock\Clock;
use Gember\EventSourcing\Util\Time\Clock\Native\NativeClock;
use Gember\IdentityGeneratorSymfony\Ulid\SymfonyUlidIdentityGenerator;
use Gember\IdentityGeneratorSymfony\Uuid\SymfonyUuidIdentityGenerator;
use Gember\MessageBusSymfony\SymfonyEventBus;
use Gember\RdbmsEventStoreDoctrineDbal\DoctrineDbalRdbmsEventFactory;
use Gember\RdbmsEventStoreDoctrineDbal\DoctrineDbalRdbmsEventStoreRepository;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\EventStoreRelationTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\EventStoreTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\TableSchemaFactory;
use Gember\SerializerSymfony\SymfonySerializer;
use Interop\Container\ServiceProviderInterface;
use Override;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Serializer as SerializerFromSymfony;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Factory\UlidFactory;
use Symfony\Component\Uid\Factory\UuidFactory;
use Exception;

/**
 * @phpstan-type ConfigurationPayload array{
 *      message_bus?: array{
 *          symfony?: array{
 *              event_bus?: MessageBusInterface
 *          }
 *      },
 *      cache?: array{
 *          enabled?: boolean,
 *          psr6?: CacheItemPoolInterface,
 *          psr16?: CacheInterface
 *      },
 *      serializer?: array{
 *          symfony?: array{
 *              serializer?: SerializerInterface
 *          }
 *      },
 *      event_store?: array{
 *          rdbms?: array{
 *              doctrine_dbal?: array{
 *                  connection?: Connection
 *              }
 *          }
 *      },
 *      generator?: array{
 *          identity?: array{
 *              service?: IdentityGenerator
 *          }
 *      },
 *      registry?: array{
 *          event?: array{
 *              reflector?: array{
 *                  path?: string
 *              }
 *          },
 *          command_handler?: array{
 *              reflector?: array{
 *                  path?: string
 *              }
 *          }
 *      }
 *  }
 */
final readonly class GemberEventSourcingServiceProvider implements ServiceProviderInterface
{
    #[Override]
    public function getFactories(): array
    {
        return [
            AttributeResolver::class => self::createAttributeResolver(...),
            Clock::class => self::createClock(...),
            DoctrineDbalRdbmsEventFactory::class => self::createDoctrineDbalRdbmsEventFactory(...),
            UseCaseRepository::class => self::createUseCaseRepository(...),
            DomainEventEnvelopeFactory::class => self::createDomainEventEnvelopeFactory(...),
            DomainTagsPropertiesResolver::class => self::createDomainTagsPropertiesResolver(...),
            DomainTagsResolver::class => self::createDomainTagsResolver(...),
            EventBus::class => self::createEventBus(...),
            EventRegistry::class => self::createEventRegistry(...),
            EventStore::class => self::createEventStore(...),
            EventStoreRelationTableSchema::class => self::createEventStoreRelationTableSchema(...),
            EventStoreTableSchema::class => self::createEventStoreTableSchema(...),
            Finder::class => self::createFileFinder(...),
            FriendlyClassNamer::class => self::createFriendlyClassNamer(...),
            IdentityGenerator::class => self::createIdentityGenerator(...),
            Inflector::class => self::createInflector(...),
            NormalizedEventNameResolver::class => self::createNormalizedEventNameResolver(...),
            RdbmsDomainEventEnvelopeFactory::class => self::createRdbmsDomainEventEnvelopeFactory(...),
            RdbmsEventFactory::class => self::createRdbmsEventFactory(...),
            RdbmsEventStoreRepository::class => self::createRdbmsEventStoreRepository(...),
            Reflector::class => self::createReflector(...),
            Serializer::class  => self::createSerializer(...),
            SubscribedEventsResolver::class => self::createSubscribedEventsResolver(...),
            SubscriberMethodForEventResolver::class => self::createSubscriberMethodForEventResolver(...),
            SymfonyUlidIdentityGenerator::class => self::createSymfonyUlidIdentityGenerator(...),
            SymfonyUuidIdentityGenerator::class => self::createSymfonyUuidIdentityGenerator(...),
            CommandHandlerRegistry::class => self::createCommandHandlerRegistry(...),
            CommandHandlersResolver::class => self::createCommandHandlersResolver(...),
            UseCaseCommandHandler::class => self::createUseCaseCommandHandler(...),
        ];
    }

    #[Override]
    public function getExtensions(): array
    {
        return [];
    }

    public static function createAttributeResolver(ContainerInterface $container): AttributeResolver
    {
        $cacheEnabled = self::getConfiguration($container)['cache']['enabled'] ?? false;

        $resolver = new ReflectorAttributeResolver();

        if ($cacheEnabled) {
            $psr6Adapter = self::getConfiguration($container)['cache']['psr6'] ?? null;

            if ($psr6Adapter !== null) {
                $cache = new Psr16Cache($psr6Adapter);
            } else {
                if (!isset(self::getConfiguration($container)['cache']['psr16'])) {
                    throw new Exception('Missing PSR-6 or PSR-16 cache adapter');
                }

                $cache = self::getConfiguration($container)['cache']['psr16'];
            }

            return new CachedAttributeResolverDecorator(
                $resolver,
                $container->get(FriendlyClassNamer::class),
                $cache,
            );
        }

        return $resolver;
    }

    public static function createClock(): Clock
    {
        return new NativeClock();
    }

    public static function createDoctrineDbalRdbmsEventFactory(): DoctrineDbalRdbmsEventFactory
    {
        return new DoctrineDbalRdbmsEventFactory();
    }

    public static function createUseCaseRepository(ContainerInterface $container): UseCaseRepository
    {
        return new EventSourcedUseCaseRepository(
            $container->get(EventStore::class),
            $container->get(DomainEventEnvelopeFactory::class),
            $container->get(SubscribedEventsResolver::class),
            $container->get(EventBus::class),
        );
    }

    public static function createDomainEventEnvelopeFactory(ContainerInterface $container): DomainEventEnvelopeFactory
    {
        return new DomainEventEnvelopeFactory(
            $container->get(DomainTagsResolver::class),
            $container->get(IdentityGenerator::class),
            $container->get(Clock::class),
        );
    }

    public static function createDomainTagsPropertiesResolver(ContainerInterface $container): DomainTagsPropertiesResolver
    {
        return new AttributeDomainTagsPropertiesResolver($container->get(AttributeResolver::class));
    }

    public static function createDomainTagsResolver(ContainerInterface $container): DomainTagsResolver
    {
        return new StackedDomainTagsResolver([
            new AttributeDomainTagsResolver($container->get(AttributeResolver::class)),
            new InterfaceDomainTagsResolver(),
        ]);
    }

    public static function createEventBus(ContainerInterface $container): EventBus
    {
        return new SymfonyEventBus(
            self::getConfiguration($container)['message_bus']['symfony']['event_bus']
            ?? $container->get('event.bus'),
        );
    }

    public static function createEventRegistry(ContainerInterface $container): EventRegistry
    {
        $cacheEnabled = self::getConfiguration($container)['cache']['enabled'] ?? false;

        $registry = new ReflectorEventRegistry(
            $container->get(Finder::class),
            $container->get(Reflector::class),
            $container->get(NormalizedEventNameResolver::class),
            self::getConfiguration($container)['registry']['event']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if ($cacheEnabled) {
            $psr6Adapter = self::getConfiguration($container)['cache']['psr6'] ?? null;

            if ($psr6Adapter !== null) {
                $cache = new Psr16Cache($psr6Adapter);
            } else {
                if (!isset(self::getConfiguration($container)['cache']['psr16'])) {
                    throw new Exception('Missing PSR-6 or PSR-16 cache adapter');
                }

                $cache = self::getConfiguration($container)['cache']['psr16'];
            }

            return new CachedEventRegistryDecorator($registry, $cache);
        }

        return $registry;
    }

    public static function createEventStore(ContainerInterface $container): EventStore
    {
        return new RdbmsEventStore(
            $container->get(NormalizedEventNameResolver::class),
            $container->get(RdbmsDomainEventEnvelopeFactory::class),
            $container->get(RdbmsEventFactory::class),
            $container->get(RdbmsEventStoreRepository::class),
        );
    }

    public static function createEventStoreRelationTableSchema(): EventStoreRelationTableSchema
    {
        return TableSchemaFactory::createDefaultEventStoreRelation();
    }

    public static function createEventStoreTableSchema(): EventStoreTableSchema
    {
        return TableSchemaFactory::createDefaultEventStore();
    }

    public static function createFileFinder(): Finder
    {
        return new NativeFinder();
    }

    public static function createFriendlyClassNamer(ContainerInterface $container): FriendlyClassNamer
    {
        return new NativeFriendlyClassNamer($container->get(Inflector::class));
    }

    public static function createIdentityGenerator(ContainerInterface $container): IdentityGenerator
    {
        return self::getConfiguration($container)['generator']['identity']['service']
            ?? $container->get(SymfonyUuidIdentityGenerator::class);
    }

    public static function createInflector(): Inflector
    {
        return new NativeInflector();
    }

    public static function createNormalizedEventNameResolver(ContainerInterface $container): NormalizedEventNameResolver
    {
        return new StackedNormalizedEventNameResolver([
            new AttributeNormalizedEventNameResolver($container->get(AttributeResolver::class)),
            new InterfaceNormalizedEventNameResolver(),
            new ClassNameNormalizedEventNameResolver($container->get(FriendlyClassNamer::class)),
        ]);
    }

    public static function createRdbmsDomainEventEnvelopeFactory(ContainerInterface $container): RdbmsDomainEventEnvelopeFactory
    {
        return new RdbmsDomainEventEnvelopeFactory(
            $container->get(Serializer::class),
            $container->get(EventRegistry::class),
        );
    }

    public static function createRdbmsEventFactory(ContainerInterface $container): RdbmsEventFactory
    {
        return new RdbmsEventFactory(
            $container->get(NormalizedEventNameResolver::class),
            $container->get(Serializer::class),
        );
    }

    public static function createRdbmsEventStoreRepository(ContainerInterface $container): RdbmsEventStoreRepository
    {
        return new DoctrineDbalRdbmsEventStoreRepository(
            self::getConfiguration($container)['event_store']['rdbms']['doctrine_dbal']['connection']
            ?? $container->get(Connection::class),
            $container->get(EventStoreTableSchema::class),
            $container->get(EventStoreRelationTableSchema::class),
            $container->get(DoctrineDbalRdbmsEventFactory::class),
        );
    }

    public static function createReflector(): Reflector
    {
        return new NativeReflector();
    }

    public static function createSerializer(ContainerInterface $container): Serializer
    {
        return new SymfonySerializer(
            self::getConfiguration($container)['serializer']['symfony']['serializer']
            ?? $container->get(SerializerFromSymfony::class),
        );
    }

    public static function createSubscribedEventsResolver(ContainerInterface $container): SubscribedEventsResolver
    {
        return new AttributeSubscribedEventsResolver($container->get(AttributeResolver::class));
    }

    public static function createSubscriberMethodForEventResolver(ContainerInterface $container): SubscriberMethodForEventResolver
    {
        return new AttributeSubscriberMethodForEventResolver($container->get(AttributeResolver::class));
    }

    public static function createSymfonyUlidIdentityGenerator(ContainerInterface $container): SymfonyUlidIdentityGenerator
    {
        return new SymfonyUlidIdentityGenerator($container->get(UlidFactory::class));
    }

    public static function createSymfonyUuidIdentityGenerator(ContainerInterface $container): SymfonyUuidIdentityGenerator
    {
        return new SymfonyUuidIdentityGenerator($container->get(UuidFactory::class));
    }

    public static function createCommandHandlerRegistry(ContainerInterface $container): CommandHandlerRegistry
    {
        $cacheEnabled = self::getConfiguration($container)['cache']['enabled'] ?? false;

        $registry = new ReflectorCommandHandlerRegistry(
            $container->get(Finder::class),
            $container->get(Reflector::class),
            $container->get(CommandHandlersResolver::class),
            self::getConfiguration($container)['registry']['command_handler']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if ($cacheEnabled) {
            $psr6Adapter = self::getConfiguration($container)['cache']['psr6'] ?? null;

            if ($psr6Adapter !== null) {
                $cache = new Psr16Cache($psr6Adapter);
            } else {
                if (!isset(self::getConfiguration($container)['cache']['psr16'])) {
                    throw new Exception('Missing PSR-6 or PSR-16 cache adapter');
                }

                $cache = self::getConfiguration($container)['cache']['psr16'];
            }

            return new CachedCommandHandlerRegistryDecorator($registry, $cache, $container->get(FriendlyClassNamer::class));
        }

        return $registry;
    }

    public static function createCommandHandlersResolver(ContainerInterface $container): CommandHandlersResolver
    {
        return new AttributeCommandHandlersResolver($container->get(AttributeResolver::class));
    }

    public static function createUseCaseCommandHandler(ContainerInterface $container): UseCaseCommandHandler
    {
        return new UseCaseCommandHandler(
            $container->get(UseCaseRepository::class),
            $container->get(CommandHandlersResolver::class),
            $container->get(DomainTagsResolver::class),
        );
    }

    /**
     * @return ConfigurationPayload
     */
    private static function getConfiguration(ContainerInterface $container): array
    {
        /** @var ConfigurationPayload */
        return $container->get('gember_event_sourcing');
    }
}
