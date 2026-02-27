<?php

namespace App\Http\Requests\Accounting\Reconciliations;

use App\Models\Journal;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReconcileRequest extends FormRequest
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
            'journal_id' => 'required|exists:journals,id',
            'beginning_at' => 'required',
            'ending_at' => 'required',
            'event' => 'required'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'journal_id' => Journal::where('uuid', $this->journal_id)->first()->id,
            'beginning_at' => Carbon::parse($this->beginning_at . " 00:00:00", 'Asia/Manila')->utc(),
            'ending_at' => Carbon::parse($this->ending_at . " 23:59:59", 'Asia/Manila')->utc()
        ]);
    }
}
