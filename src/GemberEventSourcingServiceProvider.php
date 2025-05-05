<?php

declare(strict_types=1);

namespace Gember\EventSourcingUniversalServiceProvider;

use Doctrine\DBAL\Connection;
use Gember\CachePsr\PsrSimpleCache;
use Gember\EventSourcing\EventStore\DomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\EventStore;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsDomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventStore;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventStoreRepository;
use Gember\EventSourcing\Registry\Cached\CachedEventRegistryDecorator;
use Gember\EventSourcing\Registry\EventRegistry;
use Gember\EventSourcing\Registry\Reflector\ReflectorEventRegistry;
use Gember\EventSourcing\Repository\DomainContextRepository;
use Gember\EventSourcing\Repository\EventSourced\EventSourcedDomainContextRepository;
use Gember\EventSourcing\Resolver\DomainContext\DomainIdProperties\Attribute\AttributeDomainIdPropertiesResolver;
use Gember\EventSourcing\Resolver\DomainContext\DomainIdProperties\DomainIdPropertiesResolver;
use Gember\EventSourcing\Resolver\DomainContext\SubscribedEvents\Attribute\AttributeSubscribedEventsResolver;
use Gember\EventSourcing\Resolver\DomainContext\SubscribedEvents\SubscribedEventsResolver;
use Gember\EventSourcing\Resolver\DomainContext\SubscriberMethodForEvent\Attribute\AttributeSubscriberMethodForEventResolver;
use Gember\EventSourcing\Resolver\DomainContext\SubscriberMethodForEvent\SubscriberMethodForEventResolver;
use Gember\EventSourcing\Resolver\DomainEvent\DomainIds\Attribute\AttributeDomainIdsResolver;
use Gember\EventSourcing\Resolver\DomainEvent\DomainIds\DomainIdsResolver;
use Gember\EventSourcing\Resolver\DomainEvent\DomainIds\Interface\InterfaceDomainIdsResolver;
use Gember\EventSourcing\Resolver\DomainEvent\DomainIds\Stacked\StackedDomainIdsResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Attribute\AttributeNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\ClassName\ClassNameNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Interface\InterfaceNormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\NormalizedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\NormalizedEventName\Stacked\StackedNormalizedEventNameResolver;
use Gember\EventSourcing\Util\Attribute\Resolver\AttributeResolver;
use Gember\EventSourcing\Util\Attribute\Resolver\Cached\CachedAttributeResolverDecorator;
use Gember\EventSourcing\Util\Attribute\Resolver\Reflector\ReflectorAttributeResolver;
use Gember\EventSourcing\Util\Cache\Cache;
use Gember\EventSourcing\Util\File\Finder\Finder;
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
use Gember\FileFinderSymfony\SymfonyFinder;
use Gember\FileFinderSymfony\SymfonyFinderFactory;
use Gember\FileReflectorRoave\RoaveBetterReflectionFactory;
use Gember\FileReflectorRoave\RoaveBetterReflectionReflector;
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
 *      event_registry?: array{
 *          reflector?: array{
 *              path?: string
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
            Cache::class => self::createCache(...),
            Clock::class => self::createClock(...),
            DoctrineDbalRdbmsEventFactory::class => self::createDoctrineDbalRdbmsEventFactory(...),
            DomainContextRepository::class => self::createDomainContextRepository(...),
            DomainEventEnvelopeFactory::class => self::createDomainEventEnvelopeFactory(...),
            DomainIdPropertiesResolver::class => self::createDomainIdPropertiesResolver(...),
            DomainIdsResolver::class => self::createDomainIdsResolver(...),
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
            return new CachedAttributeResolverDecorator(
                $resolver,
                $container->get(FriendlyClassNamer::class),
                $container->get(Cache::class),
            );
        }

        return $resolver;
    }

    public static function createCache(ContainerInterface $container): Cache
    {
        $psr6Adapter = self::getConfiguration($container)['cache']['psr6'] ?? null;

        if ($psr6Adapter !== null) {
            return new PsrSimpleCache(new Psr16Cache($psr6Adapter));
        }

        if (!isset(self::getConfiguration($container)['cache']['psr16'])) {
            throw new Exception('Missing PSR-6 or PSR-16 cache adapter');
        }

        return new PsrSimpleCache(self::getConfiguration($container)['cache']['psr16']);
    }

    public static function createClock(): Clock
    {
        return new NativeClock();
    }

    public static function createDoctrineDbalRdbmsEventFactory(): DoctrineDbalRdbmsEventFactory
    {
        return new DoctrineDbalRdbmsEventFactory();
    }

    public static function createDomainContextRepository(ContainerInterface $container): DomainContextRepository
    {
        return new EventSourcedDomainContextRepository(
            $container->get(EventStore::class),
            $container->get(DomainEventEnvelopeFactory::class),
            $container->get(SubscribedEventsResolver::class),
            $container->get(EventBus::class),
        );
    }

    public static function createDomainEventEnvelopeFactory(ContainerInterface $container): DomainEventEnvelopeFactory
    {
        return new DomainEventEnvelopeFactory(
            $container->get(DomainIdsResolver::class),
            $container->get(IdentityGenerator::class),
            $container->get(Clock::class),
        );
    }

    public static function createDomainIdPropertiesResolver(ContainerInterface $container): DomainIdPropertiesResolver
    {
        return new AttributeDomainIdPropertiesResolver($container->get(AttributeResolver::class));
    }

    public static function createDomainIdsResolver(ContainerInterface $container): DomainIdsResolver
    {
        return new StackedDomainIdsResolver([
            new AttributeDomainIdsResolver($container->get(AttributeResolver::class)),
            new InterfaceDomainIdsResolver(),
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
            self::getConfiguration($container)['event_registry']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if ($cacheEnabled) {
            return new CachedEventRegistryDecorator($registry, $container->get(Cache::class));
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
        return new SymfonyFinder(new SymfonyFinderFactory());
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
        return new RoaveBetterReflectionReflector(new RoaveBetterReflectionFactory());
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

    /**
     * @return ConfigurationPayload
     */
    private static function getConfiguration(ContainerInterface $container): array
    {
        /** @var ConfigurationPayload */
        return $container->get('gember_event_sourcing');
    }
}
