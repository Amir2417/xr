<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 2.1.0
Current Version: 2.2.0

Feature Update:
1. Coupon Added for new User
2. Country Section



Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate:fresh --seed && php artisan passport:install --force

2. php artisan db:seed --class=Database\\Seeders\\Admin\\CountrySectionSeeder

