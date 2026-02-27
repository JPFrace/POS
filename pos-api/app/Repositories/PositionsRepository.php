<?php

namespace App\Repositories;

use App\Models\Position;

class PositionsRepository extends Repository
{

    public function __construct(protected Position $model)
    {

    }
}