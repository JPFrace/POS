<?php

namespace App\Http\Requests\Accounting\Dimension;

use Illuminate\Foundation\Http\FormRequest;

class DimensionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:155|unique:dimensions,name',
            'description' => 'nullable|string|max:255',
            'is_inactive' => 'nullable|boolean'
        ];
    }
}
