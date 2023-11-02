<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\RemittanceBank;
use Illuminate\Database\Seeder;

class RemittanceBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $remittance_banks = array(
            array('name' => 'Access Bank Plc','slug' => 'access-bank-plc','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:47:47','updated_at' => '2023-08-06 06:47:47'),
            array('name' => 'Fidelity Bank Plc','slug' => 'fidelity-bank-plc','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:48:00','updated_at' => '2023-08-06 06:48:00'),
            array('name' => 'First City Monument Bank Limited','slug' => 'first-city-monument-bank-limited','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:48:13','updated_at' => '2023-08-06 06:48:13'),
            array('name' => 'First Bank of Bangladesh Limited','slug' => 'first-bank-of-bangladesh-limited','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:48:27','updated_at' => '2023-08-06 06:48:27'),
            array('name' => 'Guaranty Trust Holding Company Plc','slug' => 'guaranty-trust-holding-company-plc','country' => 'India','status' => '1','created_at' => '2023-08-06 06:48:40','updated_at' => '2023-08-06 06:48:40'),
            array('name' => 'Union Bank of Nigeria Plc','slug' => 'union-bank-of-nigeria-plc','country' => 'India','status' => '1','created_at' => '2023-08-06 06:48:52','updated_at' => '2023-08-06 06:48:52'),
            array('name' => 'United Bank for Africa Plc','slug' => 'united-bank-for-africa-plc','country' => 'India','status' => '1','created_at' => '2023-08-06 06:49:02','updated_at' => '2023-08-06 06:49:02'),
            array('name' => 'Zenith Bank Plc','slug' => 'zenith-bank-plc','country' => 'Nepal','status' => '1','created_at' => '2023-08-06 06:49:16','updated_at' => '2023-08-06 06:49:16'),
            array('name' => 'Citibank Nigeria Limited','slug' => 'citibank-nigeria-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:35','updated_at' => '2023-08-06 06:49:35'),
            array('name' => 'Ecobank Nigeria','slug' => 'ecobank-nigeria','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:48','updated_at' => '2023-08-06 06:49:48'),
            array('name' => 'Heritage Bank Plc','slug' => 'heritage-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:00','updated_at' => '2023-08-06 06:50:00'),
            array('name' => 'Keystone Bank Limited','slug' => 'keystone-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:14','updated_at' => '2023-08-06 06:50:14'),
            array('name' => 'Polaris Bank Limited.','slug' => 'polaris-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:40','updated_at' => '2023-08-06 06:50:40'),
            array('name' => 'Stanbic IBTC Bank Plc','slug' => 'stanbic-ibtc-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:52','updated_at' => '2023-08-06 06:50:52'),
            array('name' => 'Standard Chartered','slug' => 'standard-chartered','country' => 'Haiti','status' => '1','created_at' => '2023-08-06 06:51:04','updated_at' => '2023-08-06 06:51:04'),
            array('name' => 'Sterling Bank Plc','slug' => 'sterling-bank-plc','country' => 'Haiti','status' => '1','created_at' => '2023-08-06 06:51:34','updated_at' => '2023-08-06 06:51:34'),
            array('name' => 'Unity Bank Plc','slug' => 'unity-bank-plc','country' => 'Haiti','status' => '1','created_at' => '2023-08-06 06:51:45','updated_at' => '2023-08-06 06:51:45'),
            array('name' => 'Wema Bank Plc','slug' => 'wema-bank-plc','country' => 'Haiti','status' => '1','created_at' => '2023-08-06 06:51:59','updated_at' => '2023-08-06 06:51:59'),
            array('name' => 'Parallex Bank Limited','slug' => 'parallex-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:10','updated_at' => '2023-08-06 06:52:10'),
            array('name' => 'PremiumTrust Bank Limited','slug' => 'premiumtrust-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:21','updated_at' => '2023-08-06 06:52:21')
        );

        RemittanceBank::insert($remittance_banks);
    }
}
