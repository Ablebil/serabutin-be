<?php

namespace App\Http\Requests\Api\V1\Auth;

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
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'full_name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:client,worker'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('auth.validation.email_required'),
            'email.email' => __('auth.validation.email_email'),
            'email.max' => __('auth.validation.email_max'),
            'email.unique' => __('auth.validation.email_unique'),
            'password.required' => __('auth.validation.password_required'),
            'password.string' => __('auth.validation.password_string'),
            'password.min' => __('auth.validation.password_min'),
            'full_name.required' => __('auth.validation.full_name_required'),
            'full_name.string' => __('auth.validation.full_name_string'),
            'full_name.max' => __('auth.validation.full_name_max'),
            'role.required' => __('auth.validation.role_required'),
            'role.in' => __('auth.validation.role_in'),
        ];
    }
}
