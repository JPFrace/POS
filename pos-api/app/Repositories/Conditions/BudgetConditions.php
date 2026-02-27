<?php

namespace App\Repositories\Conditions;

trait BudgetConditions
{
    public function departmentCondition(&$builder, $query)
    {
        return $builder->when($query['department'], function ($builder) use ($query) {
            $builder->with('department');
        });
    }

    public function calendarCondition(&$builder, $query)
    {
        return $builder->when($query['calendar'], function ($builder) use ($query) {
            $builder->with('calendar');
        });
    }

    public function typeCondition(&$builder, $query)
    {
        return $builder->when($query['type'], function ($builder) use ($query) {
            $builder->with('type');
        });
    }

    public function searchCondition(&$builder, $query)
    {
        return $builder->when($query['search'], function ($builder) use ($query) {
            $builder->where('name', 'like', "%{$query['search']}%")
                ->orWhere('description', 'like', "%{$query['search']}%");
        });
    }

    public function detailsCondition(&$builder, $query)
    {
        return $builder->when($query['details'], function ($builder) use ($query) {
            $builder->with('details');
        });
    }
}