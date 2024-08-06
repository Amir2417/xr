<?php

use App\Http\Controllers\Api\V1\Agent\AgentController;
use App\Http\Controllers\Api\V1\Agent\AgentProfitLogController;
use App\Http\Helpers\Response;
use App\Models\Admin\SetupKyc;
use Illuminate\Support\Facades\Route;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Controllers\Api\V1\Agent\SecurityController;
use App\Http\Controllers\Api\V1\Agent\Auth\LoginController;
use App\Http\Controllers\Api\V1\Agent\AppSettingsController;
use App\Http\Controllers\Api\V1\Agent\AuthorizationController;
use App\Http\Controllers\Api\V1\Agent\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Agent\MoneyInController;
use App\Http\Controllers\Api\V1\Agent\MoneyOutController;
use App\Http\Controllers\Api\V1\Agent\MySenderController;
use App\Http\Controllers\Api\V1\Agent\RecipientController;
use App\Http\Controllers\Api\V1\Agent\SendRemittanceController;
use App\Http\Controllers\Api\V1\Agent\TransactionLogController;

Route::controller(AppSettingsController::class)->prefix('app-settings')->group(function(){
    Route::get('/','appSettings');
});
Route::controller(AppSettingsController::class)->prefix('v1/settings')->group(function(){
    Route::get("language","languages");
});
Route::controller(MoneyInController::class)->name('api.agent.moneyin.')->group(function(){
    // POST Route For Unauthenticated Request
    Route::post('success/response/{gateway}', 'postSuccess')->name('payment.success')->withoutMiddleware(['agent.api','CheckStatusApiAgent','agent.google.two.factor.api']);
    Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.cancel')->withoutMiddleware(['agent.api','CheckStatusApiAgent','agent.google.two.factor.api']);

    Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('payment.btn.pay')->withoutMiddleware(['agent.api','CheckStatusApiAgent','agent.google.two.factor.api']);

    // Automatic Gateway Response Routes
    Route::get('success/response/{gateway}','success')->withoutMiddleware(['agent.api','CheckStatusApiAgent','agent.google.two.factor.api'])->name("payment.success");
    Route::get("cancel/response/{gateway}",'cancel')->withoutMiddleware(['agent.api','CheckStatusApiAgent','agent.google.two.factor.api'])->name("payment.cancel");

     
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
        Route::get('kyc', [AuthorizationController::class,'showKycFrom']);
        Route::post('kyc/submit', [AuthorizationController::class,'kycSubmit']);
        Route::middleware(['CheckStatusApiAgent','agent.google.two.factor.api'])->group(function () { 
            //google-2fa
            Route::controller(SecurityController::class)->prefix("security")->group(function(){
                Route::get('/google-2fa', 'google2FA');
                Route::post('/google-2fa/status/update', 'google2FAStatusUpdate')->middleware('app.mode.api');

            });

            //agent profile
            Route::controller(AgentController::class)->group(function(){
                Route::get('dashboard', 'dashboard');
                Route::get('profile','profile');
                Route::post('profile/update', 'profileUpdate')->middleware('app.mode.api');
                Route::post('password/update', 'passwordUpdate')->middleware('app.mode.api');
                Route::post('delete/account','deleteAccount')->middleware('app.mode.api');
                Route::get('notifications','notifications');
            });

            //profit logs
            Route::controller(AgentProfitLogController::class)->prefix('profit-logs')->group(function(){
                Route::get('/','index');
            });

            //transactions
            Route::controller(TransactionLogController::class)->prefix('transactions')->group(function(){
                Route::get('/','index');
                Route::post('search','search');
            });

            //my sender
            Route::controller(MySenderController::class)->prefix('my-sender')->group(function(){
                Route::get('/','index');
                Route::post('check-user','checkUser');
                Route::post('store','store');
                Route::post('update','update');
                Route::post('delete','delete');
            });

            //recipient
            Route::controller(RecipientController::class)->prefix('recipient')->group(function(){
                Route::get('/','index');
                Route::post('get-bank-list','getBankList');
                Route::post('get-pickup-point-list','getPickupPointList');
                Route::post('get-mobile-method-list','getMobileMethodList');
                Route::post('check-user','checkUser');
                Route::get('basic-data','basicData');
                Route::post('store','store');
                Route::post('update','update');
                Route::post('delete','delete');
            });

            //money out
            Route::controller(MoneyOutController::class)->prefix('money-out')->group(function(){
                Route::get('/','index');
                Route::post('submit','submit');
                Route::post('confirm','confirm');
            });

            //send remittance 
            Route::controller(SendRemittanceController::class)->prefix('send-remittance')->group(function(){
                Route::get('/','index');
                Route::post('get-recipient','getRecipient');
                Route::post('confirm','confirm');
            });

            //money in
            Route::controller(MoneyInController::class)->prefix('money-in')->group(function(){
                Route::get('/','index');
                Route::post('submit','submit');
                Route::post('confirm','confirm');
                Route::get('manual/input-fields','manualInputFields'); 
                Route::post("manual/submit","manualSubmit");
            });
            
            
        });
        
    });
});

?>