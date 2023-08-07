<?php

namespace App\Http\Requests\Visit;

use App\Models\Cashier;
use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class CreateNewVisitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'receipt' => ['required', 'integer', 'min:1'],
            'cashier_id' => ['required', 'integer', 'exists:cashiers,id'],
        ];
    }

    public function getCashier()
    {
        return Cashier::find($this->cashier_id);
    }

    // member
    public function getMember()
    {
        return Member::find($this->member_id);
    }

    public function authorize(): bool
    {
        return true;
    }
}
