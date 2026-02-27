<?php
namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Cache;

class Configuration
{
    public static function GenerateCache()
    {
        Cache::rememberForever('configurations.all', function () {
            return Config::pluck('slug', 'value')->toArray();
        });
    }
}