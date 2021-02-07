<?php

namespace WebSK\KeyValue;

use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Http\Request;
use Slim\Http\Response;
use WebSK\Auth\AuthServiceProvider;
use WebSK\Auth\User\UserServiceProvider;
use WebSK\Cache\CacheServiceProvider;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\DB\DBWrapper;
use WebSK\Logger\LoggerRoutes;
use WebSK\Logger\LoggerServiceProvider;
use WebSK\Slim\Facade;
use WebSK\Slim\Router;

/**
 * Class KeyValueDemoApp
 * @package WebSK\Auth
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
        $this->get('/', function (Request $request, Response $response) {
            return $response->withRedirect(Router::pathFor(KeyValueRoutes::ROUTE_NAME_ADMIN_KEYVALUE_LIST));
        });

        $this->group(KeyValueConfig::getMainPageUrl(), function (App $app) {
            KeyValueRoutes::registerAdmin($app);
            LoggerRoutes::registerAdmin($app);
        });

        /** Use facade */
        Facade::setFacadeApplication($this);

        /** Set DBWrapper db service */
        DBWrapper::setDbService(KeyValueServiceProvider::getDBService($container));
    }
}
