<?php

namespace App\Http\Requests\ShopCenter\DeliveryMethods;

use App\Models\Courier;
use App\Models\DeliveryType;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryMethodStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'courier' => Rule::exists('couriers', 'uuid'),
            'delivery_type' => Rule::exists('delivery_types', 'uuid'),
            'payment_method' => Rule::exists('payment_methods', 'uuid'),

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $courier = json_decode($this->courier, true);
        $delivery_type = json_decode($this->delivery_type, true);
        $payment_method = json_decode($this->payment_method, true);

        $this->replace([
            ...$this->all(),
            'courier' => $courier['value'] ?? null,
            'delivery_type' => $delivery_type['value'] ?? null,
            'payment_method' => $payment_method['value'] ?? null,

        ]);
    }



    public function passedValidation()
    {
        $this->merge([
            'courier_id' => Courier::whereUuid($this->courier)->first()->id,
            'delivery_type_id' => DeliveryType::whereUuid($this->delivery_type)->first()->id,
            'payment_method_id' => PaymentMethod::whereUuid($this->payment_method)->first()->id,
        ]);
    }
}
