<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\MobileMethod;
use Illuminate\Database\Seeder;

class MobileMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mobile_methods = array(
            array('name' => 'Kuda Bank','slug' => 'kuda-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:59:18','updated_at' => '2023-08-06 07:02:47'),
            array('name' => 'Opay App','slug' => 'opay-app','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:59:30','updated_at' => '2023-08-06 06:59:30'),
            array('name' => 'GT World','slug' => 'gt-world','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 06:59:45','updated_at' => '2023-08-06 06:59:45'),
            array('name' => 'Access Bank','slug' => 'access-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 07:00:01','updated_at' => '2023-08-06 07:02:39'),
            array('name' => 'First Bank Mobile App','slug' => 'first-bank-mobile-app','country' => 'Bangladesh','status' => '1','created_at' => '2023-08-06 07:00:16','updated_at' => '2023-08-06 07:00:16'),
            array('name' => 'VFHD','slug' => 'vfhd','country' => 'India','status' => '1','created_at' => '2023-08-06 07:00:28','updated_at' => '2023-08-06 07:00:28'),
            array('name' => 'Alat by Wema Bank','slug' => 'alat-by-wema-bank','country' => 'India','status' => '1','created_at' => '2023-08-06 07:00:39','updated_at' => '2023-08-06 07:00:39'),
            array('name' => 'FCMB','slug' => 'fcmb','country' => 'India','status' => '1','created_at' => '2023-08-06 07:00:51','updated_at' => '2023-08-06 07:02:29'),
            array('name' => 'Zenith Bank','slug' => 'zenith-bank','country' => 'India','status' => '1','created_at' => '2023-08-06 07:01:05','updated_at' => '2023-08-06 07:02:57'),
            array('name' => 'UBA','slug' => 'uba','country' => 'India','status' => '1','created_at' => '2023-08-06 07:01:17','updated_at' => '2023-08-06 07:03:06'),
            array('name' => 'Ecobank','slug' => 'ecobank','country' => 'Nepal','status' => '1','created_at' => '2023-08-06 07:01:59','updated_at' => '2023-08-06 07:01:59'),
            array('name' => 'Fidelity Bank','slug' => 'fidelity-bank','country' => 'Nepal','status' => '1','created_at' => '2023-08-06 07:02:18','updated_at' => '2023-08-06 07:02:18'),
            array('name' => 'First Bank','slug' => 'first-bank','country' => 'Nepal','status' => '1','created_at' => '2023-08-06 07:03:24','updated_at' => '2023-08-06 07:03:24'),
            array('name' => 'GTBank','slug' => 'gtbank','country' => 'Nepal','status' => '1','created_at' => '2023-08-06 07:03:45','updated_at' => '2023-08-06 07:03:45'),
            array('name' => 'Heritage Bank','slug' => 'heritage-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:07','updated_at' => '2023-08-06 07:04:07'),
            array('name' => 'Jaiz Bank','slug' => 'jaiz-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:36','updated_at' => '2023-08-06 07:04:36'),
            array('name' => 'Keystone Bank','slug' => 'keystone-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:49','updated_at' => '2023-08-06 07:04:49'),
            array('name' => 'Stanbic IBTC Bank','slug' => 'stanbic-ibtc-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:04','updated_at' => '2023-08-06 07:05:04'),
            array('name' => 'Sterling Bank','slug' => 'sterling-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:16','updated_at' => '2023-08-06 07:05:16'),
            array('name' => 'Union Bank','slug' => 'union-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:36','updated_at' => '2023-08-06 07:05:36')
        );
        MobileMethod::insert($mobile_methods);
    }
}
