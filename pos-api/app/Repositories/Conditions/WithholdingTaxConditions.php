<?php

namespace App\Repositories\Conditions;

trait WithholdingTaxConditions
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

    public function payerTypeCondition(&$builder, $query)
    {
        return $builder->when($query['payer_type'], function ($builder) use ($query) {
            $builder->with("payerType");
        });
    }

    public function taxTypeCondition(&$builder, $query)
    {
        return $builder->when($query['tax_type'], function ($builder) use ($query) {
            $builder->with('taxType');
        });
    }
}