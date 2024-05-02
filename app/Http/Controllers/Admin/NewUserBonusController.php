<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Admin\NewUserBonus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewUserBonusController extends Controller
{
    /**
     * Method for view new user bonus page
     * @return view
     */
    public function index(){
        $page_title     = "New User Bonus";
        $bonus          = NewUserBonus::first();

        return view('admin.sections.new-user-bonus.index',compact(
            'page_title',
            'bonus'
        ));

    }
    /**
     * Method for update new user bonus information
     */
    public function update(Request $request){
        $validator      = Validator::make($request->all(),[
            'price'     => 'required',
            'max_used'  => 'required'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated  = $validator->validate();
        $bonus      = NewUserBonus::where('slug',$request->slug)->first();
        if(!$bonus){
            $validated['slug']          = $request->slug;
            $validated['last_edit_by']  = auth()->user()->id;
            try{
                NewUserBonus::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return back()->with(['success' => ['New user bonus information updated successfully.']]);
        }else{
            $validated['last_edit_by']  = auth()->user()->id;
            try{
                $bonus->update($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return back()->with(['success' => ['New user bonus information updated successfully.']]);
        }
    }
}
