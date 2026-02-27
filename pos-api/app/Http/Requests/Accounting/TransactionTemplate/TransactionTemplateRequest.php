<?php

namespace App\Http\Requests\Accounting\TransactionTemplate;

use Illuminate\Foundation\Http\FormRequest;

class TransactionTemplateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:transaction_templates,name',
            'description' => 'nullable|string',
            'is_inactive' => 'boolean',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|integer|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.amount' => 'nullable|numeric|min:0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $details = collect($this->input('details', []))
            ->map(function (array $detail) {

                $product = \App\Models\Product::where('uuid', $detail['product']['id'])->first();

                return [
                    'product_id' => $product?->id,
                    'quantity' => (int) ($detail['quantity'] ?? 1),
                    'amount' => $detail['amount'] !== '' ? (float) $detail['amount'] : null,
                ];
            })
            ->all();

        $this->merge([
            'name' => trim($this->input('name')),
            'description' => $this->filled('description') ? trim($this->input('description')) : null,
            'is_inactive' => $this->boolean('is_inactive'),
            'details' => $details,
        ]);
    }


    public function messages(): array
    {
        return [
            'name.unique' => 'A template with this name already exists.',
            'details.*.product_id.required' => 'Product is required for all detail items.',
            'details.*.product_id.exists' => 'One or more products are invalid.',
            'details.*.quantity.min' => 'Quantity must be at least 1.',
            'details.*.quantity.required' => 'Quantity is required for all detail items.',
        ];
    }
}
