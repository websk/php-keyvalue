<?php

namespace WebSK\KeyValue;

use WebSK\Cache\CacheService;
use WebSK\Entity\EntityService;
use WebSK\Entity\InterfaceEntity;
use WebSK\Logger\Entry\LoggerEntryService;

/**
 * Class KeyValueService
 * @package WebSK\KeyValue
 */
class KeyValueService extends EntityService
{
    const int OPTIONAL_VALUES_CACHE_TTL_SEC = 60;

    private KeyValueRepository $key_value_repository;
    private LoggerEntryService $logger_entry_service;

    /** @var \Closure(): ?string */
    private \Closure $current_user_full_id_provider;

    /**
     * @param class-string<KeyValue> $entity_class_name
     * @param (\Closure(): ?string)|null $current_user_full_id_provider
     */
    public function __construct(
        string $entity_class_name,
        KeyValueRepository $repository,
        CacheService $cache_service,
        LoggerEntryService $logger_entry_service,
        ?\Closure $current_user_full_id_provider = null
    ) {
        parent::__construct($entity_class_name, $repository, $cache_service);
        $this->key_value_repository = $repository;
        $this->logger_entry_service = $logger_entry_service;
        $this->current_user_full_id_provider = $current_user_full_id_provider
            ?? static fn(): null => null;
    }

    public function getById(?int $entity_id, bool $exception_if_not_loaded = true): ?KeyValue
    {
        $entity = parent::getById($entity_id, $exception_if_not_loaded);
        if ($entity === null) {
            return null;
        }
        if (!$entity instanceof KeyValue) {
            throw new \UnexpectedValueException('KeyValue repository returned an incompatible entity.');
        }

        return $entity;
    }

    /**
     * @param string $key
     * @param string $default_value
     * @return string
     */
    public function getOptionalValueForKey(string $key, string $default_value = ''): string
    {
        $cache_key = $this->getOptionalValueForKeyCacheKey($key);

        $cached = $this->cache_service->get($cache_key);
        if (is_string($cached)) {
            return $cached;
        }
        if ($cached !== false) {
            $this->cache_service->delete($cache_key);
        }

        $key_value_id = $this->key_value_repository->findIdByKey($key);
        if ($key_value_id === null) {
            return $default_value;
        }

        $key_value_obj = $this->getById($key_value_id);
        if ($key_value_obj === null) {
            throw new \LogicException('Existing KeyValue could not be loaded.');
        }
        $optional_value = $key_value_obj->getValue();

        $this->cache_service->set($cache_key, $optional_value, self::OPTIONAL_VALUES_CACHE_TTL_SEC);

        return $optional_value;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string|null $description
     * @throws \Exception
     */
    public function setValueForKey(string $key, string $value, ?string $description = null): void
    {
        $key_value_id = $this->key_value_repository->findIdByKey($key);
        $key_value_obj = new KeyValue();
        $key_value_obj->setName($key);
        if ($key_value_id !== null) {
            $key_value_obj = $this->getById($key_value_id);
            if ($key_value_obj === null) {
                throw new \LogicException('Existing KeyValue could not be loaded.');
            }
        }
        $key_value_obj->setValue($value);
        if (!is_null($description)) {
            $key_value_obj->setDescription($description);
        }

        $this->save($key_value_obj);
    }
    /**
     * @param string $key
     * @return string
     */
    protected function getOptionalValueForKeyCacheKey(string $key): string
    {
        return 'optional_value_for_key_' .  $key;
    }

    public function beforeSave(InterfaceEntity $entity_obj): void
    {
        $key_value_obj = self::keyValue($entity_obj);
        $entity_id = $key_value_obj->getId();
        if ($entity_id === null) {
            parent::beforeSave($entity_obj);
            return;
        }

        // Сбрасываем кеш перед сохранением объекта для старого значения имени ключа (если вдруг имя ключа поменяется)
        $existing_key_value_obj = $this->key_value_repository->findById($entity_id);
        if (!$existing_key_value_obj instanceof KeyValue) {
            throw new \LogicException('Existing KeyValue could not be loaded before save.');
        }

        $cache_key = $this->getOptionalValueForKeyCacheKey($existing_key_value_obj->getName());
        $this->cache_service->delete($cache_key);

        parent::beforeSave($entity_obj);
    }

    public function afterSave(InterfaceEntity $entity_obj): void
    {
        $key_value_obj = self::keyValue($entity_obj);
        parent::afterSave($entity_obj);

        $cache_key = $this->getOptionalValueForKeyCacheKey($key_value_obj->getName());
        $this->cache_service->delete($cache_key);

        $this->logger_entry_service->logObjectEvent(
            $entity_obj,
            'save',
            ($this->current_user_full_id_provider)()
        );
    }

    public function afterDelete(InterfaceEntity $entity_obj): void
    {
        $key_value_obj = self::keyValue($entity_obj);
        parent::afterDelete($entity_obj);

        $cache_key = $this->getOptionalValueForKeyCacheKey($key_value_obj->getName());
        $this->cache_service->delete($cache_key);

        $this->logger_entry_service->logObjectEvent(
            $entity_obj,
            'delete',
            ($this->current_user_full_id_provider)()
        );
    }

    private static function keyValue(InterfaceEntity $entity_obj): KeyValue
    {
        if (!$entity_obj instanceof KeyValue) {
            throw new \InvalidArgumentException('KeyValueService accepts only KeyValue entities.');
        }

        return $entity_obj;
    }
}
