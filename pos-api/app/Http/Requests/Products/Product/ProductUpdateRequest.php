<?php

namespace App\Http\Requests\Products\Product;

use App\Enums\AccountCategory as EnumsAccountCategory;
use App\Enums\ContactType;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use App\Supports\Utils\Amount;
use Hamcrest\Type\IsString;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends ProductRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Catalogue', ['edit']);
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
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->product->id)
            ],
            'file' => (is_array($this->file)) ? [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (!Storage::disk('public')->exists($value['storage_path'])) {
                        $fail('The file does not exist.');
                    }
                }
            ] : 'nullable|mimes:jpeg,jpeg,png,pdf',

        ];
    }
}
