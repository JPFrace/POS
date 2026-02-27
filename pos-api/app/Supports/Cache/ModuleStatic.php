<?php

namespace App\Supports\Cache;

use App\Contracts\Cache\Key;
use Carbon\Carbon;

trait ModuleStatic
{
    /**
     * Static remember
     * @param \App\Contracts\Cache\Key $key
     * @param callable $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public static function staticRemember(Key $key, callable $value, ?Carbon $expiredAt = null): mixed
    {
        return (new self)->remember($key, $value, $expiredAt);
    }

    /**
     * Static forget
     * @param \App\Contracts\Cache\Key|null $key
     * @return mixed
     */
    public static function staticForget(Key $key = null): mixed
    {
        return (new self)->forget($key);
    }
}