<?php

namespace Database\Seeders\Update;

use App\Models\Admin\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('admin_id' => '1','country' => 'Canada','name' => 'Canadian dollar','code' => 'CAD','symbol' => '$','type' => 'FIAT','flag' => 'seeder/canada.webp','rate' => '1.36000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:50:38','updated_at' => '2023-10-18 11:50:38'),
            array('admin_id' => '1','country' => 'United Kingdom','name' => 'British pound','code' => 'GBP','symbol' => '£','type' => 'FIAT','flag' => 'seeder/british.webp','rate' => '0.82000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:51:58','updated_at' => '2023-10-18 11:53:24'),
            array('admin_id' => '1','country' => 'Spain','name' => 'Euro','code' => 'EUR','symbol' => '€','type' => 'FIAT','flag' => 'seeder/euro.webp','rate' => '0.95000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:54:16','updated_at' => '2023-10-18 11:54:16'),
            array('admin_id' => '1','country' => 'Japan','name' => 'Japanese yen','code' => 'JPY','symbol' => '¥','type' => 'FIAT','flag' => 'seeder/japan.webp','rate' => '149.63000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:54:39','updated_at' => '2023-10-18 11:54:39'),
            array('admin_id' => '1','country' => 'South Korea','name' => 'Won','code' => 'KRW','symbol' => '₩','type' => 'FIAT','flag' => 'seeder/korea.webp','rate' => '1352.53000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:55:20','updated_at' => '2023-10-18 11:55:20'),
            array('admin_id' => '1','country' => 'Sweden','name' => 'Swedish krona','code' => 'SEK','symbol' => 'kr','type' => 'FIAT','flag' => 'seeder/sweden.webp','rate' => '10.95000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:55:52','updated_at' => '2023-10-18 11:55:52'),
            array('admin_id' => '1','country' => 'Switzerland','name' => 'Swiss franc','code' => 'CHF','symbol' => 'CHf','type' => 'FIAT','flag' => 'seeder/switzerland.webp','rate' => '0.90000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:56:14','updated_at' => '2023-10-18 11:56:14'),
            array('admin_id' => '1','country' => 'Malaysia','name' => 'Malaysian ringgit','code' => 'MYR','symbol' => 'RM','type' => 'FIAT','flag' => 'seeder/malaysia.webp','rate' => '4.74000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:56:41','updated_at' => '2023-10-18 11:56:41'),
            array('admin_id' => '1','country' => 'Singapore','name' => 'Singapore dollar','code' => 'SGD','symbol' => '$','type' => 'FIAT','flag' => 'seeder/singapore.webp','rate' => '1.37000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:57:02','updated_at' => '2023-10-18 11:57:02'),
            array('admin_id' => '1','country' => 'Saudi Arabia','name' => 'Saudi riyal','code' => 'SAR','symbol' => '﷼','type' => 'FIAT','flag' => 'seeder/saudi_arabia.webp','rate' => '3.75000000','sender' => '1','receiver' => '0','default' => '0','status' => '1','created_at' => '2023-10-18 11:57:22','updated_at' => '2023-10-18 11:57:22'),
            array('admin_id' => '1','country' => 'Kenya','name' => 'Kenyan shilling','code' => 'KES','symbol' => 'KSh','type' => 'FIAT','flag' => 'seeder/kenya.webp','rate' => '149.90000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 11:58:23','updated_at' => '2023-10-18 11:58:23'),
            array('admin_id' => '1','country' => 'Central African Republic','name' => 'Central African CFA franc','code' => 'XAF','symbol' => 'FCFA','type' => 'FIAT','flag' => 'seeder/africa.webp','rate' => '621.44000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 11:58:53','updated_at' => '2023-10-18 11:58:53'),
            array('admin_id' => '1','country' => 'Senegal','name' => 'West African CFA franc','code' => 'XOF','symbol' => 'CFA','type' => 'FIAT','flag' => 'seeder/mali.webp','rate' => '621.44000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:00:16','updated_at' => '2023-10-18 12:00:16'),
            array('admin_id' => '1','country' => 'India','name' => 'Indian rupee','code' => 'INR','symbol' => '₹','type' => 'FIAT','flag' => 'seeder/india.webp','rate' => '83.26000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:00:41','updated_at' => '2023-10-18 12:00:41'),
            array('admin_id' => '1','country' => 'Pakistan','name' => 'Pakistani rupee','code' => 'PKR','symbol' => '₨','type' => 'FIAT','flag' => 'seeder/pakistan.webp','rate' => '279.76000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:01:04','updated_at' => '2023-10-18 12:01:04'),
            array('admin_id' => '1','country' => 'Bangladesh','name' => 'Bangladeshi taka','code' => 'BDT','symbol' => '৳','type' => 'FIAT','flag' => 'seeder/bangladesh.webp','rate' => '110.26000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:01:25','updated_at' => '2023-10-18 12:01:25'),
            array('admin_id' => '1','country' => 'Nepal','name' => 'Nepalese rupee','code' => 'NPR','symbol' => '₨','type' => 'FIAT','flag' => 'seeder/nepal.webp','rate' => '133.24000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:01:44','updated_at' => '2023-10-18 12:01:44'),
            array('admin_id' => '1','country' => 'Haiti','name' => 'Haitian gourde','code' => 'HTG','symbol' => 'G','type' => 'FIAT','flag' => 'seeder/haiti.webp','rate' => '134.01000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:02:07','updated_at' => '2023-10-18 12:02:07'),
            array('admin_id' => '1','country' => 'Afghanistan','name' => 'Afghan afghani','code' => 'AFN','symbol' => '؋','type' => 'FIAT','flag' => 'seeder/afghanistan.webp','rate' => '75.20000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:02:28','updated_at' => '2023-10-18 12:02:28'),
            array('admin_id' => '1','country' => 'Sudan','name' => 'Sudanese pound','code' => 'SDG','symbol' => '.س.ج','type' => 'FIAT','flag' => 'seeder/sudan.webp','rate' => '601.00000000','sender' => '0','receiver' => '1','default' => '0','status' => '1','created_at' => '2023-10-18 12:02:48','updated_at' => '2023-10-18 12:02:48')
        );

        Currency::insert($data);

    }
}
