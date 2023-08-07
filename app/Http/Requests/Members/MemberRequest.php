<?php

namespace App\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'gender' => ['required'],
            'email' => ['required' , 'email' , 'unique:members,email'] ,
            'phone' => ['required' , 'numeric' , 'unique:members,phone'],
        ];
    }

    private function updateRequest()
    {
        return [
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'gender' => ['nullable'],
            'email' => ['nullable' , 'email' , Rule::unique('members')->ignore($this->route('member'))],
            'phone' => ['nullable' , 'numeric' , Rule::unique('members')->ignore($this->route('member'))],
        ];
    }
}
