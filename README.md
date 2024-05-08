<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.4.1
Current Version: 2.4.2

Feature Update:
1. New User Bonus.
2. Bank transfer for both automatic and manual.
3. GDPR Cookie update.
4. Google 2FA for admin.


Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate && php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Update\\BasicSettingsSeeder

3. To Update BankMethod Automatic Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\BankMethodAutomaticSeeder

4. To Update BankMethod Manual Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\RemittanceBankSeeder

5. To Update BankMethod Automatic Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\MobileMethodSeeder

