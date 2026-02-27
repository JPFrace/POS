<?php

namespace App\Http\Requests\Business\Payments;

use App\Enums\PaymentMethod;
use App\Models\BankAccount;
use App\Facades\SystemConfig;
use App\Models\Calendar;
use App\Facades\ReferenceNumb;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;

class PaymentsStoreRequest extends PaymentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Make Payments', ['create']);
    }
    public function rules(): array
    {
        return [
            // This includes any rules already defined in the parent FormRequest
            ...parent::rules(),

            // Validation rules for the "ref_no" field
            'check_no' => $this->check_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:payments,check_no',

            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:payments,ref_no',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
        $data = $this->all();

        $paymentMethod = PaymentType::find($data['payment_method_id']);

        if (strtoupper($paymentMethod->name) === PaymentMethod::CHECK->name) {
            if ($this->boolean('check_no_auto')) {
                $args = ['BANK' => BankAccount::where('account_id', $data['cash_bank_id'])->first()?->bank_code ?? ''];
                $data['check_no'] = ReferenceNumb::generate('business_make_payments_check_number', \App\Models\Payment::class, 'check_no', Carbon::parse($this->input('date')), $args);
            }
        } else {
            if ($this->boolean('check_no_auto')) {
                $args = ['PMETHOD' => $paymentMethod->code ?? 'DV'];
                $data['check_no'] = ReferenceNumb::generate('business_make_payments_disbursement_number', \App\Models\Payment::class, 'check_no', Carbon::parse($this->input('date')), $args);
            }
        }

        if ($this->boolean('ref_no_auto')) {
            $data['ref_no'] = ReferenceNumb::generate('business_make_payments_reference', \App\Models\Payment::class, 'ref_no', Carbon::parse($this->input('date')), $args);
        }

        $this->replace($data);
    }
    public function messages()
    {
        return [
            'ref_no.max' => 'The reference number must not be longer than 20 characters.',
            'ref_no.unique' => 'That reference number is already in use. Please choose a different one.',
            'check_no.max' => 'The check number must not be longer than 20 characters.',
            'check_no.unique' => 'That check number is already in use. Please choose a different one.',
        ];
    }
}
