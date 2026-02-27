<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;

trait ConfigConditions
{
    public function parentSlugCondition(Builder $builder, array $query)
    {
        return $builder->active()->where('slug', $query['parent_slug'])
            ->with('children');
    }
}