<?php

namespace App\Repositories;

use App\Models\Config;

class ConfigRepository extends Repository
{
    use Conditions\ConfigConditions;
    public function __construct(protected Config $model)
    {

    }
}

