<?php

namespace App\Http\Requests;

use App\Rules\PassRule;
use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Email;

class AuthRequest extends FormRequest
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
            'email' => [
                'required',
            ],
            'password' => [
                'required',
            ]

        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Họ không được để trống.',
            'password.required' => 'Họ không được để trống.',
        ];
    }
}
