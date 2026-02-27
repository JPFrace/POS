<?php

namespace App\Http\Requests\Business\JournalEntry;

use App\Facades\SystemConfig;
use App\Models\Calendar;
use App\Facades\ReferenceNumb;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;

class StoreJournalEntryRequest extends JournalEntryRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('Journal Entry', ['create']);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),

            'je_no' => $this->je_no_auto
                ? 'nullable|string|max:25'
                : 'nullable|string|max:25|unique:journal_entries,je_no',

            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:25'
                : 'nullable|string|max:25|unique:journal_entries,ref_no',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (SystemConfig::get('business_journal_entry_enforce_fiscal_period_lock')) {
                $date = Carbon::parse($this->input('date'));
                if ($period = Calendar::checkClosedPeriod($date)) {
                    if (Calendar::isClosed($date)) {
                        $validator->errors()->add(
                            'date',
                            "The selected date falls in a locked fiscal period "
                            . "{$period['number']} of {$period['year']}."
                        );
                    }
                }
            }
        });
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $data = $this->all();

        if ($this->boolean('je_no_auto')) {
            $data['je_no'] = ReferenceNumb::generate('business_journal_entry_number', \App\Models\JournalEntry::class, 'je_no', Carbon::parse($this->input('date')));
        }

        if ($this->boolean('ref_no_auto')) {
            $data['ref_no'] = ReferenceNumb::generate('business_journal_entry_reference', \App\Models\JournalEntry::class, 'ref_no', Carbon::parse($this->input('date')));
        }

        $this->replace($data);
    }

    public function messages()
    {
        return [
            'je_no.max' => 'The journal number must not be longer than 25 characters.',
            'je_no.unique' => 'That journal number is already in use. Please choose a different one.',
            'ref_no.max' => 'The reference number must not be longer than 25 characters.',
            'ref_no.unique' => 'That reference number is already in use. Please choose a different one.',
        ];
    }
}
