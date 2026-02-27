<?php

namespace App\Contracts\Cache;

use Carbon\Carbon;

interface Cache
{
    /**
     * Put key-value to cache
     * @param \App\Contracts\Cache\Key $key
     * @param mixed $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public static function put(Key $key, mixed $value, ?Carbon $expiredAt = null): mixed;

    /**
     * Get key value cache
     * @param \App\Contracts\Cache\Key $key
     * @return mixed
     */
    public static function get(Key $key): mixed;

    /**
     * Forget cache key value
     * @param \App\Contracts\Cache\Key $key
     * @return void
     */
    public static function forget(Key $key): void;
}