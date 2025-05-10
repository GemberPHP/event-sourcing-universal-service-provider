<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method Gember\\\\EventSourcingUniversalServiceProvider\\\\GemberEventSourcingServiceProvider\\:\\:createIdentityGenerator\\(\\) should return Gember\\\\EventSourcing\\\\Util\\\\Generator\\\\Identity\\\\IdentityGenerator but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainIds\\\\Attribute\\\\AttributeDomainIdsResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\NormalizedEventName\\\\Attribute\\\\AttributeNormalizedEventNameResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\DomainIdProperties\\\\Attribute\\\\AttributeDomainIdPropertiesResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\SubscribedEvents\\\\Attribute\\\\AttributeSubscribedEventsResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$attributeResolver of class Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\SubscriberMethodForEvent\\\\Attribute\\\\AttributeSubscriberMethodForEventResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\AttributeResolver, mixed given\\.$#',
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
	'message' => '#^Parameter \\#1 \\$eventBus of class Gember\\\\MessageBusSymfony\\\\SymfonyEventBus constructor expects Symfony\\\\Component\\\\Messenger\\\\MessageBusInterface, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$eventDomainIdsResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\DomainIds\\\\DomainIdsResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$eventNameResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\NormalizedEventName\\\\NormalizedEventNameResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$eventNameResolver of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\NormalizedEventName\\\\NormalizedEventNameResolver, mixed given\\.$#',
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
	'message' => '#^Parameter \\#1 \\$finder of class Gember\\\\EventSourcing\\\\Registry\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Finder\\\\Finder, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\NormalizedEventName\\\\ClassName\\\\ClassNameNormalizedEventNameResolver constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
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
	'message' => '#^Parameter \\#1 \\$serializer of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsDomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Util\\\\Serialization\\\\Serializer\\\\Serializer, mixed given\\.$#',
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
	'message' => '#^Parameter \\#2 \\$eventRegistry of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsDomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Registry\\\\EventRegistry, mixed given\\.$#',
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
	'message' => '#^Parameter \\#2 \\$friendlyClassNamer of class Gember\\\\EventSourcing\\\\Util\\\\Attribute\\\\Resolver\\\\Cached\\\\CachedAttributeResolverDecorator constructor expects Gember\\\\EventSourcing\\\\Util\\\\String\\\\FriendlyClassNamer\\\\FriendlyClassNamer, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$identityGenerator of class Gember\\\\EventSourcing\\\\EventStore\\\\DomainEventEnvelopeFactory constructor expects Gember\\\\EventSourcing\\\\Util\\\\Generator\\\\Identity\\\\IdentityGenerator, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$reflector of class Gember\\\\EventSourcing\\\\Registry\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Util\\\\File\\\\Reflector\\\\Reflector, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$serializer of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory constructor expects Gember\\\\EventSourcing\\\\Util\\\\Serialization\\\\Serializer\\\\Serializer, mixed given\\.$#',
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
	'message' => '#^Parameter \\#3 \\$eventNameResolver of class Gember\\\\EventSourcing\\\\Registry\\\\Reflector\\\\ReflectorEventRegistry constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\DomainEvent\\\\NormalizedEventName\\\\NormalizedEventNameResolver, mixed given\\.$#',
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
	'message' => '#^Parameter \\#3 \\$rdbmsEventFactory of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventFactory, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subscribedEventsResolver of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\EventSourcing\\\\Resolver\\\\UseCase\\\\SubscribedEvents\\\\SubscribedEventsResolver, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$eventBus of class Gember\\\\EventSourcing\\\\Repository\\\\EventSourced\\\\EventSourcedUseCaseRepository constructor expects Gember\\\\EventSourcing\\\\Util\\\\Messaging\\\\MessageBus\\\\EventBus, mixed given\\.$#',
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
	'message' => '#^Parameter \\#4 \\$repository of class Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStore constructor expects Gember\\\\EventSourcing\\\\EventStore\\\\Rdbms\\\\RdbmsEventStoreRepository, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/GemberEventSourcingServiceProvider.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
