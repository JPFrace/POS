<?php

namespace App\Http\Requests\Business\JournalEntry;

use Illuminate\Validation\Rule;

class UpdateJournalEntryRequest extends JournalEntryRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Journal Entry', ['edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $journal = $this->route('journal_entry');

        return [
            ...parent::rules(),
            'je_no' => [
                'required',
                'string',
                'max:25',
                Rule::unique('journal_entries', 'je_no')->ignore($journal->id)
            ],

            'ref_no' => [
                'required',
                'string',
                'max:25',
                Rule::unique('journal_entries', 'ref_no')->ignore($journal->id)
            ],

        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $journal = $this->route('journal_entry');
        $data = $this->all();

        if (!$this->je_no_auto || $this->je_no_auto === "false") {
            $data['je_no'] = $this->je_no;
        } else {
            $data['je_no'] = $journal->je_no;
        }

        if (!$this->ref_no_auto || $this->ref_no_auto === "false") {
            $data['ref_no'] = $this->ref_no;
        } else {
            $data['ref_no'] = $journal->ref_no;
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
