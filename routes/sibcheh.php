<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::prefix('flight')->group(function () {
    Route::post('create', [FlightController::class, 'store']);
});

Route::prefix('passenger')->group(function () {
    Route::get('{passport}/origin-destination', [FlightController::class, 'track']);
});
