<?php

namespace App\Repositories\Conditions;

trait CalendarConditions
{
    public function yearCondition(&$builder, $query)
    {
        return $builder->when($query['year'], function ($builder) use ($query) {

            $builder->where('year', 'like', '%' . $query['year'] . '%')
                ->orWhere('period_start', 'like', '%' . $query['year'] . '%')
                ->orWhere('period_end', 'like', '%' . $query['year'] . '%');
        });
    }
}