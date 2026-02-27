<?php

namespace App\Http\Requests\Security\Positions;

use Illuminate\Foundation\Http\FormRequest;

class PositionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("User's Position", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:15', 'unique:positions,code'],
            'title' => ['nullable', 'string', 'max:150'],
            'is_inactive' => ['required', 'boolean'],
        ];
    }
}
