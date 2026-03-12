<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helper\ApiResponse;

class CreateLeaveRequest extends FormRequest
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
                'required',
                'date',
                'after_or_equal:today'
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date'
            ],

            'reason' => [
                'required',
                'string',
                'max:1000'
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ]
        ];
    }

    protected function failedValidation($validator)
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
