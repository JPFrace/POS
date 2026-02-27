<?php

namespace App\Http\Requests\Products\Product;

use App\Enums\AccountUsageType;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Supports\Utils\Amount;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'nullable|file|mimes:jpeg,jpg,png,gif',
            'photo_id' => 'nullable|integer|exists:files,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|integer|exists:product_categories,id',
            'price' => 'nullable|numeric|min:0',
            'income_id' => 'required|integer|exists:chart_accounts,id',
            'receivable_id' => 'nullable|integer|exists:chart_accounts,id',
            'depository_id' => 'nullable|integer|exists:chart_accounts,id',
            'purchase_description' => 'nullable|string|max:1000',
            'expense_id' => 'nullable|integer|exists:chart_accounts,id',
            'cost' => 'nullable|numeric|min:0',
            'payable_id' => 'nullable|integer|exists:chart_accounts,id',
            'vendor_id' => 'nullable|integer|exists:contacts,id',
            'sales_tax_id' => 'nullable|integer|exists:taxes,id',
            'wth_tax_id' => 'nullable|integer|exists:taxes,id',
        ];
    }

    public function prepareForValidation()
    {
        // Check if the income_id value is existing in the chart accounts and chart account category is 'REVENUE'
        $income = is_string($this->income) ? json_decode($this->income)->value ?? null : $this->income['value'] ?? null;
        $expense = is_string($this->expense) ? json_decode($this->expense)->value ?? null : $this->expense['value'] ?? null;
        $vendor = is_string($this->vendor) ? json_decode($this->vendor)->value ?? null : $this->vendor['value'] ?? null;
        $category = is_string($this->category) ? json_decode($this->category)->value ?? null : $this->category['value'] ?? null;
        $depository = is_string($this->depository) ? json_decode($this->depository)->value ?? null : $this->depository['value'] ?? null;
        $payable = is_string($this->payable) ? json_decode($this->payable)->value ?? null : $this->payable['value'] ?? null;
        $salesTax = is_string($this->sales_tax) ? json_decode($this->sales_tax)->value ?? null : $this->sales_tax['value'] ?? null;
        $withholdingTax = is_string($this->withholding_tax) ? json_decode($this->withholding_tax)->value ?? null : $this->withholding_tax['value'] ?? null;
        $receivable = is_string($this->receivable) ? json_decode($this->receivable)->value ?? null : $this->receivable['value'] ?? null;

        $this->merge([
            'income_id' => ChartAccount::whereUuid($income)->first()?->id,
            'expense_id' => ChartAccount::whereUuid($expense)->first()?->id,
            'depository_id' => ChartAccount::whereUuid($depository)->first()?->id,
            'vendor_id' => Contact::whereUuid($vendor)->first()?->id,
            'category_id' => ProductCategory::whereUuid($category)->first()?->id,
            'payable_id' => ChartAccount::whereUuid($payable)->first()?->id,
            'sales_tax_id' => Tax::whereUuid($salesTax)->first()?->id,
            'wth_tax_id' => Tax::whereUuid($withholdingTax)->first()?->id,
            'receivable_id' => ChartAccount::whereUuid($receivable)->first()?->id,
        ]);

        $this->replace([
            ...$this->all(),
            'price' => number_format(Amount::acceptable($this->price), 2, '.', ''),
            'cost' => number_format(Amount::acceptable($this->cost), 2, '.', '')
        ]);
    }
}
