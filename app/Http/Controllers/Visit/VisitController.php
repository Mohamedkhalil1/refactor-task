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
use App\Services\ControllerLogic\Visit\VisitService;
use Exception;

class VisitController extends Controller
{
    private VisitService $visitService;

    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
    }

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
        $visits = $this->visitService->getAllVisits($request)->paginate();

        return success(VisitResource::collection($visits));
    }

    public function show(Visit $visit)
    {
        $visit = $this->visitService->getVisit($visit);

        return success(VisitResource::make($visit));
    }

    // create visit and loyalty when member buy something
    // note: cashier who create visits
    // you can assume that cashier is logged in (auth::user() == cashier)
    public function store(CreateNewVisitRequest $request)
    {
        try {
            $visit = $this->visitService->createWithLoyalty($request);

            return success(VisitResource::make($visit));
        } catch (Exception $e) {
            ray($e);

            return fail([], $e->getMessage());
        }
    }

    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        try {
            $visit = $this->visitService->updateWithLoyalty($request, $visit);

            return success(VisitResource::make($visit));
        } catch (Exception $e) {
            return fail([], $e->getMessage());
        }
    }

    public function destroy(Visit $visit)
    {
        try {
            $this->visitService->deleteVisit($visit);

            return success([], 'visit deleted successfully');
        } catch (Exception $e) {
            return fail([], $e->getMessage());
        }
    }
}
