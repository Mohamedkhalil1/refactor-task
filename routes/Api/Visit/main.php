<?php

use App\Http\Controllers\Visit\VisitController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/', VisitController::class)->parameters(['' => 'visit']);
