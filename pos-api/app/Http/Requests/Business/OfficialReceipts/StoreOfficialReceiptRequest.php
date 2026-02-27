<?php

namespace App\Http\Requests\Business\OfficialReceipts;

use App\Facades\SystemConfig;
use App\Models\Calendar;
use App\Facades\ReferenceNumb;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;

class StoreOfficialReceiptRequest extends OfficialReceiptRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Receive Money', ['create']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),

            'or_no' => $this->or_no_auto
                ? 'nullable|string|max:25'
                : 'nullable|string|max:25|unique:official_receipts,or_no',

            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:25'
                : 'nullable|string|max:25|unique:official_receipts,ref_no',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
        $data = $this->all();
        if ($this->boolean('or_no_auto')) {
            $data['or_no'] = ReferenceNumb::generate('business_receive_money_number', \App\Models\OfficialReceipt::class, 'or_no', Carbon::parse($this->input('date')));
        }

        if ($this->boolean('ref_no_auto')) {
            $data['ref_no'] = ReferenceNumb::generate('business_receive_money_reference', \App\Models\OfficialReceipt::class, 'ref_no', Carbon::parse($this->input('date')));
        }

        $this->replace($data);
    }

    // public function withValidator(Validator $validator): void
    // {
    //     parent::withValidator($validator);

    //     $validator->after(function ($validator) {
    //         if (SystemConfig::get('business_receive_money_enforce_fiscal_period_lock')) {
    //             $date = Carbon::parse($this->input('date'));
    //             if ($period = Calendar::checkClosedPeriod($date)) {
    //                 if (Calendar::isClosed($date)) {
    //                     $validator->errors()->add(
    //                         'date',
    //                         "The selected date falls in a locked fiscal period "
    //                         . "{$period['number']} of {$period['year']}."
    //                     );
    //                 }
    //             }
    //         }
    //     });
    // }

    public function messages()
    {
        return [
            'or_no.max' => 'The OR number must not be longer than 25 characters.',
            'or_no.unique' => 'That OR number is already in use. Please choose a different one.',
            'ref_no.max' => 'The reference number must not be longer than 25 characters.',
            'ref_no.unique' => 'That reference number is already in use. Please choose a different one.',
        ];
    }
}
