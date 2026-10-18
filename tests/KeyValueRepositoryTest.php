<?php

namespace WebSK\KeyValue\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WebSK\DB\DBService;
use WebSK\KeyValue\KeyValue;
use WebSK\KeyValue\KeyValueRepository;

final class KeyValueRepositoryTest extends TestCase
{
    /** @return iterable<string, array{mixed, int|null}> */
    public static function validIds(): iterable
    {
        yield 'not found' => [false, null];
        yield 'integer' => [7, 7];
        yield 'integer string' => ['42', 42];
    }

    #[DataProvider('validIds')]
    public function testNormalizesDatabaseId(mixed $database_value, ?int $expected): void
    {
        $db_service = $this->createMock(DBService::class);
        $db_service->expects(self::once())
            ->method('readField')
            ->with(self::stringContains('WHERE name=?'), ['feature'])
            ->willReturn($database_value);

        $repository = new KeyValueRepository(KeyValue::class, $db_service);

        self::assertSame($expected, $repository->findIdByKey('feature'));
    }

    public function testRejectsInvalidDatabaseId(): void
    {
        $db_service = $this->createMock(DBService::class);
        $db_service->expects(self::once())->method('readField')->willReturn('not-an-id');
        $repository = new KeyValueRepository(KeyValue::class, $db_service);

        $this->expectException(\UnexpectedValueException::class);

        $repository->findIdByKey('feature');
    }
}
