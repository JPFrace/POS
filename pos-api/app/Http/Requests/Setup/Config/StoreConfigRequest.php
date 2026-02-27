<?php

namespace App\Http\Requests\setup\Config;

use App\Models\Config;
use Illuminate\Foundation\Http\FormRequest;

class StoreConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("Configs", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:config,slug',
            'type' => 'required|string|max:50',
            'value' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:config,id',
            'prefix' => 'nullable|string|max:25',
            'suffix' => 'nullable|string|max:25',
            'is_inactive' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => strtolower(str_replace(' ', '_', $this->name)),
            'parent_id' => isset($this->parent['value']) ? Config::whereUuid($this->parent['value'])->first()?->id : null,
            'created_by' => auth()->id(),
        ]);
    }
}
