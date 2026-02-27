<?php

namespace App\Http\Requests\Products\Product;

use App\Enums\AccountCategory as EnumsAccountCategory;
use App\Enums\ContactType;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\ProductCategory;
use App\Supports\Utils\Amount;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends ProductRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Catalogue', ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules()
        ];
    }
}
