<?php

namespace App\Supports\Models;

trait RouteModelBinding
{
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}