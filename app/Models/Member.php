<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone'
    ];

    #region Attributes

    #endregion

    #region relations
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function loyalty(): HasManyThrough
    {
        return $this->hasManyThrough(Loyalty::class, Visit::class);
    }
    #endregion
}
