<?php

namespace App\Http\Requests\Setup\WithholdingTaxes;

use App\Models\WithholdingTaxType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWithholdingTaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Withholding Taxes", ['edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'code' => [
                'required',
                'string',
                'max:25',
                Rule::unique('withholding_taxes', 'code')->ignore($this->route('withholding_tax')),
            ],
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'type_id' => 'required|exists:withholding_tax_types,id',
            'payer_type_id' => 'required|exists:contact_sub_types,id',
        ];
    }


    public function prepareForValidation(): void
    {
        $this->merge([
            'type_id' => isset($this->type['value']) ? WithholdingTaxType::whereUuid($this->type['value'])->first()->id : null,
            'payer_type_id' => isset($this->payer_type['value']) ? $this->payer_type['value'] : null,
        ]);
    }
}
