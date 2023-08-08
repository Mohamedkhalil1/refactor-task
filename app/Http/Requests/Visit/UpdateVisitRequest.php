<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receipt' => ['required', 'decimal', 'min:1', 'between:1,999.99', 'decimal:2'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
