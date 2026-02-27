<?php

namespace App\Repositories;

use App\Models\BudgetType;

class BudgetTypesRepository extends Repository{

    public function __construct(protected BudgetType $model){

    }
}