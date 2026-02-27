<?php

namespace App\Http\Requests\Externals;

use App\Enums\ContactSubTypes as EnumContactSubTypes;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\ContactSubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ExternalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trans_type' => [
                'required',
                'string',
                'max:25',
                Rule::exists('financial_trans_codes', 'code')
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:250'
            ],
            'total' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    $totalDebit = 0.0;
                    $totalCredit = 0.0;
                    foreach ($this->transactions as $transaction) {
                        $totalDebit += $transaction['debit'];
                        $totalCredit += $transaction['credit'];
                    }
                    if ($totalDebit != $totalCredit) {
                        $fail('The Debit and Credit must be balance.');
                    }
                }
            ],
            'transactions' => 'required|array',
            'transactions.*.code' => [
                'required',
                'string',
                'max:35',
                Rule::exists('chart_accounts', 'code')
            ],
            'transactions.*.particular' => [
                'nullable',
                'string',
                'max:250'
            ],
            'transactions.*.debit' => 'required|numeric',
            'transactions.*.credit' => 'required|numeric',
            'transactions.*.document_date' => 'required|date',
            'transactions.*.cost_center' => [
                'required',
                'string',
                'max:25',
                Rule::exists('departments', 'code')
            ],
            'transactions.*.contact_name' => [
                'nullable',
                'string',
                'max:120'
            ],
            'transactions.*.contact_id_no' => [
                'nullable',
                'string',
                'max:10',
                Rule::exists('contacts', 'id_no')
            ],
            'transactions.*.ref_no' => [
                'nullable',
                'string',
                'max:25'
            ],
            'transactions.*.remarks' => 'nullable|string|max:75',
            'transactions.*.account' => 'required|string|max:75',
            'transactions.*.credit_debit' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if ($value[0] > 0 && $value[1] > 0) {
                        $fail('Both Debit and Credit cannot have values.');
                    } elseif ($value[0] == 0 && $value[1] == 0) {
                        $fail('Either Debit or Credit must have a value.');
                    }
                }
            ]
        ];
    }

    public function attributes()
    {
        return [
            'transactions.*.code' => 'transaction code',
            'transactions.*.particular' => 'particulars',
            'transactions.*.debit' => 'debit amount',
            'transactions.*.credit' => 'credit amount',
            'transactions.*.document_date' => 'document date',
            'transactions.*.cost_center' => 'cost center',
            'transactions.*.contact_name' => 'contact name',
            'transactions.*.contact_id_no' => 'contact ID no',
            'transactions.*.ref_no' => 'reference no',
            'transactions.*.remarks' => 'remarks',
            'transactions.*.account' => 'account',
            'transactions.*.isCDUnique' => 'debit and credit Unique',
        ];
    }

    public function prepareForValidation(): void
    {
        $transactions = is_string($this->transations) ? json_decode($this->transactions, true) : $this->transactions;

        for ($i = 0; $i < count($transactions); $i++) {
            $transaction = $transactions[$i];
            $transactions[$i]['credit_debit'] = [$transaction['credit'], $transaction['debit']];

            $contact = Contact::where('id_no', $transaction['contact_id_no'])->first();
            if (empty($contact)) {
                Contact::create([
                    'id_no' => $transaction['contact_id_no'],
                    'name' => $transaction['contact_name'],
                    'sub_type_id' => ContactSubType::where('name', EnumContactSubTypes::INDIVIDUAL->name)->first()->id,
                    'created_by' => $this->user()->id
                ]);
            }
        }

        $this->merge([
            'user_id' => $this->user()->id,
            'trans_type' => $this->trans_code,
            'total' => true,
            'transactions' => $transactions,
        ]);
    }
}
