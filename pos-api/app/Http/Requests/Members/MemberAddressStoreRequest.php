<?php

namespace App\Http\Requests\Members;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Member;
use App\Models\Province;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberAddressStoreRequest extends FormRequest
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
            'contact_name' => 'required',
            'contact_no' => 'required',
            'address1' => 'nullable',
            'address2' => 'nullable',
            'address3' => 'nullable',
            'provinces' => Rule::exists('provinces', 'uuid'),
            'cities' => Rule::exists('cities', 'uuid'),
            'barangays' => Rule::exists('barangays', 'uuid'),
            'member_id' => Rule::exists('members', 'id'),
            'zipcode' => ['required', 'numeric'],
            'default' => ['required', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $memberId = $this->user()->id;
        if ($this->user() instanceof User) {
            $memberId = Member::admin()->first()->id;
        }
        $this->merge([
            'member_id' => $memberId,
        ]);

        $this->replace([
            ...$this->all(),
            'provinces' => $this->input('provinces.value') ?? null,
            'cities' => $this->input('cities.value') ?? null,
            'barangays' => $this->input('barangays.value') ?? null,
        ]);
    }

    /**
     * Passed attributes after validated
     * @return void
     */
    public function passedValidation()
    {
        $this->merge([
            'province_id' => Province::whereUuid($this->provinces)->first()?->id,
            'city_id' => City::whereUuid($this->cities)->first()->id,
            'barangay_id' => Barangay::whereUuid($this->barangays)->first()->id,
        ]);
    }
}
