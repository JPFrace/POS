<?php

namespace App\Http\Requests\Contacts\Contacts;

use App\Enums\ContactSubTypes;
use App\Models\ContactSubType;
use App\Models\Country;
use App\Models\Tax;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $module = $this->vendor ? 'Vendors' : 'Customers';
        return $this->user()->can($module, ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'sub_type_id' => 'required|integer|exists:contact_sub_types,id',
            'class_id' => 'required|integer|exists:contact_classes,id',
            'tax_id' => 'nullable|integer|exists:taxes,id',
            'file_id' => 'nullable|integer|exists:files,id',
            'name' => (!$this->is_individual ? 'required' : 'nullable') . '|string|max:150',
            'first_name' => ($this->is_individual ? 'required' : 'nullable') . '|string|max:50',
            'last_name' => ($this->is_individual ? 'required' : 'nullable') . '|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:5',
            'email' => 'nullable|email|max:100|' . Rule::unique('contacts', 'email')->ignore(id: $this->model->id),
            'billing_address' => 'nullable|string|max:500',
            'country_id' => 'nullable|integer|exists:countries,id',
            'zip_code' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:15',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,jfif',
            'contacts' => 'nullable|array',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'model' => $this->vendor ?? $this->customer ?? null
        ]);

        $subType = $this->ensureArray($this->sub_type);
        $class = $this->ensureArray($this->class);
        $contacts = $this->ensureArray($this->contacts);
        $tax = $this->ensureArray($this->tax);
        $country = $this->ensureArray($this->country);

        if ($subTypeId = $subType['value'] ?? $subType['id'] ?? null) {
            if ($subTypeModel = ContactSubType::find($subTypeId)) {
                $this->merge([
                    'is_individual' => $subTypeModel->name === ContactSubTypes::INDIVIDUAL->value,
                    'sub_type_id' => $subTypeModel->id,
                ]);
            }
        }

        $this->merge([
            'class_id' => $class['value'] ?? $class['id'] ?? null,
            'tax_id' => Tax::whereUuid($tax['id'] ?? null)?->value('id'),
            'country_id' => Country::whereUuid($country['id'] ?? null)?->value('id'),
            'contacts' => $contacts,
        ]);
    }

    private function ensureArray($value): ?array
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }

        return is_array($value) ? $value : null;
    }
}

