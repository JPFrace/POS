<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait InvoiceConditions
{
    public function customerCondition(&$builder, $query)
    {
        return $builder->when($query['customer'], function ($builder) use ($query) {
            $builder->with('customer');
        });
    }

    public function paymentMethodCondition(&$builder, $query)
    {
        return $builder->when($query['payment_method'], function ($builder) use ($query) {
            $builder->with('paymentMethod');
        });
    }

    public function detailsCondition(&$builder, $query)
    {
        return $builder->when($query['details'], function ($builder) use ($query) {
            $builder->with('details');
        });
    }

    public function productCondition(&$builder, $query)
    {
        return $builder->when($query['product'], function ($builder) use ($query) {
            $builder->with('details.product');
        });
    }

    public function statusCondition(&$builder, $query)
    {
        return $builder->when($query['status'], function ($builder) use ($query) {
            $builder->with('status');
        });
    }

    public function invoiceNoCondition(&$builder, $query)
    {
        return $builder->when($query['invoice_no'], function ($builder) use ($query) {
            $builder->where('invoice_no', 'like', "%{$query['invoice_no']}%");
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
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere("customer_name", "like", "%{$search}%");
            });
        });
    }
}