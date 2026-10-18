<?php

namespace WebSK\KeyValue\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use WebSK\DB\DBService;
use WebSK\DB\DBServiceFactory;
use WebSK\KeyValue\KeyValue;
use WebSK\KeyValue\KeyValueRepository;

#[Group('mysql')]
final class KeyValueMySQLIntegrationTest extends TestCase
{
    private DBService $db_service;

    protected function setUp(): void
    {
        $host = getenv('MYSQL_TEST_HOST');
        $database = getenv('MYSQL_TEST_DB');
        $user = getenv('MYSQL_TEST_USER');
        $password = getenv('MYSQL_TEST_PASSWORD');

        if ($host === false || $database === false || $user === false || $password === false) {
            self::markTestSkipped('Set MYSQL_TEST_HOST, MYSQL_TEST_DB, MYSQL_TEST_USER and MYSQL_TEST_PASSWORD.');
        }

        $this->db_service = DBServiceFactory::factoryMySQL([
            'host' => $host,
            'db_name' => $database,
            'user' => $user,
            'password' => $password,
        ]);

        $this->db_service->query('DROP TABLE IF EXISTS `key_value`');
        $dump = file_get_contents(__DIR__ . '/../dumps/db_keyvalue.sql');
        if ($dump === false) {
            throw new \RuntimeException('Could not read the KeyValue database dump.');
        }
        foreach (array_filter(array_map('trim', explode(';', $dump))) as $query) {
            $this->db_service->query($query);
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->db_service)) {
            $this->db_service->query('DROP TABLE IF EXISTS `key_value`');
        }
    }

    public function testInsertReadUpdateAndDelete(): void
    {
        $repository = new KeyValueRepository(KeyValue::class, $this->db_service);
        $key_value = new KeyValue();
        $key_value->setName('feature');
        $key_value->setDescription('Feature flag');
        $key_value->setValue('enabled');

        $repository->save($key_value);
        $id = $key_value->getId();
        self::assertNotNull($id);
        self::assertSame($id, $repository->findIdByKey('feature'));

        $loaded = $repository->findById($id);
        self::assertInstanceOf(KeyValue::class, $loaded);
        self::assertSame('enabled', $loaded->getValue());

        $loaded->setName('renamed');
        $loaded->setValue('disabled');
        $repository->save($loaded);
        self::assertNull($repository->findIdByKey('feature'));
        self::assertSame($id, $repository->findIdByKey('renamed'));

        $repository->delete($loaded);
        self::assertNull($repository->findById($id));
    }
}
