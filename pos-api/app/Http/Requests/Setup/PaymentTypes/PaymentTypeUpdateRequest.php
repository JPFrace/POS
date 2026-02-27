<?php

namespace App\Http\Requests\Setup\PaymentTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentTypeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Payment Types", ['edit']);
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
                'max:10',
                Rule::unique('payment_types', 'code')->ignore($this->payment_type->id)
            ],
            'name' => [
                'required',
                'max:120',
                Rule::unique('payment_types', 'name')->ignore($this->payment_type->id)
            ],
        ];
    }
}
