<?php

namespace App\Services\Visits;

use App\Dtos\Visits\VisitsDto;
use App\Repositories\Cashier\Contracts\CashierRepositoryInterface;
use App\Repositories\Visits\Contracts\VisitRepositoryInterface;
use Illuminate\Support\Facades\DB;

class VisitService
{

    protected VisitRepositoryInterface $visitRepository;
    protected CashierRepositoryInterface $cashierRepository;

    public function __construct(VisitRepositoryInterface $visitRepository, CashierRepositoryInterface $cashierRepository)
    {
        $this->visitRepository = $visitRepository;
        $this->cashierRepository = $cashierRepository;
    }

    public function getVisits($request)
    {
        $filters = $this->prepareFilters($request);
        $perPage = $request->per_page ?? 15;
        $visits = $this->visitRepository->getVisits($filters, $perPage);
        return [
            'data' => $visits,
            'pagination' => $visits->toArray(),
        ];
    }

    public function showVisit($visit)
    {
        return $visit->load('member', 'loyalties');
    }

    /**
     * @param $request
     * @return array
     */
    private function prepareFilters($request): array
    {
        return [
            'member_name' => $request->member_name ?? '',
            'member_phone' => $request->member_phone ?? '',
            'cashier_name' => $request->cashier_name ?? '',
            'receipt' => $request->receipt ?? '',
            'date' => $request->date ?? '',
        ];
    }


    public function createVisit($request)
    {
        $visitData = new VisitsDto($request);
        $visit = $this->visitRepository->createVisit((array)$visitData);
        if ($visitData->receipt > 0) {
            $this->createVisitLoyalty($visit);
        }
        return [
            'data' => $visit->load('member', 'loyalties')
        ];
    }

    public function createVisitLoyalty($visit)
    {
        $cashier = $this->cashierRepository->getCashierWithSettings(1);
        throw_if(!$cashier->settings, new \LogicException('No settings found for cashier', 404));
        $settings = $cashier->settings;
        $points = 0;
        if ($settings->loyalty_model === 'first_model' && ($visit->receipt >= $settings->min_points)) {
            $points = $visit->receipt / $settings->factor;
        } elseif ($settings->loyalty_model === 'second_model' && $visit->receipt < $settings->min_points) {
            $points = $visit->receipt * $settings->factor;
        }
        $loyaltyData = ['points' => $points, 'visit_id' => $visit->id];
        $this->visitRepository->createVisitLoyalty($visit, $loyaltyData);
    }

    public function updateVisit($request, $visit)
    {
        $this->visitRepository->updateVisit($request, $visit);
        if (array_key_exists('receipt' , $request) &&$request['receipt'] > 0) {
            $this->updateVisitLoyalty($visit);
        }
    }

    private function updateVisitLoyalty($visit)
    {
        $this->visitRepository->deleteVisitLoyalty($visit);
        $this->createVisitLoyalty($visit);
    }

    public function deleteVisit($visit)
    {
        return DB::transaction(function () use ($visit) {
            $this->visitRepository->deleteVisit($visit);
        });
    }

}
