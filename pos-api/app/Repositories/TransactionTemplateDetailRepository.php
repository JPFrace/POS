<?php

namespace App\Repositories;

use App\Models\TransactionTemplateDetail;

class TransactionTemplateDetailRepository extends Repository
{
    public function __construct(protected TransactionTemplateDetail $model)
    {

    }
}