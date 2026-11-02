<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomApi\TransactionsApiController;
use App\Http\Controllers\CustomApi\UsersApiController;

Route::middleware(['auth:sanctum', 'ability:admin_api', 'admin_api_token'])->group(function () {
    Route::post('irt/transaction', [TransactionsApiController::class, 'irtTransaction']);
    Route::get('inquiry', [UsersApiController::class, 'inquiry']);
});
