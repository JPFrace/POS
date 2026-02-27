<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReferenceNumb extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'referencenumb';
    }
}
