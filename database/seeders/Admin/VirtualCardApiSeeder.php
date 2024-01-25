<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Admin\VirtualCardApi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VirtualCardApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $virtual_card_apis = array(
            array('admin_id' => '1','image' => 'seeder/virtual-card.png','card_details' => 'This card is property of QRPay, Wonderland. Misuse is criminal offence. If found, please return to QRPay or to the nearest bank.','config' => '{"flutterwave_secret_key":"FLWSECK_TEST-SANDBOXDEMOKEY-X","flutterwave_secret_hash":"AYxcfvgbhnj@34","flutterwave_url":"https:\/\/api.flutterwave.com\/v3","name":"flutterwave"}','created_at' => now(),'updated_at' => now())
        );
        VirtualCardApi::insert($virtual_card_apis);
    }
}
