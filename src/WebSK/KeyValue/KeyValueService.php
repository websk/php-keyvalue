<?php

namespace WebSK\KeyValue;

use WebSK\Auth\Auth;
use WebSK\Entity\EntityService;
use WebSK\Entity\InterfaceEntity;
use WebSK\Logger\Logger;
use WebSK\Utils\FullObjectId;

/**
 * Class KeyValueService
 * @method KeyValue getById($entity_id, $exception_if_not_loaded = true)
 * @package WebSK\KeyValue
 */
class KeyValueService extends EntityService
{
    const int OPTIONAL_VALUES_CACHE_TTL_SEC = 60;

    /** @var KeyValueRepository */
    protected $repository;

    /**
     * @param string $key
     * @param string $default_value
     * @return string
     */
    public function getOptionalValueForKey(string $key, string $default_value = ''): string
    {
        $cache_key = $this->getOptionalValueForKeyCacheKey($key);

        $cached = $this->cache_service->get($cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $key_value_id = $this->repository->findIdByKey($key);
        if (!$key_value_id) {
            return $default_value;
        }

        $key_value_obj = $this->getById($key_value_id);
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
        $key_value_id = $this->repository->findIdByKey($key);
        $key_value_obj = new KeyValue();
        $key_value_obj->setName($key);
        if ($key_value_id) {
            $key_value_obj = $this->getById($key_value_id);
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

    /**
     * @param KeyValue|InterfaceEntity $entity_obj
     */
    public function beforeSave(InterfaceEntity $entity_obj): void
    {
        if (is_null($entity_obj->getId())) {
            return;
        }

        // Сбрасываем кеш перед сохранением объекта для старого значения имени ключа (если вдруг имя ключа поменяется)
        $existing_key_value_obj = $this->getById($entity_obj->getId());

        $cache_key = $this->getOptionalValueForKeyCacheKey($existing_key_value_obj->getName());
        $this->cache_service->delete($cache_key);
    }

    /**
     * @param KeyValue|InterfaceEntity $entity_obj
     */
    public function afterSave(InterfaceEntity $entity_obj): void
    {
        parent::afterSave($entity_obj);

        $cache_key = $this->getOptionalValueForKeyCacheKey($entity_obj->getName());
        $this->cache_service->delete($cache_key);

        Logger::logObjectEvent(
            $entity_obj,
            'save',
            FullObjectId::getFullObjectId(Auth::getCurrentUserObj())
        );
    }

    /**
     * @param KeyValue|InterfaceEntity $entity_obj
     */
    public function afterDelete(InterfaceEntity $entity_obj): void
    {
        parent::afterDelete($entity_obj);

        $cache_key = $this->getOptionalValueForKeyCacheKey($entity_obj->getName());
        $this->cache_service->delete($cache_key);

        Logger::logObjectEvent(
            $entity_obj,
            'delete',
            FullObjectId::getFullObjectId(Auth::getCurrentUserObj())
        );
    }
}
