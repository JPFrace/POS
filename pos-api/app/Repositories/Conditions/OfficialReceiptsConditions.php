<?php

namespace App\Repositories\Conditions;

use App\Enums\AccountUsageType;

trait OfficialReceiptsConditions
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

    public function denominationsCondition(&$builder, $query)
    {
        return $builder->when($query['denominations'], function ($builder) use ($query) {
            $builder->with(['denominations.deposit_account.children', 'denominations.payment_method']);
        });
    }

    public function transdimWithDimensionCondition(&$builder, $query)
    {
        return $builder->when($query['transdim.dimension'], function ($builder) use ($query) {
            $builder->with('transactionDimensions.dimension');
        });
    }

    public function typeCondition(&$builder, $query)
    {
        return $builder->when($query['type'], function ($builder) use ($query) {
            $builder->with('type');
        });
    }

    public function statusCondition(&$builder, $query)
    {
        return $builder->when($query['status'], function ($builder) use ($query) {
            $builder->with(['status']);
        });
    }

    public function depositWithChildrenCondition(&$builder, $query)
    {
        return $builder->when($query['deposit.children'], function ($builder) use ($query) {
            $builder->with('deposit.children');
        });
    }

    public function detailsWithProductWithExpenseCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.expense'], function ($builder) use ($query) {
            $builder->with('details.product.expense');
        });
    }

    public function detailsWithProductWithSalesTaxCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.sales_tax'], function ($builder) use ($query) {
            $builder->with('details.product.salesTax');
        });
    }

    public function detailsWithProductWithWithholdingTaxCondition(&$builder, $query)
    {
        return $builder->when($query['details.product.withholding_tax'], function ($builder) use ($query) {
            $builder->with('details.product.withholdingTax');
        });
    }

    public function orNoCondition(&$builder, $query)
    {
        return $builder->when($query['or_no'], function ($builder) use ($query) {
            $builder->where('or_no', 'like', "%{$query['or_no']}%");
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
                $q->where('or_no', 'like', "%{$search}%")
                    ->orWhere("customer_name", "like", "%{$search}%");
            });
        });
    }
    public function undepositedMoneyCondition(&$builder, $query)
    {
        return $builder->when($query['undeposited_money'] ?? null, function ($builder, $query) {
            $builder->whereRelation('deposit', function ($builder) use ($query) {
                $builder->usage(AccountUsageType::UNDEPOSITED);
            });
        });
    }

    public function notDepositTransitCondition(&$builder, $query)
    {
        return $builder->when(
            $query['not_deposit_transit'],
            fn($builder) => $builder->whereNull('deposit_transit_at')
        );
    }
}
