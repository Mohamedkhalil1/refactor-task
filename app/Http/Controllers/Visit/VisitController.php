<?php

namespace App\Http\Controllers\Visit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Visit\CreateNewVisitRequest;
use App\Http\Requests\Visit\FilterAllVisitsRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Resources\VisitResource;
use App\Models\Cashier;
use App\Models\Member;
use App\Models\Visit;
use App\Services\Visit\VisitService;
use Exception;

class VisitController extends Controller
{
    // index: to get visit with search by member name
    // filter by member phone number
    // search by cashier name
    // search by date
    // search by receipt
    // load loyalty points with it
    // load cashier name with it
    // load member name with it
    // order by max loyalty points

    public function index(FilterAllVisitsRequest $request)
    {
        $visits = Visit::query()
            ->join('loyalties', 'visits.id', '=', 'loyalties.visit_id')
            ->with(['loyalty:id,points,visit_id', 'cashier:id,name', 'member:id,first_name,last_name'])
            ->searchByMember(search : request('member_search'), columns : ['name', 'email', 'phone'])
            ->searchByCashier(search : request('cashier_search'), columns  : ['name', 'email'])
            ->searchByDate(date : request('date_search'))
            ->searchByReceipt(search : request('receipt_search'))
            ->orderBy('loyalties.points', 'desc')
            ->paginate();

        return success(VisitResource::collection($visits));
    }

    public function show(Visit $visit)
    {
        $visit->load(['loyalty:id,points,visit_id', 'cashier:id,name', 'member:id,first_name,last_name']);

        return success(VisitResource::make($visit));
    }

    // create visit and loyalty when member buy something
    // note: cashier who create visits
    // you can assume that cashier is logged in (auth::user() == cashier)
    public function store(CreateNewVisitRequest $request, VisitService $visitService)
    {
        try {
            $visit = $visitService->handelCreationWithLoyalty($request);

            return success(VisitResource::make($visit));
        } catch (Exception $e) {
            return fail([], $e->getMessage());
        }
    }

    public function update(UpdateVisitRequest $request, Visit $visit, VisitService $visitService)
    {
        try {
            $visit = $visitService->handelUpdateWithLoyalty($request, $visit);

            return success(VisitResource::make($visit));
        } catch (Exception $e) {
            return fail([], $e->getMessage());
        }
    }

    public function destroy(Visit $visit, VisitService $visitService)
    {
        try {
            $visitService->deleteVisit($visit);

            return success([], 'visit deleted successfully');
        } catch (Exception $e) {
            return fail([], $e->getMessage());
        }
    }
}
