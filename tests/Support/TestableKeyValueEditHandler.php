<?php

namespace WebSK\KeyValue\Tests\Support;

use WebSK\KeyValue\KeyValueService;
use WebSK\KeyValue\RequestHandlers\KeyValueEditHandler;

final class TestableKeyValueEditHandler extends KeyValueEditHandler
{
    public function setKeyValueService(KeyValueService $service): void
    {
        $this->key_value_service = $service;
    }
}
