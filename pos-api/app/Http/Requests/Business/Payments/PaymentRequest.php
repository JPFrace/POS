<?php

namespace App\Http\Requests\Business\Payments;

use App\Enums\PaymentStatusEnum;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\PaymentType;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_no' => $this->check_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:payments,check_no',
            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:payments,ref_no',
            'date' => 'required|date',
            'payee_idno' => 'required|string|max:255|exists:contacts,id_no',
            'payment_method_id' => 'required|integer|exists:payment_types,id',
            'remarks' => 'nullable|max:250',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf',
            'cash_bank_id' => 'required|integer|exists:chart_accounts,id',
            'payee_name' => 'required|max:150',
            'payee_email' => 'nullable|email|max:150',
            'payee_address' => 'nullable|max:250',
            'creator_id' => Rule::exists('users', 'id'),
            'items.*.rate' => 'required|decimal:2',
            'items.*.quantity' => 'required|numeric',
            'items.*.product_name' => 'nullable|max:120',
            'items.*.product_expense_id' => ['required', Rule::exists("chart_accounts", "id")],
            'items.*.withholding_tax_account_id' => ['nullable', Rule::exists("chart_accounts", "id")],
            'items.*.product_description' => 'nullable|max:250',
            'items.*.contact_idno' => 'nullable|max:10'
        ];
    }

    public function prepareForValidation()
    {
        $user = $this->user();

        $items = array_filter(json_decode($this->items, true), fn($row) => !empty($row['product']['value']));

        $dimensions = null;
        if ($this->dimension) {
            $decoded = json_decode($this->dimension, true) ?: [];
            $dimensions = array_filter($decoded, fn($row) => !empty($row['id'])) ?: null;
        }

        $contact = json_decode($this->input('contact'), true) ?: [];
        $cashInBank = json_decode($this->input('cash_in_bank'), true) ?: [];

        $paymentMethod = !empty($this->payment_method) ?
            PaymentType::whereUuid($this->payment_method)->first() : null;

        $cashBank = !empty($cashInBank['value']) ?
            ChartAccount::whereUuid($cashInBank['value'])->first() : null;

        $payee = !empty($contact['value']) ?
            Contact::whereUuid($contact['value'])->first() : null;

        $payee_name = empty($this->payee_name) ? $payee?->name : $this->payee_name;

        $this->merge([
            'creator_id' => $user->id,
            'cash_bank_id' => $cashBank?->id,
            'payee_idno' => $payee?->id_no,
            'payee_email' => $this->has('payee_email') ? $this->payee_email : $payee?->email,
            'payee_address' => $this->has('payee_address') ? $this->payee_address : $payee?->billing_address,
            'payment_method_id' => $paymentMethod?->id,
            'payee_name' => empty($payee_name) ? $this->contact_name : $payee_name,
            'items' => array_map(function (array $row) {
                $product = !empty($row['product']['value']) ?
                    Product::whereUuid($row['product']['value'])->first() : null;
                $sub_contact = !empty($row['sub_contact']['value']) ?
                    Contact::whereUuid($row['sub_contact']['value'])->first() : null;

                $data = [
                    ...$row,
                    'product_id' => $product?->id,
                    'product_expense_id' => $product?->expenseAccount?->id,
                    'contact_idno' => $sub_contact?->id_no,
                    'quantity' => Amount::acceptable($row['quantity']),
                    'withholding_tax_rate' => number_format(Amount::acceptable($row['withholding_tax_rate'] ?? 0), 2, '.', ''),
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', ''),
                ];

                if ($product->withholdingTax) {
                    $data['withholding_tax_account_id'] = $product->withholdingTax?->chartAccount->id;
                }

                return $data;
            }, $items),
            'dimensions' => $dimensions,
        ]);
    }
}
