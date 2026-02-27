<?php

namespace App\Http\Requests\Taxes\Tax;

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

class TaxUpdateRequest extends TaxRequest
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
            ...parent::rules(),

        ];
    }
}
