<?php

namespace App\Http\Requests\Accounting\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Calendars', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|max:25',
            'no_of_periods' => 'required|integer|min:1|max:12',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period_1' => 'required|date',
            'period_1_closed' => 'required|boolean',
            'period_2' => 'required|date|after_or_equal:period_1',
            'period_2_closed' => 'required|boolean',
            'period_3' => 'required|date|after_or_equal:period_2',
            'period_3_closed' => 'required|boolean',
            'period_4' => 'required|date|after_or_equal:period_3',
            'period_4_closed' => 'required|boolean',
            'period_5' => 'required|date|after_or_equal:period_4',
            'period_5_closed' => 'required|boolean',
            'period_6' => 'required|date|after_or_equal:period_5',
            'period_6_closed' => 'required|boolean',
            'period_7' => 'required|date|after_or_equal:period_6',
            'period_7_closed' => 'required|boolean',
            'period_8' => 'required|date|after_or_equal:period_7',
            'period_8_closed' => 'required|boolean',
            'period_9' => 'required|date|after_or_equal:period_8',
            'period_9_closed' => 'required|boolean',
            'period_10' => 'required|date|after_or_equal:period_9',
            'period_10_closed' => 'required|boolean',
            'period_11' => 'required|date|after_or_equal:period_10',
            'period_11_closed' => 'required|boolean',
            'period_12' => 'required|date|after_or_equal:period_11',
            'period_12_closed' => 'required|boolean',
        ];
    }
}
