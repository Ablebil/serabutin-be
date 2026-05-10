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
        return [];
    }
}
