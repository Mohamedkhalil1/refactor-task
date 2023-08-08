<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

class FilterAllVisitsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_search' => ['sometimes', 'nullable', 'string'],
            'cashier_search' => ['sometimes', 'nullable', 'string'],
            'date_search' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'receipt_search' => ['sometimes', 'nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
