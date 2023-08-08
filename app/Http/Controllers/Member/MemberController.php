<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\CreateMemeberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Services\ControllerLogic\Member\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    // you should get member list with
    // 1. search by name, email, phone
    // 2. order them `DESC`
    // 3. load latest visits date as last_visit_date column (hint: use AddSelect)
    // 4. load max receipt on visits as max_receipt
    // 5. load total receipt on visits as total_receipt
    // 6. load total loyalty points as total_points
    public function index(Request $request)
    {
        $searchColumns = ['name', 'email', 'phone'];
        $members = $this->memberService->getAllMembers($request->input('search'), $searchColumns)->paginate();

        return success(MemberResource::collection($members));
    }

    public function getMemberHasNoVisit()
    {
        $members = $this->memberService->getMemberHasNoVisit()->paginate();

        return success(MemberResource::collection($members));
    }

    public function getMemberHasVisit()
    {
        $members = $this->memberService->getMemberHasVisit()->paginate();

        return success(MemberResource::collection($members));
    }

    // create member
    public function store(CreateMemeberRequest $request)
    {
        $this->memberService->create($request);

        return success([], 'Member created successfully');
    }

    // update member
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $this->memberService->update($request, $member);

        return success([], 'Member updated successfully');
    }

    // delete member
    public function destroy(Member $member)
    {
        $this->memberService->delete($member);

        return success([], 'Member deleted successfully');
    }
}
