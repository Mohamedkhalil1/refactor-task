<?php

namespace App\Http\Controllers\Members;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Members\MemberRequest;
use App\Http\Resources\Members\MemberResource;
use App\Models\Member;
use App\Services\Members\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    protected MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(Request $request)
    {
        try {
            $members = $this->memberService->getMembers($request);
            $data = MemberResource::collection($members['data']);
            return ApiResponse::success(200, $data, 'Successful', $members['pagination']);
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }


    public function getMemberHasNoVisit()
    {
        try {
            $members = $this->memberService->getMemberHasNoVisit();
            $data = MemberResource::collection($members['data']);
            return ApiResponse::success(200, $data, 'Successful', $members['pagination']);
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    public function getMemberHasVisit()
    {
        try {
            $members = $this->memberService->getMemberHasVisit();
            $data = MemberResource::collection($members['data']);
            return ApiResponse::success(200, $data, 'Successful', $members['pagination']);
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    # create member
    public function store(MemberRequest $request)
    {
        try {
            $member = $this->memberService->createMember($request->validated());
            $data = MemberResource::make($member['data']);
            return ApiResponse::success(201, $data, 'Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    # update member
    public function update(MemberRequest $request, Member $member)
    {
        try {
            $this->memberService->updateMember($request->all(), $member);
            return ApiResponse::success(200, [], 'Update Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    # delete member
    public function destroy(Member $member)
    {
        try {
            $this->memberService->deleteMember($member);
            return ApiResponse::success(200, [], 'Delete Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }
}
