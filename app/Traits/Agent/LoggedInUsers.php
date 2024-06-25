<?php

namespace App\Traits\Agent;

use App\Models\Admin\Currency;
use App\Models\AgentLoginLog;
use App\Models\AgentWallet;
use Exception;
use Jenssegers\Agent\Agent;

trait LoggedInUsers {



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
