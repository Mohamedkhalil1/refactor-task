<?php


use App\Http\Controllers\Members\MemberController;
use Illuminate\Support\Facades\Route;


Route::apiResource('members', MemberController::class)->except('show');
Route::prefix('members')->group(function () {
    Route::get('/with-no-visits', [MemberController::class, 'getMemberHasNoVisit']);
    Route::get('/with-visits', [MemberController::class, 'getMemberHasVisit']);
});

