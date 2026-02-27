<?php

namespace App\Repositories\Conditions;

trait AccountClassConditions
{
    public function categoryCondition(&$builder, $query)
    {
        return $builder->when($query['category'], function ($builder) use ($query) {
            $builder->with('category');
        });
    }

}
