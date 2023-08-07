<?php

namespace App\Repositories\Members\Contracts;

interface MemberRepositoryInterface
{
    public function getMembers($filters, $perPage, $sortBy, $orderBy);

    public function getMemberHasNoVisits($perPage, $sortBy, $orderBy);

    public function getMemberHasVisit($perPage, $sortBy, $orderBy);

    public function createMember($data);

    public function updateMember($data, $member);

    public function deleteMember($member);
}
