<?php

namespace App\Http\Requests\Business\JournalEntry;

use App\Enums\JournalEntryStatusEnum;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JournalEntryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'je_no' => $this->je_no_auto
                ? 'nullable|string|max:25'
                : 'required|string|max:25|unique:journal_entries,je_no',
            'ref_no' => $this->ref_no_auto
                ? 'nullable|string|max:25'
                : 'required|string|max:25|unique:journal_entries,ref_no',
            'date' => 'required',
            'memo' => 'required|string',
            'description' => 'nullable|max:250',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf',
            'creator_id' => Rule::exists('users', 'id'),
            'contact_idno' => [
                'nullable',
                Rule::exists("contacts", "id_no")
            ],
            'dept_id' => [
                'nullable',
                Rule::exists("departments", "id")
            ],
            'items.*.debit' => 'nullable|decimal:2',
            'items.*.credit' => 'nullable|decimal:2',
            'items.*.name' => 'nullable|max:120',
            'items.*.description' => 'nullable|max:250',
        ];
    }

    public function prepareForValidation()
    {
        $user = $this->user();

        $items = array_filter(json_decode($this->items, true), fn($row) => !empty($row['account']['value']));

        $this->merge([
            'creator_id' => $user->id,
            'items' => array_map(function (array $row) {
                $contact = !empty($row['contact']['value']) ?
                    Contact::whereUuid($row['contact']['value'])->first() : null;

                $chartAccount = !empty($row['account']['value']) ?
                    ChartAccount::whereUuid($row['account']['value'])->first() : null;

                return [
                    ...$row,
                    'chart_account_id' => $chartAccount?->id,
                    'dept_id' => $chartAccount?->dept_id,
                    'contact_idno' => $contact?->id_no,
                    'contact_name' => $contact?->name,
                    'contact_type' => $contact?->type,
                    'debit' => number_format(Amount::acceptable($row['debit']), 2, '.', ''),
                    'credit' => number_format(Amount::acceptable($row['credit']), 2, '.', '')
                ];
            }, $items),
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
        ]);
    }
}
