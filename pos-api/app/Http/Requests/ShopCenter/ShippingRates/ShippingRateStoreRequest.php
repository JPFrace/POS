<?php

namespace App\Http\Requests\ShopCenter\ShippingRates;

use App\Models\Form;
use App\Models\Region;
use App\Models\ShopCategory;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingRateStoreRequest extends FormRequest
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
            'regions' => Rule::exists('regions', 'uuid'),
            'rate' => ['required', 'numeric'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $region = json_decode($this->regions, true);

        $this->replace([
            ...$this->all(),
            'regions' => $region['value'] ?? null,
        ]);
    }



    public function passedValidation()
    {
        $this->merge([
            'region_id' => Region::whereUuid($this->regions)->first()->id,
        ]);
    }
}
