<?php

namespace WebSK\KeyValue;

use Psr\Container\ContainerInterface;
use WebSK\Cache\CacheServiceProvider;
use WebSK\DB\DBService;
use WebSK\DB\DBServiceFactory;
use WebSK\Logger\Entry\LoggerEntryService;

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
     * @param ContainerInterface&\ArrayAccess<array-key, mixed> $container
     * @param (\Closure(): ?string)|null $current_user_full_id_provider
     */
    public static function register(
        ContainerInterface&\ArrayAccess $container,
        ?\Closure $current_user_full_id_provider = null
    ): void
    {
        $container[KeyValueService::class] = static function (
            ContainerInterface $container
        ) use ($current_user_full_id_provider): KeyValueService {
            $repository = self::service($container, KeyValueRepository::class, KeyValueRepository::class);
            $logger_entry_service = self::service($container, LoggerEntryService::class, LoggerEntryService::class);

            return new KeyValueService(
                KeyValue::class,
                $repository,
                CacheServiceProvider::getCacheService($container),
                $logger_entry_service,
                $current_user_full_id_provider
            );
        };

        $container[KeyValueRepository::class] = static function (ContainerInterface $container): KeyValueRepository {
            $db_service = self::service($container, self::DB_SERVICE_CONTAINER_ID, DBService::class);

            return new KeyValueRepository(
                KeyValue::class,
                $db_service
            );
        };

        /**
         * @param ContainerInterface $container
         * @return DBService
         */
        $container[self::DB_SERVICE_CONTAINER_ID] = static function (ContainerInterface $container): DBService {
            $db_config = $container->get(
                self::SETTINGS_CONTAINER_ID . '.' . self::PARAM_DB . '.' . self::DB_ID
            );
            if (!is_array($db_config)) {
                throw new \UnexpectedValueException('KeyValue database configuration must be an array.');
            }
            foreach ($db_config as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    throw new \UnexpectedValueException('KeyValue database configuration must contain string values.');
                }
            }

            return DBServiceFactory::factoryMySQL($db_config);
        };
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    private static function service(ContainerInterface $container, string $id, string $class): object
    {
        $service = $container->get($id);
        if (!$service instanceof $class) {
            throw new \UnexpectedValueException('Container service "' . $id . '" must be an instance of ' . $class . '.');
        }

        return $service;
    }
}
