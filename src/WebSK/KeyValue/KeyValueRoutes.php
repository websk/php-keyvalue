<?php

namespace WebSK\KeyValue;

use Fig\Http\Message\RequestMethodInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use WebSK\KeyValue\RequestHandlers\KeyValueEditHandler;
use WebSK\KeyValue\RequestHandlers\KeyValueListHandler;

/**
 * Class KeyValueRoutes
 * @package WebSK\KeyValue
 */
class KeyValueRoutes
{

    /**
     * @param RouteCollectorProxyInterface $route_collector_proxy
     */
    public static function registerAdmin(RouteCollectorProxyInterface $route_collector_proxy): void
    {
        $route_collector_proxy->group('/keyvalue', function (RouteCollectorProxyInterface $route_collector_proxy) {
            $route_collector_proxy->map([RequestMethodInterface::METHOD_GET, RequestMethodInterface::METHOD_POST], '', KeyValueListHandler::class)
                ->setName(KeyValueListHandler::class);

            $route_collector_proxy->map([RequestMethodInterface::METHOD_GET, RequestMethodInterface::METHOD_POST], '/{keyvalue_id:\d+}', KeyValueEditHandler::class)
                ->setName(KeyValueEditHandler::class);
        });
    }
}
