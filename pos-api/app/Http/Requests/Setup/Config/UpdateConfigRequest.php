<?php

namespace App\Http\Requests\setup\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Configs", ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:25',
                Rule::unique('config', 'slug')->ignore($this->route('config')),
            ],
            'type' => 'required|string|max:50',
            'value' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:config,id',
            'prefix' => 'nullable|string|max:25',
            'suffix' => 'nullable|string|max:25',
            'is_inactive' => 'boolean',
        ];
    }
}
