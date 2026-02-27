<?php

namespace App\Http\Requests\Accounting\AccountUsageType;

use Illuminate\Foundation\Http\FormRequest;

class AccountUsageTypeStoreRequest extends FormRequest
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
            'code' => 'required|max:35|unique',
            'name' => 'required|max:35',
            'description' => 'nullable|max:35'
        ];
    }
}
