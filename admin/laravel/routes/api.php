<?php

use App\Http\Controllers\AmlBotWebhookController;
use App\Http\Controllers\AmlCheckController;
use App\Http\Controllers\CregisDepositCallbackController;
use App\Http\Controllers\KycWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/kyc-webhook', KycWebhookController::class);

Route::get('/aml/coins', [AmlCheckController::class, 'coins']);
Route::post('/aml/check-address', [AmlCheckController::class, 'storeAddress']);
Route::post('/aml/check-transaction', [AmlCheckController::class, 'storeTransaction']);
Route::get('/aml/checks/{id}', [AmlCheckController::class, 'show'])->whereNumber('id');
Route::post('/aml/checks/{id}/recheck', [AmlCheckController::class, 'recheck'])->whereNumber('id');
Route::match(['get', 'post'], '/webhooks/amlbot/monitoring', AmlBotWebhookController::class);

// Cregis recommends a dedicated endpoint per callback type.
Route::post('/webhooks/cregis/deposit', CregisDepositCallbackController::class)
    ->name('webhooks.cregis.deposit');

