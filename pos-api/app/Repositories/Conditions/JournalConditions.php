<?php

namespace App\Repositories\Conditions;

trait JournalConditions
{
    public function detailsCondition(&$builder, $query)
    {
        return $builder->when($query['details'], function ($builder) use ($query) {
            $builder->with('details');
        });
    }

    public function accountCondition(&$builder, $query)
    {
        return $builder->when($query['account'], function ($builder) use ($query) {
            $builder->with('details.chartAccount', 'details.chartAccount.type', 'details.chartAccount.type.category');
        });
    }

    public function jeNoCondition(&$builder, $query)
    {
        return $builder->when($query['je_no'], function ($builder) use ($query) {
            $builder->where('je_no', 'like', "%{$query['je_no']}%");
        });
    }

    public function statusCondition(&$builder, $query)
    {
        return $builder->when($query['status'], function ($builder) use ($query) {
            $builder->with(['status']);
        });
    }

    public function dateBetweenCondition(&$builder, $query)
    {
        return $builder->when($query['date_between'] ?? null, function ($builder, $dates) {
            $builder->whereBetween('date', $dates);
        });
    }

    public function filterStatusCondition(&$builder, $query)
    {
        return $builder->when(
            $query['filter_status'] ?? null,
            fn($builder, $statusUuid) => $builder->whereRelation('status', 'uuid', $statusUuid)
        );
    }


    public function searchCondition(&$builder, $query)
    {
        return $builder->when($query['search'] ?? null, function ($builder, $search) {
            $builder->where(function ($q) use ($search) {
                $q->where('je_no', 'like', "%{$search}%")
                    ->orWhere("ref_no", "like", "%{$search}%");
            });
        });
    }
}