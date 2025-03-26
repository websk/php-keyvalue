<?php

namespace WebSK\KeyValue\Demo;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use WebSK\Auth\AuthServiceProvider;
use WebSK\Auth\User\UserServiceProvider;
use WebSK\Cache\CacheServiceProvider;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\KeyValue\KeyValueRoutes;
use WebSK\KeyValue\KeyValueServiceProvider;
use WebSK\KeyValue\RequestHandlers\KeyValueListHandler;
use WebSK\Logger\LoggerRoutes;
use WebSK\Logger\LoggerServiceProvider;
use WebSK\Slim\Router;

/**
 * Class KeyValueDemoApp
 * @package WebSK\KeyValue\Demo
 */
class KeyValueDemoApp extends App
{
    /**
     * LoggerDemoApp constructor.
     * @param array $config
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(new ResponseFactory(), $container);

        $this->registerRouterSettings($container);

        CacheServiceProvider::register($container);
        KeyValueServiceProvider::register($container);
        CRUDServiceProvider::register($container);
        AuthServiceProvider::register($container);
        UserServiceProvider::register($container);
        LoggerServiceProvider::register($container);

        $this->registerRoutes();

        $error_middleware = $this->addErrorMiddleware(true, true, true);
        $error_middleware->setDefaultErrorHandler(ErrorHandler::class);
    }

    /**
     * @param ContainerInterface $container
     */
    protected function registerRouterSettings(ContainerInterface $container): void
    {
        $route_collector = $this->getRouteCollector();
        $route_collector->setDefaultInvocationStrategy($container->get(InvocationStrategyInterface::class));
        $route_parser = $route_collector->getRouteParser();

        $container->set(RouteParserInterface::class, $route_parser);
    }

    protected function registerRoutes(): void
    {
        // Demo routing. Redirects
        $this->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withHeader('Location', Router::urlFor(KeyValueListHandler::class))
                ->withStatus(StatusCodeInterface::STATUS_FOUND);
        });

        $this->get(KeyValueConfig::getAdminMainPageUrl(), function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withHeader('Location', Router::urlFor(KeyValueListHandler::class))
                ->withStatus(StatusCodeInterface::STATUS_FOUND);
        });

        $this->group(KeyValueConfig::getAdminMainPageUrl(), function (RouteCollectorProxyInterface $route_collector_proxy) {
            KeyValueRoutes::registerAdmin($route_collector_proxy);
            LoggerRoutes::registerAdmin($route_collector_proxy);
        });
    }
}
