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
    const string DUMP_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dumps' . DIRECTORY_SEPARATOR . 'db_keyvalue.sql';
    const string DB_SERVICE_CONTAINER_ID = 'keyvalue.db_service';
    const string DB_ID = 'db_keyvalue';

    const string SETTINGS_CONTAINER_ID = 'settings';
    const string PARAM_DB = 'db';


    /**
     * @param ContainerInterface $container
     */
    public static function register(ContainerInterface $container)
    {
        $container[KeyValueService::class] = function (ContainerInterface $container) {
            return new KeyValueService(
                KeyValue::class,
                $container->get(KeyValueRepository::class),
                $container->get(CacheServiceProvider::SERVICE_CONTAINER_ID)
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
            $db_config = $container->get(
                self::SETTINGS_CONTAINER_ID . '.' . self::PARAM_DB . '.' . self::DB_ID
            );

            return DBServiceFactory::factoryMySQL($db_config);
        };
    }
}
