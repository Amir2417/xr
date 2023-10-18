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
            array('id' => '1','title' => 'Welcome to XRemit','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard1.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:27:23','updated_at' => '2023-09-03 10:27:23'),
            array('id' => '2','title' => 'Safe & Secure Process','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard2.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:29:10','updated_at' => '2023-09-03 10:29:10'),
            array('id' => '3','title' => '24/7 Customer Support','sub_title' => 'Smarter way to Send Money anytime,anywhere with best exchange rate','image' => 'seeder/onboard3.webp','status' => 1,'last_edit_by' => 1,'created_at' => '2023-09-03 10:30:10','updated_at' => '2023-09-03 10:30:10')
        );

        AppOnboardScreens::insert($app_onboard_screens);
    }
}
