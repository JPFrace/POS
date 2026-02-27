<?php

namespace App\Repositories\Conditions;

trait DepartmentConditions
{
    public function codeNameCondition(&$builder, $query)
    {
        return $builder->when($query['code_name'], function ($builder) use ($query) {

            $builder->where(['name', 'like', '%' . $query['code_name'] . '%'])
                ->orWhere(['code', 'like', '%' . $query['code_name'] . '%']);
        });
    }
}