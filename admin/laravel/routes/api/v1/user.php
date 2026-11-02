<?php

use App\Http\Controllers\User\CurrencyController;
use App\Http\Controllers\User\DeviceController;
use App\Http\Controllers\User\FavoriteMarketCurrencyController;
use App\Http\Controllers\User\FutureController;
use App\Http\Controllers\User\GoogleAuthController;
use App\Http\Controllers\User\MarketController;
use App\Http\Controllers\User\OtpCodeController;
use App\Http\Controllers\User\StakingController;
use App\Http\Controllers\User\TicketsController;
use App\Http\Controllers\User\UserCardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\WalletsController;
use App\Http\Controllers\User\IrtController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\SwapController;

Route::prefix('dashboard')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('profile', [DashboardController::class, 'profile']);
    Route::post('profile', [DashboardController::class, 'updateProfile']);
    Route::post('credential', [DashboardController::class, 'updateCredential']);
    Route::post('credential/otp', [DashboardController::class, 'CredentialOtp']);
//    Route::get('marketing/logs', [DashboardController::class, 'marketingLogs']);
    Route::get('packages', [DashboardController::class, 'packages']);
    Route::get('slides', [DashboardController::class, 'slides'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
    Route::get('charts', [DashboardController::class, 'charts']);
    Route::post('marketing', [DashboardController::class, 'generateMarketingCode']);
    Route::get('marketing/codes', [DashboardController::class, 'marketingCodes']);
    Route::get('marketing/users', [DashboardController::class, 'marketingUsers']);
    Route::get('marketing/logs', [DashboardController::class, 'marketingLogs']);
});
Route::prefix('ticket')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('departments', [TicketsController::class, 'departments'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
    Route::get('/', [TicketsController::class, 'index']);
    Route::post('store', [TicketsController::class, 'store']);
    Route::post('message', [TicketsController::class, 'messageStore']);
    Route::get('messages', [TicketsController::class, 'Messages']);
});

Route::prefix('wallet')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::post('receive', [WalletsController::class, 'wallet']);
    Route::post('deposit', [WalletsController::class, 'deposit']);
    Route::post('withdraw', [WalletsController::class, 'withdraw']);
    Route::get('currencies', [WalletsController::class, 'currencies'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
    Route::post('transfer', [WalletsController::class, 'transfer']);
    Route::get('transactions', [WalletsController::class, 'transactions']);
    Route::get('assets', [WalletsController::class, 'assets']);
    Route::get('asset/spot', [WalletsController::class, 'assetSpot']);
    Route::post('swap/balance', [WalletsController::class, 'swapBalance']);
    Route::get('asset/history', [WalletsController::class, 'history']);
    Route::get('inquiry', [WalletsController::class, 'inquiryUser']);
    Route::get('converted-inventory', [WalletsController::class, 'convertedInventoryCurrencies']);
    Route::post('converted-inventory', [WalletsController::class, 'convertedInventory']);
    Route::get('converted-inventory-logs', [WalletsController::class, 'convertedInventoryLogs']);
});

Route::prefix('notifications')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('index', [NotificationController::class, 'index']);
    Route::post('create', [NotificationController::class, 'create']);
    Route::post('update', [NotificationController::class, 'update']);
});

Route::prefix('irt')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::post('request', [IrtController::class, 'request'])->middleware(['verify.identity']);
    Route::get('gateways', [IrtController::class, 'gateways']);
    Route::get('cards', [IrtController::class, 'cards']);
    Route::post('card', [IrtController::class, 'cardToCard'])->middleware(['verify.identity']);;
});

Route::get('irt/verify', [IrtController::class, 'varify'])->name('transactions.verify');
Route::get('irt/verify/gateWay', [IrtController::class, 'varifyGateway'])->name('transactions.verify.gateway');


Route::prefix('swap')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('currencies', [SwapController::class, 'currencies'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
    Route::post('/', [SwapController::class, 'swap']);
    Route::get('/index', [SwapController::class, 'swaps']);
});

Route::prefix('currency')->group(function () {
    Route::get('list', [CurrencyController::class, 'index'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
});

Route::prefix('card')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('list', [UserCardController::class, 'index']);
    Route::get('currency', [UserCardController::class, 'currency']);
    Route::get('bank', [UserCardController::class, 'bank']);
    Route::post('store', [UserCardController::class, 'store'])->middleware('verify.identity');
    Route::post('change/status', [UserCardController::class, 'changeStatus']);
});

Route::prefix('device')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('', [DeviceController::class, 'index']);
    Route::delete('destroy', [DeviceController::class, 'destroy']);
});

Route::prefix('google/auth')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('create', [GoogleAuthController::class, 'create']);
    Route::post('verify', [GoogleAuthController::class, 'verify']);
    Route::post('enabled', [GoogleAuthController::class, 'enabled']);
});
Route::prefix('otp/code')->middleware(['auth:sanctum', 'ability:user'])->group(function () {
    Route::post('verify', [OtpCodeController::class, 'verify']);
});

Route::prefix('staking')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::get('currency', [StakingController::class, 'currency']);
    Route::post('create', [StakingController::class, 'create']);
    Route::get('list', [StakingController::class, 'list']);
    Route::get('cancel', [StakingController::class, 'cancel']);
    Route::get('details', [StakingController::class, 'details']);
});

Route::prefix('market')->middleware(['auth:sanctum', 'ability:user','user.status.inActive'])->group(function () {
    Route::prefix('currency')->group(function () {
        Route::get('', [MarketController::class, 'currency'])->withoutMiddleware(['auth:sanctum', 'ability:user']);;
        Route::get('base', [MarketController::class, 'index'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
        Route::get('detail', [MarketController::class, 'details']);
        Route::get('list', [MarketController::class, 'currencyList']);
        Route::post('favorite', [FavoriteMarketCurrencyController::class, 'store']);
        Route::get('favorite/list', [FavoriteMarketCurrencyController::class, 'index']);
        Route::post('favorite/destroy', [FavoriteMarketCurrencyController::class, 'destroy']);
    });
    Route::post('create', [MarketController::class, 'create']);
    Route::post('update', [MarketController::class, 'update']);
    Route::post('cancel', [MarketController::class, 'cancel']);
    Route::get('history', [MarketController::class, 'history']);
    Route::get('user/order', [MarketController::class, 'orders']);
});

Route::prefix('future')->middleware(['auth:sanctum', 'ability:user'])->group(function () {
    Route::prefix('currency')->group(function () {
        Route::get('/price', [FutureController::class, 'currencyPrice'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
        Route::get('/base', [FutureController::class, 'baseCurrency'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
        Route::get('/list', [FutureController::class, 'currencyList'])->withoutMiddleware(['auth:sanctum', 'ability:user']);
    });
    Route::get('option', [FutureController::class, 'option']);
    Route::post('update/margin/mode', [FutureController::class, 'updateMarginMode']);
    Route::post('update/leverage', [FutureController::class, 'updateLeverage']);
    Route::post('order/create', [FutureController::class, 'createOrder']);
    Route::post('order/close', [FutureController::class, 'closeOrder']);
    Route::get('order', [FutureController::class, 'order']);
    Route::get('order/history', [FutureController::class, 'history']);
    Route::post('order/update', [FutureController::class, 'updateOrder']);
});

