<?php

namespace App\Http\Requests\Reports\Bookmarks;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BookmarkUpdateRequest extends FormRequest
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
            'date_from' => 'required|string|exists:bookmarks,date_from',
            'date_to' => 'required|string|exists:bookmarks,date_to',
            'report_id' => 'required|integer|exists:reports,id',
            'name' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'report_id' => $this->report_id ? Report::where('uuid', $this->report_id)->value('id') : null,
        ]);
    }
}
