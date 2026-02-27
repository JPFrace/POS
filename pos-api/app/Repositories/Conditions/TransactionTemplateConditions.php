<?php

namespace App\Repositories\Conditions;

trait TransactionTemplateConditions
{
    public function detailsCondition(&$builder, $query)
    {
        return $builder->when($query['details'], function ($builder) use ($query) {
            $builder->with('details');
        });
    }
    public function searchCondition(&$builder, $query)
    {
        return $builder->when($query['search'] ?? null, function ($builder, $search) {
            $builder->where('name', 'like', "%{$search}%");
        });
    }

}