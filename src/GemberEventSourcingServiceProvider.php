<?php

declare(strict_types=1);

namespace Gember\EventSourcingUniversalServiceProvider;

use Doctrine\DBAL\Connection;
use Exception;
use Gember\DependencyContracts\EventStore\Rdbms\RdbmsEventStoreRepository;
use Gember\DependencyContracts\EventStore\Saga\RdbmsSagaStoreRepository;
use Gember\DependencyContracts\Util\Generator\Identity\IdentityGenerator;
use Gember\DependencyContracts\Util\Messaging\MessageBus\CommandBus;
use Gember\DependencyContracts\Util\Messaging\MessageBus\EventBus;
use Gember\DependencyContracts\Util\Serialization\Serializer\Serializer;
use Gember\DependencyContracts\Util\Transaction\Transactional;
use Gember\EventSourcing\EventStore\DomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\EventStore;
use Gember\EventSourcing\EventStore\Loggable\LoggableEventStoreDecorator;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsDomainEventEnvelopeFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventFactory;
use Gember\EventSourcing\EventStore\Rdbms\RdbmsEventStore;
use Gember\EventSourcing\Outbox\Bus\OutboxCommandBus;
use Gember\EventSourcing\Outbox\Bus\OutboxEventBus;
use Gember\EventSourcing\Outbox\OutboxStore;
use Gember\EventSourcing\Outbox\Processor\Default\DefaultOutboxProcessor;
use Gember\EventSourcing\Outbox\Processor\OutboxProcessor;
use Gember\EventSourcing\Outbox\Rdbms\RdbmsOutboxStore;
use Gember\EventSourcing\Registry\CommandHandler\Cached\CachedCommandHandlerRegistryDecorator;
use Gember\EventSourcing\Registry\CommandHandler\CommandHandlerRegistry;
use Gember\EventSourcing\Registry\CommandHandler\Reflector\ReflectorCommandHandlerRegistry;
use Gember\EventSourcing\Registry\Event\Cached\CachedEventRegistryDecorator;
use Gember\EventSourcing\Registry\Event\EventRegistry;
use Gember\EventSourcing\Registry\Event\Reflector\ReflectorEventRegistry;
use Gember\EventSourcing\Registry\Saga\Cached\CachedSagaRegistryDecorator;
use Gember\EventSourcing\Registry\Saga\Reflector\ReflectorSagaRegistry;
use Gember\EventSourcing\Registry\Saga\SagaRegistry;
use Gember\EventSourcing\Repository\EventSourced\EventSourcedUseCaseRepository;
use Gember\EventSourcing\Repository\Rdbms\RdbmsSagaStore;
use Gember\EventSourcing\Repository\Rdbms\SagaFactory;
use Gember\EventSourcing\Repository\SagaStore;
use Gember\EventSourcing\Repository\Snapshot\SnapshotUseCaseRepositoryDecorator;
use Gember\EventSourcing\Repository\Transactional\TransactionalUseCaseRepositoryDecorator;
use Gember\EventSourcing\Repository\UseCaseRepository;
use Gember\EventSourcing\Resolver\Common\DomainTag\Attribute\AttributeDomainTagResolver;
use Gember\EventSourcing\Resolver\Common\DomainTag\DomainTagResolver;
use Gember\EventSourcing\Resolver\Common\DomainTag\Interface\InterfaceDomainTagResolver;
use Gember\EventSourcing\Resolver\Common\DomainTag\Stacked\StackedDomainTagResolver;
use Gember\EventSourcing\Resolver\Common\SagaId\Attribute\AttributeSagaIdResolver;
use Gember\EventSourcing\Resolver\Common\SagaId\SagaIdResolver;
use Gember\EventSourcing\Resolver\DomainCommand\Cached\CachedDomainCommandResolverDecorator;
use Gember\EventSourcing\Resolver\DomainCommand\Default\DefaultDomainCommandResolver;
use Gember\EventSourcing\Resolver\DomainCommand\DomainCommandResolver;
use Gember\EventSourcing\Resolver\DomainEvent\Cached\CachedDomainEventResolverDecorator;
use Gember\EventSourcing\Resolver\DomainEvent\Default\DefaultDomainEventResolver;
use Gember\EventSourcing\Resolver\DomainEvent\Default\EventName\Attribute\AttributeEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\Default\EventName\ClassName\ClassNameEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\Default\EventName\Interface\InterfaceEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\Default\EventName\Stacked\StackedEventNameResolver;
use Gember\EventSourcing\Resolver\DomainEvent\DomainEventResolver;
use Gember\EventSourcing\Resolver\Saga\Cached\CachedSagaResolverDecorator;
use Gember\EventSourcing\Resolver\Saga\Default\DefaultSagaResolver;
use Gember\EventSourcing\Resolver\Saga\Default\EventSubscriber\Attribute\AttributeSagaEventSubscriberResolver;
use Gember\EventSourcing\Resolver\Saga\Default\EventSubscriber\SagaEventSubscriberResolver;
use Gember\EventSourcing\Resolver\Saga\Default\SagaName\Attribute\AttributeSagaNameResolver;
use Gember\EventSourcing\Resolver\Saga\Default\SagaName\ClassName\ClassNameSagaNameResolver;
use Gember\EventSourcing\Resolver\Saga\Default\SagaName\Interface\InterfaceSagaNameResolver;
use Gember\EventSourcing\Resolver\Saga\Default\SagaName\SagaNameResolver;
use Gember\EventSourcing\Resolver\Saga\Default\SagaName\Stacked\StackedSagaNameResolver;
use Gember\EventSourcing\Resolver\Saga\SagaResolver;
use Gember\EventSourcing\Resolver\UseCase\Cached\CachedUseCaseResolverDecorator;
use Gember\EventSourcing\Resolver\UseCase\Default\CommandHandler\Attribute\AttributeCommandHandlerResolver;
use Gember\EventSourcing\Resolver\UseCase\Default\DefaultUseCaseResolver;
use Gember\EventSourcing\Resolver\UseCase\Default\EventSubscriber\Attribute\AttributeEventSubscriberResolver;
use Gember\EventSourcing\Resolver\UseCase\Default\Snapshot\Attribute\AttributeSnapshotResolver;
use Gember\EventSourcing\Resolver\UseCase\UseCaseResolver;
use Gember\EventSourcing\Saga\Default\DefaultSagaEventExecutor;
use Gember\EventSourcing\Saga\Loggable\LoggableSagaEventExecutorDecorator;
use Gember\EventSourcing\Saga\SagaEventExecutor;
use Gember\EventSourcing\Saga\SagaEventHandler;
use Gember\EventSourcing\Saga\Transactional\TransactionalSagaEventExecutorDecorator;
use Gember\EventSourcing\Snapshot\Loggable\LoggableSnapshotStoreDecorator;
use Gember\EventSourcing\Snapshot\Policy\AfterEventsSnapshotPolicy;
use Gember\EventSourcing\Snapshot\Policy\AfterSourcingTimeSnapshotPolicy;
use Gember\EventSourcing\Snapshot\Policy\OnEventsSnapshotPolicy;
use Gember\EventSourcing\Snapshot\Rdbms\RdbmsSnapshotStore;
use Gember\EventSourcing\Snapshot\SnapshotStore;
use Gember\EventSourcing\UseCase\CommandHandler\Default\DefaultUseCaseCommandExecutor;
use Gember\EventSourcing\UseCase\CommandHandler\Loggable\LoggableUseCaseCommandExecutorDecorator;
use Gember\EventSourcing\UseCase\CommandHandler\UseCaseCommandExecutor;
use Gember\EventSourcing\UseCase\CommandHandler\UseCaseCommandHandler;
use Gember\EventSourcing\Util\Attribute\Resolver\AttributeResolver;
use Gember\EventSourcing\Util\Attribute\Resolver\Reflector\ReflectorAttributeResolver;
use Gember\EventSourcing\Util\File\Finder\Finder;
use Gember\EventSourcing\Util\File\Finder\Native\NativeFinder;
use Gember\EventSourcing\Util\File\Reflector\Native\NativeReflector;
use Gember\EventSourcing\Util\File\Reflector\Reflector;
use Gember\EventSourcing\Util\Serialization\Serializer\Interface\SerializableInterfaceSerializer;
use Gember\EventSourcing\Util\Serialization\Serializer\Stacked\StackedSerializer;
use Gember\EventSourcing\Util\String\FriendlyClassNamer\FriendlyClassNamer;
use Gember\EventSourcing\Util\String\FriendlyClassNamer\Native\NativeFriendlyClassNamer;
use Gember\EventSourcing\Util\String\Inflector\Inflector;
use Gember\EventSourcing\Util\String\Inflector\Native\NativeInflector;
use Gember\EventSourcing\Util\Time\Clock\Clock;
use Gember\EventSourcing\Util\Time\Clock\Native\NativeClock;
use Gember\IdentityGeneratorSymfony\Ulid\SymfonyUlidIdentityGenerator;
use Gember\IdentityGeneratorSymfony\Uuid\SymfonyUuidIdentityGenerator;
use Gember\MessageBusSymfony\SymfonyCommandBus;
use Gember\MessageBusSymfony\SymfonyEventBus;
use Gember\RdbmsEventStoreDoctrineDbal\DoctrineDbalRdbmsEventFactory;
use Gember\RdbmsEventStoreDoctrineDbal\DoctrineDbalRdbmsEventStoreRepository;
use Gember\RdbmsEventStoreDoctrineDbal\Outbox\DoctrineDbalRdbmsOutboxFactory;
use Gember\RdbmsEventStoreDoctrineDbal\Outbox\DoctrineDbalRdbmsOutboxRepository;
use Gember\RdbmsEventStoreDoctrineDbal\Outbox\TableSchema\OutboxTableSchemaFactory;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\DoctrineDbalRdbmsSagaFactory;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\DoctrineRdbmsSagaStoreRepository;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\TableSchema\SagaStoreLockTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\TableSchema\SagaStoreRelationTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\TableSchema\SagaStoreTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\Saga\TableSchema\SagaTableSchemaFactory;
use Gember\RdbmsEventStoreDoctrineDbal\Snapshot\DoctrineDbalRdbmsSnapshotStoreRepository;
use Gember\RdbmsEventStoreDoctrineDbal\Snapshot\TableSchema\SnapshotStoreTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\Snapshot\TableSchema\SnapshotTableSchemaFactory;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\EventStoreLockTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\EventStoreRelationTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\EventStoreTableSchema;
use Gember\RdbmsEventStoreDoctrineDbal\TableSchema\TableSchemaFactory;
use Gember\RdbmsEventStoreDoctrineDbal\Transaction\DoctrineDbalTransactional;
use Gember\SerializerSymfony\SymfonySerializer;
use Interop\Container\ServiceProviderInterface;
use Override;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Serializer as SerializerFromSymfony;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Factory\UlidFactory;
use Symfony\Component\Uid\Factory\UuidFactory;

/**
 * @phpstan-type ConfigurationPayload array{
 *      message_bus?: array{
 *          symfony?: array{
 *              event_bus?: MessageBusInterface,
 *              command_bus?: MessageBusInterface
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
 *          },
 *          saga?: array{
 *              reflector?: array{
 *                  path?: string
 *              }
 *          }
 *      },
 *      logging?: array{
 *          logger?: LoggerInterface
 *      },
 *      snapshot?: array{
 *          enabled?: boolean
 *      },
 *      dispatch?: array{
 *          strategy?: 'direct'|'outbox',
 *          max_retries?: int
 *      }
 *  }
 */
final readonly class GemberEventSourcingServiceProvider implements ServiceProviderInterface
{
    #[Override]
    public function getFactories(): array
    {
        return [
            // Utilities
            AttributeResolver::class => self::createAttributeResolver(...),
            Clock::class => self::createClock(...),
            Finder::class => self::createFileFinder(...),
            Reflector::class => self::createReflector(...),
            FriendlyClassNamer::class => self::createFriendlyClassNamer(...),
            Inflector::class => self::createInflector(...),

            // Identity generators
            IdentityGenerator::class => self::createIdentityGenerator(...),
            SymfonyUuidIdentityGenerator::class => self::createSymfonyUuidIdentityGenerator(...),
            SymfonyUlidIdentityGenerator::class => self::createSymfonyUlidIdentityGenerator(...),

            // Serializer
            Serializer::class => self::createSerializer(...),

            // Message buses
            EventBus::class => self::createEventBus(...),
            CommandBus::class => self::createCommandBus(...),

            // Event store table schemas
            EventStoreTableSchema::class => self::createEventStoreTableSchema(...),
            EventStoreRelationTableSchema::class => self::createEventStoreRelationTableSchema(...),
            EventStoreLockTableSchema::class => self::createEventStoreLockTableSchema(...),

            // Event store
            DoctrineDbalRdbmsEventFactory::class => self::createDoctrineDbalRdbmsEventFactory(...),
            RdbmsEventStoreRepository::class => self::createRdbmsEventStoreRepository(...),
            RdbmsEventFactory::class => self::createRdbmsEventFactory(...),
            RdbmsDomainEventEnvelopeFactory::class => self::createRdbmsDomainEventEnvelopeFactory(...),
            DomainEventEnvelopeFactory::class => self::createDomainEventEnvelopeFactory(...),
            EventStore::class => self::createEventStore(...),

            // Resolvers
            DomainTagResolver::class => self::createDomainTagResolver(...),
            SagaIdResolver::class => self::createSagaIdResolver(...),
            DomainEventResolver::class => self::createDomainEventResolver(...),
            DomainCommandResolver::class => self::createDomainCommandResolver(...),
            UseCaseResolver::class => self::createUseCaseResolver(...),
            SagaNameResolver::class => self::createSagaNameResolver(...),
            SagaEventSubscriberResolver::class => self::createSagaEventSubscriberResolver(...),
            SagaResolver::class => self::createSagaResolver(...),

            // Registries
            EventRegistry::class => self::createEventRegistry(...),
            CommandHandlerRegistry::class => self::createCommandHandlerRegistry(...),
            SagaRegistry::class => self::createSagaRegistry(...),

            // Use case command handling
            UseCaseCommandExecutor::class => self::createUseCaseCommandExecutor(...),
            UseCaseCommandHandler::class => self::createUseCaseCommandHandler(...),

            // Use case repository
            UseCaseRepository::class => self::createUseCaseRepository(...),

            // Saga
            SagaFactory::class => self::createSagaFactory(...),
            SagaStoreTableSchema::class => self::createSagaStoreTableSchema(...),
            SagaStoreRelationTableSchema::class => self::createSagaStoreRelationTableSchema(...),
            SagaStoreLockTableSchema::class => self::createSagaStoreLockTableSchema(...),
            DoctrineDbalRdbmsSagaFactory::class => self::createDoctrineDbalRdbmsSagaFactory(...),
            RdbmsSagaStoreRepository::class => self::createRdbmsSagaStoreRepository(...),
            SagaStore::class => self::createSagaStore(...),
            SagaEventExecutor::class => self::createSagaEventExecutor(...),
            SagaEventHandler::class => self::createSagaEventHandler(...),

            // Snapshot
            SnapshotStoreTableSchema::class => self::createSnapshotStoreTableSchema(...),
            SnapshotStore::class => self::createSnapshotStore(...),

            // Outbox
            OutboxStore::class => self::createOutboxStore(...),
            OutboxProcessor::class => self::createOutboxProcessor(...),
            Transactional::class => self::createTransactional(...),
        ];
    }

    #[Override]
    public function getExtensions(): array
    {
        return [];
    }

    // -- Utilities --

    public static function createAttributeResolver(): AttributeResolver
    {
        return new ReflectorAttributeResolver();
    }

    public static function createClock(): Clock
    {
        return new NativeClock();
    }

    public static function createFileFinder(): Finder
    {
        return new NativeFinder();
    }

    public static function createReflector(): Reflector
    {
        return new NativeReflector();
    }

    public static function createFriendlyClassNamer(ContainerInterface $container): FriendlyClassNamer
    {
        return new NativeFriendlyClassNamer($container->get(Inflector::class));
    }

    public static function createInflector(): Inflector
    {
        return new NativeInflector();
    }

    // -- Identity generators --

    public static function createIdentityGenerator(ContainerInterface $container): IdentityGenerator
    {
        return self::getConfiguration($container)['generator']['identity']['service']
            ?? $container->get(SymfonyUuidIdentityGenerator::class);
    }

    public static function createSymfonyUuidIdentityGenerator(ContainerInterface $container): SymfonyUuidIdentityGenerator
    {
        return new SymfonyUuidIdentityGenerator($container->get(UuidFactory::class));
    }

    public static function createSymfonyUlidIdentityGenerator(ContainerInterface $container): SymfonyUlidIdentityGenerator
    {
        return new SymfonyUlidIdentityGenerator($container->get(UlidFactory::class));
    }

    // -- Serializer --

    public static function createSerializer(ContainerInterface $container): Serializer
    {
        return new StackedSerializer([
            new SerializableInterfaceSerializer(),
            new SymfonySerializer(
                self::getConfiguration($container)['serializer']['symfony']['serializer']
                ?? $container->get(SerializerFromSymfony::class),
            ),
        ]);
    }

    // -- Message buses --

    public static function createEventBus(ContainerInterface $container): EventBus
    {
        $config = self::getConfiguration($container);

        if (($config['dispatch']['strategy'] ?? 'direct') === 'outbox') {
            return new OutboxEventBus($container->get(OutboxStore::class));
        }

        return new SymfonyEventBus(
            $config['message_bus']['symfony']['event_bus']
            ?? $container->get('event.bus'),
        );
    }

    public static function createCommandBus(ContainerInterface $container): CommandBus
    {
        $config = self::getConfiguration($container);

        if (($config['dispatch']['strategy'] ?? 'direct') === 'outbox') {
            return new OutboxCommandBus($container->get(OutboxStore::class));
        }

        return new SymfonyCommandBus(
            $config['message_bus']['symfony']['command_bus']
            ?? $container->get('command.bus'),
        );
    }

    // -- Event store table schemas --

    public static function createEventStoreTableSchema(): EventStoreTableSchema
    {
        return TableSchemaFactory::createDefaultEventStore();
    }

    public static function createEventStoreRelationTableSchema(): EventStoreRelationTableSchema
    {
        return TableSchemaFactory::createDefaultEventStoreRelation();
    }

    public static function createEventStoreLockTableSchema(): EventStoreLockTableSchema
    {
        return TableSchemaFactory::createDefaultEventStoreLock();
    }

    // -- Event store --

    public static function createDoctrineDbalRdbmsEventFactory(): DoctrineDbalRdbmsEventFactory
    {
        return new DoctrineDbalRdbmsEventFactory();
    }

    public static function createRdbmsEventStoreRepository(ContainerInterface $container): RdbmsEventStoreRepository
    {
        return new DoctrineDbalRdbmsEventStoreRepository(
            self::getConnection($container),
            $container->get(EventStoreTableSchema::class),
            $container->get(EventStoreRelationTableSchema::class),
            $container->get(EventStoreLockTableSchema::class),
            $container->get(DoctrineDbalRdbmsEventFactory::class),
        );
    }

    public static function createRdbmsEventFactory(ContainerInterface $container): RdbmsEventFactory
    {
        return new RdbmsEventFactory(
            $container->get(DomainEventResolver::class),
            $container->get(Serializer::class),
        );
    }

    public static function createRdbmsDomainEventEnvelopeFactory(ContainerInterface $container): RdbmsDomainEventEnvelopeFactory
    {
        return new RdbmsDomainEventEnvelopeFactory(
            $container->get(Serializer::class),
            $container->get(EventRegistry::class),
        );
    }

    public static function createDomainEventEnvelopeFactory(ContainerInterface $container): DomainEventEnvelopeFactory
    {
        return new DomainEventEnvelopeFactory(
            $container->get(DomainEventResolver::class),
            $container->get(IdentityGenerator::class),
            $container->get(Clock::class),
        );
    }

    public static function createEventStore(ContainerInterface $container): EventStore
    {
        $eventStore = new RdbmsEventStore(
            $container->get(DomainEventResolver::class),
            $container->get(RdbmsDomainEventEnvelopeFactory::class),
            $container->get(RdbmsEventFactory::class),
            $container->get(RdbmsEventStoreRepository::class),
        );

        $logger = self::getLogger($container);

        if ($logger !== null) {
            return new LoggableEventStoreDecorator($eventStore, $logger);
        }

        return $eventStore;
    }

    // -- Resolvers --

    public static function createDomainTagResolver(ContainerInterface $container): DomainTagResolver
    {
        return new StackedDomainTagResolver([
            new AttributeDomainTagResolver($container->get(AttributeResolver::class)),
            new InterfaceDomainTagResolver(),
        ]);
    }

    public static function createSagaIdResolver(ContainerInterface $container): SagaIdResolver
    {
        return new AttributeSagaIdResolver($container->get(AttributeResolver::class));
    }

    public static function createDomainEventResolver(ContainerInterface $container): DomainEventResolver
    {
        $resolver = new DefaultDomainEventResolver(
            new StackedEventNameResolver(
                [
                    new AttributeEventNameResolver($container->get(AttributeResolver::class)),
                    new InterfaceEventNameResolver(),
                ],
                new ClassNameEventNameResolver($container->get(FriendlyClassNamer::class)),
            ),
            $container->get(DomainTagResolver::class),
            $container->get(SagaIdResolver::class),
        );

        if (self::isCacheEnabled($container)) {
            return new CachedDomainEventResolverDecorator(
                $resolver,
                self::getCache($container),
                $container->get(FriendlyClassNamer::class),
            );
        }

        return $resolver;
    }

    public static function createDomainCommandResolver(ContainerInterface $container): DomainCommandResolver
    {
        $resolver = new DefaultDomainCommandResolver(
            $container->get(DomainTagResolver::class),
        );

        if (self::isCacheEnabled($container)) {
            return new CachedDomainCommandResolverDecorator(
                $resolver,
                self::getCache($container),
                $container->get(FriendlyClassNamer::class),
            );
        }

        return $resolver;
    }

    public static function createUseCaseResolver(ContainerInterface $container): UseCaseResolver
    {
        $resolver = new DefaultUseCaseResolver(
            $container->get(DomainTagResolver::class),
            new AttributeCommandHandlerResolver($container->get(AttributeResolver::class)),
            new AttributeEventSubscriberResolver($container->get(AttributeResolver::class)),
            new AttributeSnapshotResolver($container->get(AttributeResolver::class)),
        );

        if (self::isCacheEnabled($container)) {
            return new CachedUseCaseResolverDecorator(
                $resolver,
                self::getCache($container),
                $container->get(FriendlyClassNamer::class),
            );
        }

        return $resolver;
    }

    public static function createSagaNameResolver(ContainerInterface $container): SagaNameResolver
    {
        return new StackedSagaNameResolver(
            [
                new AttributeSagaNameResolver($container->get(AttributeResolver::class)),
                new InterfaceSagaNameResolver(),
            ],
            new ClassNameSagaNameResolver($container->get(FriendlyClassNamer::class)),
        );
    }

    public static function createSagaEventSubscriberResolver(ContainerInterface $container): SagaEventSubscriberResolver
    {
        return new AttributeSagaEventSubscriberResolver($container->get(AttributeResolver::class));
    }

    public static function createSagaResolver(ContainerInterface $container): SagaResolver
    {
        $resolver = new DefaultSagaResolver(
            $container->get(SagaNameResolver::class),
            $container->get(SagaIdResolver::class),
            $container->get(SagaEventSubscriberResolver::class),
        );

        if (self::isCacheEnabled($container)) {
            return new CachedSagaResolverDecorator(
                $resolver,
                self::getCache($container),
                $container->get(FriendlyClassNamer::class),
            );
        }

        return $resolver;
    }

    // -- Registries --

    public static function createEventRegistry(ContainerInterface $container): EventRegistry
    {
        $registry = new ReflectorEventRegistry(
            $container->get(Finder::class),
            $container->get(Reflector::class),
            $container->get(DomainEventResolver::class),
            self::getConfiguration($container)['registry']['event']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if (self::isCacheEnabled($container)) {
            return new CachedEventRegistryDecorator($registry, self::getCache($container));
        }

        return $registry;
    }

    public static function createCommandHandlerRegistry(ContainerInterface $container): CommandHandlerRegistry
    {
        $registry = new ReflectorCommandHandlerRegistry(
            $container->get(Finder::class),
            $container->get(Reflector::class),
            $container->get(UseCaseResolver::class),
            self::getConfiguration($container)['registry']['command_handler']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if (self::isCacheEnabled($container)) {
            return new CachedCommandHandlerRegistryDecorator(
                $registry,
                self::getCache($container),
                $container->get(FriendlyClassNamer::class),
            );
        }

        return $registry;
    }

    public static function createSagaRegistry(ContainerInterface $container): SagaRegistry
    {
        $registry = new ReflectorSagaRegistry(
            $container->get(Finder::class),
            $container->get(Reflector::class),
            $container->get(SagaResolver::class),
            $container->get(SagaEventSubscriberResolver::class),
            self::getConfiguration($container)['registry']['saga']['reflector']['path']
            ?? getcwd() . '/../src',
        );

        if (self::isCacheEnabled($container)) {
            return new CachedSagaRegistryDecorator($registry, self::getCache($container));
        }

        return $registry;
    }

    // -- Use case command handling --

    public static function createUseCaseCommandExecutor(ContainerInterface $container): UseCaseCommandExecutor
    {
        $executor = new DefaultUseCaseCommandExecutor(
            $container->get(UseCaseRepository::class),
        );

        $logger = self::getLogger($container);

        if ($logger !== null) {
            return new LoggableUseCaseCommandExecutorDecorator($executor, $logger);
        }

        return $executor;
    }

    public static function createUseCaseCommandHandler(ContainerInterface $container): UseCaseCommandHandler
    {
        return new UseCaseCommandHandler(
            $container->get(CommandHandlerRegistry::class),
            $container->get(DomainCommandResolver::class),
            $container->get(UseCaseCommandExecutor::class),
        );
    }

    // -- Use case repository --

    public static function createUseCaseRepository(ContainerInterface $container): UseCaseRepository
    {
        $config = self::getConfiguration($container);

        $repository = new EventSourcedUseCaseRepository(
            $container->get(EventStore::class),
            $container->get(DomainEventEnvelopeFactory::class),
            $container->get(UseCaseResolver::class),
            $container->get(EventBus::class),
        );

        // Snapshot decorator (must be applied BEFORE transactional)
        if ($config['snapshot']['enabled'] ?? false) {
            $repository = new SnapshotUseCaseRepositoryDecorator(
                $repository,
                $container->get(EventStore::class),
                $container->get(UseCaseResolver::class),
                $container->get(SnapshotStore::class),
                $container->get(Serializer::class),
                [
                    new AfterEventsSnapshotPolicy(),
                    new AfterSourcingTimeSnapshotPolicy(),
                    new OnEventsSnapshotPolicy(),
                ],
                self::getLogger($container) ?? new NullLogger(),
            );
        }

        // Transactional decorator (when outbox enabled)
        if (($config['dispatch']['strategy'] ?? 'direct') === 'outbox') {
            $repository = new TransactionalUseCaseRepositoryDecorator(
                $repository,
                $container->get(Transactional::class),
            );
        }

        return $repository;
    }

    // -- Saga --

    public static function createSagaFactory(ContainerInterface $container): SagaFactory
    {
        return new SagaFactory($container->get(Serializer::class));
    }

    public static function createSagaStoreTableSchema(): SagaStoreTableSchema
    {
        return SagaTableSchemaFactory::createDefaultSagaStore();
    }

    public static function createSagaStoreRelationTableSchema(): SagaStoreRelationTableSchema
    {
        return SagaTableSchemaFactory::createDefaultSagaStoreRelation();
    }

    public static function createSagaStoreLockTableSchema(): SagaStoreLockTableSchema
    {
        return SagaTableSchemaFactory::createDefaultSagaStoreLock();
    }

    public static function createDoctrineDbalRdbmsSagaFactory(): DoctrineDbalRdbmsSagaFactory
    {
        return new DoctrineDbalRdbmsSagaFactory();
    }

    public static function createRdbmsSagaStoreRepository(ContainerInterface $container): RdbmsSagaStoreRepository
    {
        return new DoctrineRdbmsSagaStoreRepository(
            self::getConnection($container),
            $container->get(SagaStoreTableSchema::class),
            $container->get(SagaStoreRelationTableSchema::class),
            $container->get(SagaStoreLockTableSchema::class),
            $container->get(DoctrineDbalRdbmsSagaFactory::class),
            $container->get(IdentityGenerator::class),
        );
    }

    public static function createSagaStore(ContainerInterface $container): SagaStore
    {
        return new RdbmsSagaStore(
            $container->get(SagaResolver::class),
            $container->get(RdbmsSagaStoreRepository::class),
            $container->get(SagaFactory::class),
            $container->get(Serializer::class),
            $container->get(Clock::class),
        );
    }

    public static function createSagaEventExecutor(ContainerInterface $container): SagaEventExecutor
    {
        $config = self::getConfiguration($container);

        $executor = new DefaultSagaEventExecutor(
            $container->get(CommandBus::class),
            $container->get(SagaStore::class),
        );

        // Loggable decorator
        $logger = self::getLogger($container);

        if ($logger !== null) {
            $executor = new LoggableSagaEventExecutorDecorator($executor, $logger);
        }

        // Transactional decorator (when outbox enabled)
        if (($config['dispatch']['strategy'] ?? 'direct') === 'outbox') {
            $executor = new TransactionalSagaEventExecutorDecorator(
                $executor,
                $container->get(Transactional::class),
            );
        }

        return $executor;
    }

    public static function createSagaEventHandler(ContainerInterface $container): SagaEventHandler
    {
        return new SagaEventHandler(
            $container->get(DomainEventResolver::class),
            $container->get(SagaRegistry::class),
            $container->get(SagaEventExecutor::class),
        );
    }

    // -- Snapshot --

    public static function createSnapshotStoreTableSchema(): SnapshotStoreTableSchema
    {
        return SnapshotTableSchemaFactory::createDefault();
    }

    public static function createSnapshotStore(ContainerInterface $container): SnapshotStore
    {
        $snapshotStore = new RdbmsSnapshotStore(
            new DoctrineDbalRdbmsSnapshotStoreRepository(
                self::getConnection($container),
                $container->get(SnapshotStoreTableSchema::class),
            ),
        );

        $logger = self::getLogger($container);

        if ($logger !== null) {
            return new LoggableSnapshotStoreDecorator($snapshotStore, $logger);
        }

        return $snapshotStore;
    }

    // -- Outbox --

    public static function createOutboxStore(ContainerInterface $container): OutboxStore
    {
        return new RdbmsOutboxStore(
            new DoctrineDbalRdbmsOutboxRepository(
                self::getConnection($container),
                OutboxTableSchemaFactory::createDefault(),
                new DoctrineDbalRdbmsOutboxFactory(),
            ),
            $container->get(Serializer::class),
            $container->get(IdentityGenerator::class),
            $container->get(Clock::class),
        );
    }

    public static function createOutboxProcessor(ContainerInterface $container): OutboxProcessor
    {
        $config = self::getConfiguration($container);

        // The processor uses "real" buses (not outbox buses) for actual dispatch
        $realEventBus = new SymfonyEventBus(
            $config['message_bus']['symfony']['event_bus']
            ?? $container->get('event.bus'),
        );

        $realCommandBus = new SymfonyCommandBus(
            $config['message_bus']['symfony']['command_bus']
            ?? $container->get('command.bus'),
        );

        return new DefaultOutboxProcessor(
            $container->get(OutboxStore::class),
            $realEventBus,
            $realCommandBus,
            self::getLogger($container) ?? new NullLogger(),
            $config['dispatch']['max_retries'] ?? 5,
        );
    }

    public static function createTransactional(ContainerInterface $container): Transactional
    {
        return new DoctrineDbalTransactional(self::getConnection($container));
    }

    // -- Helpers --

    /**
     * @return ConfigurationPayload
     */
    private static function getConfiguration(ContainerInterface $container): array
    {
        /** @var ConfigurationPayload */
        return $container->get('gember_event_sourcing');
    }

    private static function getConnection(ContainerInterface $container): Connection
    {
        return self::getConfiguration($container)['event_store']['rdbms']['doctrine_dbal']['connection']
            ?? $container->get(Connection::class);
    }

    private static function isCacheEnabled(ContainerInterface $container): bool
    {
        return self::getConfiguration($container)['cache']['enabled'] ?? false;
    }

    private static function getCache(ContainerInterface $container): CacheInterface
    {
        $config = self::getConfiguration($container);
        $psr6Adapter = $config['cache']['psr6'] ?? null;

        if ($psr6Adapter !== null) {
            return new Psr16Cache($psr6Adapter);
        }

        if (!isset($config['cache']['psr16'])) {
            throw new Exception('Missing PSR-6 or PSR-16 cache adapter');
        }

        return $config['cache']['psr16'];
    }

    private static function getLogger(ContainerInterface $container): ?LoggerInterface
    {
        return self::getConfiguration($container)['logging']['logger'] ?? null;
    }
}
