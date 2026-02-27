<?php

namespace App\Repositories\Conditions;

trait ProductConditions
{
    public function categoryCondition(&$builder, $query)
    {
        return $builder->when($query['category'], function ($builder) {
            $builder->with('category');
        });
    }

    public function nameSkuCondition(&$builder, $query)
    {
        return $builder->when(isset($query['name_sku']) && $query['name_sku'], function ($builder) use ($query) {
            $builder->where('name', 'like', '%' . $query['name_sku'] . '%')->orWhere('sku', 'like', '%' . $query['name_sku'] . '%');
        });
    }

    public function incomeAccountCondition(&$builder, $query)
    {
        return $builder->when($query['income_account'], function ($builder) {
            $builder->with([
                'incomeAccount',
            ]);
        });
    }

    public function expenseAccountCondition(&$builder, $query)
    {
        return $builder->when($query['expense_account'], function ($builder) {
            $builder->with([
                'expenseAccount',
            ]);
        });
    }

    public function receivableAccountCondition(&$builder, $query)
    {
        return $builder->when($query['receivable_account'], function ($builder) {
            $builder->with([
                'receivableAccount',
            ]);
        });
    }

    public function depositoryAccountCondition(&$builder, $query)
    {
        return $builder->when($query['depository_account'], function ($builder) {
            $builder->with('depositoryAccount');
        });
    }

    public function payableAccountCondition(&$builder, $query)
    {
        return $builder->when($query['payable_account'], function ($builder) {
            $builder->with('payableAccount');
        });
    }

    public function vendorCondition(&$builder, $query)
    {
        return $builder->when($query['vendor'], function ($builder) {
            $builder->with('vendor');
        });
    }

    public function incomeCondition(&$builder, $query)
    {
        return $builder->when($query['income'], function ($builder) {
            $builder->with('income');
        });
    }
    public function expenseCondition(&$builder, $query)
    {
        return $builder->when($query['expense'], function ($builder) {
            $builder->with('expense');
        });
    }

    public function depositoryCondition(&$builder, $query)
    {
        return $builder->when($query['depository'], function ($builder) {
            $builder->with('depository');
        });
    }

    public function payableCondition(&$builder, $query)
    {
        return $builder->when($query['payable'], function ($builder) {
            $builder->with('payable');
        });
    }

    public function salesTaxCondition(&$builder, $query)
    {
        return $builder->when($query['sales_tax'], function ($builder) use ($query) {
            $builder->with("salesTax");
        });
    }

    public function withholdingTaxCondition(&$builder, $query)
    {
        return $builder->when($query['withholding_tax'], function ($builder) use ($query) {
            $builder->with("withholdingTax");
        });
    }
}
