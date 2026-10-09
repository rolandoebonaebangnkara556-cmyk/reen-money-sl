<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone_number' => 'nullable|string|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
            'email.unique' => 'Email already registered',
            'phone_number.unique' => 'Phone number already registered',
        ];
    }
}
