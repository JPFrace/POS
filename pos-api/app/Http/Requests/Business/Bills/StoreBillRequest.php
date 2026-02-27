<?php

namespace App\Http\Requests\Business\Bills;

use App\Facades\ReferenceNumb;
use Carbon\Carbon;

class StoreBillRequest extends BillRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Bills', ['create']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'bill_no' => 'required|string|max:25|unique:bills,bill_no',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
        $data = $this->all();
        if ($this->boolean('bill_no_auto')) {
            $data['bill_no'] = ReferenceNumb::generate('business_bills_number', \App\Models\Bill::class, 'bill_no', Carbon::parse($this->input('date')));
        }
        $this->replace($data);
    }

    public function messages()
    {
        return [
            'bill_no.max' => 'The bill number must not be longer than 25 characters.',
            'bill_no.unique' => 'That bill number is already in use. Please choose a different one.',
        ];
    }
}
