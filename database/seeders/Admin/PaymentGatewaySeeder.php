<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Seeder;

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
            array('id' => '1','slug' => 'remittance-gateway','code' => '105','type' => 'AUTOMATIC','name' => 'Paypal','title' => 'Paypal Gateway','alias' => 'paypal','image' => 'seeder/paypal.png','credentials' => '[{"label":"Client ID","placeholder":"Enter Client ID","name":"client-id","value":"AbMgZu03hDEAs8aMK96dj52nCFfEEFd2nSffXsdf8NIBbOiogClRVFbsFqxqPjQHeb221XXCrZR2GXyZ"},{"label":"Secret ID","placeholder":"Enter Secret ID","name":"secret-id","value":"EHjAeQn76vtKvJBUipJ54BFqUrcuP4bB01xgbAGAn7q-p5WgtGzj6FFeEzXuTNEVaPtCcP4qKSwQu0sb"}]','supported_currencies' => '["USD","GBP","PHP","NZD","EUR","CAD","AUD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-05-29 11:09:41','updated_at' => '2023-08-08 05:45:34'),
            
            array('id' => '2','slug' => 'remittance-gateway','code' => '120','type' => 'AUTOMATIC','name' => 'Flutterwave','title' => 'Flutterwave Gateway','alias' => 'flutterwave','image' => 'seeder/flutterwave.png','credentials' => '[{"label":"Encryption key","placeholder":"Enter Encryption key","name":"encryption-key","value":"FLWSECK_TEST27bee2235efd"},{"label":"Secret key","placeholder":"Enter Secret key","name":"secret-key","value":"FLWSECK_TEST-da35e3dbd28be1e7dc5d5f3519e2ebef-X"},{"label":"Public key","placeholder":"Enter Public key","name":"public-key","value":"FLWPUBK_TEST-e0bc02a00395b938a4a2bed65e1bc94f-X"}]','supported_currencies' => '["USD","GBP","PHP","NZD","MYR","EUR","CNY","CAD","AUD","NGN"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-08-08 05:02:12','updated_at' => '2023-08-08 05:45:10'),
            
            array('id' => '3' ,'slug' => 'remittance-gateway','code' => '125','type' => 'AUTOMATIC','name' => 'Stripe','title' => 'Stripe Gateway','alias' => 'stripe','image' => 'seeder/stripe.webp','credentials' => '[{"label":"Test Publishable Key","placeholder":"Enter Test Publishable Key","name":"test-publishable-key","value":"pk_test_51NECrlJXLo7QTdMco2E4YxHSeoBnDvKmmi0CZl3hxjGgH1JwgcLVUF3ZR0yFraoRgT7hf0LtOReFADhShAZqTNuB003PnBSlGP"},{"label":"Test Secret Key","placeholder":"Enter Test Secret Key","name":"test-secret-key","value":"sk_test_51NECrlJXLo7QTdMc2x7K5LaDuiS0MGNYHkO9dzzV0Y9XuWNZsXjECFsusjZEnqtxMIjCh3qtogc5sHHwL2oQ083900aFy1k7DE"},{"label":"Live Publishable Key","placeholder":"Enter Live Publishable Key","name":"live-publishable-key","value":null},{"label":"Live Secret Key","placeholder":"Enter Live Secret Key","name":"live-secret-key","value":null}]','supported_currencies' => '["USD","GBP","PHP","NZD","MYR","EUR","CNY","CAD","AUD","NGN"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-11-30 08:42:28','updated_at' => '2023-11-30 08:43:57'),
            
            array('id' => '4','slug' => 'remittance-gateway','code' => '210','type' => 'AUTOMATIC','name' => 'SSLCommerz','title' => 'SSLCommerz Payment Gateway For Add Money','alias' => 'sslcommerz','image' => 'seeder/sslcommerz.webp','credentials' => '[{"label":"Store Id","placeholder":"Enter Store Id","name":"store-id","value":"appde6513b3970d62c"},{"label":"Store Password","placeholder":"Enter Store Password","name":"store-password","value":"appde6513b3970d62c@ssl"},{"label":"Sandbox Url","placeholder":"Enter Sandbox Url","name":"sandbox-url","value":"https:\\/\\/sandbox.sslcommerz.com"},{"label":"Live Url","placeholder":"Enter Live Url","name":"live-url","value":"https:\\/\\/securepay.sslcommerz.com"}]','supported_currencies' => '["BDT","EUR","GBP","AUD","USD","CAD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-09-27 16:11:26','updated_at' => '2023-09-27 16:11:53','env' => 'SANDBOX'),
            
            array('id' => '5','slug' => 'remittance-gateway','code' => '130','type' => 'MANUAL','name' => 'ADPay','title' => 'ADPay Gateway','alias' => 'adpay','image' => NULL,'credentials' => NULL,'supported_currencies' => '["GBP"]','crypto' => '0','desc' => '<p><span style="background-color:rgb(248,248,248);color:rgb(29,28,29);">To initiate a payment using our manual payment gateway, please follow the instructions provided below. We offer two convenient methods for you to choose from:</span></p><p><strong>Bank Transfer</strong></p><ol><li>Visit your local bank or access your online banking platform.</li><li>Initiate a new fund transfer or payment.</li><li>Enter the recipient’s bank account details:&nbsp;</li><li>&nbsp;Bank Name: HSBC</li><li>IBAN (International Bank Account Number) : 01234567890</li><li>Specify the payment amount in the currency you intend to use.</li><li>Double-check all details, including the recipient’s account information.</li><li>Confirm and authorize the transfer.</li><li>Retain the payment receipt or confirmation for future reference.</li></ol><p>Please ensure that you keep a record of your payment as proof of the transaction. In case of any discrepancies or verification requirements, you may be asked to provide this documentation. Your payment will be manually verified by our team, and once confirmed, your order will be processed promptly. We appreciate your cooperation and look forward to serving you!</p>','input_fields' => '[{"type":"file","label":"Screenshot","name":"screenshot","required":true,"validation":{"max":"10","mimes":["jpg"," jpeg"," png"],"min":0,"options":[],"required":true}},{"type":"text","label":"TRX ID","name":"trx_id","required":false,"validation":{"max":"30","mimes":[],"min":"06","options":[],"required":false}}]','env' => NULL,'status' => '1','last_edit_by' => '1','created_at' => NULL,'updated_at' => NULL),


            array('id' => '5000' ,'slug' => 'remittance-gateway','code' => '10010','type' => 'AUTOMATIC','name' => 'CoinGate','title' => 'Crypto Payment gateway','alias' => 'coingate','image' => 'seeder/coin_gate.png','credentials' => '[{"label":"Sandbox URL","placeholder":"Enter Sandbox URL","name":"sandbox-url","value":"https:\\/\\/api-sandbox.coingate.com\\/v2"},{"label":"Sandbox App Token","placeholder":"Enter Sandbox App Token","name":"sandbox-app-token","value":"XJW4RyhT8F-xssX2PvaHMWJjYe5nsbsrbb2Uqy4m"},{"label":"Production URL","placeholder":"Enter Production URL","name":"production-url","value":"https:\\/\\/api.coingate.com\\/v2"},{"label":"Production App Token","placeholder":"Enter Production App Token","name":"production-app-token","value":null}]','supported_currencies' => '["USD","BTC","LTC","ETH","BCH","TRX","ETC","DOGE","BTG","BNB","TUSD","USDT","BSV","MATIC","BUSD","SOL","WBTC","RVN","BCD","ATOM","BTTC","EURT"]','crypto' => '1','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-08-07 10:36:30','updated_at' => '2023-08-07 12:06:12'),
        
            array('id' => '5001','slug' => 'remittance-gateway','code' => '10015','type' => 'AUTOMATIC','name' => 'Tatum','title' => 'Tatum Gateway','alias' => 'tatum','image' => 'seeder/tatum.jpg','credentials' => '[{"label":"Testnet","placeholder":"Enter Testnet","name":"test-net","value":"t-64c8e10396979a001d135363-64c8e10496979a001d135367"},{"label":"Mainnet","placeholder":"Enter Mainnet","name":"main-net","value":"t-64c8e10396979a001d135363-64c8e10496979a001d135369"}]','supported_currencies' => '["BTC","ETH","SOL"]','crypto' => '1','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-11-07 17:05:37','updated_at' => '2023-11-07 17:44:00'),

            array('id' => '10001','slug' => 'remittance-gateway','code' => '20025','type' => 'AUTOMATIC','name' => 'Razorpay','title' => 'Razorpay Gateway','alias' => 'razorpay','image' => 'seeder/razor-pay.webp','credentials' => '[{"label":"Key ID","placeholder":"Enter Key ID","name":"key-id","value":"rzp_test_voV4gKUbSxoQez"},{"label":"Secret Key","placeholder":"Enter Secret Key","name":"secret-key","value":"cJltc1jy6evA4Vvh9lTO7SWr"}]','supported_currencies' => '["USD","EUR","GBP","SGD","AED","AUD","CAD","CNY","SEK","NZD","MXN","BDT","EGP","HKD","INR","LBP","LKR","MAD","MYR","NGN","NPR","PHP","PKR","QAR","SAR","UZS","GHS"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-11-09 17:26:21','updated_at' => '2023-11-09 17:36:05'),

            array('id' => '10002','slug' => 'remittance-gateway','code' => '20035','type' => 'AUTOMATIC','name' => 'Pagadito','title' => 'Pagadito Payment gateway','alias' => 'pagadito','image' => 'seeder/pagadito.webp','credentials' => '[{"label":"UID","placeholder":"Enter UID","name":"uid","value":"b73eb3fa1dc8bea4b4363322c906a8fd"},{"label":"WSK","placeholder":"Enter WSK","name":"wsk","value":"dc843ff5865bac2858ad8f23af081256"},{"label":"base_url","placeholder":"Enter base_url","name":"base_url","value":"https:\\/\\/sandbox.pagadito.com"}]','supported_currencies' => '["USD","HNL","CRC","DOP","GTQ","NIO","PAB"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2024-01-08 04:05:40','updated_at' => '2024-01-08 04:05:40'),

        );
        PaymentGateway::upsert($payment_gateways,['code'],[]);

        $payment_gateway_currencies = array(
            
            array('payment_gateway_id' => '1','name' => 'Paypal AUD','alias' => 'remittance-gateway-paypal-aud-automatic','currency_code' => 'AUD','currency_symbol' => 'A$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.53000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '1','name' => 'Paypal CAD','alias' => 'remittance-gateway-paypal-cad-automatic','currency_code' => 'CAD','currency_symbol' => 'C$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.34000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            
            array('payment_gateway_id' => '1','name' => 'Paypal EUR','alias' => 'remittance-gateway-paypal-eur-automatic','currency_code' => 'EUR','currency_symbol' => '€','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.91000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            
            array('payment_gateway_id' => '1','name' => 'Paypal NZD','alias' => 'remittance-gateway-paypal-nzd-automatic','currency_code' => 'NZD','currency_symbol' => 'NZ$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.65000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '1','name' => 'Paypal PHP','alias' => 'remittance-gateway-paypal-php-automatic','currency_code' => 'PHP','currency_symbol' => '₱','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '56.25000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '1','name' => 'Paypal GBP','alias' => 'remittance-gateway-paypal-gbp-automatic','currency_code' => 'GBP','currency_symbol' => '£','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.78000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '1','name' => 'Paypal USD','alias' => 'remittance-gateway-paypal-usd-automatic','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            
            
            array('payment_gateway_id' => '2','name' => 'Flutterwave NGN','alias' => 'remittance-gateway-flutter-wave-ngn-automatic','currency_code' => 'NGN','currency_symbol' => '₦','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '766.00000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave AUD','alias' => 'remittance-gateway-flutter-wave-aud-automatic','currency_code' => 'AUD','currency_symbol' => 'A$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.53000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave CAD','alias' => 'remittance-gateway-flutter-wave-cad-automatic','currency_code' => 'CAD','currency_symbol' => 'C$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.34000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave CNY','alias' => 'remittance-gateway-flutter-wave-cny-automatic','currency_code' => 'CNY','currency_symbol' => '¥','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '1.00000000','rate' => '7.21000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave EUR','alias' => 'remittance-gateway-flutter-wave-eur-automatic','currency_code' => 'EUR','currency_symbol' => '€','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.91000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave MYR','alias' => 'remittance-gateway-flutter-wave-myr-automatic','currency_code' => 'MYR','currency_symbol' => 'RM','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '4.58000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave NZD','alias' => 'remittance-gateway-flutter-wave-nzd-automatic','currency_code' => 'NZD','currency_symbol' => 'NZ$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.65000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave PHP','alias' => 'remittance-gateway-flutter-wave-php-automatic','currency_code' => 'PHP','currency_symbol' => '₱','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '56.25000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave GBP','alias' => 'remittance-gateway-flutter-wave-gbp-automatic','currency_code' => 'GBP','currency_symbol' => '£','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.78000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave USD','alias' => 'remittance-gateway-flutter-wave-usd-automatic','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),


            array('payment_gateway_id' => '3','name' => 'Stripe NGN','alias' => 'remittance-gateway-stripe-ngn-automatic','currency_code' => 'NGN','currency_symbol' => '₦','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '482.88000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe AUD','alias' => 'remittance-gateway-stripe-aud-automatic','currency_code' => 'AUD','currency_symbol' => 'A$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.53000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe CAD','alias' => 'remittance-gateway-stripe-cad-automatic','currency_code' => 'CAD','currency_symbol' => 'C$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.34000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe CNY','alias' => 'remittance-gateway-stripe-cny-automatic','currency_code' => 'CNY','currency_symbol' => '¥','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '1.00000000','rate' => '7.21000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe EUR','alias' => 'remittance-gateway-stripe-eur-automatic','currency_code' => 'EUR','currency_symbol' => '€','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.91000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe MYR','alias' => 'remittance-gateway-stripe-myr-automatic','currency_code' => 'MYR','currency_symbol' => 'RM','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '4.58000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe NZD','alias' => 'remittance-gateway-stripe-nzd-automatic','currency_code' => 'NZD','currency_symbol' => 'NZ$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.65000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe PHP','alias' => 'remittance-gateway-stripe-php-automatic','currency_code' => 'PHP','currency_symbol' => '₱','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '56.25000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe GBP','alias' => 'remittance-gateway-stripe-gbp-automatic','currency_code' => 'GBP','currency_symbol' => '£','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.78000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
            array('payment_gateway_id' => '3','name' => 'Stripe USD','alias' => 'remittance-gateway-stripe-usd-automatic','currency_code' => 'USD','currency_symbol' =>'$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-08-08 06:43:21','updated_at' => '2023-08-08 06:57:02'),
        
            
            array('payment_gateway_id' => '4','name' => 'SSLCommerz BDT','alias' => 'sslcommerz-bdt-automatic','currency_code' => 'BDT','currency_symbol' => '৳','image' => NULL,'min_limit' => '100.00000000','max_limit' => '50000.00000000','percent_charge' => '0.00000000','fixed_charge' => '1.00000000','rate' => '110.64000000','created_at' => '2023-09-27 16:11:53','updated_at' => '2023-09-27 16:12:04'),

            array('payment_gateway_id' => '5','name' => 'ADPay USD','alias' => 'remittance-gateway-adpay-usd-manual','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '0.00000000','max_limit' => '0.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-08-26 04:46:32','updated_at' => '2023-08-26 04:46:32'),

            array('payment_gateway_id' => '5000','name' => 'CoinGate USDT','alias' => 'remittance-gateway-coingate-usdt-automatic','currency_code' => 'USDT','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '10000.00000000','percent_charge' => '3.00000000','fixed_charge' => '0.00000000','rate' => '1.00','created_at' => '2023-08-07 12:06:12','updated_at' => '2023-08-07 12:36:10'),
            
            array('payment_gateway_id' => '5001','name' => 'Tatum ETH','alias' => 'remittance-gateway-tatum-eth-automatic','currency_code' => 'ETH','currency_symbol' => 'ETH','image' => NULL,'min_limit' => '0.001','max_limit' => '1000000.00000000','percent_charge' => '2.00000000','fixed_charge' => '1.00000000','rate' => '0.00052000','created_at' => '2023-11-07 17:10:38','updated_at' => '2023-11-07 17:44:00'),

            array('payment_gateway_id' => '10001','name' => 'Razorpay USD','alias' => 'remittance-gateway-razorpay-usd-automatic','currency_code' => 'USD','currency_symbol' => "$",'image' => NULL,'min_limit' => '1.00000000','max_limit' => '1000.00000000','percent_charge' => '2.00000000','fixed_charge' => '1.00000000','rate' => '1','created_at' => '2023-11-09 17:36:05','updated_at' => '2023-11-09 17:36:05'),

            array('payment_gateway_id' => '10002','name' => 'Pagadito USD','alias' => 'remittance-gateway-pagadito-usd-automatic','currency_code' => 'USD','currency_symbol' => '$','image' => 'seeder/pagadito.webp','min_limit' => '1.00000000','max_limit' => '1000.00000000','percent_charge' => '2.00000000','fixed_charge' => '1.00000000','rate' => '1','created_at' => '2024-01-08 04:11:48','updated_at' => '2024-01-08 04:12:21'),
        );
        PaymentGatewayCurrency::upsert($payment_gateway_currencies,['alias'],[]);
    }
}
