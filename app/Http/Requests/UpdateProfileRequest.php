<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Helper\ApiResponse;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null;
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
                'sometimes',
                'string',
                'max:255'
            ],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],

            'password' => [
                'sometimes',
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ]
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
