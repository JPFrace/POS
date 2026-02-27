<?php

namespace App\Http\Requests\Security\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        return $this->user()->can("Users", ['create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:120',
                Rule::unique('users', 'name')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'password' => 'required_if:default_password,false',
            'default_password' => 'nullable',
            'send_email_account' => 'nullable',
            'roles' => 'required',
            'roles.*' => [
                'required',
                Rule::exists('roles', 'uuid')
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $roles = is_array($this->roles) ? $this->roles : [$this->roles];

        $roles = isset($roles[0]) ? $roles : [$roles];
        $roles = array_filter($roles);

        $this->replace([
            ...$this->all(),
            'roles' => array_map(fn($role) => $role['value'], $roles)
        ]);
    }

    /**
     * Passed attributes after validated
     * @return void
     */
    public function passedValidation()
    {
        $password = $this->default_password ? config('system.default_password') : $this->password;

        $this->replace([
            ...$this->all(),
            'password' => bcrypt($password)
        ]);
    }
}
