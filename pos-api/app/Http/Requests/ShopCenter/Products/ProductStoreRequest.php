<?php

namespace App\Http\Requests\ShopCenter\Products;

use App\Models\Form;
use App\Models\ShopCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'title' => [
                'required',
                'max:120',
                Rule::unique('products', 'title')
            ],
            'description' => 'required',
            'form' => Rule::when(!empty($this->form), [
                Rule::exists('forms', 'uuid')
            ]),
            'shops_category' => Rule::exists('shop_categories', 'uuid'),
            'vendor' => ['required', Rule::exists('vendors', 'uuid')],
            'file' => ['nullable', 'mimes:jpg,png,jpeg'],
            'price' => ['required'],
            'active' => ['required', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $form = json_decode($this->form, true);
        $category = json_decode($this->shops_category, true);
        $vendor = json_decode($this->vendor, true);

        $vendor = $vendor['value'] ?? null;
        if ($this->user() instanceof Vendor) {
            $vendor = $this->user()->uuid;
        }

        $this->replace([
            ...$this->all(),
            'form' => $form['value'] ?? null,
            'shops_category' => $category['value'] ?? null,
            'vendor' => $vendor,

        ]);
    }



    public function passedValidation()
    {
        $this->merge([
            'attribute_id' => Form::whereUuid($this->form)->first()?->attribute?->id,
            'category_id' => ShopCategory::whereUuid($this->shops_category)->first()->id,
            'vendor_id' => Vendor::whereUuid($this->vendor)->first()->id,
        ]);
    }
}
