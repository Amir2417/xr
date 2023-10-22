<?php
namespace App\Constants;
use Illuminate\Support\Str;

class PaymentGatewayConst {

    const AUTOMATIC             = "AUTOMATIC";
    const MANUAL                = "MANUAL";
    const ADDMONEY              = "Add Money";
    const REMITTANCE_MONEY      = "Remittance Gateway";
    const MONEYOUT              = "Money Out";
    const ACTIVE                =  true;

    const TYPESENDREMITTANCE    = "SEND-REMITTANCE";
    
    const APP           = "APP";
    const SEND          = "SEND";
    const RECEIVED      = "RECEIVED";
    const MANUA_GATEWAY = 'manual';
    


    const PAYPAL        = 'paypal';
    const STRIPE        = 'stripe';
    const FLUTTER_WAVE  = 'flutterwave';
    const RAZORPAY      = 'razorpay';
    const SSLCOMMERZ    = 'sslcommerz';

    //transaction type

    const TRANSACTION_TYPE_BANK     = "Bank Transfer";
    const TRANSACTION_TYPE_MOBILE   = "Mobile Money";

    const ENV_SANDBOX       = "SANDBOX";
    const ENV_PRODUCTION    = "PRODUCTION";

    public static function add_money_slug() {
        return Str::slug(self::ADDMONEY);
    }
    public static function remittance_money_slug() {
        return Str::slug(self::REMITTANCE_MONEY);
    }


    public static function money_out_slug() {
        return Str::slug(self::MONEYOUT);
    }

    public static function register($alias = null) {
        $gateway_alias          = [
            self::PAYPAL        => "paypalInit",
            self::STRIPE        => "stripeInit",
            self::MANUA_GATEWAY => "manualInit",
            self::FLUTTER_WAVE  => 'flutterwaveInit',
            self::RAZORPAY      => 'razorInit',
            self::SSLCOMMERZ    => 'sslcommerzInit'

        ];

        if($alias == null) {
            return $gateway_alias;
        }

        if(array_key_exists($alias,$gateway_alias)) {
            return $gateway_alias[$alias];
        }
        return "init";
    }

    public static function apiAuthenticateGuard() {
        return [
            'api'   => 'web',
        ];
    }
}
