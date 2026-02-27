<?php

namespace App\Http\Requests\Business\OfficialReceipts;

use App\Enums\PostingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfficialReceiptStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuids' => ['required', 'array', 'min:1'],
            'uuids.*' => [
                'required',
                'uuid',
                Rule::exists('official_receipts', 'uuid')
            ],
            'status' => ['required', Rule::enum(PostingStatus::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['status' => ucfirst($this->get('status'))]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'uuids.required' => 'Please select at least one official receipt.',
            'uuids.*.exists' => 'One or more selected official receipts do not exist.',
            'status.enum' => 'Invalid status value.',
        ];
    }
}
