<?php

namespace App\Http\Requests\Business\Deposits;

use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\OfficialReceipt;
use App\Models\PaymentType;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:official_receipts,ref_no',
            'date' => 'required|date',
            'remarks' => 'nullable|max:250',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf',
            'cash_bank_id' => 'required|integer|exists:chart_accounts,id',
            'creator_id' => Rule::exists('users', 'id'),
            'items.*.payment_method_id' => Rule::exists('payment_types', 'id'),
            'items.*.contact_idno' => 'required|string|max:255|exists:contacts,id_no',
            'items.*.date' => 'required|date',
            'items.*.rate' => 'required|decimal:2',
            'items.*.ref_no' => 'required|string',
            'items.*.memo' => 'nullable|max:120',
        ];
    }

    public function prepareForValidation()
    {
        $user = $this->user();

        $items = json_decode($this->items, true);

        $cashInBank = json_decode($this->input('cash_in_bank'), true) ?: [];

        $cashBank = !empty($cashInBank['value']) ?
            ChartAccount::whereUuid($cashInBank['value'])->first() : null;


        $this->merge([
            'creator_id' => $user->id,
            'cash_bank_id' => $cashBank?->id,
            'items' => array_map(function (array $row) {
                $or = !empty($row['uuid']) ?
                    OfficialReceipt::whereUuid($row['uuid'])->first() : null;

                $paymentMethod = !empty($or) ? PaymentType::whereId($or->payment_method_id)->first() : null;

                $contact = !empty($or) ? Contact::where('id_no', $or->customer_idno)->first() : null;

                $data = [
                    'official_receipt_id' => $or->id,
                    'date' => $or->date,
                    'payment_method_id' => $paymentMethod->id,
                    'contact_idno' => $contact->id_no,
                    'memo' => $row['memo'],
                    'ref_no' => $row['ref_no'],
                    'rate' => number_format(Amount::acceptable($row['rate']), 2, '.', ''),
                ];


                return $data;
            }, $items)
        ]);
    }
}
