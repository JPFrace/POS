<?php

namespace App\Repositories\Conditions;

use App\Enums\AccountCategory as EnumsAccountCategory;

use App\Enums\AccountUsageType as EnumAccountUsageType;
use Illuminate\Database\Eloquent\Builder;



trait ChartAccountConditions
{
    public function typeCondition(&$builder, $query)
    {
        return $builder->when($query['type'], function ($builder) use ($query) {
            $builder->with('type');
        });
    }

    public function classCondition(&$builder, $query)
    {
        return $builder->when($query['class'], function ($builder) use ($query) {
            $builder->with('class');
        });
    }

    public function departmentCondition(&$builder, $query)
    {
        return $builder->when($query['department'], function ($builder) use ($query) {
            $builder->with('department');
        });
    }

    public function categoryCondition(&$builder, $query)
    {
        return $builder->when($query['category'], function ($builder) use ($query) {
            $builder->with('type.category');
        });
    }

    public function usageTypeCondition(&$builder, $query)
    {
        return $builder->when($query['usage_type'], function ($builder) use ($query) {
            $builder->with('usageType');
        });
    }

    public function categoryUuidCondition(&$builder, $query)
    {
        return $builder->when($query['category_uuid'], function (Builder $builder) use ($query) {
            $builder->whereRelation('type', function (Builder $builder) use ($query) {
                $builder->whereRelation('category', 'uuid', $query['category_uuid']);
            });
        });
    }

    public function childrenCondition(&$builder, $query)
    {
        return $builder->when($query['children'], function ($builder) use ($query) {
            $builder->when(isset($query['children_type']), function (Builder $builder) use ($query) {
                $builder->when(isset($query['children_category']), function ($builder) use ($query) {
                    $builder->with(['children.children.type.category', 'children.type.category']);
                }, fn(Builder $builder) => $builder->with(['children.children.type', 'children.type']));
            })
                ->when(isset($query['children_class']), function ($builder) use ($query) {
                    $builder->with(['children.children.class', 'children.class']);
                })
                ->when(isset($query['children_dept']), function ($builder) use ($query) {
                    $builder->with(['children.children.department', 'children.department']);
                });
        });
    }

    public function childrenTypeCondition(&$builder, $query)
    {
        return $builder;
    }

    public function childrenClassCondition(&$builder, $query)
    {
        return $builder;
    }

    public function childrenCategoryCondition(&$builder, $query)
    {
        return $builder;
    }

    public function parentOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['parent_only'], function ($builder) use ($query) {
            $builder->whereDoesntHave('parent');
        });
    }

    public function categoryExpenseCondition(&$builder, $query)
    {
        return $builder->when($query['category_expense'], function ($builder) use ($query) {
            $builder->with('type.category')->WhereHas('type.category', function ($q) use ($query) {
                $q->where('name', EnumsAccountCategory::EXPENSES->value);
            });
            ;
        });
    }
    public function categoryRevenueCondition(&$builder, $query)
    {
        return $builder->when($query['category_revenue'], function ($builder) use ($query) {
            $builder->with('type.category')->WhereHas('type.category', function ($q) use ($query) {
                $q->where('name', EnumsAccountCategory::REVENUE->value);
            });
            ;
        });
    }

    public function cashInBankCondition(&$builder, $query)
    {
        return $builder->when($query['cash_in_bank'], function ($builder) use ($query) {
            $builder->whereRelation('usageType', 'name', EnumAccountUsageType::CASH_IN_BANK);
        });
    }

    public function depositoryOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['depository_only'], function ($builder) {
            $builder->with('usageType')->WhereHas('usageType', function ($q) {
                $q->where('name', EnumAccountUsageType::DEPOSITORY);
            });
        });
    }

    public function payableOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['payable_only'], function ($builder) {
            $builder->whereHas('usageType', function ($q) {
                $q->where('name', EnumAccountUsageType::ACCOUNTS_PAYABLE);
            });
        });
    }

    public function cashInBankWithUndepositedCondition(&$builder, $query)
    {
        return $builder->when($query['cash_in_bank.undeposited'], function ($builder) use ($query) {
            $builder->where(function ($builder) use ($query) {
                $builder->whereRelation('usageType', 'name', EnumAccountUsageType::CASH_IN_BANK)
                    ->orWhereRelation('usageType', 'name', EnumAccountUsageType::UNDEPOSITED);
            });
        });
    }
}