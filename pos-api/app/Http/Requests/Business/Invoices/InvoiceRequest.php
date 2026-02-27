<?php

namespace App\Http\Requests\Business\Invoices;

use App\Enums\InvoiceStatusEnum;
use App\Models\Contact;
use App\Models\PaymentType;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required',
            'due_date' => 'nullable',
            'remarks' => 'nullable|max:250',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'creator_id' => Rule::exists('users', 'id'),
            'customer_idno' => [
                'nullable',
                Rule::exists("contacts", "id_no")
            ],
            'customer_name' => 'required|max:120',
            'customer_email' => 'nullable|email|max:120',
            'billing_address' => 'nullable|max:250',
            'payment_method_id' => [
                'nullable',
                Rule::exists("payment_types", "id")
            ],
            'items.*.rate' => 'required|decimal:2',
            'items.*.tax_rate' => 'nullable|decimal:2',
            'items.*.quantity' => 'required|numeric',
            'items.*.product_name' => 'nullable|max:120',
            'items.*.product_income_id' => ['required', Rule::exists("chart_accounts", "id")],
            'items.*.product_receivable_id' => ['required', Rule::exists("chart_accounts", "id")],
            'items.*.product_description' => 'nullable|max:250',
        ];
    }

    public function prepareForValidation()
    {

        $user = $this->user();

        $items = array_filter(json_decode($this->items, true), fn($row) => !empty($row['product']['value']));

        $customer = json_decode($this->input('customer'), true) ?: [];

        $customer = !empty($customer['value']) ?
            Contact::whereUuid($customer['value'])->first() : null;

        $paymentMethod = !empty($this->payment_method) ?
            PaymentType::whereUuid($this->payment_method)->first() : null;

        $this->merge([
            'payment_method_id' => $paymentMethod?->id,
            'customer_idno' => $customer?->id_no,
            'customer_name' => $customer?->full_name,
            'customer_email' => $this->has('customer_email') ? $this->customer_email : $customer?->email,
            'billing_address' => $this->has('billing_address') ? $this->billing_address : $customer?->billing_address,
            'creator_id' => $user->id,
            'items' => array_map(function (array $row) {
                $product = !empty($row['product']['value']) ?
                    Product::whereUuid($row['product']['value'])->first() : null;

                return [
                    ...$row,
                    'product_id' => $product?->id,
                    'product_income_id' => $product?->incomeAccount?->id,
                    'product_receivable_id' => $product?->receivableAccount?->id,
                    'quantity' => Amount::acceptable($row['quantity']),
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', ''),
                    'tax_rate' => number_format(Amount::acceptable($row['tax_rate']), 2, '.', '')
                ];
            }, $items),
            'date' => Carbon::parse($this->date),
            'due_date' => !empty($this->due_date) ? Carbon::parse($this->due_date) : null
        ]);
    }
}
