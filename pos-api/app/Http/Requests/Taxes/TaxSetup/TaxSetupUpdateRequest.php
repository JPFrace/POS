<?php

namespace App\Http\Requests\Taxes\TaxSetup;

use App\Models\Calendar;
use App\Models\Tax;

class TaxSetupUpdateRequest extends TaxSetupRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can('Catalogue', ['edit']);
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

    protected function prepareForValidation(): void
    {
        if ($this->has('tax') && $this->input('tax')) {
            $taxInput = $this->input('tax');

            $tax = Tax::where('uuid', $taxInput)->orWhere('id', $taxInput)->first();

            if ($tax) {
                $this->merge([
                    'tax_id' => $tax->id,
                ]);
            }
        }
        if ($this->has('calendar') && $this->input('calendar')) {
            $calendarInput = $this->input('calendar');

            $calendar = Calendar::where('uuid', $calendarInput)->orWhere('id', $calendarInput)->first();

            if ($calendar) {
                $this->merge([
                    'calendar_id' => $calendar->id,
                ]);
            }
        }

        if ($this->has('start_tax_period_obj') && $this->input('start_tax_period_obj')) {
            $start_tax_period = $this->input('start_tax_period_obj');


            if (is_array($start_tax_period) && isset($start_tax_period['value'])) {
                $this->merge([
                    'start_tax_period' => $start_tax_period['value'],
                ]);
            }
        }

        if ($this->has('period_obj') && $this->input('period_obj')) {
            $period = $this->input('period_obj');


            if (is_array($period) && isset($period['value'])) {
                $this->merge([
                    'period' => $period['value'],
                ]);
            }
        }

        if ($this->has('reporting_method_obj') && $this->input('reporting_method_obj')) {
            $reporting_method = $this->input('reporting_method_obj');


            if (is_array($reporting_method) && isset($reporting_method['value'])) {
                $this->merge([
                    'reporting_method' => $reporting_method['value'],
                ]);
            }
        }

    }

}
