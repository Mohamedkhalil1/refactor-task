<?php

namespace App\Services\ControllerLogic\Visit;

use App\Http\Requests\Visit\CreateNewVisitRequest;
use App\Http\Requests\Visit\FilterAllVisitsRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Models\Visit;
use App\Services\Visit\VisitService as CoreVisitService;

class VisitService
{
    public function getAllVisits(FilterAllVisitsRequest $request)
    {
        return Visit::query()
            ->join('loyalties', 'visits.id', '=', 'loyalties.visit_id')
            ->with(['loyalty:id,points,visit_id', 'cashier:id,name', 'member:id,first_name,last_name'])
            ->searchByMember(search : $request->input('member_search'), columns : ['name', 'email', 'phone'])
            ->searchByCashier(search : $request->input('cashier_search'), columns  : ['name', 'email'])
            ->searchByDate(date : $request->input('date_search'))
            ->searchByReceipt(search : $request->input('receipt_search'))
            ->orderBy('loyalties.points', 'desc');
    }

    public function getVisit(Visit $visit)
    {
        return $visit->load(['loyalty:id,points,visit_id', 'cashier:id,name', 'member:id,first_name,last_name']);
    }

    public function createWithLoyalty(CreateNewVisitRequest $request)
    {
        $service = new CoreVisitService();

        return $service->handelCreationWithLoyalty($request);
    }

    public function updateWithLoyalty(UpdateVisitRequest $request, Visit $visit)
    {
        $service = new CoreVisitService();

        return $service->handelUpdateWithLoyalty($request, $visit);
    }

    public function deleteVisit(Visit $visit): void
    {
        $visit->delete();
    }
}
