<?php

namespace App\Http\Requests\Setup\WithholdingTaxes;

use App\Models\ContactSubType;
use App\Models\WithholdingTaxType;
use Illuminate\Foundation\Http\FormRequest;

class StoreWithholdingTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Withholding Taxes", ['create']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:25',
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:255',
        ];
    }
}
