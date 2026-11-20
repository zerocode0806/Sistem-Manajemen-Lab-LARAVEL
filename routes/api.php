<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\KerusakanApiController;
use App\Http\Controllers\Api\PcApiController;
use App\Http\Middleware\CheckSupportToken;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1', CheckSupportToken::class])->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);

    Route::get('/pc', [PcApiController::class, 'index']);
    Route::get('/pc/{id_meja}', [PcApiController::class, 'show'])->whereNumber('id_meja');
    Route::post('/pc/{id_meja}/selesai-maintenance', [PcApiController::class, 'selesaiMaintenance'])->whereNumber('id_meja');

    Route::get('/kerusakan', [KerusakanApiController::class, 'index']);
});