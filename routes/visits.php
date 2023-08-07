<?php


use App\Http\Controllers\Visits\VisitController;
use Illuminate\Support\Facades\Route;




Route::apiResource('visits', VisitController::class);
