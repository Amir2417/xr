<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\MobileMethod;
use Illuminate\Database\Seeder;

class MobileMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mobile_methods = array(
            array('name' => 'Kuda Bank','slug' => 'kuda-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:59:18','updated_at' => '2023-08-06 07:02:47'),
            array('name' => 'Opay App','slug' => 'opay-app','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:59:30','updated_at' => '2023-08-06 06:59:30'),
            array('name' => 'GT World','slug' => 'gt-world','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:59:45','updated_at' => '2023-08-06 06:59:45'),
            array('name' => 'Access Bank','slug' => 'access-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:00:01','updated_at' => '2023-08-06 07:02:39'),
            array('name' => 'First Bank Mobile App','slug' => 'first-bank-mobile-app','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:00:16','updated_at' => '2023-08-06 07:00:16'),
            array('name' => 'VFHD','slug' => 'vfhd','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:00:28','updated_at' => '2023-08-06 07:00:28'),
            array('name' => 'Alat by Wema Bank','slug' => 'alat-by-wema-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:00:39','updated_at' => '2023-08-06 07:00:39'),
            array('name' => 'FCMB','slug' => 'fcmb','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:00:51','updated_at' => '2023-08-06 07:02:29'),
            array('name' => 'Zenith Bank','slug' => 'zenith-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:01:05','updated_at' => '2023-08-06 07:02:57'),
            array('name' => 'UBA','slug' => 'uba','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:01:17','updated_at' => '2023-08-06 07:03:06'),
            array('name' => 'Ecobank','slug' => 'ecobank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:01:59','updated_at' => '2023-08-06 07:01:59'),
            array('name' => 'Fidelity Bank','slug' => 'fidelity-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:02:18','updated_at' => '2023-08-06 07:02:18'),
            array('name' => 'First Bank','slug' => 'first-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:03:24','updated_at' => '2023-08-06 07:03:24'),
            array('name' => 'GTBank','slug' => 'gtbank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:03:45','updated_at' => '2023-08-06 07:03:45'),
            array('name' => 'Heritage Bank','slug' => 'heritage-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:07','updated_at' => '2023-08-06 07:04:07'),
            array('name' => 'Jaiz Bank','slug' => 'jaiz-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:36','updated_at' => '2023-08-06 07:04:36'),
            array('name' => 'Keystone Bank','slug' => 'keystone-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:04:49','updated_at' => '2023-08-06 07:04:49'),
            array('name' => 'Stanbic IBTC Bank','slug' => 'stanbic-ibtc-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:04','updated_at' => '2023-08-06 07:05:04'),
            array('name' => 'Sterling Bank','slug' => 'sterling-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:16','updated_at' => '2023-08-06 07:05:16'),
            array('name' => 'Union Bank','slug' => 'union-bank','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 07:05:36','updated_at' => '2023-08-06 07:05:36'),
            array('name' => 'Bank of Khartoum (BOK)','slug' => 'bank-of-khartoum-bok','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 11:04:43','updated_at' => '2023-11-02 11:04:43'),
            array('name' => 'Qatar National Bank (QNB) Sudan','slug' => 'qatar-national-bank-qnb-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 11:04:57','updated_at' => '2023-11-02 11:04:57'),
            array('name' => 'Faisal Islamic Bank of Sudan','slug' => 'faisal-islamic-bank-of-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 11:05:07','updated_at' => '2023-11-02 11:05:07'),
            array('name' => 'Mashreq Bank Sudan','slug' => 'mashreq-bank-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 11:05:16','updated_at' => '2023-11-02 11:05:16'),
            array('name' => 'Omdurman National Bank (ONB)','slug' => 'omdurman-national-bank-onb','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 11:05:25','updated_at' => '2023-11-02 11:05:25'),
            array('name' => 'Afghan Wireless Communication Company (AWCC) - M-Paisa','slug' => 'afghan-wireless-communication-company-awcc-m-paisa','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 11:05:56','updated_at' => '2023-11-02 11:05:56'),
            array('name' => 'Roshan - M-Paisa','slug' => 'roshan-m-paisa','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 11:06:06','updated_at' => '2023-11-02 11:06:06'),
            array('name' => 'Azizi Bank','slug' => 'azizi-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 11:06:13','updated_at' => '2023-11-02 11:06:13'),
            array('name' => 'Afghanistan International Bank (AIB)','slug' => 'afghanistan-international-bank-aib','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 11:06:23','updated_at' => '2023-11-02 11:06:23'),
            array('name' => 'New Kabul Bank','slug' => 'new-kabul-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 11:06:32','updated_at' => '2023-11-02 11:06:32'),
            array('name' => 'Tcho Tcho Mobile','slug' => 'tcho-tcho-mobile','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 11:07:16','updated_at' => '2023-11-02 11:07:16'),
            array('name' => 'Digicel Mobile Money','slug' => 'digicel-mobile-money','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 11:07:27','updated_at' => '2023-11-02 11:07:27'),
            array('name' => 'Natcom Mobile Money','slug' => 'natcom-mobile-money','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 11:07:39','updated_at' => '2023-11-02 11:07:39'),
            array('name' => 'eSewa','slug' => 'esewa','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 11:08:01','updated_at' => '2023-11-02 11:08:01'),
            array('name' => 'Khalti','slug' => 'khalti','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 11:08:10','updated_at' => '2023-11-02 11:08:10'),
            array('name' => 'IME Pay','slug' => 'ime-pay','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 11:08:19','updated_at' => '2023-11-02 11:08:25'),
            array('name' => 'Prabhu Pay','slug' => 'prabhu-pay','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 11:08:36','updated_at' => '2023-11-02 11:08:36'),
            array('name' => 'ConnectIPS','slug' => 'connectips','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 11:08:43','updated_at' => '2023-11-02 11:08:43'),
            array('name' => 'bKash','slug' => 'bkash','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 11:09:06','updated_at' => '2023-11-02 11:09:06'),
            array('name' => 'Nagad','slug' => 'nagad','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 11:09:14','updated_at' => '2023-11-02 11:09:14'),
            array('name' => 'Rocket','slug' => 'rocket','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 11:09:21','updated_at' => '2023-11-02 11:09:21'),
            array('name' => 'SureCash','slug' => 'surecash','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 11:09:31','updated_at' => '2023-11-02 11:09:31'),
            array('name' => 'Upay','slug' => 'upay','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 11:09:39','updated_at' => '2023-11-02 11:09:39'),
            array('name' => 'JazzCash','slug' => 'jazzcash','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 11:09:55','updated_at' => '2023-11-02 11:09:55'),
            array('name' => 'Easypaisa','slug' => 'easypaisa','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 11:10:04','updated_at' => '2023-11-02 11:10:04'),
            array('name' => 'UBL Digital App','slug' => 'ubl-digital-app','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 11:10:13','updated_at' => '2023-11-02 11:10:13'),
            array('name' => 'HBL Mobile App','slug' => 'hbl-mobile-app','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 11:10:23','updated_at' => '2023-11-02 11:10:23'),
            array('name' => 'MCB Mobile','slug' => 'mcb-mobile','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 11:10:32','updated_at' => '2023-11-02 11:10:32'),
            array('name' => 'PhonePe','slug' => 'phonepe','country' => 'India','status' => '1','created_at' => '2023-11-02 11:10:54','updated_at' => '2023-11-02 11:10:54'),
            array('name' => 'Paytm','slug' => 'paytm','country' => 'India','status' => '1','created_at' => '2023-11-02 11:11:03','updated_at' => '2023-11-02 11:11:03'),
            array('name' => 'BHIM (Bharat Interface for Money)','slug' => 'bhim-bharat-interface-for-money','country' => 'India','status' => '1','created_at' => '2023-11-02 11:11:19','updated_at' => '2023-11-02 11:11:19'),
            array('name' => 'Amazon Pay','slug' => 'amazon-pay','country' => 'India','status' => '1','created_at' => '2023-11-02 11:11:30','updated_at' => '2023-11-02 11:11:30'),
            array('name' => 'Airtel Payments Bank','slug' => 'airtel-payments-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 11:11:41','updated_at' => '2023-11-02 11:11:41'),
            array('name' => 'Orange Money','slug' => 'orange-money','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 11:12:03','updated_at' => '2023-11-02 11:12:03'),
            array('name' => 'Free Money','slug' => 'free-money','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 11:12:11','updated_at' => '2023-11-02 11:12:11'),
            array('name' => 'Wari','slug' => 'wari','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 11:12:19','updated_at' => '2023-11-02 11:12:19'),
            array('name' => 'Joni Joni','slug' => 'joni-joni','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 11:12:28','updated_at' => '2023-11-02 11:12:28'),
            array('name' => 'Flooz','slug' => 'flooz','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 11:12:56','updated_at' => '2023-11-02 11:12:56'),
            array('name' => 'M-Pesa','slug' => 'm-pesa','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 11:13:19','updated_at' => '2023-11-02 11:13:19'),
            array('name' => 'Equitel','slug' => 'equitel','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 11:13:27','updated_at' => '2023-11-02 11:13:27'),
            array('name' => 'Airtel Money','slug' => 'airtel-money','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 11:13:36','updated_at' => '2023-11-02 11:13:36'),
            array('name' => 'T-Kash','slug' => 't-kash','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 11:13:44','updated_at' => '2023-11-02 11:13:44')
        );
        MobileMethod::insert($mobile_methods);
    }
}
