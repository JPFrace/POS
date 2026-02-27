<?php

namespace App\Http\Requests\Setup\Companies;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Company", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'tin_no' => 'required|string|max:25',
            'address' => 'required|string',
            'phone' => 'required|string|max:150',
            'email' => 'nullable|email|max:75',
            'logo' => 'nullable|string|max:255',
        ];
    }
}
