<?php

namespace App\Contracts\Cache;

use Carbon\Carbon;

interface Module
{
    /**
     * Remember key value
     * @param \App\Contracts\Cache\Key $key
     * @param callable $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public function remember(Key $key, callable $value, ?Carbon $expiredAt = null): mixed;


    /**
     * Forget key value
     * @param \App\Contracts\Cache\Key $key
     * @return mixed
     */
    public function forget(Key $key): mixed;
}