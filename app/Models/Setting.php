<?php

namespace App\Models;

use App\Enums\LoyaltyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'cashier_id' => 'integer',
        'loyalty_model' => LoyaltyModel::class,
        'factor' => 'float',
        'min_points' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'cashier_id',
        'loyalty_model',
        'factor',
        'min_points',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }
}
