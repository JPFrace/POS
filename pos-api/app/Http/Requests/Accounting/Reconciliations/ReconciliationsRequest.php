<?php

namespace App\Http\Requests\Accounting\Reconciliations;

use App\Models\BankAccount;
use App\Models\Calendar;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ReconciliationsRequest extends FormRequest
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
            'account_id' => ['required', Rule::exists('chart_accounts', 'id')],
            'calendar_id' => 'required|exists:calendars,id',
            'ending_balance' => 'required|decimal:2',
            'bank_statement_ending_balance' => 'required|decimal:2',
            'start_at' => 'required',
            'end_at' => 'required'
        ];
    }

    public function prepareForValidation()
    {
        // throw new HttpResponseException(response()->json(['m' => $this->bank_account], 500));
        $cib = BankAccount::where('uuid', $this->bank_account)->first()->chartAccount()->first()->id;
        $now = Carbon::now();
        $fiscalYear = Calendar::where('start_date', '<=', $now)->where('end_date', '>', $now)->first()->id;
        $this->merge([
            'calendar_id' => $fiscalYear,
            'account_id' => $cib,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'bank_statement_ending_balance' => number_format(Amount::acceptable($this->bank_statement_ending_balance), 2, '.', ''),
            'ending_balance' => number_format(Amount::acceptable($this->ending_balance), 2, '.', '')
        ]);
    }
}
