<?php

namespace App\Http\Requests\Setup\PaymentTypes;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTypeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Payment Types", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "code" => "required|max:10|unique:payment_types,code",
            "name" => "required|max:120|unique:payment_types,name"
        ];
    }
}
