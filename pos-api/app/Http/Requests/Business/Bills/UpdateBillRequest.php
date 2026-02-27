<?php

namespace App\Http\Requests\Business\Bills;

use Illuminate\Validation\Rule;

class UpdateBillRequest extends BillRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Bills', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'bill_no' => [
                'required',
                'string',
                'max:25',
                Rule::unique('bills')->ignore($this->bill->id)
            ],
        ];
    }
    public function messages()
    {
        return [
            'bill_no.max' => 'The bill number must not be longer than 25 characters.',
            'bill_no.unique' => 'That bill number is already in use. Please choose a different one.',
        ];
    }
}
