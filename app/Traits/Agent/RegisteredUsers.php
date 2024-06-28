<?php

namespace App\Traits\Agent;

use Exception;
use App\Models\Admin\Currency;
use App\Models\Agent\AgentWallet;

trait RegisteredUsers {
    protected function createUserWallets($user) {
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


    protected function breakAuthentication($error) {
        return back()->with(['error' => [$error]]);
    }
}
