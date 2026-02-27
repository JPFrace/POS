<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class GlobalRepository extends Repository
{
    public function __construct(protected Model $model)
    {

    }
}