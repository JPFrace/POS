<?php

namespace App\Repositories;

use App\Models\WithholdingTax;

class WithholdingTaxRepository extends Repository
{
    use Conditions\WithholdingTaxConditions;
    public function __construct(protected WithholdingTax $model)
    {

    }
}