<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppOnboardScreens;
use Illuminate\Database\Seeder;

class AppOnboardScreensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_onboard_screens = array(
            array('type' => 'User','title' => 'Welcome to XRemit','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard1.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:27:23','updated_at' => '2023-09-03 10:27:23'),
            array('type' => 'User','title' => 'Safe & Secure Process','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard2.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:29:10','updated_at' => '2023-09-03 10:29:10'),
            array('type' => 'User','title' => '24/7 Customer Support','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard3.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:30:10','updated_at' => '2023-09-03 10:30:10'),
            array('type' => 'Agent','title' => 'Welcome to XRemit','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'agent/onboard1.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:27:23','updated_at' => '2023-09-03 10:27:23'),
            array('type' => 'Agent','title' => 'Safe & Secure Process','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'agent/onboard2.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:29:10','updated_at' => '2023-09-03 10:29:10'),
            array('type' => 'Agent','title' => '24/7 Customer Support','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'agent/onboard3.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:30:10','updated_at' => '2023-09-03 10:30:10')
        );

        AppOnboardScreens::upsert($app_onboard_screens,[]);
    }
}
