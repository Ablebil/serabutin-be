<?php

namespace App\Http\Requests\Api\V1\Bids;

use Illuminate\Foundation\Http\FormRequest;

class ListBidsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:pending,accepted,rejected,withdrawn'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status bid tidak valid.',
            'limit.integer' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal 1.',
            'limit.max' => 'Limit maksimal 100.',
        ];
    }
}
