<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    use HasFactory;

    # Region Properties

    protected $fillable = [
        'points',
        'visit_id',
    ];

    protected $casts = [
        'points' => 'integer',
        'visit_id' => 'integer',
    ];

    # EndRegion Properties

    # Region Relationships

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    # EndRegion Relationships
}
