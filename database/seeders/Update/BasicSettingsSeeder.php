<?php

namespace Database\Seeders\Update;

use Illuminate\Database\Seeder;
use App\Models\Admin\BasicSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'agent_site_name'           => "XRemitPro Agent",
            'agent_site_title'          => "Transfer Your Remittance At Secure and Fastest",
            'agent_base_color'          => "#723eeb",
            'agent_otp_exp_seconds'     => "3600",
            'agent_kyc_verification'    => true,
            'agent_email_verification'  => true,
            'agent_registration'        => true,
            'agent_agree_policy'        => true,
            'agent_email_notification'  => true,
            'agent_push_notification'   => true,
            'agent_site_logo_dark'      => "seeder/agent/logo-agent.png",
            'agent_site_logo'           => "seeder/agent/logo-agent.png",
            'agent_site_fav_dark'       => "seeder/agent/favicon.png",
            'agent_site_fav'            => "seeder/agent/favicon.png",
            'web_version'               => "2.5.2"
        ];
        $basicSettings = BasicSettings::first();
        $basicSettings->update($data);
    }
}
