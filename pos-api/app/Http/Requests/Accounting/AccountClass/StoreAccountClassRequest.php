<?php

namespace App\Http\Requests\Accounting\AccountClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Accounting.Account Class', ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:200|unique:\App\Models\AccountClass,name'
        ];
    }
}
