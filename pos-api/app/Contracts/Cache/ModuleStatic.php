<?php

namespace App\Contracts\Cache;

use Carbon\Carbon;

interface ModuleStatic
{
    /**
     * Static remember
     * @param \App\Contracts\Cache\Key $key
     * @param callable $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public static function staticRemember(Key $key, callable $value, ?Carbon $expiredAt = null): mixed;

    /**
     * Static forget
     * @param \App\Contracts\Cache\Key $key
     * @return mixed
     */
    public static function staticForget(Key $key): mixed;
}