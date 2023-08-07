<?php

namespace App\Repositories\Members;

use App\Models\loyalty;
use App\Models\Member;
use App\Models\Visit;
use App\Repositories\Members\Contracts\MemberRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class MemberRepository implements MemberRepositoryInterface
{

    public function getMembers($filters, $perPage, $sortBy, $orderBy)
    {
        $query = Member::query();
        $subQueries = $this->loadSubQueries();
        if (!empty($subQueries)) {
            $query = $query->addSelect($subQueries);
        }
        $query = $this->applyFilters($query, $filters);
        return $query->orderBy($sortBy, $orderBy)->paginate($perPage);
    }

    public function getMemberHasNoVisits($perPage, $sortBy, $orderBy)
    {
        return Member::query()
            ->select('members.*')
            ->addSelect($this->loadSubQueries())
            ->leftJoin('visits', 'members.id', '=', 'visits.member_id')
            ->whereNull('visits.member_id')
            ->distinct()
            ->orderBy($sortBy, $orderBy)->paginate($perPage);
    }

    public function getMemberHasVisit($perPage, $sortBy, $orderBy)
    {
        return Member::query()
            ->select('members.*')
            ->addSelect($this->loadSubQueries())
            ->leftJoin('visits', 'members.id', '=', 'visits.member_id')
            ->whereNotNull('visits.member_id')
            ->distinct()
            ->orderBy($sortBy, $orderBy)->paginate($perPage);
    }

    public function createMember($data)
    {
        return Member::create($data);
    }

    public function updateMember($data, $member)
    {
        return $member->update($data);
    }

    public function deleteMember($member)
    {
        $member->loyalties()->delete();
        $member->visits()->delete();
        $member->delete();
    }


    protected function applyFilters(Builder $query, $filters = []): Builder
    {
        if (array_key_exists('id', $filters)) {
            $query = $query->where('id', $filters['id']);
        }
        if (array_key_exists('first_name', $filters)) {
            $query = $query->whereRaw("CONCAT(COALESCE(members.first_name, ''), ' ', COALESCE(members.last_name, '')) LIKE ?", ["%{$filters['first_name']}%"]);
        }
        if (array_key_exists('last_name', $filters)) {
            $query = $query->whereRaw("CONCAT(COALESCE(members.first_name, ''), ' ', COALESCE(members.last_name, '')) LIKE ?", ["%{$filters['last_name']}%"]);
        }

        if (array_key_exists('email', $filters)) {
            $query = $query->where('members.email', 'like', "%{$filters['email']}%");
        }

        if (array_key_exists('phone', $filters)) {
            $query = $query->where('members.phone', 'like', "%{$filters['phone']}%");
        }

        return $query;
    }


    /**
     * @return array
     */
    public function loadSubQueries(): array
    {
        return [
            'last_visit_date' => Visit::query()->select(DB::raw('MAX(visits.created_at)'))->whereColumn('visits.member_id', '=', 'members.id'),
            'max_receipt' => Visit::query()->select(DB::raw('MAX(visits.receipt)'))->whereColumn('visits.member_id', '=', 'members.id'),
            'total_receipt' => Visit::query()->select(DB::raw('SUM(visits.receipt)'))->whereColumn('visits.member_id', '=', 'members.id'),
            'total_points' => loyalty::query()
                ->join('visits', 'visits.id', '=', 'loyalty.visit_id')
                ->select(DB::raw('SUM(loyalty.points)'))
        ];
    }

}
