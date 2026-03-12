<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helper\ApiResponse;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->reason) {
            $this->merge([
                'reason' => trim($this->reason)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'start_date' => [
                'sometimes',
                'date',
                'after_or_equal:today'
            ],

            'end_date' => [
                'sometimes',
                'date',
                'after_or_equal:start_date'
            ],

            'reason' => [
                'sometimes',
                'string',
                'max:500'
            ],

            'attachment' => [
                'sometimes',
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048'
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