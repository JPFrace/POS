<?php

namespace App\Repositories\Conditions;

trait AccountUsageTypesConditions
{
    public function categoryCondition(&$builder, $query)
    {
        return $builder->when($query['category'], function ($builder) use ($query) {
            $builder->with('category');
        });
    }

}
