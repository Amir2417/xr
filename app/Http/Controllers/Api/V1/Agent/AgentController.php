<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentProfit;
use App\Models\Agent\AgentWallet;
use App\Models\AgentNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;

class AgentController extends Controller
{
    /**
     * Method for agent profile information.
     */
    public function profile(){
        $user = authGuardApi()['user'];
        $data =[
            'base_url'          => url("/"),
            'default_image'     => files_asset_path_basename("default"),
            "image_path"        =>  files_asset_path_basename('agent-profile'),
            'agent'             =>   $user,
        ];
        return Response::success(['Agent Profile'],[$data],200);
    }
    /**
     * Method for update agent profle information.
     * @param Illuminate\Http\Request $request
     */
    public function profileUpdate(Request $request){
        $user =authGuardApi()['user'];
        $validator = Validator::make($request->all(), [
            'firstname'     => "required|string|max:60",
            'lastname'      => "required|string|max:60",
            'store_name'    => "required|string|max:60",
            'country'       => "required|string|max:50",
            'phone'         => "required|string",
            'state'         => "nullable|string|max:50",
            'city'          => "nullable|string|max:50",
            'zip_code'      => "nullable|string",
            'address'       => "nullable|string|max:250",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);
        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }
        $data = $request->all();
        $mobile = remove_speacial_char($data['phone']);

        $validated['firstname']     = $data['firstname'];
        $validated['lastname']      = $data['lastname'];
        $validated['store_name']      =$data['store_name'];
        $validated['mobile']        = $mobile;
        $complete_phone             = $mobile;

        $validated['full_mobile']   = $complete_phone;

        $validated['address']       = [
            'country'   => $data['country']??"",
            'state'     => $data['state'] ?? "",
            'city'      => $data['city'] ?? "",
            'zip'       => $data['zip_code'] ?? "",
            'address'   => $data['address'] ?? "",
        ];
        if($request->hasFile("image")) {
            $oldImage = $user->image;
            $image = upload_file($data['image'],'agent-profile', $oldImage);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'agent-profile');
            delete_file($image['dev_path']);
            $validated['image']     = $upload_image;
        }
        try{
            $user->update($validated);
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again."],[],400);
        }
       
        return Response::success(['Profile successfully updated!'],[],200);
    }
    /**
     * Method for update agent password.
     */
    public function passwordUpdate(Request $request) {
        $basic_settings = BasicSettingsProvider::get();
        $password_rule = "required|string|min:6|confirmed";
        if($basic_settings->agent_secure_password) {
            $password_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }
        $validator = Validator::make($request->all(), [
            'current_password'      => "required|string",
            'password'              => $password_rule,
        ]);
        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }
        if(!Hash::check($request->current_password,auth()->user()->password)) {
            return Response::error(["Current password didn't match."],[],400);
        }
        try{
            auth()->user()->update([
                'password'  => Hash::make($request->password),
            ]);
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again."],[],400);
        }
        return Response::success(["Password successfully updated!"],[],200);

    }
    /**
     * Method for agent delete account.
     * @param Illuminate\Http\Request $request
     */
    public function deleteAccount(Request $request) {
        $user = authGuardApi()['user'];
        $user->status = false;
        $user->email_verified = false;
        $user->kyc_verified = false;
        $user->deleted_at = now();
        $user->save();

        try{
            $user->token()->revoke();
            return Response::success(['Your profile deleted successfully!'],[],200);
        }catch(Exception $e) {
            return Response::success(['Something went wrong! Please try again.'],[],400);
        }
    }
    /**
     * Method for agent notifications.
     */
    public function notifications(){
        $notifications = AgentNotification::auth()->latest()->get()->map(function($item){
            return[
                'id' => $item->id,
                'message' => $item->message->message??"",
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });
        $data =[
            'notifications'  => $notifications
        ];
        return Response::success(['Agent Notifications data fetched successfully!'],$data,200);
    }
    /**
     * Method for agent dashboard
     */
    public function dashboard(){
        $agent_wallet           = AgentWallet::auth()->first();
        $profit_balance         = AgentProfit::auth()->sum('total_commissions');
        $total_send_remittance  = Transaction::agentAuth()
                                    ->where('type',PaymentGatewayConst::TYPESENDREMITTANCE)
                                    ->count();
        $total_confirm_send_remittance  = Transaction::agentAuth()
                                    ->where('type',PaymentGatewayConst::TYPESENDREMITTANCE)
                                    ->where('status',GlobalConst::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                    ->count();
                                    
        $total_canceled_transactions  = Transaction::agentAuth()->where('status',GlobalConst::REMITTANCE_STATUS_CANCEL)->count();


        $current_month = now()->month;
        $categories = [];
        for ($i = 1; $i <= $current_month; $i++) {
            $categories[] = date("F", mktime(0, 0, 0, $i, 10));
        }

        $send_remittance = [];
        $money_in        = [];
        $money_out       = [];

        foreach ($categories as $index => $month) {
            $month_number = $index + 1;
            $send_remittance[] = Transaction::agentAuth()->where('type', PaymentGatewayConst::TYPESENDREMITTANCE)
                                        ->whereMonth('created_at', $month_number)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('request_amount');

            $money_in[] = Transaction::agentAuth()->where('type', PaymentGatewayConst::MONEYIN)
                                    ->whereMonth('created_at', $month_number)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('request_amount');

            $money_out[] = Transaction::agentAuth()->where('type', PaymentGatewayConst::MONEYOUT)
                                    ->whereMonth('created_at', $month_number)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('request_amount');
        }

        $data = [
            'base_currency'         => get_default_currency_code(),
            'agent_wallet_balance'  => floatval($agent_wallet->balance),
            'profit_balance'        => floatval($profit_balance),
            'total_send_remittance' => $total_send_remittance,
            'total_confirm_send_remittance' => $total_confirm_send_remittance,
            'total_canceled_transactions' => $total_canceled_transactions,
            'dashboard_content' => [
                'categories' => $categories,
                'send_remittance' => $send_remittance,
                'money_in' => $money_in,
                'money_out' => $money_out,
            ]
        ];    
        return Response::success(['Agent Dashboard data fetched successfully!'],$data,200);

    }

}
