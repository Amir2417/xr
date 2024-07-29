<?php

use App\Http\Helpers\Response;
use App\Models\Admin\SetupKyc;
use Illuminate\Support\Facades\Route;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Controllers\Api\V1\Agent\Auth\LoginController;
use App\Http\Controllers\Api\V1\Agent\AppSettingsController;
use App\Http\Controllers\Api\V1\Agent\AuthorizationController;

Route::controller(AppSettingsController::class)->prefix('app-settings')->group(function(){
    Route::get('/','appSettings');
});

Route::prefix('agent')->group(function(){
    Route::get('get/basic/data', function() {
        $basic_settings = BasicSettingsProvider::get();
        $user_kyc = SetupKyc::agentKyc()->first();
        return Response::success(['Basic information fetch successfully.'],[
            'email_verification'    => $basic_settings->agent_email_verification,
            'agree_policy'          => $basic_settings->agent_agree_policy,
            'kyc_verification'      => $basic_settings->agent_kyc_verification,
            'register_kyc_fields'   => $user_kyc,
            'countries'             => get_all_countries()
        ],200);
    });
    Route::prefix('register')->middleware(['agent.registration.permission'])->group(function(){
        Route::post('check/exist',[AuthorizationController::class,'checkExist']);
        Route::post('send/otp', [AuthorizationController::class,'sendEmailOtp']);
        Route::post('verify/otp',[AuthorizationController::class,"verifyEmailOtp"]);
        Route::post('resend/otp',[AuthorizationController::class,"resendEmailOtp"]);
    });
    Route::post('login',[LoginController::class,'login']);
    Route::post('register',[LoginController::class,'register'])->middleware(['agent.registration.permission']);
    //forget password for email
    Route::prefix('forget')->group(function(){
        Route::post('password', [ForgotPasswordController::class,'sendCode']);
        Route::post('verify/otp', [ForgotPasswordController::class,'verifyCode']);
        Route::post('reset/password', [ForgotPasswordController::class,'resetPassword']);
    });

    //account re-verifications
    Route::middleware(['agent.api'])->group(function(){
        Route::post('send-code', [AuthorizationController::class,'sendMailCode']);
        Route::post('email-verify', [AuthorizationController::class,'mailVerify']);
        Route::post('google-2fa/otp/verify', [AuthorizationController::class,'verify2FACode']);
    });
    Route::middleware(['agent.api'])->group(function(){
        Route::get('logout', [LoginController::class,'logout']);
    });
});

?>