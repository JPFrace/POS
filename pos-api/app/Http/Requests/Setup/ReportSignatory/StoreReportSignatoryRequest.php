<?php

namespace App\Http\Requests\setup\ReportSignatory;

use App\Models\Report;
use App\Models\Signatories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreReportSignatoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Report Signatories", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_id' => 'required|exists:reports,id',
            'label' => 'nullable|string|max:50',
            'signatory_id' => 'required|exists:signatories,id',
            'created_by' => 'nullable|exists:users,id',
            'is_inactive' => 'boolean',
            'sort' => [
                'required',
                'integer',
                Rule::unique('report_signatories')
                    ->where(fn($query) => $query->where('report_id', $this->report_id))
                    ->ignore($this->report_signatory?->id),
            ],
            'year_signatory' => 'required|digits:4',
        ];
    }
    public function prepareForValidation()
    {

        $this->merge([
            'report_id' => isset($this->report) ? Report::whereUuid($this->report)->first()?->id : null,
            'created_by' => auth()->id(),
            'signatory_id' => isset($this->signatory) ? Signatories::whereUuid($this->signatory)->first()?->id : null, //check payload for value (signatory)
        ]);

    }
}
