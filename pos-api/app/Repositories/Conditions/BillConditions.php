<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait BillConditions
{
    public function vendorCondition(&$builder, $query)
    {
        return $builder->when($query['vendor'], function ($builder) use ($query) {
            $builder->with('vendor');
        });
    }

    public function detailsCondition(&$builder, $query)
    {
        return $builder->when($query['details'], function ($builder) use ($query) {
            $builder->with('details.product');
        });
    }

    public function productCondition(&$builder, $query)
    {
        return $builder->when($query['product'], function ($builder) use ($query) {
            $builder->with('details.product');
        });
    }

    public function billNoCondition(&$builder, $query)
    {
        return $builder->when($query['bill_no'], function ($builder) use ($query) {
            $builder->where('bill_no', 'like', "%{$query['bill_no']}%");
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

    public function termCondition(&$builder, $query)
    {
        return $builder->when($query['term'], function ($builder) use ($query) {
            $builder->with('term');
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
                $q->where('bill_no', 'like', "%{$search}%")
                    ->orWhere("vendor_name", "like", "%{$search}%");
            });
        });
    }
}