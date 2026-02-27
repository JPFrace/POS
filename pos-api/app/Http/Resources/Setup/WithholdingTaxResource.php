<?php

namespace App\Http\Resources\Setup;

use App\Http\Resources\Contact\ContactSubTypeResource;
use App\Http\Resources\Security\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithholdingTaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'code' => $this->code,
            'rate' => $this->rate,
            'description' => $this->description,
            'is_inactive' => $this->is_inactive,
            'type' => new WithholdingTaxTypesResource($this->taxtype),
            'payer_type' => new ContactSubTypeResource($this->payertype),
            'created_by' => new UserResource($this->createdby),
            'created_at' => $this->created_at ? $this->created_at->format('m/d/Y') : null,
        ];
    }
}
