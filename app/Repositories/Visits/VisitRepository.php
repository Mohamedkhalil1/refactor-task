<?php

namespace App\Repositories\Visits;

use App\Models\Views\VisitView;
use App\Models\Visit;
use App\Repositories\Visits\Contracts\VisitRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class VisitRepository implements VisitRepositoryInterface
{


    public function getVisits($filters, $perPage)
    {
        $query = VisitView::query()->with('loyalties');
        $query = $this->applyFilters($query, $filters);
        return $query->orderByDesc('max_points')->paginate($perPage);
    }


    public function createVisit($data)
    {
        return Visit::create($data);
    }

    public function createVisitLoyalty($visit, $data)
    {
        $visit->loyalties()->create($data);
    }

    public function updateVisit($data, $visit)
    {
        return $visit->update($data);
    }

    public function deleteVisit($visit)
    {
        $visit->loyalties()->delete();
        $visit->delete();
    }

    public function deleteVisitLoyalty($visit)
    {
        $visit->loyalties()->delete();
    }


    protected function applyFilters(Builder $query, $filters = []): Builder
    {
        if (array_key_exists('member_name', $filters)) {
            if ($filters['member_name'] !== "") {
                $query = $query->where('visits_view.member_full_name', 'like', "%{$filters['member_name']}%");
            }
        }

        if (array_key_exists('member_phone', $filters)) {
            if ($filters['member_phone'] !== "") {
                $query = $query->where('visits_view.member_phone', 'like', "%{$filters['member_phone']}%");
            }
        }

        if (array_key_exists('cashier_name', $filters)) {
            if ($filters['cashier_name'] !== "") {
                $query = $query->where('visits_view.cashier_name', 'like', "%{$filters['cashier_name']}%");
            }
        }

        if (array_key_exists('receipt', $filters)) {
            if ($filters['receipt'] !== "") {
                $query = $query->where('visits_view.receipt', '=', $filters['receipt']);
            }
        }

        if (array_key_exists('date', $filters)) {
            if ($filters['date'] !== "") {
                $query = $query->where('visits_view.created_at', '=', $filters['date']);
            }
        }

        return $query;
    }
}
