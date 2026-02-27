<?php

namespace App\Http\Requests\Business\Deposits;

use App\Facades\ReferenceNumb;
use Carbon\Carbon;

class DepositStoreRequest extends DepositRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Business.Bank Deposits', ['create']);
    }
    public function rules(): array
    {
        return [
            // This includes any rules already defined in the parent FormRequest
            ...parent::rules(),

            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:20'
                : 'nullable|string|max:20|unique:deposits,ref_no',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $data = $this->all();

        if ($this->input('ref_no_auto')) {
            $data['ref_no'] = ReferenceNumb::generate(
                'business_bank_deposits_reference',
                \App\Models\Deposit::class,
                'ref_no',
                Carbon::parse($this->input('date'))
            );
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
