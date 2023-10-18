<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Setup Currency";
        $currencies = Currency::orderByDesc('default')->paginate(10);
        $sender_currency = Currency::where('sender',true)->first();
        $receive_currency = Currency::where('receiver',true)->first();
        return view('admin.sections.currency.index',compact(
            'page_title',
            'currencies',
            'sender_currency',
            'receive_currency'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $target = $request->target ?? $request->currency_code;
        $currency = Currency::where('code',$target)->first();
        if(!$currency) {
            return back()->with(['warning' => ['Currency not found!']]);
        }
        $request->merge(['old_flag' => $currency->flag]);

        $validator = Validator::make($request->all(),[
            'currency_type'      => 'required|string',
            'currency_country'   => 'required|string',
            'currency_name'      => 'required|string',
            'currency_code'      => ['required','string',Rule::unique('currencies','code')->ignore($currency->id)],
            'currency_symbol'    => 'required|string',
            'currency_rate'      => 'required|numeric',
            'currency_target'    => 'nullable|string',
            'currency_role'      => 'nullable|string',
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal','currency_edit');
        }
        $validated = $validator->validate();

        
        

        if($request->hasFile('currency_flag')) {
            try{
                $image = get_files_from_fileholder($request,'currency_flag');
                $uploadFlag = upload_files_from_path_dynamic($image,'currency-flag',$currency->flag);
                $validated['currency_flag'] = $uploadFlag;
            }catch(Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        $validated = replace_array_key($validated,"currency_");
       
        try{
            $currency->update($validated);
        }catch(Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Successfully updated the information.']]);
    }
}
