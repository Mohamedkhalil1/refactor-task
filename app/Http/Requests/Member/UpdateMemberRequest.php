<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function rules(): array
    {
        $memeber = $this->route('member');

        return [
            'first_name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'last_name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:members,email,'.$memeber->id],
            'phone' => ['sometimes', 'numeric', 'unique:members,phone,'.$memeber->id],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
