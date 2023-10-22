<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\RecipientController;
use App\Http\Controllers\User\RemittanceController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\AuthorizationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\User\SendRemittanceController;

Route::prefix("user")->name("user.")->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard','index')->name('dashboard');
        Route::post('logout','logout')->name('logout');
        Route::post('get/bank/name','getBankName')->name('get.bank.name');
        Route::post('get-mobile-method','getMobileMethod')->name('get.mobile.method');
        
    });

    //send-remittance
    Route::controller(SendRemittanceController::class)->prefix('send-remittance')->name('send.remittance.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::get('receipt-payment/{identifier}','receiptPayment')->name('receipt.payment');
        Route::post('receipant-payment-store/{identifier}','receipantPaymentStore')->name('receipant.payment.store');
        Route::get('receipt-preview/{identifier}','receiptPreview')->name('receipt.preview');
        
    });

    //transaction
    Route::controller(TransactionController::class)->prefix('transaction')->name('transaction.')->group(function(){
        Route::get('/','transaction')->name('index');
        Route::get('search', 'search')->name('search');
    });
    //beneficary 
    Route::controller(RecipientController::class)->prefix('recipient')->name('recipient.')->group(function(){
        Route::get('index/{identifier}','index')->name('index');
        Route::get('add/{identifier}','add')->name('add');
        Route::post('store/{identifier}','store')->name('store');
        Route::post('send/{identifier}','send')->name('send');

        //side nav
        Route::get('show','show')->name('show');
        Route::get('create','create')->name('create');
        Route::post('recipient-data-store','recipientDataStore')->name('data.store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::post('update/{id}','update')->name('data.update');
        Route::post('delete/{id}','delete')->name('delete');
    });

    //payment
    Route::controller(RemittanceController::class)->group(function(){
        Route::controller(RemittanceController::class)->name('send.remittance.')->group(function(){
            //paypal
            Route::get('success/response/{gateway}','success')->name('payment.success');
            Route::get("cancel/response/{gateway}",'cancel')->name('payment.cancel');

            //stripe
            Route::get('payment/{gateway}','payment')->name('payment');
            Route::get('stripe/payment/success/{trx}','stripePaymentSuccess')->name('stripe.payment.success');

            //flutterwave
            Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');

            //manual
            Route::get('manual/payment','manualPayment')->name('manual.payment');
            Route::post('manual/payment/confirmed','manualPaymentConfirmed')->name('manual.payment.confirmed');

            //razor pay
            Route::get('razor/callback', 'razorCallback')->name('razor.callback');
        });

        //paypal
        Route::post('money-submit','submit')->name('money.submit');

        //stripe
        Route::post('stripe/payment/confirm','paymentConfirmed')->name('stripe.payment.confirmed'); 

        //payment confirmation
        Route::get('payment-confirmation/{trx_id}','paymentConfirmation')->name('payment.confirmation');  
    });

    Route::controller(ProfileController::class)->prefix("profile")->name("profile.")->group(function(){
        Route::get('/','index')->name('index');
        Route::put('password/update','passwordUpdate')->name('password.update');
        Route::put('update','update')->name('update');
        Route::post('delete-account/{id}','delete')->name('delete');
    });

    //security
    Route::controller(SecurityController::class)->prefix('security')->name('security.')->group(function(){
        Route::get('google/2fa','google2FA')->name('google.2fa');
        Route::post('google/2fa/status/update','google2FAStatusUpdate')->name('google.2fa.status.update');
    });

    Route::controller(SupportTicketController::class)->prefix("prefix")->name("support.ticket.")->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('conversation/{encrypt_id}','conversation')->name('conversation');
        Route::post('message/send','messageSend')->name('message.send');
    });

    Route::controller(AuthorizationController::class)->prefix("authorize")->name('authorize.')->group(function(){
        Route::get('kyc','showKycFrom')->name('kyc');
        Route::post('kyc/submit','kycSubmit')->name('kyc.submit');
    });
});


