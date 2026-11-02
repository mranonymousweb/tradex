<?php

use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('auth')->group(function () {
    Route::post('sign-in', [AuthController::class, 'signIn']);
    Route::post('sign-up', [AuthController::class, 'signUp']);
    Route::post('sign-out', [AuthController::class, 'signOut']);
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('send_register_otp', [AuthController::class, 'sendRegisterOtp']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);

    Route::post('sign-out', [AuthController::class, 'signOut'])->middleware(['auth:sanctum', 'ability:user']);
    Route::get('update-locale', [AuthController::class, 'updateLocale']);
    Route::post('authentication', [AuthController::class, 'authentication'])->middleware(['auth:sanctum', 'ability:user']);
    Route::get('config',[AuthController::class,'authenticationOptions'])->middleware(['auth:sanctum', 'ability:user']);
    Route::post('kyc/step2/start', [AuthController::class, 'startStep2Kyc'])->middleware(['auth:sanctum', 'ability:user']);
    Route::get('kyc/step2/status', [AuthController::class, 'step2KycStatus'])->middleware(['auth:sanctum', 'ability:user']);
    Route::post('otp-by-section',[AuthController::class,'otpBySection']);
});
