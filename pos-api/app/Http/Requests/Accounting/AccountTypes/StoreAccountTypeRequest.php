<?php

namespace App\Http\Requests\Accounting\AccountTypes;

use App\Models\AccountCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Accounting.Account Types', ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:35|unique:\App\Models\AccountType,name',
            'description' => 'nullable|max:250',
            'is_inactive' => 'nullable',
            'category_id' => [
                'required',
                Rule::exists('account_categories', 'id')
            ]
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'category_id' => isset($this->category['value']) ? AccountCategory::whereUuid($this->category['value'])->first()?->id : null
        ]);
    }
}
