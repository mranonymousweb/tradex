<?php

use App\Http\Controllers\Front\ChartController;
use App\Http\Controllers\Front\CurrencyController;
use App\Http\Controllers\Front\FutureChartController;
use App\Http\Controllers\Front\IpController;
use App\Http\Controllers\Front\SiteController;
use  Illuminate\Support\Facades\Route;


Route::prefix('user/meta/data')->controller(SiteController::class)->group(function () {
    Route::get('', 'metaData');
});

Route::prefix('chart/spot')->controller(ChartController::class)->group(function () {
    Route::get('time', 'time');
    Route::get('config', 'config');
    Route::get('symbols', 'symbols');
    Route::get('history', 'history');
});

Route::prefix('chart/future')->controller(FutureChartController::class)->group(function () {
    Route::get('time', 'time');
    Route::get('config', 'config');
    Route::get('symbols', 'symbols');
    Route::get('history', 'history');
});

Route::prefix('my/ip')->controller(IpController::class)->group(function () {
    Route::get('', 'getIp');
});

Route::prefix('currency')->controller(CurrencyController::class)->group(function () {
    Route::get('/chart', 'chart');
});
