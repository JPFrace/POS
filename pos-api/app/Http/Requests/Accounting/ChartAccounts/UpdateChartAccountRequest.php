<?php

namespace App\Http\Requests\Accounting\ChartAccounts;

use App\Models\AccountClass;
use App\Models\AccountType;
use App\Models\AccountUsageType;
use App\Models\BudgetType;
use App\Models\ChartAccount;
use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChartAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Accounting.Chart of Accounts', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'max:45',
                Rule::unique("chart_accounts")->ignore($this->chart_account->id)
            ],
            'name' => 'required|max:120',
            'description' => 'max:250',
            'type_id' => [
                'required',
                Rule::exists("account_types", 'id')
            ],
            'class_id' => [
                'nullable',
                Rule::exists("account_classes", 'id')
            ],
            'parent_id' => [
                'nullable',
                Rule::exists("chart_accounts", 'id')
            ],
            'dept_id' => [
                'nullable',
                Rule::exists("departments", 'id')
            ],
            'usage_type_id' => [
                'nullable',
                Rule::exists("account_usage_types", 'id')
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'type_id' => isset($this->type['value']) ? AccountType::whereUuid($this->type['value'])->first()?->id : null,
            'class_id' => isset($this->class['value']) ? AccountClass::whereUuid($this->class['value'])->first()?->id : null,
            'parent_id' => isset($this->parent['value']) ? ChartAccount::whereUuid($this->parent['value'])->first()?->id : null,
            'dept_id' => isset($this->department['value']) ? Department::whereUuid($this->department['value'])->first()?->id : null,
            'usage_type_id' => isset($this->usage['value']) ? AccountUsageType::whereUuid($this->usage['value'])->first()?->id : null,
        ]);
    }
}
