<?php

namespace App\Http\Requests\Business\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentsUpdateRequest extends PaymentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Make Payments', ['edit']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'check_no' => [
                'required',
                'max:20',
                Rule::unique('payments', 'check_no')->ignore($this->payment->id)
            ],
            'ref_no' => [
                'required',
                'max:20',
                Rule::unique('payments', 'ref_no')->ignore($this->payment->id)
            ],
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $payment = $this->route('payment');

        $data = $this->all();

        if (!$this->check_no_auto || $this->check_no_auto === "false") {
            $data['check_no'] = $this->check_no;
        } else {
            $data['check_no'] = $payment->check_no;
        }

        if (!$this->ref_no_auto || $this->ref_no_auto === "false") {
            $data['ref_no'] = $this->ref_no;
        } else {
            $data['ref_no'] = $payment->ref_no;
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
