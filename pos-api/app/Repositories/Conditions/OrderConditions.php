<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait OrderConditions
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
            $builder->with('details');
        });
    }

    public function detailsWithProductWithExpenseCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.expense'], function ($builder) use ($query) {
            $builder->with('details.product.expense');
        });
    }

    public function orderNoCondition(&$builder, $query)
    {
        return $builder->when($query['order_no'], function ($builder) use ($query) {
            $builder->where('order_no', 'like', "%{$query['order_no']}%");
        });
    }

    public function dateBetweenCondition(&$builder, $query)
    {
        return $builder->when($query['date_between'] ?? null, function ($builder, $dates) {
            $builder->whereBetween('date', $dates);
        });
    }

    public function searchCondition(&$builder, $query)
    {
        return $builder->when($query['search'] ?? null, function ($builder, $search) {
            $builder->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere("vendor_name", "like", "%{$search}%");
            });
        });
    }
}