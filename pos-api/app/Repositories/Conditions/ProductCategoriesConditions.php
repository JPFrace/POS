<?php

namespace App\Repositories\Conditions;

trait ProductCategoriesConditions
{
    public function nameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['name']) && $query['name'], function ($builder) use ($query) {
            $builder->where('name', 'like', '%' . $query['name'] . '%')
            ->orWhereHas('children', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query['name'] . '%');
            });
        });
    }
    public function childrenCondition(&$builder, $query)
    {
        return $builder->when($query['children'], function ($builder) use ($query) {
            $builder->with(['children']);
        });
    }
     public function parentOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['parent_only'], function ($builder) use ($query) {
            $builder->whereDoesntHave('parent');
        });
    }
}
