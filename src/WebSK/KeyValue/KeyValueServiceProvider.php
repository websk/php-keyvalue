<?php

namespace WebSK\KeyValue;

use Psr\Container\ContainerInterface;
use WebSK\Cache\CacheServiceProvider;
use WebSK\DB\DBService;
use WebSK\DB\DBServiceFactory;

/**
 * Class KeyValueServiceProvider
 * @package WebSK\KeyValue
 */
class KeyValueServiceProvider
{
    const DUMP_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dumps' . DIRECTORY_SEPARATOR . 'db_keyvalue.sql';
    const DB_SERVICE_CONTAINER_ID = 'keyvalue.db_service';
    const DB_ID = 'db_keyvalue';

    /**
     * @param ContainerInterface $container
     */
    public static function register(ContainerInterface $container)
    {
        $container[KeyValueService::class] = function (ContainerInterface $container) {
            return new KeyValueService(
                KeyValue::class,
                $container->get(KeyValueRepository::class),
                CacheServiceProvider::getCacheService($container)
            );
        };

        $container[KeyValueRepository::class] = function (ContainerInterface $container) {
            return new KeyValueRepository(
                KeyValue::class,
                $container->get(self::DB_SERVICE_CONTAINER_ID)
            );
        };

        /**
         * @param ContainerInterface $container
         * @return DBService
         */
        $container[self::DB_SERVICE_CONTAINER_ID] = function (ContainerInterface $container) {
            $db_config = $container['settings']['db'][self::DB_ID];

            return DBServiceFactory::factoryMySQL($db_config);
        };
    }

    /**
     * @param ContainerInterface $container
     * @return KeyValueService
     */
    public static function getKeyValueService(ContainerInterface $container): KeyValueService
    {
        return $container->get(KeyValueService::class);
    }

    /**
     * @param ContainerInterface $container
     * @return DBService
     */
    public static function getDbService(ContainerInterface $container): DBService
    {
        return $container->get(self::DB_SERVICE_CONTAINER_ID);
    }
}
