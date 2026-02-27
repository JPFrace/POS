<?php

namespace App\Repositories\Conditions;

trait PaymentsConditions
{
    public function refNoCondition(&$builder, $query)
    {
        return $builder->when($query['ref_no'], function ($builder) use ($query) {
            $builder->where('ref_no', 'like', "%{$query['ref_no']}%");
        });
    }

    public function dateBetweenCondition(&$builder, $query)
    {
        return $builder->when($query['date_between'] ?? null, function ($builder, $dates) {
            $builder->whereBetween('date', $dates);
        });
    }

    public function payeeCondition(&$builder, $query)
    {
        return $builder->when($query['payee'], function ($builder) use ($query) {
            $builder->with('payee');
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

    public function detailsWithProductWithExpenseCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.expense'], function ($builder) use ($query) {
            $builder->with('details.product.expenseAccount');
        });
    }

    public function detailsWithContactCondition(&$builder, $query)
    {
        return $builder->when($query['details.contact'], function ($builder) use ($query) {
            $builder->with('details.subContact');
        });
    }

    public function detailsWithProductWithPayableCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.payable'], function ($builder) use ($query) {
            $builder->with('details.product.payable');
        });
    }

    public function statusCondition(&$builder, $query)
    {
        return $builder->when($query['status'], function ($builder) use ($query) {
            $builder->with('status');
        });
    }

    public function cashInBankCondition(&$builder, $query)
    {
        return $builder->when($query['cash_in_bank'], function ($builder) use ($query) {
            $builder->with('cashInBank');
        });
    }

    public function cashInBankWithTypeCondition(&$builder, $query)
    {
        return $builder->when($query['cash_in_bank.type'], function ($builder) use ($query) {
            $builder->with('cashInBank.type');
        });
    }

    public function transdimWithDimensionCondition(&$builder, $query)
    {
        return $builder->when($query['transdim.dimension'], function ($builder) use ($query) {
            $builder->with('transactionDimensions.dimension');
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
                $q->where('ref_no', 'like', "%{$search}%")
                    ->orWhere("payee_name", "like", "%{$search}%");
            });
        });
    }
}