<?php

namespace App\Dtos\Members;

use Illuminate\Support\Arr;

class MemberDto
{


    public string $first_name;
    public string $last_name;
    public string $email;
    public string $phone;
    public string $gender;

    public function __construct(array $data)
    {
        $this->first_name = Arr::get($data, 'first_name', '');
        $this->last_name = Arr::get($data, 'last_name', '');
        $this->email = Arr::get($data, 'email', '');
        $this->phone = Arr::get($data, 'phone', '');
        $this->gender = Arr::get($data, 'gender', '');
    }

}
