<?php

namespace App\Http\Requests\Reports\Bookmarks;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookmarkStoreRequest extends FormRequest
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
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'report_id' => 'required|integer|exists:reports,id',
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'report_id' => Report::where('uuid', $this->report)->value('id'),
        ]);

        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}
