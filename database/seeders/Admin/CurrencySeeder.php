<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('admin_id' => '1','country' => 'United States','name' => 'United States dollar','code' => 'USD','symbol' => '$','type' => 'FIAT','flag' => 'seeder/sender.webp','rate' => '1.00000000','sender' => '1','receiver' => '0','default' => '1','status' => '1','created_at' => '2023-08-01 09:14:53','updated_at' => '2023-08-01 09:14:53'),
            array('admin_id' => '1','country' => 'Nigeria','name' => 'Nigerian naira','code' => 'NGN','symbol' => '₦','type' => 'FIAT','flag' => 'seeder/receiver.webp','rate' => '760.00000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-08-01 09:15:31','updated_at' => '2023-08-01 09:15:31')
          );

        Currency::insert($data);
        
    }
}
