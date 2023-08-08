<?php

use Illuminate\Support\Facades\Route;


Route::get('/visits', [App\Http\Controllers\VisitController::class, 'index']);
Route::get('/visits/{visit}', [App\Http\Controllers\VisitController::class, 'show']);
Route::post('/visits', [App\Http\Controllers\VisitController::class, 'store']);
Route::put('/visits/{visit}', [App\Http\Controllers\VisitController::class, 'update']);
Route::delete('/visits/{visit}', [App\Http\Controllers\VisitController::class, 'destroy']);

Route::get('/members', [App\Http\Controllers\MemberController::class, 'index']);
Route::post('/members', [App\Http\Controllers\MemberController::class, 'store']);
Route::put('/members/{member}',  [App\Http\Controllers\MemberController::class, 'update']);
Route::delete('/members/{member}',  [App\Http\Controllers\MemberController::class, 'destroy']);
