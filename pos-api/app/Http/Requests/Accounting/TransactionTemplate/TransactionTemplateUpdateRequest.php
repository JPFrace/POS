<?php

namespace App\Http\Requests\Accounting\TransactionTemplate;

use Illuminate\Validation\Rule;

class TransactionTemplateUpdateRequest extends TransactionTemplateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Accounting.Transaction Templates', ['Edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = [
            'required',
            'string',
            'max:100',
            Rule::unique('transaction_templates')->ignore($this->transaction_template->id),
        ];
        return $rules;
    }
}
