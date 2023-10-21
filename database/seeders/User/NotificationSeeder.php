<?php

namespace Database\Seeders\User;

use App\Models\UserNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_notifications = array(
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
            array('user_id' => '1','message' => '"Your Remittance  (Payable amount: 160.59,\\r\\n                    Get Amount: 76,483.00) Successfully Sended."','created_at' => '2023-10-21 04:38:53','updated_at' => '2023-10-21 04:38:53'),
        );
        UserNotification::insert($user_notifications);
    }
}
