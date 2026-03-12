<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Helper\ApiResponse;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->email) {
            $this->merge([
                'email' => strtolower($this->email)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],

            'role' => [
                'required',
                'string',
                Rule::in(['employee', 'admin'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email already registered',
            'password.confirmed' => 'Password confirmation does not match',
            'role.in' => 'Invalid role value'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                'Validation failed',
                422,
                $validator->errors()
            )
        );
    }
}