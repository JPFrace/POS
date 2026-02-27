<?php

namespace App\Http\Requests\Security\Positions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PositionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("User's Position", ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:15',
                Rule::unique('positions', 'code')->ignore($this->user_position->id),
            ],
            'title' => ['nullable', 'string', 'max:150'],
            'is_inactive' => ['required', 'boolean'],
        ];
    }
}
