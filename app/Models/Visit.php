<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory;

    //region Attributes
    protected $fillable = [
        'member_id',
        'receipt',
        'cashier_id',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'receipt' => 'integer',
        'cashier_id' => 'integer',
    ];

    //endregion

    //region relations
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function loyalty(): HasOne
    {
        return $this->hasOne(Loyalty::class);
    }
    //endregion

    public function scopeSearchByMember($query, $search, $columns = [])
    {
        if (empty($search)) {
            return $query;
        }

        return $query->whereHas('member', function ($member_query) use ($search, $columns) {
            return $member_query->search(search : $search, columns : $columns);
        });
    }

    public function scopeSearchByCashier($query, $search, $columns = [])
    {
        if (empty($search)) {
            return $query;
        }

        return $query->whereHas('cashier', function ($member_query) use ($search, $columns) {
            return $member_query->search(search : $search, columns : $columns);
        });
    }

    public function scopeSearchByDate($query, $date)
    {
        if (empty($date)) {
            return $query;
        }

        return $query->whereDate('visits.created_at', $date);
    }

    public function scopeSearchByReceipt($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('receipt', $search);
    }
}
