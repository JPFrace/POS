<?php

namespace App\Http\Requests\Taxes\Tax;

use App\Models\AccountClass;
use App\Models\ChartAccount;
use App\Models\Tax;
use App\Models\TaxAgency;
use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('tax_agency') && $this->input('tax_agency')) {
            $tax_agency_input = $this->input('tax_agency');

            $tax_agency = TaxAgency::whereUuid($tax_agency_input['id'])->first();

            if ($tax_agency) {
                $this->merge([
                    'tax_agency_id' => $tax_agency->id,
                ]);
            }

        }
        if ($this->has('parent') && $this->input('parent')) {
            \Log::info('Parent input exists');

            $parent_input = $this->input('parent');
            \Log::info('Parent input data:', $parent_input);

            $parent = Tax::whereUuid($parent_input['id'])->first();
            \Log::info('Parent model result:', [
                'found' => (bool) $parent,
                'parent_id' => optional($parent)->id,
            ]);

            if ($parent) {
                $this->merge([
                    'parent_id' => $parent->id,
                ]);

                \Log::info('Merged parent_id into request', [
                    'parent_id' => $parent->id,
                ]);
            }
        } else {
            \Log::warning('Parent input missing or empty', [
                'has_parent' => $this->has('parent'),
                'parent_value' => $this->input('parent'),
            ]);
        }


        if ($this->has('chart_account') && $this->input('chart_account')) {
            $chart_account_input = $this->input('chart_account');
            $chart_account = ChartAccount::whereUuid($chart_account_input['id'])->first();

            if ($chart_account) {
                $this->merge([
                    'chart_account_id' => $chart_account->id,
                ]);
            }

        }

        if ($this->has('class') && $this->input('class')) {
            $class_input = $this->input('class_id');

            $class = AccountClass::where('id', $class_input)->first();
            if ($chart_account) {
                $this->merge([
                    'class_id' => $class->id,
                ]);
            }

        }
        if ($this->has('type_obj') && $this->input('type_obj')) {
            $type = $this->input('type_obj');


            if (is_array($type) && isset($type['value'])) {
                $this->merge([
                    'type' => $type['value'],
                ]);
            }
        }

        if ($this->has('rate_type_obj') && $this->input('rate_type_obj')) {
            $rate_type = $this->input('rate_type_obj');


            if (is_array($rate_type) && isset($rate_type['value'])) {
                $this->merge([
                    'rate_type' => $rate_type['value'],
                ]);
            }
        }

    }
}
