<?php

namespace App\Http\Requests\Setup\Perks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerkStoreRequest extends FormRequest
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
                Rule::unique('perks', 'title')
            ],
            'description' => 'nullable|max:250',
            'active' => 'nullable|boolean'
        ];
    }
}
