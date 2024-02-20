<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.3.1
Current Version: 2.4.0

Feature Update:
1. Basic settings user register update.
2. Automatic Bank list load based on receiver country.(Bank transfer)
3. Pagadito and Razorpay payment Gateway added.




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
