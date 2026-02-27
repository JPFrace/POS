<?php

namespace App\Repositories;

use App\Models\OfficialReceiptDenomination;

class OfficialReceiptDenominationsRepository extends Repository
{

    public function __construct(protected OfficialReceiptDenomination $model)
    {

    }
}