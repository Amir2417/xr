<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBasicSettingsTableUpdateColumnFor extends Migration
{
    public function up()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->integer('otp_resend_seconds')->nullable();

            $table->string('agent_site_name',100)->nullable();
            $table->string('agent_site_title',255)->nullable();
            $table->string('agent_base_color',50)->nullable();
            $table->integer('agent_otp_resend_seconds')->nullable();
            $table->integer('agent_otp_exp_seconds')->nullable();
            $table->string('agent_site_logo_dark',255)->nullable();
            $table->string('agent_site_logo',255)->nullable();
            $table->string('agent_site_fav_dark',255)->nullable();
            $table->string('agent_site_fav',255)->nullable();
            $table->boolean('agent_registration')->default(true);
            $table->boolean('agent_secure_password')->default(false);
            $table->boolean('agent_agree_policy')->default(false);
            $table->boolean('agent_email_verification')->default(false);
            $table->boolean('agent_email_notification')->default(false);
            $table->boolean('agent_push_notification')->default(false);
            $table->boolean('agent_kyc_verification')->default(false);

        });
    }
    public function down()
    {
        
    }
}
