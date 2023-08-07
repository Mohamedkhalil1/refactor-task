<?php

namespace App\Http\Requests\Visits;

use Illuminate\Foundation\Http\FormRequest;

class VisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->getMethod() === "POST" ? $this->createRequest() : $this->updateRequest();
    }

    private function createRequest()
    {
        return [
            'receipt' => ['required'],
            'member_id' => ['required']
        ];
    }

    private function updateRequest()
    {
        return [
            'receipt' => ['nullable'],
            'member_id' => ['nullable']
        ];
    }
}
