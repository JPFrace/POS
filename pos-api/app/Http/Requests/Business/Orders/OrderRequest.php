<?php

namespace App\Http\Requests\Business\Orders;

use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\PaymentType;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'remarks' => 'nullable|max:250',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf',
            'creator_id' => Rule::exists('users', 'id'),
            'vendor_idno' => [
                'nullable',
                Rule::exists("contacts", "id_no")
            ],
            'vendor_name' => 'required|max:120',
            'vendor_email' => 'nullable|email|max:120',
            'billing_address' => 'nullable|max:250',
            'items.*.rate' => 'required|decimal:2',
            'items.*.quantity' => 'required|numeric',
            'items.*.product_name' => 'nullable|max:120',
            'items.*.product_expense_id' => ['required', Rule::exists("chart_accounts", "id")],
            'items.*.product_description' => 'nullable|max:250',
        ];
    }

    public function prepareForValidation()
    {
        $user = $this->user();

        $items = array_filter(json_decode($this->items, true), fn($row) => !empty($row['product']['value']));

        $vendor = json_decode($this->input('vendor'), true) ?: [];

        $vendor = !empty($vendor['value']) ?
            Contact::whereUuid($vendor['value'])->first() : null;

        $this->replace([
            ...$this->all(),
            'vendor_idno' => $vendor?->id_no,
            'vendor_name' => $vendor?->full_name,
            'vendor_email' => $this->has('vendor_email') ? $this->vendor_email : $vendor?->email,
            'billing_address' => $this->has('billing_address') ? $this->billing_address : $vendor?->billing_address,
            'creator_id' => $user->id,
            'items' => array_map(function (array $row) {
                $product = !empty($row['product']['value']) ?
                    Product::whereUuid($row['product']['value'])->first() : null;

                return [
                    ...$row,
                    'product_id' => $product?->id,
                    'product_expense_id' => $product?->expenseAccount?->id,
                    'quantity' => Amount::acceptable($row['quantity']),
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', ''),
                ];
            }, $items),
            'date' => Carbon::parse($this->date)
        ]);
    }
}
