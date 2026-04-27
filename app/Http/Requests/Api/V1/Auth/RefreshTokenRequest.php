<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $cookieName = (string) config('auth.refresh_token.cookie.name', 'refresh_token');

        if (!is_null($this->cookie($cookieName))) {
            $this->merge([
                'refresh_token' => $this->cookie($cookieName),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'refresh_token.required' => __('auth.validation.refresh_token_required'),
            'refresh_token.string' => __('auth.validation.refresh_token_string'),
        ];
    }
}
