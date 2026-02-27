<?php

namespace App\Http\Requests\Setup\Signatories;

use App\Http\Requests\Setup\Signatories\SignatoryRequest;
use App\Models\Department;
use App\Models\Position;

class SignatoryStoreRequest extends SignatoryRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Signatories", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return $rules;
    }
    public function prepareForValidation()
    {
        $this->merge([
            'department_id' => isset($this->department['value']) ? Department::whereUuid($this->department['value'])->first()?->id : null,
            'created_by' => auth()->id(),
            'position_id' => isset($this->position['value']) ? Position::whereUuid($this->position['value'])->first()?->id : null,
        ]);

    }
}
