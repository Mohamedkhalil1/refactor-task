<?php

namespace App\Models;

use App\Traits\HasMemberVisitDetails;
use App\Traits\HasSearch;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Member extends Model
{
    use HasFactory , HasSearch , HasMemberVisitDetails;

    //region Attributes
    protected $fillable = [
        'first_name',
        'last_name',
        'is_male',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'is_male' => 'boolean',
    ];

    //endregion

    //region relations
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function loyalty(): HasManyThrough
    {
        return $this->hasManyThrough(Loyalty::class, Visit::class);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get : function (mixed $value, array $attributes) {
                return $attributes['first_name'].' '.$attributes['last_name'];
            },
        );
    }
}
