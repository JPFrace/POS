<?php

namespace App\Http\Requests\Setup\Reports;

use Illuminate\Foundation\Http\FormRequest;


class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Setup.Setup Reports", ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:75',
            'description' => 'nullable|string|max:250',
            'is_inactive' => 'boolean',
        ];
    }
}
