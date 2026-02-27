<?php

namespace App\Http\Requests\Business\OfficialReceipts;

use Illuminate\Validation\Rule;

class UpdateOfficialReceiptRequest extends OfficialReceiptRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Receive Money', ['edit']);
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
            'or_no' => [
                'required',
                'max:25',
                Rule::unique('official_receipts', 'or_no')->ignore($this->official_receipt->id)
            ],
            'ref_no' => [
                'required',
                'max:25',
                Rule::unique('official_receipts', 'ref_no')->ignore($this->official_receipt->id)
            ],
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $official_receipt = $this->route('official_receipt');
        $data = $this->all();

        if (!$this->or_no_auto || $this->or_no_auto === "false") {
            $data['or_no'] = $this->or_no;
        } else {
            $data['or_no'] = $official_receipt->or_no;
        }

        if (!$this->ref_no_auto || $this->ref_no_auto === "false") {
            $data['ref_no'] = $this->ref_no;
        } else {
            $data['ref_no'] = $official_receipt->ref_no;
        }

        $this->replace($data);
    }
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
