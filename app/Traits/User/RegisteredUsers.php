<?php

namespace App\Traits\User;

use Exception;
use App\Models\UserCoupon;

trait RegisteredUsers {

    //function for create user coupon
    protected function createCoupon($user){
        $coupon_name        = strtoupper($user->username)."10";
        $data               = [
            'user_id'       => $user->id,
            'coupon_name'   => $coupon_name,
            'price'        => 10,
            'status'        => 0
        ];
        
        try{
            UserCoupon::insert($data);
        }catch(Exception $e){
            $this->guard()->logout();
            $user->delete();
            return $this->breakAuthentication("Failed to create coupon! Please try again.");
        }

    }

    protected function breakAuthentication($error) {
        return back()->with(['error' => [$error]]);
    }
}