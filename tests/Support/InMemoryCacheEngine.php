<?php

namespace WebSK\KeyValue\Tests\Support;

use WebSK\Cache\Engines\CacheEngineInterface;

final class InMemoryCacheEngine implements CacheEngineInterface
{
    /** @var array<string, mixed> */
    private array $values = [];

    public function set(string $key, mixed $value, int $ttl_sec = 0): bool
    {
        $this->values[$key] = $value;

        return true;
    }

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? false;
    }

    public function delete(string $key): bool
    {
        unset($this->values[$key]);

        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }
}
