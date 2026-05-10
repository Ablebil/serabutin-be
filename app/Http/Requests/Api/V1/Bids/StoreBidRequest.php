<?php

namespace App\Http\Requests\Api\V1\Bids;

use Illuminate\Foundation\Http\FormRequest;

class StoreBidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proposed_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'proposed_price.required' => 'Harga penawaran wajib diisi.',
            'proposed_price.numeric' => 'Harga penawaran harus berupa angka.',
            'proposed_price.min' => 'Harga penawaran tidak boleh negatif.',
        ];
    }
}
