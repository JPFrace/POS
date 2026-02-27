<?php

namespace App\Http\Resources\Taxes;

use App\Enums\Method;
use App\Enums\Months;
use App\Enums\Period;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxSetupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $months = collect(Months::cases())->map(function ($month) {
            return [
                'value' => $month->value,
                'label' => $month->label(),
            ];
        });

        $periods = collect(Period::cases())->map(function ($period) {
            return [
                'value' => $period->value,
                'label' => $period->label(),
            ];
        });

        $methods = collect(Method::cases())->map(function ($method) {
            return [
                'value' => $method->value,
                'label' => $method->label(),
            ];
        });

        return array_merge(
            parent::toArray($request),
            [
                'start_tax_period_obj' => $months->firstWhere('value', $this->start_tax_period) ?? null,
                'period_obj' => $periods->firstWhere('value', $this->period) ?? null,
                'reporting_method_obj' => $methods->firstWhere('value', $this->reporting_method) ?? null,
            ]
        );

    }
}
