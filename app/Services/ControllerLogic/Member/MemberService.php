<?php

namespace App\Services\ControllerLogic\Member;

use App\Http\Requests\Member\CreateMemeberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;

class MemberService
{
    public function create(CreateMemeberRequest $request): Member
    {
        return Member::create($request->validated());
    }

    public function update(UpdateMemberRequest $request, Member $member): Member
    {
        $member->update($request->validated());

        return $member;
    }

    public function delete(Member $member): void
    {
        $member->delete();
    }

    /**
     * @return mixed
     */
    public function getAllMembers($search = null, $searchColumns = [])
    {
        return Member::search(search: $search, columns : $searchColumns)
            ->withVisitDetails()
            ->orderByDesc('id');
    }

    /**
     * @return Builder
     */
    public function getMemberHasNoVisit()
    {
        //        // This method is the fastest for larger datasets
        //        $members = Member::where('visit_count', 0)
        //            ->orderBy('id', 'desc')
        //            ->paginate();
        //
        //        // his method is best suited for small to medium datasets or applications
        //        // where database performance isn't critical and maintainability / readability of code is crucial.
        //        $members = Member::whereDoesntHave('visits')
        //            ->orderBy('id', 'desc')
        //            ->paginate();

        // This method works relatively well for both small and large datasets as it avoids the subquery,
        return Member::query()
            ->select('members.*')
            ->leftJoin('visits', 'members.id', '=', 'visits.member_id')
            ->whereNull('visits.member_id')
            ->orderByDesc('members.id');
    }

    /**
     * @return mixed
     */
    public function getMemberHasVisit()
    {
        //        // This method is the fastest for larger datasets
        //        $members = Member::where('visit_count', '>', 0)
        //            ->withVisitDetails()
        //            ->orderBy('id', 'desc')
        //            ->paginate();

        //
        //        // his method is best suited for small to medium datasets or applications
        //        // where database performance isn't critical and maintainability / readability of code is crucial.
        //        $members = Member::whereHas('visits')
        //            ->orderBy('id', 'desc')
        //            ->withVisitDetails()
        //            ->paginate();

        // This method works relatively well for both small and large datasets as it avoids the subquery,
        return Member::query()
            ->select('members.*')
            ->join('visits', 'members.id', '=', 'visits.member_id')
            ->withVisitDetails()
            ->orderByDesc('members.id');
    }
}
