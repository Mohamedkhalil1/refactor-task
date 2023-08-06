<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisitResource;
use App\Models\Cashier;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VisitController extends Controller
{
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
            ->when(request()->search, function ($query) {
                $query->whereHas('member', function ($query) {
                    $query->where('first_name', 'like', '%' . request()->search . '%')
                        ->orWhere('last_name', 'like', '%' . request()->search . '%')
                        ->orWhere('email', 'like', '%' . request()->search . '%')
                        ->orWhere('phone', 'like', '%' . request()->search . '%');
                });
            })
            ->when(request()->search, function ($query) {
                $query->whereHas('cashier', function ($query) {
                    $query->where('name', 'like', '%' . request()->search . '%');
                });
            })
            ->when(request()->search, function ($query) {
                $query->whereHas('member', function ($query) {
                    $query->where('phone', 'like', '%' . request()->search . '%');
                });
            })
            ->all();

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

        if ($settings->loyalty_model == 'first_model' && $request->receipt >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $request->receipt * $settings->factor,
            ]);
        } elseif ($settings->loyalty_model == 'second_model' && $request->receipt >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $request->receipt / $settings->factor,
            ]);
        }
    }

    public function update(Request $request, Visit $visit)
    {
        $visit->update($request->all());
        $cashier = Cashier::find($request->cashier_id);
        $settings = $cashier->settings;
        if ($settings->loyalty_model == 'first_model' && $request->receipt >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $request->receipt * $settings->factor,
            ]);
        } elseif ($settings->loyalty_model == 'second_model' && $request->receipt >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $request->receipt / $settings->factor,
            ]);
        }
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        $visit->loyalty()->delete();
    }

}
