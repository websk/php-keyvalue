<?php

namespace WebSK\KeyValue;

use WebSK\Slim\Facade;

/**
 * Class KeyValueFacade
 * @see KeyValueService
 * @method static string getOptionalValueForKey(string $key, string $default_value = '')
 * @method static void setValueForKey(string $key, string $value, ?string $description = null)
 * @package WebSK\KeyValue
 */
class KeyValueFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return KeyValueService::class;
    }
}
