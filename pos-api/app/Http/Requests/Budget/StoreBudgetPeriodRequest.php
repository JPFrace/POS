<?php

namespace App\Http\Requests\budget;

use App\Models\BudgetDetail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetPeriodRequest extends FormRequest
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
            'period_1' => ['required', 'numeric', 'min:1'],
            'period_2' => ['required', 'numeric', 'min:1'],
            'period_3' => ['required', 'numeric', 'min:1'],
            'period_4' => ['required', 'numeric', 'min:1'],
            'period_5' => ['required', 'numeric', 'min:1'],
            'period_6' => ['required', 'numeric', 'min:1'],
            'period_7' => ['required', 'numeric', 'min:1'],
            'period_8' => ['required', 'numeric', 'min:1'],
            'period_9' => ['required', 'numeric', 'min:1'],
            'period_10' => ['required', 'numeric', 'min:1'],
            'period_11' => ['required', 'numeric', 'min:1'],
            'period_12' => ['required', 'numeric', 'min:1'],
        ];
    }

    protected function prepareForValidation()
    {
        $data = collect(range(1, 12))
            ->mapWithKeys(fn($i) => [
                "period_$i" => $this->amount($this->input("period_$i.amount", 0)),
            ])
            ->toArray();
        $data['total'] = $this->amount($this->input('total', 0));
        $this->replace($data);
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $budgeted = $this->amount($this->input('total', 0));

            $periodTotal = collect(range(1, 12))
                ->sum(
                    fn($i) =>
                    $this->amount($this->input("period_$i", 0))
                );

            if (round($periodTotal, 2) !== round($budgeted, 2)) {
                $validator->errors()->add(
                    'total',
                    'The total amount of all periods must equal the budgeted amount.'
                );
            }
        });
    }

    private function amount($value): float
    {
        return (float) str_replace(',', '', $value ?? 0);
    }
}
