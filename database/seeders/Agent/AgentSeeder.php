<?php

namespace Database\Seeders\Agent;

use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agents = array(
            array('firstname' => 'Test','lastname' => 'Agent','username' => 'testagent','store_name' => 'Appdevs','email' => 'agent@appdevs.net','mobile_code' => '880','mobile' => '123456789','full_mobile' => '880123456789','password' => '$2y$10$tGm7lxgM80OMZF6ck0vC/uYLKUvN0KNNr9VIalE1Zvx1eE52VEB5G','refferal_user_id' => NULL,'image' => NULL,'status' => '1','address' => '{"country":"Bangladesh","city":"Dhaka","zip":"1230","state":"Bangladesh","address":"Dhaka,Bangladesh"}','email_verified' => '1','sms_verified' => '1','kyc_verified' => '1','ver_code' => NULL,'ver_code_send_at' => NULL,'two_factor_verified' => '0','two_factor_status' => '0','two_factor_secret' => NULL,'device_id' => NULL,'email_verified_at' => NULL,'remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2024-06-27 03:12:57','updated_at' => '2024-06-27 03:12:57'),
            array('firstname' => 'Test','lastname' => 'Agent 2','username' => 'testagent2','store_name' => 'AppdevsX','email' => 'agent2@appdevs.net','mobile_code' => '880','mobile' => '123456781','full_mobile' => '880123456781','password' => '$2y$10$ZGHt1Q4/S.MNwN1X5rel4.tEwT2lwiuNZ0q6YsrdWgli4EonmY.6S','refferal_user_id' => NULL,'image' => NULL,'status' => '1','address' => '{"country":"Bangladesh","city":"Dhaka","zip":"1230","state":"Bangladesh","address":"Dhaka,Bangladesh"}','email_verified' => '1','sms_verified' => '1','kyc_verified' => '1','ver_code' => NULL,'ver_code_send_at' => NULL,'two_factor_verified' => '0','two_factor_status' => '0','two_factor_secret' => NULL,'device_id' => NULL,'email_verified_at' => NULL,'remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2024-06-27 03:12:57','updated_at' => '2024-06-27 03:12:57')
        );

        Agent::insert($agents);
    }
}
