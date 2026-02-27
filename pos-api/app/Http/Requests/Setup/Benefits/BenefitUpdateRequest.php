<?php

namespace App\Http\Requests\Setup\Benefits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BenefitUpdateRequest extends FormRequest
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
            'title' => [
                'required',
                'max:120',
                Rule::unique('benefits', 'title')->ignore($this->benefit->id)
            ],
            'description' => 'nullable|max:250',
            'active' => 'nullable|boolean'
        ];
    }
}
