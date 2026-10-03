<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\PerfumeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'abilities:perfumes:read'])->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
        ]);
    });

    Route::get('/perfumes', [PerfumeController::class, 'index']);

    Route::get('/perfumes/{perfume}', [PerfumeController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/categories/{category}/perfumes', [CategoryController::class, 'perfumes']);

    Route::get('/exchange-rate', [ExchangeRateController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'abilities:perfumes:write'])->group(function () {

    Route::post('/perfumes', [PerfumeController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});