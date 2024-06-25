<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\User\Auth\ForgotPasswordController as UserForgotPasswordController;
use App\Http\Controllers\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\User\AuthorizationController;
use App\Http\Controllers\Admin\AuthorizationController as AdminAuthorizationController;

use App\Http\Controllers\Agent\Auth\ForgotPasswordController as AgentAuthForgotPasswordController;
use App\Http\Controllers\Agent\Auth\LoginController as AgentAuthLoginController;
use App\Http\Controllers\Agent\Auth\RegisterController as AuthRegisterController;
use App\Http\Controllers\Agent\AuthorizationController as AgentAuthorizationController;

// Admin Authentication Route
Route::middleware(['guest','admin.login.guard'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',function(){
        return redirect()->route('admin.login');
    });
    Route::get('login',[LoginController::class,"showLoginForm"])->name('login');
    Route::post('login/submit',[LoginController::class,"login"])->name('login.submit');

    Route::get('password/forgot',[ForgotPasswordController::class,"showLinkRequestForm"])->name('password.forgot');
    Route::post('password/forgot',[ForgotPasswordController::class,"sendResetLinkEmail"])->name('password.forgot.request');

    Route::get('password/reset/{token}',[ResetPasswordController::class,"showResetForm"])->name('password.reset');
    Route::post('password/update',[ResetPasswordController::class,'reset'])->name('password.update');

    Route::controller(AdminAuthorizationController::class)->prefix("authorize")->middleware(['auth:admin'])->withoutMiddleware(['admin.login.guard','guest'])->name('authorize.')->group(function(){
        Route::get('google/2fa','showGoogle2FAForm')->name('google.2fa');
        Route::post('google/2fa/submit','google2FASubmit')->name('google.2fa.submit');
    });
});

Route::name('user.')->group(function(){
    Route::get('login',[UserLoginController::class,"showLoginForm"])->name('login');
    Route::post('login',[UserLoginController::class,"login"])->name('login.submit');

    Route::get('register',[UserRegisterController::class,"showRegistrationForm"])->name('register')->middleware('register.verification.guard');
    Route::post('register',[UserRegisterController::class,"register"])->name('register.submit');

    Route::controller(UserForgotPasswordController::class)->prefix("password")->name("password.")->group(function(){
        Route::get('forgot','showForgotForm')->name('forgot');
        Route::post('forgot/send/code','sendCode')->name('forgot.send.code');
        Route::get('forgot/code/verify/form/{token}','showVerifyForm')->name('forgot.code.verify.form');
        Route::post('forgot/verify/{token}','verifyCode')->name('forgot.verify.code');
        Route::get('forgot/resend/code/{token}','resendCode')->name('forgot.resend.code');
        Route::get('forgot/reset/form/{token}','showResetForm')->name('forgot.reset.form');
        Route::post('forgot/reset/{token}','resetPassword')->name('reset');
    });

    Route::controller(AuthorizationController::class)->prefix("authorize")->name('authorize.')->middleware("auth")->group(function(){
        Route::get('mail/{token}','showMailFrom')->name('mail');
        Route::post('mail/verify/{token}','mailVerify')->name('mail.verify');
        Route::get('mail/resend/{token}','mailResend')->name('mail.resend');
        Route::get('google/2fa','showGoogle2FAForm')->name('google.2fa');
        Route::post('google/2fa/submit','google2FASubmit')->name('google.2fa.submit');

        Route::get('kyc','showKycFrom')->name('kyc');
        Route::post('kyc/submit','kycSubmit')->name('kyc.submit');
    });
});

Route::prefix('agent')->name('agent.')->group(function(){
    Route::get('/',function(){
        return redirect()->route('agent.login');
    });
    Route::get('login',[AgentAuthLoginController::class,"showLoginForm"])->name('login');
    Route::post('login',[AgentAuthLoginController::class,"login"])->name('login.submit');

    //register
    Route::get('register',[AuthRegisterController::class,"showRegistrationForm"])->name('register')->middleware(['agent.registration.permission']);
    Route::post('register',[AuthRegisterController::class,"register"])->name('register.submit')->middleware(['agent.registration.permission']);
    Route::post('send/verify-code',[AuthRegisterController::class,"sendVerifyCode"])->name('send.code')->middleware(['agent.registration.permission']);
    Route::get('email/verify/{token}',[AgentAuthorizationController::class,"showEmailFromRegister"])->name('email.verify')->middleware(['agent.registration.permission']);
    Route::post('verify/code/{token}',[AuthRegisterController::class,"verifyCode"])->name('verify.code')->middleware(['agent.registration.permission']);
    Route::get('resend/code',[AuthRegisterController::class,"resendCode"])->name('resend.code')->middleware(['agent.registration.permission']);
    Route::get('register/kyc',[AuthRegisterController::class,"registerKyc"])->name('register.kyc')->middleware(['agent.registration.permission']);

     // recovery password by email
     Route::controller(AgentAuthForgotPasswordController::class)->prefix("password")->name("password.")->group(function(){
        Route::get('forgot','showForgotForm')->name('forgot');
        Route::post('forgot/send/code','sendCode')->name('forgot.send.code');
        Route::get('forgot/code/verify/form/{token}','showVerifyForm')->name('forgot.code.verify.form');
        Route::post('forgot/verify/{token}','verifyCode')->name('forgot.verify.code');
        Route::get('forgot/resend/code/{token}','resendCode')->name('forgot.resend.code');
        Route::get('forgot/reset/form/{token}','showResetForm')->name('forgot.reset.form');
        Route::post('forgot/reset/{token}','resetPassword')->name('reset');

    });

    Route::controller(AgentAuthorizationController::class)->prefix("authorize")->name('authorize.')->middleware("auth:agent")->group(function(){
        Route::get('mail/{token}','showMailFrom')->name('mail');
        Route::post('mail/verify/{token}','mailVerify')->name('mail.verify');
        Route::get('resend/code','resendCode')->name('resend.code');
        Route::get('kyc','showKycFrom')->name('kyc');
        Route::post('kyc/submit','kycSubmit')->name('kyc.submit');
        Route::get('google/2fa','showGoogle2FAForm')->name('google.2fa');
        Route::post('google/2fa/submit','google2FASubmit')->name('google.2fa.submit');

    });
});
