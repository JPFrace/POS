<?php

namespace App\Http\Requests\Contacts\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'id_no' => 'required|string|max:10|unique:contacts,id_no',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:5',
            'email' => 'required|email|max:100|unique:contacts,email',
            'billing_address' => 'required|string|max:500',
            'country' => 'required|string|max:50',
            'zip_code' => 'required|string',
            'contact_number' => 'required|string|max:15',
            'contacts' => 'nullable|array',
            'created_by' => 'required|exists:users,id',
        ];
    }

    public function prepareForValidation()
    {
        if ($this['id_no'] == null) {
            // 10 characters long
            $this->merge([
                'id_no' => substr((string) now()->timestamp, 0, 10)
            ]);
        }

        $this->merge([
            'created_by' => 1,
        ]);
    }
}
