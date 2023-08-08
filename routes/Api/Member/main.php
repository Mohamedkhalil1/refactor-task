<?php

use App\Http\Controllers\Member\MemberController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/', MemberController::class)->except(['show'])->parameters(['' => 'member']);

Route::get('has-no-visit', [MemberController::class, 'getMemberHasNoVisit']);
Route::get('has-visit', [MemberController::class, 'getMemberHasVisit']);
