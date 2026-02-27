<?php

namespace App\Http\Requests\Accounting\Dimension;

use Illuminate\Validation\Rule;

class DimensionUpdateRequest extends DimensionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Accounting.Dimensions', ['Edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => [
                'required',
                'string',
                'max:155',
                Rule::unique('dimensions')->ignore($this->dimension->id)
            ],
        ];
    }
}
