<?php

namespace App\Http\Requests\Business\Payments;

use App\Enums\PostingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentStatusRequest extends FormRequest
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
                Rule::exists('payments', 'uuid')
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
            'uuids.required' => 'Please select at least one payment.',
            'uuids.*.exists' => 'One or more selected payments do not exist.',
            'status.enum' => 'Invalid status value.',
        ];
    }
}
