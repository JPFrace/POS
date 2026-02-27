<?php

namespace App\Http\Requests\Budget;

use App\Models\BudgetType;
use App\Models\Calendar;
use App\Models\ChartAccount;
use App\Models\Department;
use App\Models\Product;
use App\Supports\Utils\Amount;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Budgeting.Budgets', ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'calendar_id' => ['required', 'integer', 'exists:calendars,id'],
            'type_id' => ['nullable', 'integer', 'exists:budget_types,id'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.chart_account_id' => ['required', 'integer', 'exists:chart_accounts,id'],
            'items.*.name' => ['required', 'string'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $department = !empty($this->department) ? json_decode($this->department, true) : null;
        $calendar = isset($this->calendar) ? json_decode($this->calendar, true) : null;
        $type = isset($this->type) ? json_decode($this->type, true) : null;
        $items = array_filter(json_decode($this->items, true), fn($row) => !empty($row['account']['value']));

        $data = [
            ...$this->all(),
            'department_id' => isset($department['value']) ? Department::whereUuid($department['value'])->first()?->id : null,
            'calendar_id' => isset($calendar['value']) ? Calendar::whereUuid($calendar['value'])->first()?->id : null,
            'type_id' => isset($type['value']) ? BudgetType::whereUuid($type['value'])->first()?->id : null,
            'creator_id' => auth()->user()->id,
            'items' => array_map(function (array $row) {

                $acctID = $row['account']['value'] ?? null;
                $account = isset($acctID) ? ChartAccount::whereUuid($acctID)->first() : null;

                return [
                    'chart_account_id' => $account->id,
                    'name' => $account?->name,
                    'amount' => $row['amount'],
                    'description' => $row['description'],
                ];
            }, $items)
        ];

        $this->replace($data);
    }
}
