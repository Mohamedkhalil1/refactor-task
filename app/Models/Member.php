<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Member extends Model
{
    use HasFactory;


    protected $fillable = ['first_name', 'last_name', 'email', 'gender', 'phone'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function loyalties(): HasManyThrough
    {
        return $this->hasManyThrough(Loyalty::class, Visit::class, 'member_id', 'visit_id');
    }
}
