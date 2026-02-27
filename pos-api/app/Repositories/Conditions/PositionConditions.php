<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait PositionConditions
{
    public function codeCondition($builder, $query)
    {
        return $builder->when($query['code'], function ($builder) use ($query) {
            $builder->where('code', 'like', "%{$query['code']}%");
        });
    }

    public function titleCondition($builder, $query)
    {
        return $builder->when($query['title'], function ($builder) use ($query) {
            $builder->where('title', 'like', "%{$query['title']}%");
        });
    }
}