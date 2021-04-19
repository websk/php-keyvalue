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

    /**
     * @param App $app
     */
    public static function registerAdmin(App $app): void
    {
        $app->group('/keyvalue', function (App $app) {
            $app->map([HTTP::METHOD_GET, HTTP::METHOD_POST], '', KeyValueListHandler::class)
                ->setName(KeyValueListHandler::class);

            $app->map([HTTP::METHOD_GET, HTTP::METHOD_POST], '/{keyvalue_id:\d+}', KeyValueEditHandler::class)
                ->setName(KeyValueEditHandler::class);
        });
    }
}
