<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receipt' => ['required', 'decimal', 'numeric', 'min:1',  'between:1,999.99'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
