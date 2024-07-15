<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\MoneyInController;
use App\Http\Controllers\Agent\MoneyOutController;
use App\Http\Controllers\Agent\MySenderController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Agent\ProfitLogController;
use App\Http\Controllers\Agent\RecipientController;
use App\Http\Controllers\Agent\SecurityController;
use App\Http\Controllers\Agent\SendRemittanceController;
use App\Http\Controllers\Agent\StatementController;
use App\Http\Controllers\Agent\SupportTicketController;
use App\Http\Controllers\Agent\TransactionLogController;

Route::prefix("agent")->name("agent.")->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard','index')->name('dashboard');
        Route::post('logout','logout')->name('logout');
        Route::post('get-user-data','getUserData')->name('get.user.data');
        Route::post('get-recipient-data','getRecipientData')->name('get.recipient.data');
    });
    //agent profile 
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function(){
        Route::get('/','index')->name('index');
        Route::put('update','update')->name('update')->middleware('app.mode');
        Route::put('password-update','passwordUpdate')->name('password.update')->middleware('app.mode');
        Route::post('delete-account/{id}','delete')->name('delete')->middleware('app.mode');
    });
    //send remittance
    Route::controller(SendRemittanceController::class)->prefix('send-remittance')->name('send.remittance.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('submit','submit')->name('submit');
        Route::get('preview/{identifier}','preview')->name('preview');
        Route::post('confirm/{identifier}','confirm')->name('confirm');
    });
    //moneyin
    Route::controller(MoneyInController::class)->prefix('money-in')->name('moneyin.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('submit','submit')->name('submit');


        //paypal
        Route::match('get','success/response/{gateway}','success')->name('payment.success');
        Route::get('success/{gateway}','successPagadito')->name('payment.success.pagadito')->withoutMiddleware(['web','auth:agent','verification.guard.agent','agent.google.two.factor']);
        Route::match('post',"cancel/response/{gateway}",'cancel')->name('payment.cancel');
        Route::post("callback/response/{gateway}",'callback')->name('payment.callback')->withoutMiddleware(['web','auth:agent','verification.guard.agent','agent.google.two.factor']);

        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('payment.success')->withoutMiddleware(['web','auth:agent','verification.guard.agent','agent.google.two.factor']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.cancel')->withoutMiddleware(['web','auth:agent','verification.guard.agent','agent.google.two.factor']);

        //redirect with Btn Pay
        Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('payment.btn.pay')->withoutMiddleware(['auth','verification.guard','user.google.two.factor']);

        //manual
        Route::get('manual/{token}','showManualForm')->name('manual.form');
        Route::post('manual/submit/{token}','manualSubmit')->name('manual.submit');

        Route::prefix('payment')->name('payment.')->group(function() {
            Route::get('crypto/address/{trx_id}','cryptoPaymentAddress')->name('crypto.address');
            Route::post('crypto/confirm/{trx_id}','cryptoPaymentConfirm')->name('crypto.confirm');
        });
    });
    //money out
    Route::controller(MoneyOutController::class)->prefix('money-out')->name('money.out.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('submit','submit')->name('submit');
        Route::get('preview/{identifier}','preview')->name('preview');
        Route::post('confirm/{identifier}','confirm')->name('confirm');
    });
    //mysender
    Route::controller(MySenderController::class)->prefix('my-sender')->name('my.sender.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('store','store')->name('store');
        Route::get('edit/{slug}','edit')->name('edit');
        Route::post('update/{slug}','update')->name('update');
        Route::post('delete/{slug}','delete')->name('delete');
    });
    //recipient 
    Route::controller(RecipientController::class)->prefix('recipient')->name('recipient.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('get-bank-list','getBankList')->name('get.bank.list');
        Route::post('get-pickup-point-list','getPickupPointList')->name('get.pickup.point.list');
        Route::post('get-mobile-method-list','getMobileMethodList')->name('get.mobile.method.list');
        Route::post('store','store')->name('store');
        Route::get('edit/{slug}','edit')->name('edit');
        Route::post('update/{slug}','update')->name('update');
        Route::post('delete/{slug}','delete')->name('delete');
    });
    //profit log
    Route::controller(ProfitLogController::class)->prefix('profit-log')->name('profit.logs.')->group(function(){
        Route::get('/','index')->name('index');
    });
    //transaction logs
    Route::controller(TransactionLogController::class)->prefix('transaction-logs')->name('transaction.logs.')->group(function(){
        Route::get('/','index')->name('index');
    });
    //statements
    Route::controller(StatementController::class)->prefix('statement')->name('statements.')->group(function(){
        Route::get('/','index')->name('index');
    });
    //google 2fa
    Route::controller(SecurityController::class)->name('security')->name('security.')->group(function(){
        Route::get('google-2fa','google2FA')->name('google.2fa');
        Route::post('google-2fa-status-update','google2FAStatusUpdate')->name('google.2fa.status.update')->middleware('app.mode');
        Route::get('kyc','kyc')->name('kyc.index');
        Route::post('kyc-submit','kycSubmit')->name('kyc.submit');
    });

    //support ticket
    Route::controller(SupportTicketController::class)->prefix('support-ticket')->name('support.ticket.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('conversation/{encrypt_id}','conversation')->name('conversation');
        Route::post('message/send','messageSend')->name('message.send');
    });
});

Route::get('agent/pusher/beams-auth', function (Request $request) {
    if(Auth::check() == false) {
        return response(['Inconsistent request'], 401);
    }
    $userID = Auth::user()->id;

    $basic_settings = BasicSettingsProvider::get();
    if(!$basic_settings) {
        return response('Basic setting not found!', 404);
    }

    $notification_config = $basic_settings->push_notification_config;

    if(!$notification_config) {
        return response('Notification configuration not found!', 404);
    }

    $instance_id    = $notification_config->instance_id ?? null;
    $primary_key    = $notification_config->primary_key ?? null;
    if($instance_id == null || $primary_key == null) {
        return response('Sorry! You have to configure first to send push notification.', 404);
    }
    $beamsClient = new PushNotifications(
        array(
            "instanceId" => $notification_config->instance_id,
            "secretKey" => $notification_config->primary_key,
        )
    );
    $publisherUserId = "agent-".$userID;
    try{
        $beamsToken = $beamsClient->generateToken($publisherUserId);
    }catch(Exception $e) {
        return response(['Server Error. Failed to generate beams token.'], 500);
    }

    return response()->json($beamsToken);
})->name('agent.pusher.beams.auth');

?>