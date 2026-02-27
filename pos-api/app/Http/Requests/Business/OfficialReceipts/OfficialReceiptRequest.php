<?php

namespace App\Http\Requests\Business\OfficialReceipts;

use App\Enums\OfficialReceiptStatusEnum;
use App\Enums\TransType;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\OfficialReceiptDenomination;
use App\Models\PaymentType;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficialReceiptRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'or_no' => $this->or_no_auto
                ? 'nullable|string|max:25'
                : 'required|string|max:25|unique:official_receipts,or_no',
            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:25'
                : 'required|string|max:25|unique:official_receipts,ref_no',
            'date' => 'required',
            'remarks' => 'required|max:250',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf',
            'creator_id' => Rule::exists('users', 'id'),
            'customer_idno' => [
                'nullable',
                Rule::exists("contacts", "id_no")
            ],
            'customer_name' => 'required|max:150',
            'customer_email' => 'nullable|email|max:150',
            'billing_address' => 'nullable|max:250',
            'denominations' => 'required|array',
            'denominations.*.payment_method_id' => [
                'required',
                Rule::exists("payment_types", "id")
            ],
            'denominations.*.deposit_id' => [
                'required',
                Rule::exists("chart_accounts", "id")
            ],
            'denominations.*.amount' => 'required|decimal:2',
            'denominations.*.quantity' => 'required|numeric',
            'denominations.*.denomination' => 'required|decimal:2',
            'denominations.*.bank' => 'nullable|string|max:50',
            'denominations.*.reference_date' => 'nullable|date',
            'denominations.*.reference_no' => 'nullable|string|max:50',
            'items.*.rate' => 'required|decimal:2',
            'items.*.quantity' => 'required|numeric',
            'items.*.product_name' => 'nullable|max:120',
            'items.*.product_income_id' => ['required', Rule::exists("chart_accounts", "id")],
            'items.*.tax_account_id' => ['nullable', Rule::exists("chart_accounts", "id")],
            'items.*.product_description' => 'nullable|max:250',
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

        $customer = json_decode($this->input('customer'), true) ?: [];
        $deposit = json_decode($this->input('deposit'), true) ?: [];

        $customer = !empty($customer['value']) ?
            Contact::whereUuid($customer['value'])->first() : null;

        $deposit = !empty($deposit['value']) ?
            ChartAccount::whereUuid($deposit['value'])->first() : null;

        $paymentMethod = !empty($this->payment_method) ?
            PaymentType::whereUuid($this->payment_method)->first() : null;

        $denominations = $this->prepareDenominations();

        $this->merge([
            'deposit_id' => $deposit?->id,
            'payment_method_id' => $paymentMethod?->id,
            'customer_idno' => $customer?->id_no,
            'customer_name' => $customer?->full_name,
            'customer_email' => $this->has('customer_email') ? $this->customer_email : $customer?->email,
            'billing_address' => $this->has('billing_address') ? $this->billing_address : $customer?->billing_address,
            'creator_id' => $user->id,
            'items' => array_map(function (array $row) {
                $product = !empty($row['product']['value']) ?
                    Product::whereUuid($row['product']['value'])->first() : null;

                $invoice = !empty($row['invoice']['uuid']) ?
                    Invoice::whereUuid($row['invoice']['uuid'])->first() : null;

                $data = [
                    ...$row,
                    'trans_type' => !empty($invoice) ? TransType::INVOICE : TransType::COLLECTION,
                    'ref_no' => $invoice?->id,
                    'product_id' => $product?->id,
                    'product_income_id' => $product?->incomeAccount?->id,
                    'quantity' => Amount::acceptable($row['quantity']),
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', ''),
                    'witholding_tax_rate' => number_format(Amount::acceptable($row['witholding_tax_rate'] ?? 0), 2, '.', ''),
                    'sales_tax_rate' => number_format(Amount::acceptable($row['sales_tax_rate'] ?? 0), 2, '.', ''),
                ];

                if ($product?->withholdingTax) {
                    $data['tax_account_id'] = $product->withholdingTax?->chartAccount->id;
                }

                return $data;

            }, $items),
            'dimensions' => $dimensions,
            'date' => Carbon::parse($this->date),
            'denominations' => $denominations,
        ]);
    }

    /**
     * Decode and prepare denominations from request (JSON or array) using OfficialReceiptDenomination model.
     * Resolves depositAccount and payment_method UUIDs to IDs and normalizes to model fillable attributes.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareDenominations(): array
    {
        $input = $this->input('denominations');
        if (is_string($input)) {
            $input = json_decode($input, true) ?: [];
        }
        $rows = is_array($input) ? $input : [];

        $fillable = array_diff(
            (new OfficialReceiptDenomination)->getFillable(),
            ['or_id']
        );

        return array_values(array_map(function (array $row) use ($fillable): array {
            $depositUuid = $row['depositAccount']['value'] ?? $row['depositAccount']['uuid'] ?? null;
            $paymentUuid = $row['payment_method']['value'] ?? $row['payment_method']['uuid'] ?? $row['payment_method'] ?? null;

            $deposit = !empty($depositUuid)
                ? ChartAccount::whereUuid($depositUuid)->first()
                : null;
            $paymentType = !empty($paymentUuid)
                ? PaymentType::whereUuid($paymentUuid)->first()
                : null;

            $quantity = Amount::acceptable($row['quantity'] ?? 0);
            $denomination = Amount::acceptable($row['denomination'] ?? 0);
            $amount = Amount::acceptable($row['amount'] ?? ($quantity * $denomination));

            $referenceDate = $row['reference_date'] ?? null;
            $referenceDate = (is_string($referenceDate) && trim($referenceDate) === '') ? null : $referenceDate;
            $referenceDate = in_array($referenceDate, ['0000-00-00', '0000-00-00 00:00:00'], true) ? null : $referenceDate;
            if ($referenceDate !== null) {
                $referenceDate = Carbon::parse($referenceDate)->format('Y-m-d');
            }

            $attributes = array_fill_keys($fillable, null);
            $attributes['deposit_id'] = $deposit?->id;
            $attributes['payment_method_id'] = $paymentType?->id;
            $attributes['quantity'] = (int) $quantity;
            $attributes['denomination'] = number_format((float) $denomination, 2, '.', '');
            $attributes['bank'] = $row['bank'] ?? null;
            $attributes['reference_date'] = $referenceDate;
            $attributes['reference_no'] = $row['reference_no'] ?? null;

            $attributes['amount'] = number_format((float) $amount, 2, '.', '');

            return $attributes;
        }, $rows));
    }

    /**
     * Ensure total payment amount is equal to or greater than total items amount.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $denominations = $this->input('denominations', []);
            $items = $this->input('items', []);

            if (! is_array($denominations) || count($denominations) === 0) {
                return;
            }

            $itemsTotal = array_reduce($items, function (float $sum, array $row): float {
                $qty = (float) ($row['quantity'] ?? 0);
                $rate = (float) ($row['rate'] ?? 0);

                return $sum + ($qty * $rate);
            }, 0.0);

            $paymentTotal = array_reduce($denominations, function (float $sum, array $row): float {
                return $sum + (float) ($row['amount'] ?? 0);
            }, 0.0);

            if ($paymentTotal < $itemsTotal) {
                $validator->errors()->add(
                    'denominations',
                    'Total payment amount must be equal to or more than the total amount of items.'
                );
            }
        });
    }
}
