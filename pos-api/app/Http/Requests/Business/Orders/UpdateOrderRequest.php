<?php

namespace App\Http\Requests\Business\Orders;

use Illuminate\Validation\Rule;

class UpdateOrderRequest extends OrderRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Purchase Orders', ['edit']);
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
            'order_no' => [
                'required',
                'max:25',
                Rule::unique('orders')->ignore($this->order->id)
            ],
        ];
    }
    public function messages()
    {
        return [
            'order_no.max' => 'The order number must not be longer than 25 characters.',
            'order_no.unique' => 'That order number is already in use. Please choose a different one.',
        ];
    }
}
