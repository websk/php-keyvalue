<?php

namespace WebSK\KeyValue\Demo;

use DI\Container;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use WebSK\Auth\Auth;
use WebSK\Auth\AuthServiceProvider;
use WebSK\Auth\User\UserServiceProvider;
use WebSK\Cache\CacheServiceProvider;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\CRUD\MutableContainerAdapter;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\KeyValue\KeyValueRoutes;
use WebSK\KeyValue\KeyValueServiceProvider;
use WebSK\KeyValue\RequestHandlers\KeyValueListHandler;
use WebSK\Logger\LoggerRoutes;
use WebSK\Logger\LoggerServiceProvider;
use WebSK\Slim\Router;
use WebSK\Utils\FullObjectId;

/**
 * Class KeyValueDemoApp
 * @package WebSK\KeyValue\Demo
 * @extends App<Container>
 */
class KeyValueDemoApp extends App
{
    public function __construct(Container $container)
    {
        parent::__construct(new ResponseFactory(), $container);

        $this->registerRouterSettings($container);

        $mutable_container = new MutableContainerAdapter($container);
        CacheServiceProvider::register($mutable_container);
        KeyValueServiceProvider::register(
            $mutable_container,
            static fn(): ?string => FullObjectId::getFullObjectId(Auth::getCurrentUserObj())
        );
        CRUDServiceProvider::register($mutable_container);
        AuthServiceProvider::register($mutable_container);
        UserServiceProvider::register($mutable_container);
        LoggerServiceProvider::register($mutable_container);

        $this->registerRoutes();

        $display_error_details = $container->get('settings.displayErrorDetails');
        if (!is_bool($display_error_details)) {
            throw new \UnexpectedValueException('displayErrorDetails must be a boolean.');
        }
        $error_middleware = $this->addErrorMiddleware($display_error_details, true, true);
        $error_middleware->setDefaultErrorHandler(ErrorHandler::class);
    }

    protected function registerRouterSettings(Container $container): void
    {
        $route_collector = $this->getRouteCollector();
        $invocation_strategy = $container->get(InvocationStrategyInterface::class);
        if (!$invocation_strategy instanceof InvocationStrategyInterface) {
            throw new \UnexpectedValueException('Container invocation strategy has an invalid type.');
        }
        $route_collector->setDefaultInvocationStrategy($invocation_strategy);
        $route_parser = $route_collector->getRouteParser();

        $container->set(RouteParserInterface::class, $route_parser);
    }

    protected function registerRoutes(): void
    {
        // Demo routing. Redirects
        $this->get('/', function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
            return $response->withHeader('Location', Router::urlFor(KeyValueListHandler::class))
                ->withStatus(StatusCodeInterface::STATUS_TEMPORARY_REDIRECT);
        });

        $this->get(KeyValueConfig::getAdminMainPageUrl(), function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
            return $response->withHeader('Location', Router::urlFor(KeyValueListHandler::class))
                ->withStatus(StatusCodeInterface::STATUS_TEMPORARY_REDIRECT);
        });

        $this->group(KeyValueConfig::getAdminMainPageUrl(), function (RouteCollectorProxyInterface $route_collector_proxy): void {
            KeyValueRoutes::registerAdmin($route_collector_proxy);
            LoggerRoutes::registerAdmin($route_collector_proxy);
        });
    }
}
