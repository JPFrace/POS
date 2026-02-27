<?php

namespace App\Http\Requests\Externals;

use Illuminate\Foundation\Http\FormRequest;

class ExternalUpdateRequest extends ExternalRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Transactions', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            parent::rules()
        ];
    }
}
