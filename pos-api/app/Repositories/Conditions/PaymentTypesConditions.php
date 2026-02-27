<?php

namespace App\Repositories\Conditions;

trait PaymentTypesConditions
{
    public function createdByCondition(&$builder, $query)
    {
        return $builder->when($query['createdBy'], function ($builder) use ($query) {
            $builder->with(['createdBy:id,name']);
        });
    }
}