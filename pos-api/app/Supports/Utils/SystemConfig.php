<?php

namespace App\Supports\Utils;

use App\Models\Config;

class SystemConfig
{
    /**
     * Get a system configuration value by its slug.
     */
    public function value(string $slug, $default = null)
    {
        return optional(Config::where('slug', $slug)->first())->value ?? $default;
    }
    /**
     * Get a system configuration by its slug.
     */
    public function get(string $slug)
    {
        return Config::where('slug', $slug)
            ->where('is_inactive', false)
            ->first();
    }
}
