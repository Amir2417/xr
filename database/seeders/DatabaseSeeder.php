<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DemoFixSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\CurrencySeeder;
use Database\Seeders\Admin\LanguageSeeder;
use Database\Seeders\Admin\SetupKycSeeder;
use Database\Seeders\Admin\SetupSeoSeeder;
use Database\Seeders\Admin\ExtensionSeeder;
use Database\Seeders\Admin\SetupPageSeeder;
use Database\Seeders\Admin\UsefulLinkSeeder;
use Database\Seeders\User\BeneficiarySeeder;
use Database\Seeders\User\TransactionSeeder;
use Database\Seeders\Admin\AppSettingsSeeder;
use Database\Seeders\Admin\AdminHasRoleSeeder;
use Database\Seeders\Admin\MobileMethodSeeder;
use Database\Seeders\Admin\SiteSectionsSeeder;
use Database\Seeders\Admin\BasicSettingsSeeder;
use Database\Seeders\Admin\SourceOfFundsSeeder;
use Database\Seeders\Admin\PaymentGatewaySeeder;
use Database\Seeders\Admin\RemittanceBankSeeder;
use Database\Seeders\Admin\SendingPurposeSeeder;
use Database\Seeders\Admin\AppOnboardScreensSeeder;
use Database\Seeders\Admin\CountrySectionSeeder;
use Database\Seeders\Admin\TransactionSettingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            TransactionSettingSeeder::class,
            CurrencySeeder::class,
            BasicSettingsSeeder::class,
            SetupSeoSeeder::class,
            AppSettingsSeeder::class,
            SiteSectionsSeeder::class,
            CountrySectionSeeder::class,
            SetupKycSeeder::class,
            ExtensionSeeder::class,
            AdminHasRoleSeeder::class,
            SetupPageSeeder::class,
            PaymentGatewaySeeder::class,
            LanguageSeeder::class,
            UsefulLinkSeeder::class,
            AppOnboardScreensSeeder::class,

            /**
             * demo
             */
            // UserSeeder::class,
            // RemittanceBankSeeder::class,
            // MobileMethodSeeder::class,
            // SendingPurposeSeeder::class,
            // SourceOfFundsSeeder::class,
            // TransactionSeeder::class,
            // BeneficiarySeeder::class,
            // DemoFixSeeder::class,


        ]);
    }
}
