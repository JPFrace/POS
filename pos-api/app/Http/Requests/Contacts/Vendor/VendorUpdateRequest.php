<?php

namespace App\Http\Requests\Contacts\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorUpdateRequest extends FormRequest
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
            'type' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:5',
            Rule::unique('contacts', 'email')->ignore($this->vendor->id),
            'billing_address' => 'required|string|max:500',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:50',
            'zip_code' => 'required|string',
            'contact_number' => 'required|string|max:15',
            'contacts' => 'nullable|array',
        ];
    }
}
