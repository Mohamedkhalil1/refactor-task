<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemberController extends Controller
{
    # you should get member list with
    # 1. search by name, email, phone
    # 2. order them `DESC`
    # 3. load latest visits date as last_visit_date column (hint: use AddSelect)
    # 4. load max receipt on visits as max_receipt
    # 5. load total receipt on visits as total_receipt
    # 6. load total loyalty points as total_points
    public function index(Request $request)
    {
        $member = Member::query()
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . request('search') . '%')
                    ->orWhere('last_name', 'like', '%' . request('search') . '%')
                    ->orWhere(\DB::raw("CONCAT(`first_name`,' ',`last_name`)"), 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%')
                    ->orWhere('phone', 'like', '%' . request('search') . '%');
            })
            ->withCount(['visits as total_receipt' => function ($query) {
                $query->select(\DB::raw('sum(receipt)'))->limit(1);
            }])
            ->addSelect(['last_visit_date' => function($query) {
                $query->select('created_at')
                    ->from('visits')
                    ->whereColumn('members.id', 'visits.member_id')->limit(1);
            }])
            ->addSelect(['max_receipt' => function($query) {
                $query->select(\DB::raw('MAX(receipt)'))
                    ->from('visits')
                    ->whereColumn('members.id', 'visits.member_id')->limit(1);
            }])
            ->leftJoin('visits', 'members.id', '=', 'visits.member_id')
                           ->leftJoin('loyalty', 'visits.id', '=', 'loyalty.visit_id')
                           ->selectRaw('SUM(loyalty.points) as total_points')
            ->orderBy('id', 'desc')
            ->groupBy('members.id', 'members.first_name', 'members.last_name', 'members.gender', 'members.phone'
            ,'members.email', 'members.created_at', 'members.updated_at')
            ->get();

        return MemberResource::collection($member);
    }


    public function getMemberHasNoVisit(): AnonymousResourceCollection
    {
        $member = Member::query()
            ->whereDoesntHave('visits')
            ->get();

        return MemberResource::collection($member);
    }

    public function getMemberHasVisit(): AnonymousResourceCollection
    {
        $member = Member::query()
            ->whereHas('visits')
            ->get();

        return MemberResource::collection($member);
    }

    # create member
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:members,email',
            'phone'      => 'required|numeric|unique:members,phone',
        ]);

        Member::create($request->all());
    }

    # update member
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'first_name' => 'sometimes|required',
            'last_name'  => 'sometimes|required',
            'email'      => 'sometimes|required|email|unique:members,email,' . $member->id,
            'phone'      => 'sometimes|required|numeric|unique:members,phone,' . $member->id,
        ]);

        $member->update($request->all());
    }

    # delete member
    public function destroy(Member $member)
    {
        $member->loyalty()->delete();
        $member->visits()->delete();
        $member->delete();
    }
}
