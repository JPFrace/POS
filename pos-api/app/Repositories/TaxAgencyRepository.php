<?php

namespace App\Repositories;

use App\Models\TaxAgency;

class TaxAgencyRepository extends Repository
{
    use Conditions\TaxAgencyCondition;


    public function __construct(protected TaxAgency $model)
    {
    }

}
