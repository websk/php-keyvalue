<?php

namespace WebSK\KeyValue\Tests;

use DI\Container;
use PHPUnit\Framework\TestCase;
use WebSK\CRUD\MutableContainerAdapter;
use WebSK\KeyValue\KeyValueRepository;
use WebSK\KeyValue\KeyValueService;
use WebSK\KeyValue\KeyValueServiceProvider;

final class KeyValueServiceProviderTest extends TestCase
{
    public function testRegistersDefinitionsInMutableContainer(): void
    {
        $container = new Container();
        KeyValueServiceProvider::register(new MutableContainerAdapter($container));

        self::assertTrue($container->has(KeyValueService::class));
        self::assertTrue($container->has(KeyValueRepository::class));
        self::assertTrue($container->has(KeyValueServiceProvider::DB_SERVICE_CONTAINER_ID));
    }

    public function testRejectsInvalidDatabaseConfiguration(): void
    {
        $container = new Container();
        $container->set('settings.db.db_keyvalue', ['host' => 123]);
        KeyValueServiceProvider::register(new MutableContainerAdapter($container));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('must contain string values');

        $container->get(KeyValueServiceProvider::DB_SERVICE_CONTAINER_ID);
    }
}
