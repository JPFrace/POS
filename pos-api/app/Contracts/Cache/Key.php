<?php

namespace App\Contracts\Cache;

use App\Contracts\Cache\CacheKey;

interface Key
{
    public function get(): string;
}