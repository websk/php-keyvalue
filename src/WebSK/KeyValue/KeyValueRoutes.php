<?php

namespace WebSK\KeyValue;

use Slim\App;
use WebSK\Utils\HTTP;
use WebSK\KeyValue\RequestHandlers\KeyValueEditHandler;
use WebSK\KeyValue\RequestHandlers\KeyValueListHandler;

/**
 * Class KeyValueRoutes
 * @package WebSK\KeyValue
 */
class KeyValueRoutes
{
    const ROUTE_NAME_ADMIN_KEYVALUE_LIST = 'admin:keyvalue:list';
    const ROUTE_NAME_ADMIN_KEYVALUE_EDIT = 'admin:keyvalue:edit';

    /**
     * @param App $app
     */
    public static function registerAdmin(App $app): void
    {
        $app->group('/keyvalue', function (App $app) {
            $app->map([HTTP::METHOD_GET, HTTP::METHOD_POST], '', KeyValueListHandler::class)
                ->setName(self::ROUTE_NAME_ADMIN_KEYVALUE_LIST);

            $app->map([HTTP::METHOD_GET, HTTP::METHOD_POST], '/{keyvalue_id:\d+}', KeyValueEditHandler::class)
                ->setName(self::ROUTE_NAME_ADMIN_KEYVALUE_EDIT);
        });
    }
}
