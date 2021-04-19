<?php

namespace WebSK\KeyValue;

use WebSK\Slim\Facade;

/**
 * Class KeyValueFacade
 * @see KeyValueService
 * @method static getOptionalValueForKey(string $key, string $default_value = '')
 * @method static setValueForKey(string $key, string $value, ?string $decription = null)
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
