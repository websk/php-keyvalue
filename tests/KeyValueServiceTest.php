<?php

namespace WebSK\KeyValue\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use WebSK\Cache\CacheService;
use WebSK\KeyValue\KeyValue;
use WebSK\KeyValue\KeyValueRepository;
use WebSK\KeyValue\KeyValueService;
use WebSK\KeyValue\Tests\Support\InMemoryCacheEngine;
use WebSK\KeyValue\Tests\Support\OtherEntity;
use WebSK\KeyValue\Tests\Support\TestKeyValue;
use WebSK\Logger\Entry\LoggerEntryService;

final class KeyValueServiceTest extends TestCase
{
    private InMemoryCacheEngine $cache_engine;
    private CacheService $cache_service;
    /** @var KeyValueRepository&MockObject */
    private KeyValueRepository $repository;

    /** @var LoggerEntryService&MockObject */
    private LoggerEntryService $logger_service;
    private KeyValueService $service;

    protected function setUp(): void
    {
        $this->cache_engine = new InMemoryCacheEngine();
        $this->cache_service = new CacheService($this->cache_engine);
        $this->repository = $this->createMock(KeyValueRepository::class);
        $this->logger_service = $this->createMock(LoggerEntryService::class);
        $this->service = new KeyValueService(
            KeyValue::class,
            $this->repository,
            $this->cache_service,
            $this->logger_service
        );
    }

    public function testReturnsCachedValueWithoutRepositoryLookup(): void
    {
        $this->cache_service->set('optional_value_for_key_feature', 'enabled');
        $this->repository->expects(self::never())->method('findIdByKey');
        $this->logger_service->expects(self::never())->method('logObjectEvent');

        self::assertSame('enabled', $this->service->getOptionalValueForKey('feature'));
    }

    public function testReturnsDefaultForMissingKey(): void
    {
        $this->repository->expects(self::once())
            ->method('findIdByKey')
            ->with('missing')
            ->willReturn(null);
        $this->logger_service->expects(self::never())->method('logObjectEvent');

        self::assertSame('fallback', $this->service->getOptionalValueForKey('missing', 'fallback'));
    }

    public function testDropsInvalidCachedValueBeforeLookup(): void
    {
        $this->cache_service->set('optional_value_for_key_feature', ['invalid']);
        $this->repository->expects(self::once())->method('findIdByKey')->willReturn(null);
        $this->logger_service->expects(self::never())->method('logObjectEvent');

        self::assertSame('', $this->service->getOptionalValueForKey('feature'));
        self::assertFalse($this->cache_engine->has('optional_value_for_key_feature'));
    }

    public function testLifecycleClearsEntityAndOptionalCachesAndWritesLog(): void
    {
        $stored = new TestKeyValue();
        $stored->setTestId(10);
        $stored->setName('old-name');
        $stored->setValue('old');

        $changed = new TestKeyValue();
        $changed->setTestId(10);
        $changed->setName('new-name');
        $changed->setValue('new');

        $this->cache_service->set(KeyValue::class . '::10', $changed);
        $this->cache_service->set('optional_value_for_key_old-name', 'old');
        $this->cache_service->set('optional_value_for_key_new-name', 'new');
        $this->repository->expects(self::once())->method('findById')->with(10)->willReturn($stored);

        $this->service->beforeSave($changed);

        self::assertFalse($this->cache_engine->has(KeyValue::class . '::10'));
        self::assertFalse($this->cache_engine->has('optional_value_for_key_old-name'));

        $this->logger_service->expects(self::once())
            ->method('logObjectEvent')
            ->with($changed, 'save', null);

        $this->service->afterSave($changed);

        self::assertFalse($this->cache_engine->has('optional_value_for_key_new-name'));
    }

    public function testRejectsIncompatibleEntity(): void
    {
        $entity = new OtherEntity();
        $this->repository->expects(self::never())->method('findById');
        $this->logger_service->expects(self::never())->method('logObjectEvent');

        $this->expectException(\InvalidArgumentException::class);

        $this->service->afterSave($entity);
    }

    public function testAfterDeleteClearsCachesAndWritesLog(): void
    {
        $key_value = new TestKeyValue();
        $key_value->setTestId(11);
        $key_value->setName('deleted');

        $this->cache_service->set(KeyValue::class . '::11', $key_value);
        $this->cache_service->set('optional_value_for_key_deleted', 'old');
        $this->repository->expects(self::never())->method('findById');
        $this->logger_service->expects(self::once())
            ->method('logObjectEvent')
            ->with($key_value, 'delete', null);

        $this->service->afterDelete($key_value);

        self::assertFalse($this->cache_engine->has(KeyValue::class . '::11'));
        self::assertFalse($this->cache_engine->has('optional_value_for_key_deleted'));
    }

    public function testPassesInjectedCurrentUserFullIdToLogger(): void
    {
        $key_value = new TestKeyValue();
        $key_value->setTestId(12);
        $key_value->setName('with-user');

        $service = new KeyValueService(
            KeyValue::class,
            $this->repository,
            $this->cache_service,
            $this->logger_service,
            static fn(): string => 'WebSK\\Auth\\User\\User.42'
        );

        $this->repository->expects(self::never())->method('findById');
        $this->logger_service->expects(self::once())
            ->method('logObjectEvent')
            ->with($key_value, 'delete', 'WebSK\\Auth\\User\\User.42');

        $service->afterDelete($key_value);
    }
}
