<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helper\ApiResponse;

class ApproveLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->admin_note) {
            $this->merge([
                'admin_note' => trim($this->admin_note)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:approved,rejected'
            ],
            'admin_note' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
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