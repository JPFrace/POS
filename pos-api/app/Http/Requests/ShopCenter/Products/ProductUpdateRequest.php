<?php

namespace App\Http\Requests\ShopCenter\Products;

use App\Models\File;
use App\Models\Form;
use App\Models\ShopCategory;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
                Rule::unique('products', 'title')->ignore($this->product->id)
            ],
            'description' => 'required',
            'attribute' => ['nullable', Rule::exists('attributes', 'uuid')],
            'shops_category' => Rule::exists('shop_categories', 'uuid'),
            'vendors' => Rule::exists('vendors', 'uuid'),
            'file' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        if (!in_array($value->getMimeType(), ['image/jpeg', 'image/png'])) {
                            return $fail('The file must be a valid image (jpg, png).');
                        }
                    } elseif (gettype($value) == 'string') {
                        if (!File::whereUuid($value)->first()) {
                            return $fail('The uuid must exist in the files table.');
                        }
                    }

                    return null;
                }
            ],
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
        $vendor = json_decode($this->vendors, true);

        $this->replace([
            ...$this->all(),
            'form' => $form['value'] ?? null,
            'shops_category' => $category['value'] ?? null,
            'vendors' => $vendor['value'] ?? null,
        ]);
    }



    public function passedValidation()
    {
        $this->merge([
            'attribute_id' => Form::whereUuid($this->form)->first()?->attribute?->id,
            'category_id' => ShopCategory::whereUuid($this->shops_category)->first()->id,
            'vendor_id' => Vendor::whereUuid($this->vendors)->first()->id,
        ]);
    }
}
