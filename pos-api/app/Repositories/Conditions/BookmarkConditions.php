<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait BookmarkConditions
{
    public function userBookmarkCondition(&$builder, $query): Builder
    {
        return $builder->when($query['user_bookmark'], function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function groupCondition(&$builder, $query): Builder
    {
        return $builder->when($query['group'] ?? null, function ($builder) use ($query) {
            $builder->where('group', $query['group']);
        });
    }
}
