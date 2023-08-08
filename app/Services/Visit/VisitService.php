<?php

namespace App\Services\Visit;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitService
{
    private LoyaltyService $loyaltyService;

    public function __construct()
    {
        $this->loyaltyService = new LoyaltyService();
    }

    public function create(Request $request): Visit
    {

        // we can save last_visit_date , max_receipt , total_receipt , total_points in member table each time we create visit
        // or we can calculate it when we need it

        return Visit::create([
            'member_id' => $request->member_id,
            'receipt' => $request->receipt,
            'cashier_id' => $request->cashier_id,
        ]);
    }

    public function updateVisit(Request $request, Visit $visit): Visit
    {
        $visit->update([
            'receipt' => $request->receipt,
        ]);

        return $visit;
    }

    public function deleteVisit(Visit $visit): void
    {
        $visit->delete();
    }

    /**
     * @throws \Throwable
     */
    public function handelCreationWithLoyalty(Request $request): Visit
    {
        $visit = $this->create($request)->load('cashier.settings');

        $this->loyaltyService->setVisit($visit)->handelCreation();

        return $visit;
    }

    /**
     * @throws \Throwable
     */
    public function handelUpdateWithLoyalty(Request $request, Visit $visit): Visit
    {
        $visit = $this->updateVisit($request, $visit)->load('cashier.settings');

        $this->loyaltyService->setVisit($visit)->deleteLoyalty()->handelCreation(); // refresh loyalty

        return $visit;
    }
}
