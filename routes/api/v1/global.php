<?php

use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\User\DashboardController;
use App\Http\Controllers\Api\V1\User\SendRemittanceController;
use App\Http\Controllers\Api\V1\User\TransactionController;
use Illuminate\Support\Facades\Route;

// Settings
Route::controller(SettingController::class)->prefix("settings")->group(function(){
    Route::get("basic-settings","basicSettings");
    Route::get("language","languages");
    
});
Route::controller(DashboardController::class)->prefix("user")->group(function(){
    Route::get("dashboard","dashboard");
});

Route::controller(SendRemittanceController::class)->prefix('send-remittance')->group(function(){
    Route::post('send-money','sendMoney');
    Route::get('index','index');
});
Route::controller(TransactionController::class)->group(function(){
    Route::get('all/transaction','allTransction');
});