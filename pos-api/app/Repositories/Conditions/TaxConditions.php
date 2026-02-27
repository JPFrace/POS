<?php

namespace App\Repositories\Conditions;

use App\Enums\TaxTypes;

trait TaxConditions
{
    public function codeDescCondition(&$builder, $query)
    {
        $value = $query['codeDesc'] ?? null;
        return $builder->when(
            filled($value),
            fn($q) =>
            $q->where(
                fn($w) =>
                $w->where('code', 'like', "%{$value}%")
                    ->orWhere('description', 'like', "%{$value}%")
                    ->orWhere('rate', 'like', "%{$value}%")
            )
        );
    }

    public function parentOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['parent_only'], function ($builder) use ($query) {
            $builder->whereDoesntHave('parent');
        });
    }

    public function taxAgencyIdCondition(&$builder, $query)
    {
        return $builder->when(
            filled($query['tax_agency_id'] ?? null),
            fn($builder) => $this->whereUuid($builder, 'taxAgency', $query['tax_agency_id'])
        );
    }

    public function classCondition(&$builder, $query)
    {
        return $builder->when(isset($query['class']) && $query['class'], function ($builder) use ($query) {
            $builder->with('class');
        });
    }


    public function chartAccountCondition(&$builder, $query)
    {
        return $builder->when(isset($query['chartAccount']) && $query['chartAccount'], function ($builder) use ($query) {
            $builder->with('chartAccount');
        });
    }

    public function taxAgencyCondition(&$builder, $query)
    {
        return $builder->when(isset($query['taxAgency']) && $query['taxAgency'], function ($builder) use ($query) {
            $builder->with('taxAgency');
        });
    }

    public function childrenClassCondition(&$builder, $query)
    {
        return $builder;
    }

    public function childrenCondition(&$builder, $query)
    {
        return $builder->when($query['children'] ?? false, function ($builder) use ($query) {

            $builder->when(isset($query['children_chartAccount']), function ($builder) {
                $builder->with(['children.chartAccount']);
            });

            $builder->when(isset($query['children_taxAgency']), function ($builder) {
                $builder->with(['children.taxAgency']);
            });

            $builder->when(isset($query['children_class']), function ($builder) {
                $builder->with(['children.class']);
            });

            $builder->when(isset($query['children_parent']), function ($builder) {
                $builder->with(['children.parent']);
            });

            $builder->when(isset($query['parent']), function ($builder) {
                $builder->with('parent');
            });

        });
    }

    public function childrenParentCondition(&$builder, $query)
    {
        return $builder;
    }
    public function childrenChartAccountCondition(&$builder, $query)
    {
        return $builder;
    }


    public function childrenTaxAgencyCondition(&$builder, $query)
    {
        return $builder;
    }


    public function typeCondition(&$builder, $query)
    {
        return $builder->when($query['type'], function ($builder) use ($query) {
            $builder->where("type", $query["type"]);
        });
    }

    public function rateTypeCondition(&$builder, $query)
    {
        return $builder->when($query['rateType'], function ($builder) use ($query) {
            $builder->where("rateType", $query["rateType"]);
        });
    }

    public function vatOnlyCondition(&$builder, $query)
    {
        return $builder->when($query['vat_only'], function ($builder) use ($query) {
            $builder->where("type", TaxTypes::VAT);
        });
    }

}