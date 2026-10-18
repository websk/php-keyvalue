<?php

namespace WebSK\KeyValue\Tests\Support;

use WebSK\KeyValue\KeyValue;

final class TestKeyValue extends KeyValue
{
    public function setTestId(int $id): void
    {
        $this->id = $id;
    }
}
