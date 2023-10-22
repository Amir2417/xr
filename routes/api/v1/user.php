<?php


use App\Http\Controllers\Api\V1\User\AuthorizationController;
use App\Http\Controllers\Api\V1\User\BeneficiaryController;
use App\Http\Controllers\Api\V1\User\NotificationController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\SendRemittanceController;
use App\Http\Controllers\Api\V1\User\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->name("api.user.")->group(function(){

    Route::controller(SendRemittanceController::class)->prefix('send-remittance')->name('send-remittance.')->group(function(){
        Route::get('success/response/{gateway}','success')->name('payment.success');
        Route::get("cancel/response/{gateway}",'cancel')->name('payment.cancel');
        Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
        Route::get('stripe/payment/success/{trx}','stripePaymentSuccess')->name('stripe.payment.success');

        //razor pay
        Route::get('razor/callback', 'razorCallback')->name('razor.callback');
    });


    Route::middleware('auth:api')->group(function(){
    
        Route::post('google-2fa/otp/verify', [AuthorizationController::class,'verify2FACode']);

        Route::controller(ProfileController::class)->prefix('profile')->group(function(){
            Route::get('info','profileInfo');
            Route::post('info/update','profileInfoUpdate');
            Route::post('password/update','profilePasswordUpdate');
            Route::post('delete-account','deleteProfile');
            Route::get('/google-2fa', 'google2FA');
            Route::post('/google-2fa/status/update', 'google2FAStatusUpdate');

            Route::controller(AuthorizationController::class)->prefix('kyc')->group(function(){
                Route::get('input-fields','getKycInputFields');
                Route::post('submit','KycSubmit');
            });
        });



        // Logout Route
        Route::post('logout',[ProfileController::class,'logout']);

        //send remittance 

        Route::controller(SendRemittanceController::class)->prefix('send-remittance')->group(function(){
            Route::post('store','store');
            Route::get('beneficiary','beneficiary');
            Route::get('beneficiary-add','beneficiaryAdd');
            Route::post('beneficiary-store','beneficiaryStore');
            Route::post('beneficiary-send','beneficiarySend');
            Route::get('receipt-payment','receiptPayment');
            Route::post('receipt-payment-store','receiptPaymentStore');
            Route::post('submit-data','submitData');
            Route::post('stripe/payment/confirm','paymentConfirmedApi')->name('stripe.payment.confirmed');
            Route::post('manual/payment/confirmed','manualPaymentConfirmedApi')->name('manual.payment.confirmed');
        });

        //beneficiary
        Route::controller(BeneficiaryController::class)->prefix('beneficiary')->group(function(){
            Route::get('index','index');
            Route::get('create','create');
            Route::post('store','store');
            Route::post('update','update');
            Route::post('delete','delete');
        });

        //transaction 

        Route::controller(TransactionController::class)->prefix('transaction')->group(function(){
            Route::get('index','index');
        });

       //notification
       Route::controller(NotificationController::class)->group(function(){
        Route::get('notification','notification');
       });

    });
    
});

