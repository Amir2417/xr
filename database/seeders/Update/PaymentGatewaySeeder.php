<?php

namespace Database\Seeders\Update;

use Illuminate\Database\Seeder;
use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_gateways = array(

            array('id' => '5','slug' => 'remittance-gateway','code' => '200','type' => 'AUTOMATIC','name' => 'RazorPay','title' => 'Razor Pay Payment Gateway','alias' => 'razorpay','image' => '7f46e145-774e-41bf-9170-243605a5d4d0.webp','credentials' => '[{"label":"Public key","placeholder":"Enter Public key","name":"public-key","value":"rzp_test_B6FCT9ZBZylfoY"},{"label":"Secret Key","placeholder":"Enter Secret Key","name":"secret-key","value":"s4UYHtNwq5TkHSexU5Pnp1pm"}]','supported_currencies' => '["INR"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-08-23 13:21:55','updated_at' => '2023-08-23 13:23:20','env' => 'SANDBOX'),
            array('id' => '6','slug' => 'remittance-gateway','code' => '210','type' => 'AUTOMATIC','name' => 'SSLCommerz','title' => 'SSLCommerz Payment Gateway For Add Money','alias' => 'sslcommerz','image' => 'f4fe90e9-9b25-48b8-b3f5-9847cfbc6da7.webp','credentials' => '[{"label":"Store Id","placeholder":"Enter Store Id","name":"store-id","value":"appde6513b3970d62c"},{"label":"Store Password","placeholder":"Enter Store Password","name":"store-password","value":"appde6513b3970d62c@ssl"},{"label":"Sandbox Url","placeholder":"Enter Sandbox Url","name":"sandbox-url","value":"https:\\/\\/sandbox.sslcommerz.com"},{"label":"Live Url","placeholder":"Enter Live Url","name":"live-url","value":"https:\\/\\/securepay.sslcommerz.com"}]','supported_currencies' => '["BDT","EUR","GBP","AUD","USD","CAD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-09-27 16:11:26','updated_at' => '2023-09-27 16:11:53','env' => 'SANDBOX'),
        );
        PaymentGateway::insert($payment_gateways);

        $payment_gateway_currencies = array(
            array('payment_gateway_id' => '5','name' => 'RazorPay INR','alias' => 'razorpay-inr-automatic','currency_code' => 'INR','currency_symbol' => '₹','image' => NULL,'min_limit' => '100.00000000','max_limit' => '100000.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '82.87000000','created_at' => '2023-08-23 13:23:21','updated_at' => '2023-08-23 13:26:35'),
            array('payment_gateway_id' => '6','name' => 'SSLCommerz BDT','alias' => 'sslcommerz-bdt-automatic','currency_code' => 'BDT','currency_symbol' => '৳','image' => NULL,'min_limit' => '100.00000000','max_limit' => '50000.00000000','percent_charge' => '0.00000000','fixed_charge' => '1.00000000','rate' => '110.64000000','created_at' => '2023-09-27 16:11:53','updated_at' => '2023-09-27 16:12:04'),
        );
        PaymentGatewayCurrency::insert($payment_gateway_currencies);
    }
}
