<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('user.dashboard');
    });

    Route::prefix('auth')->group(function () {
        Route::get('sign-in', [AuthController::class, 'signInShow'])->name('user.sign_in.show')->middleware('guest');
        Route::get('sign-up', [AuthController::class, 'signUpShow'])->name('user.sign_up.show')->middleware('guest');
        Route::post('sign-in', [AuthController::class, 'signIn'])->name('user.sign_in')->middleware('guest');
        Route::post('sign-up', [AuthController::class, 'signUp'])->name('user.sign_up')->middleware('guest');
        Route::post('sign-out', [AuthController::class, 'signOut'])->name('user.sign-out')->middleware('auth');
        Route::get('verify', [AuthController::class, 'verifyShow'])->name('user.verify.show')->middleware('auth');
        Route::post('verify', [AuthController::class, 'verify'])->name('user.verify')->middleware('auth');;
    });

    Route::post('send_otp', [AuthController::class, 'sendOtp'])->name('user.send.otp')->middleware('auth');
    Route::post('send_register_otp', [AuthController::class, 'sendRegisterOtp'])->name('user.send.register.otp')->middleware('auth');

});

Route::get('test', [AuthController::class, 'test']);
Route::get('test2', function () {
    return view('test2');
});

Route::get('prices', function () {
    $price = \App\Services\Global\CurrencyPrice\Facade\CurrencyPrice::getSpotPrice();
    return response()->json($price);
});
