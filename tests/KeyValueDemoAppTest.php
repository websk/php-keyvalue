<?php

namespace WebSK\KeyValue\Tests;

use Jgut\Slim\PHPDI\Configuration;
use Jgut\Slim\PHPDI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use WebSK\Config\ConfWrapper;
use WebSK\KeyValue\Demo\KeyValueDemoApp;
use WebSK\Slim\Facade;

final class KeyValueDemoAppTest extends TestCase
{
    protected function tearDown(): void
    {
        ConfWrapper::setConfig([]);
    }

    public function testRootAndAdminRoutesRedirectToKeyValueList(): void
    {
        $config = require __DIR__ . '/../config/config.default.php';
        if (!is_array($config) || !isset($config['settings']) || !is_array($config['settings'])) {
            self::fail('Default configuration must contain a settings array.');
        }

        $configuration = new Configuration();
        $configuration->setDefinitions([$config]);
        $configuration->setUseAttributes(true);
        $container = ContainerBuilder::build($configuration);
        ConfWrapper::setConfig($config['settings']);

        $app = new KeyValueDemoApp($container);
        Facade::setFacadeApplication($app);
        $request_factory = new ServerRequestFactory();

        foreach (['/', '/admin'] as $path) {
            $response = $app->handle($request_factory->createServerRequest('GET', $path));

            self::assertSame(307, $response->getStatusCode());
            self::assertSame('/admin/keyvalue', $response->getHeaderLine('Location'));
        }
    }
}
