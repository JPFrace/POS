<?php

namespace App\Supports\Utils;

class Url
{
    /**
     * Generate frontend url
     * @param string $url
     * @return string
     */
    public static function frontEndUrl(string $url)
    {
        return rtrim(config("system.frontend_url"), "/") . $url;
    }

    /**
     * Generate IS base url
     * @param string $endpoint
     * @return string
     */
    public static function ISbaseUrl(string $endpoint): string
    {
        return rtrim(config("system.is_base_url"), "/") . $endpoint;
    }
}