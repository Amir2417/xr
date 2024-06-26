<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Models\Admin\SetupKyc;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    use ControlDynamicInputFields;
    /**
     * Method for show google 2FA page
     * @return view
     */
    public function google2FA(){
        $page_title     = "Google 2FA";
        $qr_code        = generate_google_2fa_auth_qr();

        return view('agent.sections.security.google-2fa',compact(
            'page_title',
            'qr_code'
        ));
    }
    /**
     * Method for update google 2fa status update
     */
    public function google2FAStatusUpdate(Request $request){
        $validated = Validator::make($request->all(),[
            'target'        => "required|numeric",
        ])->validate();

        $agent = auth()->user();
        try{
            $agent->update([
                'two_factor_status'         => $agent->two_factor_status ? 0 : 1,
                'two_factor_verified'       => true,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Security Setting Updated Successfully!']]);
    }
    /**
     * Method for show kyc page
     * @return view
     */
    public function kyc(){
        $page_title         = "KYC Verification";
        $basic_settings     = BasicSettings::first();
        if($basic_settings['kyc_verification'] == false) return back()->with(['success' => ['Do not need to identity verification!!']]);
        $agent_kyc          = SetupKyc::agentKyc()->first();
        if(!$agent_kyc) return back()->with(['success' => ['Do not need to identity verification!!']]);
            $kyc_data       = $agent_kyc->fields;
            $kyc_fields     = [];
        if($kyc_data) {
            $kyc_fields     = array_reverse($kyc_data);
        }

        return view('agent.sections.security.verify-kyc',compact(
            'page_title',
            'agent_kyc',
            'kyc_fields'
        ));
    }
    /**
     * Method for update kyc information
     * @param Illuminate\Http\Request $request
     */
    public function kycSubmit(Request $request){
        $agent = auth()->user();
        if($agent->kyc_verified == GlobalConst::VERIFIED) return back()->with(['success' => ['You are already KYC Verified User']]);
        
        $agent_kyc_fields = SetupKyc::agentKyc()->first()->fields ?? [];
        $validation_rules = $this->generateValidationRules($agent_kyc_fields);

        $validated = Validator::make($request->all(),$validation_rules)->validate();
        $get_values = $this->placeValueWithFields($agent_kyc_fields,$validated);
        
        $create = [
            'agent_id'      => auth()->user()->id,
            'data'          => json_encode($get_values),
            'created_at'    => now(),
        ];

        DB::beginTransaction();
        try{
            DB::table('agent_kyc_data')->updateOrInsert(["agent_id" => $agent->id],$create);
            $agent->update([
                'kyc_verified'  => GlobalConst::PENDING,
            ]);
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            $agent->update([
                'kyc_verified'  => GlobalConst::DEFAULT,
            ]);
            $this->generatedFieldsFilesDelete($get_values);
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['KYC information successfully submitted.']]);
    }
}
