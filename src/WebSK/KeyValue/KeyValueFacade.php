<?php

namespace WebSK\KeyValue;

use WebSK\Slim\Facade;

/**
 * Class KeyValueFacade
 * @see KeyValueService
 * @method static getOptionalValueForKey(string $key, string $default_value = '')
 * @package WebSK\KeyValue
 */
class KeyValueFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return KeyValue::ENTITY_SERVICE_CONTAINER_ID;
    }
}
