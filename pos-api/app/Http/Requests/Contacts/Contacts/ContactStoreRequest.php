<?php

namespace App\Http\Requests\Contacts\Contacts;

use App\Enums\ContactSubTypes;
use App\Models\Contact;
use App\Models\ContactSubType;
use App\Models\Country;
use App\Models\Tax;
use Illuminate\Foundation\Http\FormRequest;


class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $module = $this->vendor ? 'Vendors' : 'Customers';
        return $this->user()->can($module, ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_no' => 'required|string|max:10|unique:' . Contact::class . ',id_no',
            'sub_type_id' => 'required|integer|exists:contact_sub_types,id',
            'class_id' => 'required|integer|exists:contact_classes,id',
            'tax_id' => 'nullable|integer|exists:taxes,id',
            'name' => (!$this->is_individual ? 'required' : 'nullable') . '|string|max:150',
            'first_name' => ($this->is_individual ? 'required' : 'nullable') . '|string|max:50',
            'last_name' => ($this->is_individual ? 'required' : 'nullable') . '|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:5',
            'email' => 'nullable|email|max:100|unique:' . Contact::class . ',email',
            'billing_address' => 'nullable|string|max:500',
            'country_id' => 'nullable|integer|exists:countries,id',
            'zip_code' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:15',
            'contacts' => 'nullable|array',
            'created_by' => 'required|exists:users,id',
        ];
    }

    public function prepareForValidation()
    {
        // Check if the sub_type_id is provided and is INDIVIDUAL sub type
        $subType = ContactSubType::find($this->sub_type['id']);
        switch ($subType->name) {
            case ContactSubTypes::INDIVIDUAL->value:
                $this->merge([
                    'is_individual' => true,
                    'sub_type_id' => (int) $subType->id
                ]);
                break;
            default:
                $this->merge([
                    'is_individual' => false,
                    'sub_type_id' => $subType != null ? (int) $subType->id : null
                ]);
        }

        $this->merge(['zip_code' => !empty($this->zip_code) ? (string) $this->zip_code : null]);

        if ($this['id_no'] == null) {
            // 10 characters long
            $this->merge([
                'id_no' => substr((string) now()->timestamp, 0, 10)
            ]);
        }

        $this->merge([
            'created_by' => $this->user()->id,
            'class_id' => $this->class["id"] ?? null,
            'tax_id' => Tax::whereUuid($this->tax['id'] ?? null)?->value('id'),
            'country_id' => Country::whereUuid($this->country['id'] ?? null)?->value('id'),
        ]);
    }
}
