<?php

namespace WebSK\KeyValue\Tests;

use DI\Container;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use WebSK\KeyValue\KeyValueService;
use WebSK\KeyValue\Tests\Support\TestableKeyValueEditHandler;

final class KeyValueEditHandlerTest extends TestCase
{
    public function testReturnsNotFoundForMissingKeyValue(): void
    {
        /** @var KeyValueService&MockObject $service */
        $service = $this->createMock(KeyValueService::class);
        $service->expects(self::once())->method('getById')->with(404, false)->willReturn(null);

        $handler = new TestableKeyValueEditHandler(new Container());
        $handler->setKeyValueService($service);

        $response = $handler(
            (new ServerRequestFactory())->createServerRequest('GET', '/admin/keyvalue/404'),
            (new ResponseFactory())->createResponse(),
            404
        );

        self::assertSame(404, $response->getStatusCode());
    }
}
