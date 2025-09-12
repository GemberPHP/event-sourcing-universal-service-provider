<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method Gember\\\\EventSourcingUniversalServiceProvider\\\\GemberEventSourcingServiceProvider\\:\\:createIdentityGenerator\\(\\) should return Gember\\\\DependencyContracts\\\\Util\\\\Generator\\\\Identity\\\\IdentityGenerator but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\Common\\\\DomainTag\\\\Attribute\\\\AttributeDomainTagResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\Default\\\\EventName\\\\Attribute\\\\AttributeEventNameResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\Default\\\\CommandHandler\\\\Attribute\\\\AttributeCommandHandlerResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\Default\\\\EventSubscriber\\\\Attribute\\\\AttributeEventSubscriberResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$connection of class Gember\\\\RdbmsEventStoreDoctrineDbal\\\\DoctrineDbalRdbmsEventStoreRepository constructor expects Doctrine\\\\DBAL\\\\Connection, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$domainEventResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainEventResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$domainEventResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainEventResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$domainEventResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainEventResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$domainTagResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainCommand\\\\Default\\\\DefaultDomainCommandResolver constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\Common\\\\DomainTag\\\\DomainTagResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$domainTagResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\Default\\\\DefaultUseCaseResolver constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\Common\\\\DomainTag\\\\DomainTagResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$eventBus of class Gember\\\\MessageBusSymfony\\\\SymfonyEventBus constructor expects Symfony\\\\Component\\\\Messenger\\\\MessageBusInterface, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$eventStore of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\EventStore, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$finder of class Gember\\\\EventSourcing\\\\Registry\\\\CommandHandler\\\\Reflector\\\\ReflectorCommandHandlerRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Finder\\\\Finder, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$finder of class Gember\\\\EventSourcing\\\\Registry\\\\Event\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Finder\\\\Finder, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\Default\\\\EventName\\\\ClassName\\\\ClassNameEventNameResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$inflector of class Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\Native\\\\NativeFriendlyClassNamer constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\Inflector\\\\Inflector, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$repository of class Gember\\\\EventSourcing\\\\UseCase\\\\CommandHandler\\\\UseCaseCommandHandler constructor expects Gember\\\\EventSourcing\\\\Repository\\\\UseCaseRepository, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$serializer of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsDomainEventEnvelopeFactory constructor expects Gember\\\\DependencyContracts\\\\Util\\\\Serialization\\\\Serializer\\\\Serializer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$serializer of class Gember\\\\SerializerSymfony\\\\SymfonySerializer constructor expects Symfony\\\\Component\\\\Serializer\\\\SerializerInterface, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$ulidFactory of class Gember\\\\IdentityGeneratorSymfony\\\\Ulid\\\\SymfonyUlidIdentityGenerator constructor expects Symfony\\\\Component\\\\Uid\\\\Factory\\\\UlidFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$uuidFactory of class Gember\\\\IdentityGeneratorSymfony\\\\Uuid\\\\SymfonyUuidIdentityGenerator constructor expects Symfony\\\\Component\\\\Uid\\\\Factory\\\\UuidFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$commandHandlerRegistry of class Gember\\\\EventSourcing\\\\UseCase\\\\CommandHandler\\\\UseCaseCommandHandler constructor expects Gember\\\\EventSourcing\\\\Registry\\\\CommandHandler\\\\CommandHandlerRegistry, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$domainEventEnvelopeFactory of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsDomainEventEnvelopeFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$domainEventEnvelopeFactory of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$domainTagResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\Default\\\\DefaultDomainEventResolver constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\Common\\\\DomainTag\\\\DomainTagResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$eventRegistry of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsDomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Registry\\\\Event\\\\EventRegistry, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$eventStoreTableSchema of class Gember\\\\RdbmsEventStoreDoctrineDbal\\\\DoctrineDbalRdbmsEventStoreRepository constructor expects Gember\\\\RdbmsEventStoreDoctrineDbal\\\\TableSchema\\\\EventStoreTableSchema, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$identityGenerator of class Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory constructor expects Gember\\\\DependencyContracts\\\\Util\\\\Generator\\\\Identity\\\\IdentityGenerator, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$reflector of class Gember\\\\EventSourcing\\\\Registry\\\\CommandHandler\\\\Reflector\\\\ReflectorCommandHandlerRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Reflector\\\\Reflector, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$reflector of class Gember\\\\EventSourcing\\\\Registry\\\\Event\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Reflector\\\\Reflector, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$serializer of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory constructor expects Gember\\\\DependencyContracts\\\\Util\\\\Serialization\\\\Serializer\\\\Serializer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$clock of class Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Util\\\\Time\\\\Clock\\\\Clock, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$domainCommandResolver of class Gember\\\\EventSourcing\\\\UseCase\\\\CommandHandler\\\\UseCaseCommandHandler constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainCommand\\\\DomainCommandResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$domainEventResolver of class Gember\\\\EventSourcing\\\\Registry\\\\Event\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainEventResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$eventStoreRelationTableSchema of class Gember\\\\RdbmsEventStoreDoctrineDbal\\\\DoctrineDbalRdbmsEventStoreRepository constructor expects Gember\\\\RdbmsEventStoreDoctrineDbal\\\\TableSchema\\\\EventStoreRelationTableSchema, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Registry\\\\CommandHandler\\\\Cached\\\\CachedCommandHandlerRegistryDecorator constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainCommand\\\\Cached\\\\CachedDomainCommandResolverDecorator constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\Cached\\\\CachedDomainEventResolverDecorator constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\Cached\\\\CachedUseCaseResolverDecorator constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$rdbmsEventFactory of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$useCaseResolver of class Gember\\\\EventSourcing\\\\Registry\\\\CommandHandler\\\\Reflector\\\\ReflectorCommandHandlerRegistry constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\UseCaseResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$useCaseResolver of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\UseCaseResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$eventBus of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\DependencyContracts\\\\Util\\\\Messaging\\\\MessageBus\\\\EventBus, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$rdbmsEventFactory of class Gember\\\\RdbmsEventStoreDoctrineDbal\\\\DoctrineDbalRdbmsEventStoreRepository constructor expects Gember\\\\RdbmsEventStoreDoctrineDbal\\\\DoctrineDbalRdbmsEventFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$repository of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\DependencyContracts\\\\EventStore\\\\Rdbms\\\\RdbmsEventStoreRepository, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
