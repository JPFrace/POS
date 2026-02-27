<?php

namespace App\Http\Requests\budget;

use App\Http\Requests\Budget\StoreBudgetRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\HttpCache\Store;

class UpdateBudgetRequest extends StoreBudgetRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Budgeting.Budgets', ['edit']);
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
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();
    }
}
