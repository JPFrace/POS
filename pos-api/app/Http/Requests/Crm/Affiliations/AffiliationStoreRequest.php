<?php

namespace App\Http\Requests\Crm\Affiliations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AffiliationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:120',
                Rule::unique('affiliations', 'name')
            ],
            'description' => 'nullable|max:250',
            'user_defined' => 'nullable'
        ];
    }
}
