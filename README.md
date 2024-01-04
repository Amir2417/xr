<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.2.0
Current Version: 2.3.0

Feature Update:
1. Payment Gateway CoinGate and Tatum Added.




Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate && php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\BasicSettingsSeeder
    
3. To Update Site Sections Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\SiteSectionsSeeder

4. To Update Currency Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\CurrencySeeder

5. To Update Journal Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\JournalSeeder

6. To Update Payment Gateway Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\PaymentGatewaySeeder