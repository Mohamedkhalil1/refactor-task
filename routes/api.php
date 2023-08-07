<?php

use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Visit\VisitController;
use Illuminate\Support\Facades\Route;

// if routes number is more than [10], we can use separate files for each related routes like for visits or members
Route::apiResource('visits', VisitController::class);

Route::apiResource('members', MemberController::class)->except(['show']);
Route::group(['prefix' => 'members/'], function () {
    Route::get('has-no-visit', [MemberController::class, 'getMemberHasNoVisit']);
    Route::get('has-visit', [MemberController::class, 'getMemberHasVisit']);
});
