<?php
namespace App\Traits\PaymentGateway;

use Exception;
use App\Models\TemporaryData;
use App\Http\Helpers\PaymentGateway;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Constants\PaymentGatewayConst;
use Illuminate\Http\Client\RequestException;

trait SslCommerz {

    private $sslCommerz_gateway_credentials;
    private $request_credentials;
    private $sslCommerz_sandbox_api_base_url = "https://sandbox.sslcommerz.com/";
    private $sslCommerz_production_api_base_url = "https://securepay.sslcommerz.com";
    private $sslCommerz_api_v4       = "v4";

    public function sslCommerzInit($output = null) 
    {
        throw new Exception("This gateway is under development!");

        if(!$output) $output = $this->output;
        $request_credentials = $this->getSslCommerzRequestCredentials($output);
        $payment_link = $this->createSslCommerzPaymentLink($output, $request_credentials);

        return $payment_link;
    }

    public function createSslCommerzPaymentLink($output, $request_credentials) 
    {
        $v4 = $this->sslCommerz_api_v4;
        $endpoint = "gwprocess/$v4/api.php";
        $endpoint = $request_credentials->base_url . $endpoint;

        $temp_record_token = generate_unique_string('temporary_datas','identifier',60);
        // $this->setUrlParams("token=" . $temp_record_token); // set Parameter to URL for identifying when return success/cancel

        $redirection = $this->getRedirection();
        $url_parameter = $this->getUrlParams();

        $user = auth()->guard(get_auth_guard())->user();

        // dd($this->setGatewayRoute($redirection['return_url'],PaymentGatewayConst::SSLCOMMERZ,$url_parameter));

        $temp_data = $this->sslCommerzJunkInsert($temp_record_token); // create temporary information

        $response = Http::asForm()->post($endpoint, [
            'store_id'      => $this->request_credentials->store_id,
            'store_passwd'  => $this->request_credentials->secret_key,
            'total_amount'  => $output['amount']->total_amount,
            'currency'      => $output['currency']->currency_code,
            'tran_id'       => $temp_record_token,
            'success_url'   => $this->setGatewayRoute($redirection['return_url'],PaymentGatewayConst::SSLCOMMERZ,$url_parameter),
            'fail_url'      => $this->setGatewayRoute($redirection['cancel_url'],PaymentGatewayConst::SSLCOMMERZ,$url_parameter),
            'cancel_url'    => $this->setGatewayRoute($redirection['cancel_url'],PaymentGatewayConst::SSLCOMMERZ,$url_parameter),
            'ipn_url'       => $this->setGatewayRoute($redirection['callback_url'],PaymentGatewayConst::SSLCOMMERZ,$url_parameter),
            'cus_name'      => $user->firstname ?? "",
            'cus_email'     => $user->email ?? "",
            'cus_add1'      => $user->address->address ?? "",
            'cus_city'      => $user->address->city ?? "",
            'cus_country'   => $user->address->country ?? "",
            'cus_phone'     => " ",
            'shipping_method'   => "No",
            'product_name'      => "Add Money",
            'product_category'  => " ",
            'product_profile'   => " ",
        ])->throw(function(Response $response, RequestException $exception) use ($temp_data) {
            $temp_data->delete();
            throw new Exception($exception->getMessage());
        })->json();

        $response_array = json_decode(json_encode($response), true);

        if(isset($response_array['status']) && $response_array['status'] == "SUCCESS") {

            $temp_data_contains = json_decode(json_encode($temp_data->data),true);
            $temp_data_contains['response'] = $response_array;

            $temp_data->update([
                'data'  => $temp_data_contains,
            ]);

            return redirect()->away($response_array['GatewayPageURL']);
        }

        throw new Exception($response_array['failedreason']);
    }

    public function sslCommerzJunkInsert($temp_token) {
        $output = $this->output;

        $data = [
            'gateway'       => $output['gateway']->id,
            'currency'      => $output['currency']->id,
            'amount'        => json_decode(json_encode($output['amount']),true),
            'wallet_table'  => $output['wallet']->getTable(),
            'wallet_id'     => $output['wallet']->id,
            'creator_table' => auth()->guard(get_auth_guard())->user()->getTable(),
            'creator_id'    => auth()->guard(get_auth_guard())->user()->id,
            'creator_guard' => get_auth_guard(),
        ];

        return TemporaryData::create([
            'type'          => PaymentGatewayConst::TYPEADDMONEY,
            'identifier'    => $temp_token,
            'data'          => $data,
        ]);
    }

    public function getSslCommerzCredentials($output)
    {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");

        $store_id_sample = ['store id','store','public key','public', 'test public key', 'id'];
        $secret_key_sample = ['secret','secret key','store password', 'password','secret id','store password (api/secret key)'];

        $store_id           = PaymentGateway::getValueFromGatewayCredentials($gateway,$store_id_sample);
        $secret_key         = PaymentGateway::getValueFromGatewayCredentials($gateway,$secret_key_sample);
        
        $mode = $gateway->env;
        $gateway_register_mode = [
            PaymentGatewayConst::ENV_SANDBOX => PaymentGatewayConst::ENV_SANDBOX,
            PaymentGatewayConst::ENV_PRODUCTION => PaymentGatewayConst::ENV_PRODUCTION,
        ];

        if(array_key_exists($mode,$gateway_register_mode)) {
            $mode = $gateway_register_mode[$mode];
        }else {
            $mode = PaymentGatewayConst::ENV_SANDBOX;
        }

        $credentials = (object) [
            'store_id'                  => $store_id,
            'secret_key'                => $secret_key,
            'mode'                      => $mode
        ];

        $this->sslCommerz_gateway_credentials = $credentials;

        return $credentials;
    }

    public function getSslCommerzRequestCredentials($output = null) 
    {
        if(!$this->sslCommerz_gateway_credentials) $this->getSslCommerzCredentials($output);
        $credentials = $this->sslCommerz_gateway_credentials;
        if(!$output) $output = $this->output;

        $request_credentials = [];
        $request_credentials['store_id']        = $credentials->store_id;
        $request_credentials['secret_key']      = $credentials->secret_key;

        if($output['gateway']->env == PaymentGatewayConst::ENV_PRODUCTION) {
            $request_credentials['base_url']      = $this->sslCommerz_production_api_base_url;
        }else {
            $request_credentials['base_url']      = $this->sslCommerz_sandbox_api_base_url;
        }

        $this->request_credentials = (object) $request_credentials;
        return (object) $request_credentials;
    }

}