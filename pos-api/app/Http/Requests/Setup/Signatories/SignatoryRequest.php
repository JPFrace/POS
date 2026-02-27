<?php

namespace App\Http\Requests\Setup\Signatories;

use Illuminate\Foundation\Http\FormRequest;

class SignatoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            // 'attachment' => 'nullable|mimes:jpeg,jpeg,png,pdf',
            'created_by' => 'nullable|integer',
        ];
    }
}
