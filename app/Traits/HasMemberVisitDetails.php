<?php

namespace App\Traits;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;

trait HasMemberVisitDetails
{
    public function scopeWithVisitDetails(Builder $query): void
    {
        $query->addSelect([
            'last_visit_date' => $this->lastVisitDateQuery(),
            'max_receipt' => $this->maxReceiptQuery(),
            'total_receipt' => $this->totalReceiptQuery(),
            'total_points' => $this->totalPointsQuery(),
        ]);
    }

    private function lastVisitDateQuery()
    {
        return Visit::selectRaw('MAX(created_at)')
            ->whereColumn('member_id', 'members.id');
    }

    private function maxReceiptQuery()
    {
        return Visit::selectRaw('MAX(receipt)')
            ->whereColumn('member_id', 'members.id');
    }

    private function totalReceiptQuery()
    {
        return Visit::selectRaw('SUM(receipt)')
            ->whereColumn('member_id', 'members.id');
    }

    private function totalPointsQuery()
    {
        return Visit::selectRaw('SUM(loyalties.points)')
            ->from('loyalties')
            ->join('visits', 'visits.id', 'loyalties.visit_id')
            ->whereColumn('visits.member_id', 'members.id');
    }
}
