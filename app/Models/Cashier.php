<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cashier extends Model
{
    use HasFactory;


    public function settings(): hasOne
    {
        return $this->hasOne(Setting::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
