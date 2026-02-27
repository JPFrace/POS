<?php

namespace App\Repositories\Conditions;

trait RoleConditions
{
    public function permissionsCondition(&$builder, $query)
    {
        return $builder->when($query['permissions'], function ($builder) use ($query) {
            $builder->with("permissions");
        });
    }

    public function permissionsWithActionCondition(&$builder, $query)
    {
        return $builder->when($query['permissions.action'], function ($builder) use ($query) {
            $builder->with("permissions.action");
        });
    }
}