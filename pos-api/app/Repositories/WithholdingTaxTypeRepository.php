<?php

namespace App\Repositories;

use App\Models\WithholdingTax;
use App\Models\WithholdingTaxType;

class WithholdingTaxTypeRepository extends Repository
{
    public function __construct(protected WithholdingTaxType $model)
    {

    }
}