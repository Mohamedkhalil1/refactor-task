<?php

namespace App\Dtos\Visits;

use Illuminate\Support\Arr;

class VisitsDto
{

    public int $receipt;
    public int $member_id;
    public int $cashier_id;

    public function __construct(array $data)
    {
        $this->receipt = Arr::get($data , 'receipt' );
        $this->member_id = Arr::get($data , 'member_id' );
        $this->cashier_id = 1; # if we have auth system the value will be auth()->id()
    }

}
