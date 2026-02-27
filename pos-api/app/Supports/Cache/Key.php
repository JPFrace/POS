<?php

namespace App\Supports\Cache;

use App\Contracts\Cache\CacheKey;
use Illuminate\Support\Collection;

class Key implements \App\Contracts\Cache\Key
{
    public function __construct(private array|string|int|CacheKey $key)
    {
        // 
    }

    public function get(): string
    {
        $key = $this->key;

        $key = !is_array($key) ? [$key] : $key;
        $key = Collection::wrap($key);

        $key = $key->map(function ($key) {
            if ($key instanceof CacheKey) {
                return $key->name;
            }
            return $key;
        });

        $key = $key->flatten();

        $key = $key->map(fn($value) => strtoupper(preg_replace('/-|\s/i', '', $value)));

        return $key->implode('_');
    }

    public static function value(mixed $key)
    {
        return (new static($key))->get();
    }

    public function __tostring(): string
    {
        return $this->get();
    }
}