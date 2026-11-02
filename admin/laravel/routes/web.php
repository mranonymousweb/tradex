<?php

use App\Http\Controllers\Front\SiteController;
use App\Http\Controllers\User\DashboardController;
use App\Models\KycProviderLog;
use Illuminate\Support\Facades\Route;

Route::get('', [DashboardController::class, 'index']);
Route::get('/future', function () {
    return view('user.pages.market.futures.index');
});
Route::controller(SiteController::class)->group(function () {
    Route::get('/switch/{locale}', 'switchLang')->name('site.switch.lang');
});


