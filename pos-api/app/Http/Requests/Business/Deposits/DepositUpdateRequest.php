<?php

namespace App\Http\Requests\Business\Deposits;

use App\Http\Requests\Business\Payments\DepositRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositUpdateRequest extends DepositRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Business.Bank Deposits', ['edit']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'ref_no' => [
                'required',
                'max:20',
                Rule::unique('deposits', 'ref_no')->ignore($this->payment->id)
            ],
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $deposit = $this->route('deposit');

        $data = $this->all();

        if (!$this->ref_no_auto || $this->ref_no_auto === "false") {
            $data['ref_no'] = $this->ref_no;
        } else {
            $data['ref_no'] = $deposit->ref_no;
        }

        $this->replace($data);
    }

    public function messages()
    {
        return [
            'ref_no.max' => 'The reference number must not be longer than 20 characters.',
            'ref_no.unique' => 'That reference number is already in use. Please choose a different one.',
        ];
    }
}
