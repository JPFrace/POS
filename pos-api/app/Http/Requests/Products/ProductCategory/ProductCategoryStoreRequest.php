<?php

namespace App\Http\Requests\Products\ProductCategory;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Categories', ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'parent_id' => [
                'nullable',
                Rule::exists("product_categories", 'id')
            ],
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([

            'parent_id' => isset($this->parent['value']) ? ProductCategory::whereUuid($this->parent['value'])->first()?->id : null
        ]);
    }
}
