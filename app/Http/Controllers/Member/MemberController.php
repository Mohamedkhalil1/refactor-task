<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\CreateMemeberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // you should get member list with
    // 1. search by name, email, phone
    // 2. order them `DESC`
    // 3. load latest visits date as last_visit_date column (hint: use AddSelect)
    // 4. load max receipt on visits as max_receipt
    // 5. load total receipt on visits as total_receipt
    // 6. load total loyalty points as total_points
    public function index(Request $request)
    {
        $members = Member::search(search : request('search'), columns : ['name', 'email', 'phone'])
            ->withVisitDetails()
            ->orderBy('id', 'desc')
            ->paginate();

        return success(MemberResource::collection($members));
    }

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
        $members = Member::query()
            ->select('members.*')
            ->leftJoin('visits', 'members.id', '=', 'visits.member_id')
            ->whereNull('visits.member_id')
            ->orderBy('members.id', 'desc')
            ->paginate();

        return success(MemberResource::collection($members));
    }

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
        $members = Member::query()
            ->select('members.*')
            ->join('visits', 'members.id', '=', 'visits.member_id')
            ->orderBy('members.id', 'desc')
            ->withVisitDetails()
            ->paginate();

        return success(MemberResource::collection($members));
    }

    // create member
    public function store(CreateMemeberRequest $request)
    {
        Member::create($request->validated());

        return success([], 'Member created successfully');
    }

    // update member
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return success([], 'Member updated successfully');
    }

    // delete member
    public function destroy(Member $member)
    {
        $member->delete();

        return success([], 'Member deleted successfully');
    }
}
