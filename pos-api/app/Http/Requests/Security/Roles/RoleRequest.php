<?php

namespace App\Http\Requests\Security\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'slug' => 'required|string|max:120',
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'is_inactive' => 'boolean',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str($this->name)->slug('_')->lower()->toString(),
        ]);
    }
}
