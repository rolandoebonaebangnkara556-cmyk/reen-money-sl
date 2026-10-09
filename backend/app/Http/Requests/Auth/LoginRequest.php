<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => 'required|string', // email o phone_number
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'Email or phone number is required',
            'password.required' => 'Password is required',
        ];
    }
}
