<?php

namespace App\Services\Members;

use App\Dtos\Members\MemberDto;
use App\Repositories\Members\Contracts\MemberRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MemberService
{

    protected MemberRepositoryInterface $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function getMembers($request)
    {
        $filters = $this->prepareFilters($request);
        $perPage = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'id';
        $orderBy = $request->order_by ?? 'desc';
        $members = $this->memberRepository->getMembers($filters, $perPage, $sortBy, $orderBy);
        return [
            'data' => $members,
            'pagination' => $members->toArray(),
        ];
    }

    public function getMemberHasNoVisit()
    {
        $perPage = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'id';
        $orderBy = $request->order_by ?? 'desc';
        $members = $this->memberRepository->getMemberHasNoVisits($perPage, $sortBy, $orderBy);
        return [
            'data' => $members,
            'pagination' => $members->toArray(),
        ];
    }

    public function getMemberHasVisit()
    {
        $perPage = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'id';
        $orderBy = $request->order_by ?? 'desc';
        $members = $this->memberRepository->getMemberHasVisit($perPage, $sortBy, $orderBy);
        return [
            'data' => $members,
            'pagination' => $members->toArray(),
        ];
    }

    /**
     * @param $request
     * @return array
     */
    private function prepareFilters($request): array
    {
        return [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
    }


    public function createMember($request)
    {
        $memberData = new MemberDto($request);
        return [
            'data' => $this->memberRepository->createMember((array)$memberData)
        ];
    }


    public function updateMember($request, $member)
    {
        $this->memberRepository->updateMember($request, $member);
    }

    public function deleteMember($member)
    {
        return DB::transaction(function () use ($member) {
            $this->memberRepository->deleteMember($member);
        });
    }

}
