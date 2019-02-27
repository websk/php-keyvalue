<?php

namespace WebSK\KeyValue;

use Psr\Container\ContainerInterface;
use WebSK\Cache\CacheServiceProvider;
use WebSK\DB\DBConnectorMySQL;
use WebSK\DB\DBService;
use WebSK\DB\DBSettings;

/**
 * Class KeyValueServiceProvider
 * @package WebSK\KeyValue
 */
class KeyValueServiceProvider
{
    const DB_SERVICE_CONTAINER_ID = 'keyvalue.db_service';
    const DB_ID = 'db_keyvalue';

    /**
     * @param ContainerInterface $container
     */
    public static function register(ContainerInterface $container)
    {
        $container[KeyValue::ENTITY_SERVICE_CONTAINER_ID] = function (ContainerInterface $container) {
            return new KeyValueService(
                KeyValue::class,
                $container->get(KeyValue::ENTITY_REPOSITORY_CONTAINER_ID),
                CacheServiceProvider::getCacheService($container)
            );
        };

        $container[KeyValue::ENTITY_REPOSITORY_CONTAINER_ID] = function (ContainerInterface $container) {
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

            $db_connector = new DBConnectorMySQL(
                $db_config['host'],
                $db_config['db_name'],
                $db_config['user'],
                $db_config['password']
            );

            $db_settings = new DBSettings(
                'mysql'
            );

            return new DBService($db_connector, $db_settings);
        };
    }

    /**
     * @param ContainerInterface $container
     * @return KeyValueService
     */
    public static function getKeyValueService(ContainerInterface $container): KeyValueService
    {
        return $container[KeyValue::ENTITY_SERVICE_CONTAINER_ID];
    }
}
