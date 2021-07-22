<?php

namespace WebSK\KeyValue\Demo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;
use WebSK\Auth\AuthServiceProvider;
use WebSK\Auth\User\UserServiceProvider;
use WebSK\Cache\CacheServiceProvider;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\DB\DBWrapper;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\KeyValue\KeyValueRoutes;
use WebSK\KeyValue\KeyValueServiceProvider;
use WebSK\KeyValue\RequestHandlers\KeyValueListHandler;
use WebSK\Logger\LoggerRoutes;
use WebSK\Logger\LoggerServiceProvider;
use WebSK\Slim\Facade;
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
    public function __construct($config = [])
    {
        parent::__construct($config);

        $container = $this->getContainer();

        CacheServiceProvider::register($container);
        KeyValueServiceProvider::register($container);
        CRUDServiceProvider::register($container);
        AuthServiceProvider::register($container);
        UserServiceProvider::register($container);
        LoggerServiceProvider::register($container);

        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $container = $this->getContainer();
        $container['foundHandler'] = function () {
            return new RequestResponseArgs();
        };

        // Demo routing. Redirects
        $this->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withRedirect(Router::pathFor(KeyValueListHandler::class));
        });

        $this->get(KeyValueConfig::getAdminMainPageUrl(), function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withRedirect(Router::pathFor(KeyValueListHandler::class));
        });

        $this->group(KeyValueConfig::getAdminMainPageUrl(), function (App $app) {
            KeyValueRoutes::registerAdmin($app);
            LoggerRoutes::registerAdmin($app);
        });

        /** Use facade */
        Facade::setFacadeApplication($this);

        /** Set DBWrapper db service */
        DBWrapper::setDbService(KeyValueServiceProvider::getDBService($container));
    }
}
