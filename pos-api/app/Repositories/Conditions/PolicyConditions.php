<?php

namespace App\Repositories\Conditions;

trait PolicyConditions
{
    public function childrenCondition(&$builder, $query)
    {
        return $builder->when($query['children'], function ($builder) use ($query) {
            $builder->with('children');
        });
    }

    public function childrenWithActionsCondition(&$builder, $query)
    {
        return $builder->when($query['children.actions'], function ($builder) use ($query) {
            $builder->with('children.actions');
        });
    }

    public function actionsCondition(&$builder, $query)
    {
        return $builder->when($query['actions'], function ($builder) use ($query) {
            $builder->with('actions');
        });
    }

    public function rootCondition(&$builder, $query)
    {
        return $builder->when($query['root'], function ($builder) use ($query) {
            $builder->whereNull("policy_id");
        });
    }
}