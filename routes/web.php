<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Frontend\SiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SiteController::class,'index'])->name('index');

Route::post('frontend/request/send-money',function(Request $request) {

    Session::put('frontend_money_req',$request->all());
    
    $validator = Validator::make($request->all(),[
        'type'           => 'required',
        'send_money'     => 'required',
        'receive_money'  => 'required',
    ]);
    if($validator->fails()){
        return back()->with(['error' => ['Please enter send money.']]);
    }
    
    $validated = $validator->validate();

    $validated['identifier']    = Str::uuid();
    
    $data = [
        'type'                  => $validated['type'],
        'identifier'            => $validated['identifier'],
        'data'                  => [
            'send_money'        => $validated['send_money'],
            'fees'              => $request->fees,
            'convert_amount'    => $request->convert_amount,
            'payable_amount'    => $request->payable,
            'receive_money'     => $request->receive_money,
            'sender_currency'   => $request->sender_currency,
            'receiver_currency' => $request->receiver_currency,
            'sender_ex_rate'    => $request->sender_ex_rate,
            'sender_base_rate'  => $request->sender_base_rate,
            'receiver_ex_rate'  => $request->receiver_ex_rate,
        ],
        
    ];
    
    $record = TemporaryData::create($data);

    return redirect()->route('user.recipient.index',$record->identifier);

})->name('frontend.request.send.money');


//landing page

Route::controller(SiteController::class)->name('frontend.')->group(function(){
    Route::get('about','about')->name('about');
    Route::get('how-it-works','howItWorks')->name('howitworks');
    Route::get('web-journal','webJournal')->name('web.journal');
    Route::get('journal-details/{slug}','journalDetails')->name('journal.details');
    Route::get('contact','contact')->name('contact');
});

Route::post("subscribe",[SiteController::class,'subscribe'])->name("subscribe");
Route::post("contact-request",[SiteController::class,'contactRequest'])->name("contact.request");

Route::get('link/{slug}',[SiteController::class,'link'])->name('link');