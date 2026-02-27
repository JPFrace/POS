<?php

namespace App\Http\Requests\Business\Bills;

use App\Enums\BillStatusEnum;
use App\Models\BillTerm;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'term_id' => 'nullable|exists:bill_terms,id',
            'date' => 'required',
            'due_date' => 'nullable',
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
        $term = json_decode($this->input('term'), true) ?: [];

        $vendor = !empty($vendor['value']) ?
            Contact::whereUuid($vendor['value'])->first() : null;

        $term = !empty($term['value']) ?
            BillTerm::whereUuid($term['value'])->first() : null;

        $this->merge([
            'vendor_idno' => $vendor?->id_no,
            'vendor_name' => $vendor?->full_name,
            'vendor_email' => $this->has('vendor_email') ? $this->vendor_email : $vendor?->email,
            'billing_address' => $this->has('billing_address') ? $this->billing_address : $vendor?->billing_address,
            'creator_id' => $user->id,
            'items' => array_map(function (array $row) {
                $product = !empty($row['product']['value']) ?
                    Product::whereUuid($row['product']['value'])->first() : null;

                $order = !empty($row['order']['uuid']) ?
                    Order::whereUuid($row['order']['uuid'])->first() : null;

                return [
                    ...$row,
                    'order_id' => $order?->id,
                    'product_id' => $product?->id,
                    'product_expense_id' => $product?->expenseAccount?->id,
                    'quantity' => Amount::acceptable($row['quantity']),
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', '')
                ];
            }, $items),
            'term_id' => $term?->id,
            'date' => Carbon::parse($this->date),
            'due_date' => !empty($this->due_date) ? Carbon::parse($this->due_date) : null,
        ]);
    }
}
