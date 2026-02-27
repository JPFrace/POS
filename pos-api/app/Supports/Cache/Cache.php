<?php

namespace App\Supports\Cache;

use App\Contracts\Cache\Key;
use Carbon\Carbon;

class Cache implements \App\Contracts\Cache\Cache
{
    /**
     * Put value in cache
     * @param \App\Supports\Cache\Key $key
     * @param mixed $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public static function put(Key $key, mixed $value, ?Carbon $expiredAt = null): mixed
    {
        return (new self)->store($key, $value, $expiredAt);
    }

    /**
     * Get cache key value
     * @param string|int $key
     * @return mixed
     */
    public static function get(Key $key): mixed
    {
        return (new self())->retrieve($key);
    }

    /**
     * Forget certain cache key
     * @param string $key
     * @return void
     */
    public static function forget(Key $key): void
    {
        (new self())->keyForget($key);
    }

    /**
     * Forget certain key
     * @param string $key
     * @return void
     */
    public function keyForget(Key $key): void
    {
        \Illuminate\Support\Facades\Cache::forget($key->get());
    }

    /**
     * Cache store
     * @param string|int $key
     * @param mixed $value
     * @param ?Carbon $expiredAt
     * @return bool
     */
    public function store(Key $key, mixed $value, ?Carbon $expiredAt = null): bool
    {
        $expiredAt ??= config('system.cache_expiration')();

        $value = is_callable($value) ? $value() : $value;

        return \Illuminate\Support\Facades\Cache::put($key->get(), $value, $expiredAt);
    }

    /**
     * Cache retrieve
     * @param string|int $key
     * @return mixed
     */
    public function retrieve(Key $key): mixed
    {
        return \Illuminate\Support\Facades\Cache::get($key->get());
    }
}