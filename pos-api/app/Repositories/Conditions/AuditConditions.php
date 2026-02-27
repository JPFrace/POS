<?php

namespace App\Repositories\Conditions;

trait AuditConditions
{
    public function userCondition(&$builder, $query)
    {
        return $builder->when($query['user'], function ($builder) use ($query) {
            $builder->with(['user:id,name']);
        });
    }
}