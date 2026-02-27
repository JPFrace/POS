<?php

namespace App\Http\Requests\Business\Invoices;

use App\Facades\ReferenceNumb;
use Carbon\Carbon;

class StoreInvoiceRequest extends InvoiceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Invoice', ['create']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'invoice_no' => 'required|max:25|unique:invoices,invoice_no',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
        $data = $this->all();
        if ($this->boolean('invoice_no_auto')) {
            $data['invoice_no'] = ReferenceNumb::generate('business_invoice_number', \App\Models\Invoice::class, 'invoice_no', Carbon::parse($this->input('date')));
        }
        $this->replace($data);
    }

    public function messages()
    {
        return [
            'invoice_no.max' => 'The invoice number must not be longer than 25 characters.',
            'invoice_no.unique' => 'That invoice number is already in use. Please choose a different one.',
        ];
    }
}
