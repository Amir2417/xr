<?php

namespace App\Traits\Agent;

use Exception;
use Jenssegers\Agent\Agent;
use App\Models\AgentLoginLog;
use App\Models\Admin\Currency;
use App\Models\Agent\AgentWallet;

trait LoggedInUsers {

    protected function refreshUserWallets($user) {
        
        if($user->wallet == null || $user->wallet->count() == 0){
            
            $currency = Currency::default();       
            $wallet= [
                'agent_id'      => $user->id,
                'currency_id'   => $currency->id,
                'balance'       => 0,
                'status'        => true,
            ];
        
            try{
                AgentWallet::create($wallet);
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
