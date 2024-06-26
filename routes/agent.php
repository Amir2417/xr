<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Agent\RecipientController;
use App\Http\Controllers\Agent\SecurityController;
use App\Http\Controllers\Agent\SendRemittanceController;

Route::prefix("agent")->name("agent.")->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard','index')->name('dashboard');
        Route::post('logout','logout')->name('logout');
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
    });
    //recipient 
    Route::controller(RecipientController::class)->prefix('recipient')->name('recipient.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('add','add')->name('add');
        Route::post('get-bank-list','getBankList')->name('get.bank.list');
        Route::post('get-pickup-point-list','getPickupPointList')->name('get.pickup.point.list');
        Route::post('get-mobile-method-list','getMobileMethodList')->name('get.mobile.method.list');
        Route::post('store','store')->name('store');
    });
    //google 2fa
    Route::controller(SecurityController::class)->name('security')->name('security.')->group(function(){
        Route::get('google-2fa','google2FA')->name('google.2fa');
        Route::post('google-2fa-status-update','google2FAStatusUpdate')->name('google.2fa.status.update')->middleware('app.mode');
        Route::get('kyc','kyc')->name('kyc.index');
        Route::post('kyc-submit','kycSubmit')->name('kyc.submit');
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