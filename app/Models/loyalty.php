<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loyalty extends Model
{
    use HasFactory;

    protected $table = 'loyalty';

    protected $fillable = [
        'points',
        'visit_id',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

}
