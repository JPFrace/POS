<?php

namespace App\Http\Requests\Setup\BankAccounts;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankAccountsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Bank Accounts", ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:120|unique:\App\Models\BankAccount,name'
        ];
    }
}
