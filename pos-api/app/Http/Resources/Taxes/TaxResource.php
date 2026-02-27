<?php

namespace App\Http\Resources\Taxes;

use App\Enums\RateType;
use App\Enums\TaxTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        $tax_types = collect(TaxTypes::cases())->map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->label(),
            ];
        });
        $rate_types = collect(RateType::cases())->map(function ($rate_type) {
            return [
                'value' => $rate_type->value,
                'label' => $rate_type->label(),
            ];
        });
        return array_merge(
            parent::toArray($request),
            [
                'type_obj' => $tax_types->firstWhere('value', $this->type) ?? null,
                'rate_type_obj' => $rate_types->firstWhere('value', $this->rate_type) ?? null,

            ]
        );
    }
}
