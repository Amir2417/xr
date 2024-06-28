<?php

namespace App\Traits\Agent;

use Exception;
use Jenssegers\Agent\Agent;
use App\Models\AgentLoginLog;
use App\Models\Admin\Currency;
use App\Models\Agent\AgentWallet;

trait LoggedInUsers {

    protected function refreshUserWallets($user) {
        if(isset($user->wallet)){
            $user_wallet = $user->wallet;
            $currencies = Currency::default();
            
            $new_wallets = [
                'agent_id'      => $user->id,
                'currency_id'   => $user_wallet->currency_id,
                'balance'       => $user_wallet->balance,
                'status'        => $user_wallet->status,
                'created_at'    => now(),
            ];
    
            try{
                $user_wallet->update($new_wallets);
            }catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }else{
            $currencies = Currency::default();       
            $wallets= [
                'agent_id'      => $user->id,
                'currency_id'   => $currencies->id,
                'balance'       => 0,
                'status'        => true,
                'created_at'    => now(),
            ];
        
            try{
                AgentWallet::insert($wallets);
            }catch(Exception $e) {
                // handle error
                $this->guard()->logout();
                $user->delete();
                return $this->breakAuthentication("Failed to create wallet! Please try again");
            }
        }
        
    }

    protected function createLoginLog($user) {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);
        $agent = new Agent();

        // $mac = exec('getmac');
        // $mac = explode(" ",$mac);
        // $mac = array_shift($mac);
        $mac = "";

        $data = [
            'agent_id'       => $user->id,
            'ip'            => $client_ip,
            'mac'           => $mac,
            'city'          => $location['city'] ?? "",
            'country'       => $location['country'] ?? "",
            'longitude'     => $location['lon'] ?? "",
            'latitude'      => $location['lat'] ?? "",
            'timezone'      => $location['timezone'] ?? "",
            'browser'       => $agent->browser() ?? "",
            'os'            => $agent->platform() ?? "",
        ];

        try{
            AgentLoginLog::create($data);
        }catch(Exception $e) {
            // return false;
        }
    }
}
