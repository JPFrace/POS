<?php

namespace App\Http\Requests\Business\Invoices;

use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends InvoiceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Invoice', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // This includes any rules already defined in the parent FormRequest
            ...parent::rules(),

            // Validation rules for the "invoice_no" field
            'invoice_no' => [
                'required',
                'max:25',
                Rule::unique('invoices', 'invoice_no') // Must be unique in the "invoices" table, column "invoice_no"
                    ->ignore($this->invoice->id)                 // But ignore the current record’s id (useful for updates)
            ],
        ];
    }
    public function messages()
    {
        return [
            'invoice_no.max' => 'The invoice number must not be longer than 25 characters.',
            'invoice_no.unique' => 'That invoice number is already in use. Please choose a different one.',
        ];
    }
}
