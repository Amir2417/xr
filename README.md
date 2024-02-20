<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.2.0
Current Version: 2.3.0

Feature Update:
1. Payment Gateway Pagadito and Razorpay Added.




Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate && php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\BasicSettingsSeeder


3. To Update Payment Gateway Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\PaymentGatewaySeeder

4. To Update Virtual Card Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\VirtualCardApiSeeder
