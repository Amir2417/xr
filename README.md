<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.5.0
Current Version: 2.5.1

Feature Update:
1. Installer file update.


Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Update\\BasicSettingsSeeder

3. To Update Transaction Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\TransactionSettingSeeder


4. To Update Cash Pickup Method Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\CashPickupMethodSeeder

5. To Update Setup KYC Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\SetupKycSeeder

6. To Update Payment Gateway Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\PaymentGatewaySeeder


