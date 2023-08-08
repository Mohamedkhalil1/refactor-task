<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisitResource;
use App\Models\Cashier;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\LoyaltyService;

class VisitController extends Controller
{

    public function __construct()
    {
        $this->LoyaltyService = new LoyaltyService();
    }

    # index: to get visit with search by member name
    # filter by member phone number
    # search by cashier name
    # search by date
    # search by receipt
    # load loyalty points with it
    # load cashier name with it
    # load member name with it
    # order by max loyalty points

    public function index(): AnonymousResourceCollection
    {
        $visits = Visit::query()
            ->when(request('search'), function ($query) {
                $query->where(function ($query) {
                    $query->whereDate('visits.created_at', request('search'))
                          ->orWhere('visits.receipt', 'like', '%' .request('search') . '%');
                })

                ->orWhereHas('member', function ($query) {
                    $query->where('first_name', 'like', '%' . request('search') . '%')
                        ->orWhere('last_name', 'like', '%' . request('search') . '%')
                        ->orWhere(\DB::raw("CONCAT(`first_name`,' ',`last_name`)"), 'like', '%' . request('search') . '%')
                        ->orWhere('email', 'like', '%' . request('search') . '%')
                        ->orWhere('phone', 'like', '%' . request('search') . '%');
                })

                ->orWhereHas('cashier', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })

                ->orWhereHas('member', function ($query) {
                    $query->where('phone', 'like', '%' . request('search') . '%');
                });

            })
            
            ->addSelect(['points' => function($query) {
                $query->select('points')
                    ->from('loyalty')
                    ->whereColumn('visits.id', 'loyalty.visit_id')->limit(1);
            }])

            ->addSelect(['cashier' => function($query) {
                $query->select('name')
                    ->from('cashiers')
                    ->whereColumn('cashiers.id', 'visits.cashier_id')->limit(1);
            }])

            ->addSelect(['member' => function($query) {
                $query->selectRaw('CONCAT(first_name, " ", last_name) as full_name')
                    ->from('members')
                    ->whereColumn('members.id', 'visits.member_id')->limit(1);
            }])
            ->leftJoin('loyalty', 'visits.id', '=', 'loyalty.visit_id')
            ->groupBy('visits.id', 'visits.receipt', 'visits.member_id', 'visits.cashier_id', 'visits.created_at'
            , 'visits.updated_at')
            ->orderByRaw('MAX(loyalty.points) DESC')
            ->get();

        return VisitResource::collection($visits);
    }


    public function show(Visit $visit)
    {
        return VisitResource::make($visit);
    }

    # create visit and loyalty when member buy something
    # note: cashier who create visits
    # you can assume that cashier is logged in (auth::user() == cashier)
    public function store(Request $request)
    {
        $visit = Visit::create($request->all());
       
        $cashier = Cashier::find($request->cashier_id);
        $settings = $cashier->settings;
        $this->LoyaltyService->createLoyaltyPoints($visit, $settings, $request->all());

    }

    public function update(Request $request, Visit $visit)
    {
        $visit->update($request->all());
        
        $cashier = Cashier::find($request->cashier_id);
        $settings = $cashier->settings;
        $this->LoyaltyService->createLoyaltyPoints($visit, $settings, $request->all());

    }

    public function destroy(Visit $visit)
    {
        $visit->loyalty()->delete();
        $visit->delete();
    }

}
