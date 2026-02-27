<?php

namespace App\Http\Requests\Business\Orders;

use App\Facades\ReferenceNumb;
use Carbon\Carbon;

class StoreOrderRequest extends OrderRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Purchase Orders', ['create']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'order_no' => [
                'required',
                'max:25',
                'unique:orders,order_no'
            ]
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
        $data = $this->all();
        if ($this->boolean('order_no_auto')) {
            $data['order_no'] = ReferenceNumb::generate('business_purchase_orders_number', \App\Models\Order::class, 'order_no', Carbon::parse($this->input('date')));
        }
        $this->replace($data);
    }

    public function messages()
    {
        return [
            'order_no.max' => 'The order number must not be longer than 25 characters.',
            'order_no.unique' => 'That order number is already in use. Please choose a different one.',
        ];
    }
}
