<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CashPickupMethodController extends Controller
{
    /**
     * Method for view cash pickup index page
     * @return view
     */
    public function index(){
        $page_title         = "Cash Pickups";
        $cash_pickups       = CashPickup::orderBy('id','desc')->paginate(50);
        $receiver_currency  = Currency::where('status',true)->where('receiver',true)->get();

        return view('admin.sections.cash-pickup.index',compact(
            'page_title',
            'cash_pickups',
            'receiver_currency'
        ));
    }
    /**
     * Method for store cash pickups information
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator      = Validator::make($request->all(),[
            'country'   => 'required',
            'address'   => 'required'
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-cash-pickup");
        $validated          = $validator->validate();
        $validated['slug']  = Str::slug($validated['address']);
        if(CashPickup::where('country',$validated['country'])->where('address',$validated['address'])->exists()){
            throw ValidationException::withMessages([
                'address'   => 'Pickup Point already exists'
            ]);
        }

        try{
            CashPickup::create($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Pickup point added successfully.']]);
    }
}
