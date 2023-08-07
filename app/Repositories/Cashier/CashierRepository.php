<?php

namespace App\Repositories\Cashier;


use App\Models\Cashier;
use App\Repositories\Cashier\Contracts\CashierRepositoryInterface;


class CashierRepository implements CashierRepositoryInterface
{

    public function getCashierWithSettings($id)
    {
        return Cashier::query()->with('settings')->find($id);
    }
}
