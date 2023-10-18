<?php

namespace Database\Seeders\User;


use App\Models\Recipient;
use Illuminate\Database\Seeder;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $beneficiaries = array(
            array('user_id' => '2','first_name' => 'Lionel','middle_name' => 'Andress','last_name' => 'Messi','email' => 'messi@gmail.com','country' => 'Argentina','city' => 'Rozario','state' => 'Rodri','zip_code' => '7412587','phone' => '445','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Access Bank Plc','iban_number' => 'ASCE74102589630214569874','address' => 'Argentina','document_type' => 'NID','front_image' => NULL,'back_image' => NULL,'created_at' => '2023-08-30 10:17:21','updated_at' => '2023-08-31 03:22:42'),
            array('user_id' => '2','first_name' => 'Paulo','middle_name' => 'Androw','last_name' => 'Dybala','email' => 'dybala@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bond','zip_code' => '254','phone' => '1245214','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Access Bank Plc','iban_number' => '741025896321456987412569','address' => 'Central','document_type' => 'NID','front_image' => NULL,'back_image' => NULL,'created_at' => '2023-08-30 11:15:47','updated_at' => '2023-08-30 11:15:47'),
            array('user_id' => '2','first_name' => 'Paulo','middle_name' => 'Mark','last_name' => 'Robert','email' => 'robert@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bond','zip_code' => '254454','phone' => '1245214741','method' => 'Mobile Money','mobile_name' => 'Kuda Bank','account_number' => '787465467654','bank_name' => NULL,'iban_number' => NULL,'address' => 'Central','document_type' => 'NID','front_image' => NULL,'back_image' => NULL,'created_at' => '2023-08-30 11:16:45','updated_at' => '2023-08-30 11:16:45'),
            array('user_id' => '2','first_name' => 'Angel','middle_name' => 'Di','last_name' => 'Maria','email' => 'dimaria@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bon','zip_code' => '74105454','phone' => '54564112','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Guaranty Trust Holding Company Plc','iban_number' => '741025896321456987410258','address' => 'Bali','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2023-08-30 12:51:35','updated_at' => '2023-08-30 12:51:35'),
            array('user_id' => '2','first_name' => 'Cuti','middle_name' => 'Di','last_name' => 'Romero','email' => 'romero@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bon','zip_code' => '7410041','phone' => '545644545','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Guaranty Trust Holding Company Plc','iban_number' => '741025896321456987410741','address' => 'Bali','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2023-08-31 03:38:19','updated_at' => '2023-08-31 03:38:19'),
            array('user_id' => '2','first_name' => 'Rodrogo','middle_name' => 'Di','last_name' => 'paul','email' => 'depaul@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bon','zip_code' => '7410698','phone' => '5456456456','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Guaranty Trust Holding Company Plc','iban_number' => '741025896321456987410852','address' => 'Bali','document_type' => NULL,'front_image' => '6c9adedc-6ab5-4158-b9a5-61d77f707a47.webp','back_image' => NULL,'created_at' => '2023-08-31 03:39:41','updated_at' => '2023-08-31 03:39:41'),
            array('user_id' => '2','first_name' => 'Lisandro','middle_name' => 'De','last_name' => 'Martinex','email' => 'martinez@gmail.com','country' => 'Nigeria','city' => 'Kuda','state' => 'Bon','zip_code' => '7411440','phone' => '545454','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Guaranty Trust Holding Company Plc','iban_number' => '741025896321456987410963','address' => 'Bali','document_type' => NULL,'front_image' => '537c64b1-dfec-4a56-94ea-ba0f4f9520e0.webp','back_image' => NULL,'created_at' => '2023-08-31 03:57:16','updated_at' => '2023-08-31 03:57:16'),
            array('user_id' => '2','first_name' => 'Wylie','middle_name' => 'Kevin Wilder','last_name' => 'Guerrero','email' => 'turegomywe@mailinator.com','country' => 'Nigeria','city' => 'Et enim amet rerum','state' => 'Amet nihil ad repel','zip_code' => '10809','phone' => '63','method' => 'Mobile Money','mobile_name' => 'GT World','account_number' => '45564564','bank_name' => NULL,'iban_number' => NULL,'address' => 'Iure cum nisi verita','document_type' => 'NID Card','front_image' => '882a8492-8550-4c33-aaee-e148df82d8a2.webp','back_image' => '0','created_at' => '2023-09-03 10:10:43','updated_at' => '2023-09-03 10:10:43')
        );
        Recipient::insert($beneficiaries);
    }
}
