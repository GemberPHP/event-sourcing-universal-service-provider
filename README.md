# 🫚 Gember Event Sourcing Universal Service Provider
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat)](http://www.php.net)

Universal Service Provider for [Gember Event Sourcing ](https://github.com/GemberPHP/event-sourcing), compatible with any application or framework that supports [container-interop/service-provider](https://github.com/container-interop/service-provider).

## Installation
Install the service provider with composer:

```bash
composer require gember/event-sourcing-universal-service-provider
```

## Configuration
This package installs _Gember Event Sourcing_ with all required dependency adapters. 
Some of these adapters need to be configured. 

By default, it uses the following configuration:

**PSR-11 Container definition (https://www.php-fig.org/psr/psr-11):**
```php
use Doctrine\DBAL\Connection;
use Gember\IdentityGeneratorSymfony\Ulid\SymfonyUuidIdentityGenerator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

return [
    'gember_event_sourcing' => fn(ContainerInterface $container) => [
        'message_bus' => [
            'symfony' => [
                'event_bus' => $container->get('event.bus'),
            ],
        ],
        'cache' => [
            'enabled' => false,
            // set a PSR-6 or PSR-16 implementation
            // 'psr16' => $container->get('cache.file'),
            // 'psr6' => $container->get('cache.file'),
        ],
        'serializer' => [
            'symfony' => [
                'serializer' => $container->get(Serializer::class),
            ],
        ],
        'event_store' => [
            'rdbms' => [
                'doctrine_dbal' => [
                    'connection' => $container->get(Connection::class),
                ],
            ],
        ],
        'generator' => [
            'identity' => [
                'service' => $container->get(SymfonyUuidIdentityGenerator::class),
                // or use ULIDs
                // 'service' => $container->get(SymfonyUlidIdentityGenerator::class),
            ],
        ],
        'registry' => [
            'event_registry' => [
                'reflector' => [
                    'path' => '/path/to/src',
                    // default: getcwd() . '/../src'
                ],
            ],
        ],
    ],
];
```

You can override any of these defaults however you like.

## Required dependencies

Some of the required dependencies also need to be configured separately.

### Symfony Messenger (`symfony/messenger`)
The default configuration makes use of a service with name `event.bus`.

When this bus is configured, _Gember Event Sourcing_ works out of the box.
However, when a different event bus is preferred, it must be a service implementing `Symfony\Component\Messenger\MessageBusInterface`.

Example (minimum) configuration for [symfony/messenger](https://github.com/symfony/messenger):

**PSR-11 Container definition (https://www.php-fig.org/psr/psr-11):**
```php
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

return [
    'event.bus' => fn(ContainerInterface $container) => new MessageBus([
        new HandleMessageMiddleware(new HandlersLocator([
            // Ideally this could be autoconfigured
            SomeDomainEvent::class => [
                new HandlerDescriptor([
                    $container->get(SomeProjector::class), 
                    'onSomeDomainEvent'
                ]),
            ],
        ]), allowNoHandlers: true),
    ]),
];
```

### Symfony Cache (`symfony/cache`)
The default configuration makes use of a service with name `cache.file`.

When this cache service is configured, _Gember Event Sourcing_ works out of the box.
However, when a different cache pool is preferred, it must be a service implementing `Psr\Cache\CacheItemPoolInterface` (PSR-6) or `Psr\SimpleCache\CacheInterface` (PSR-16).

Example (minimum) configuration for [symfony/cache](https://github.com/symfony/cache):

**PSR-11 Container definition (https://www.php-fig.org/psr/psr-11):**
```php
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

return [
    'cache.file' => fn() => new Psr16Cache(new FilesystemAdapter(
        directory: __DIR__ . '/../../var/cache',
    )),
];
```

### Symfony Serializer (`symfony/serializer`)
The default configuration makes use of a service with name `Symfony\Component\Serializer\Serializer`.

When this serializer service is configured, _Gember Event Sourcing_ works out of the box.
However, when a different serializer is preferred, it must be a service implementing `Symfony\Component\Serializer\SerializerInterface`.

Example (minimum) configuration for [symfony/serializer](https://github.com/symfony/serializer):

**PSR-11 Container definition (https://www.php-fig.org/psr/psr-11):**
```php
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

return [
    Serializer::class => fn() => new Serializer([
        new DateTimeNormalizer([
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.uP',
        ]),
        new ObjectNormalizer(),
    ], [
        new JsonEncoder(),
    ]),
];
```

### Doctrine DBAL/ORM (`doctrine/dbal`, `doctrine/orm`)
The default configuration makes use of a service with name `Doctrine\DBAL\Connection`.

When this connection service is configured, _Gember Event Sourcing_ works out of the box.
However, when a different Doctrine connection is preferred, it must be a service implementing `Doctrine\DBAL\Connection`.

Example (minimum) configuration for [doctrine/dbal](https://github.com/doctrine/dbal):

**PSR-11 Container definition (https://www.php-fig.org/psr/psr-11):**
```php
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

return [
    Connection::class => fn() => DriverManager::getConnection([
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_DBNAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'driver' => $_ENV['DB_DRIVER'],
        'charset' => $_ENV['DB_CHARSET'],
    ]),
];
```

## Database
In order to persist all domain events in database, a running SQL database is needed.
The event store requires two tables. Schema is available in either raw SQL or in a migration file format:

Raw SQL schema: https://github.com/GemberPHP/rdbms-event-store-doctrine-dbal/blob/main/resources/schema.sql

Migrations:
- Doctrine migrations: https://github.com/GemberPHP/rdbms-event-store-doctrine-dbal/blob/main/resources/migrations/doctrine
- Phinx migrations: https://github.com/GemberPHP/rdbms-event-store-doctrine-dbal/tree/main/resources/migrations/phinx

## Good to go!
Check the main package [gember/event-sourcing](https://github.com/GemberPHP/event-sourcing) or
the demo application [gember/example-event-sourcing-dcb](https://github.com/GemberPHP/example-event-sourcing-dcb) for examples.