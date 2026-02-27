<?php

namespace App\Http\Requests\ShopCenter\Orders;

use App\Enums\OrderStatus;
use App\Models\DeliveryType;
use App\Models\MemberAddress;
use App\Models\Attribute;
use App\Models\Courier;
use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use App\Services\Builder;
use App\Supports\Transformers\Builder\Entry;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'member_id' => 'required',
            // 'courier_id' => 'required',
            // 'payment_method_id' => 'required',
            // 'delivery_address_id' => 'required',
            // 'delivery_type_id' => 'required',
            'paid_amount' => 'required',
            'paid_at' => 'required',
            'total_price' => 'required',
            'status' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->replace([
            ...$this->all(),
            'member_id' => $this->getMemberId(),
            'courier_id' => $this->getCourierId(),
            'payment_method_id' => $this->getPaymentMethodId(),
            'paid_amount' => $this->getTotal(),
            'paid_at' => Carbon::now(),
            'total_price' => $this->getTotal(),
            'status' => OrderStatus::UNPAID->value,
            'items' => $this->getItems(),
            'delivery_address_id' => $this->getDeliveryAddressId(),
            'delivery_type_id' => $this->getDeliveryTypeId()
        ]);
    }

    /**
     * Get member id
     * default is current login id for member accounts,
     * if the current login is admin, get the first record of member database
     * @return int
     */
    public function getMemberId(): int
    {
        $memberId = $this->user()->id;

        if ($this->user() instanceof User) {
            $memberId = Member::admin()->first()->id;
        }

        return $memberId;
    }

    /**
     * Get courier id
     * @return mixed
     */
    public function getCourierId(): ?int
    {
        return Courier::whereUuid($this->courier_uuid)->first()?->id;
    }

    /**
     * Get payment method id
     * @return mixed
     */
    public function getPaymentMethodId(): ?int
    {
        return PaymentMethod::whereUuid($this->payment_method_uuid)->first()?->id;
    }

    /**
     * Get delivery address id
     * @return mixed
     */
    public function getDeliveryAddressId(): ?int
    {
        return MemberAddress::whereUuid($this->delivery_address_uuid)->first()?->id;
    }

    /**
     * Get delivery address id
     * @return mixed
     */
    public function getDeliveryTypeId(): ?int
    {
        return DeliveryType::whereUuid($this->delivery_type_uuid)->first()?->id;
    }

    /**
     * Get total purchased
     * @return float
     */
    public function getTotal(): float
    {
        return array_reduce($this->getItems(), function ($sum, $item) {
            $sum += $item['quantity'] * $item['price'];

            return $sum;
        }, 0);
    }

    /**
     * Get items purchased
     * @return array<array|mixed|null>[]
     */
    public function getItems(): array
    {
        return array_map(function ($item) {
            $product = Product::whereUuid($item['product']['uuid'])->first();

            $attribute_id = null;
            $registration = null;
            if (isset($item['product']['attribute']['uuid'])) {
                $attribute_id = Attribute::whereUuid($item['product']['attribute']['uuid'])->first()->id;
                $registration = Builder::single(new Entry($item['product']['attribute']['contents'] ?? []));
            }

            return [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'attribute_id' => $attribute_id,
                'registration' => $registration,
                'status' => OrderStatus::UNPAID->value,
            ];
        }, $this->items);
    }
}
