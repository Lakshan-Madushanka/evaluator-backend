<?php

namespace App\Http\Requests\User;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @property string|int $role
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'max:100',
            ],
            'email' => [
                'string',
                'required',
                'email',
                Rule::unique('users', 'email'),
                'max:255',
            ],
            'password' => [
                'required',
                //Rule::requiredIf((int) $this->role !== Role::REGULAR->value),
                'confirmed',
                Password::defaults(),
            ],
            'role' => [
                'required',
                'int',
                Rule::in([
                    Role::ADMIN->value,
                    Role::REGULAR->value,
                ]),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        /*
         * As regular users aren't meant to login
         * we just assign a random integer
         */
        if ((int) $this->role === Role::REGULAR->value) {
            $password = Str::random();
            $this->merge([
                'password' => $password,
                'password_confirmation' => $password,

            ]);
        }
    }
}
