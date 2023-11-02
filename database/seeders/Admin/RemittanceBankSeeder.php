<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\RemittanceBank;
use Illuminate\Database\Seeder;

class RemittanceBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $remittance_banks = array(
            array('name' => 'Access Bank Plc','slug' => 'access-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:47:47','updated_at' => '2023-08-06 06:47:47'),
            array('name' => 'Fidelity Bank Plc','slug' => 'fidelity-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:00','updated_at' => '2023-08-06 06:48:00'),
            array('name' => 'First City Monument Bank Limited','slug' => 'first-city-monument-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:13','updated_at' => '2023-08-06 06:48:13'),
            array('name' => 'First Bank of Nigeria Limited','slug' => 'first-bank-of-nigeria-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:27','updated_at' => '2023-08-06 06:48:27'),
            array('name' => 'Guaranty Trust Holding Company Plc','slug' => 'guaranty-trust-holding-company-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:40','updated_at' => '2023-08-06 06:48:40'),
            array('name' => 'Union Bank of Nigeria Plc','slug' => 'union-bank-of-nigeria-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:52','updated_at' => '2023-08-06 06:48:52'),
            array('name' => 'United Bank for Africa Plc','slug' => 'united-bank-for-africa-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:02','updated_at' => '2023-08-06 06:49:02'),
            array('name' => 'Zenith Bank Plc','slug' => 'zenith-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:16','updated_at' => '2023-08-06 06:49:16'),
            array('name' => 'Citibank Nigeria Limited','slug' => 'citibank-nigeria-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:35','updated_at' => '2023-08-06 06:49:35'),
            array('name' => 'Ecobank Nigeria','slug' => 'ecobank-nigeria','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:48','updated_at' => '2023-08-06 06:49:48'),
            array('name' => 'Heritage Bank Plc','slug' => 'heritage-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:00','updated_at' => '2023-08-06 06:50:00'),
            array('name' => 'Keystone Bank Limited','slug' => 'keystone-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:14','updated_at' => '2023-08-06 06:50:14'),
            array('name' => 'Polaris Bank Limited.','slug' => 'polaris-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:40','updated_at' => '2023-08-06 06:50:40'),
            array('name' => 'Stanbic IBTC Bank Plc','slug' => 'stanbic-ibtc-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:52','updated_at' => '2023-08-06 06:50:52'),
            array('name' => 'Standard Chartered','slug' => 'standard-chartered','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:04','updated_at' => '2023-08-06 06:51:04'),
            array('name' => 'Sterling Bank Plc','slug' => 'sterling-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:34','updated_at' => '2023-08-06 06:51:34'),
            array('name' => 'Unity Bank Plc','slug' => 'unity-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:45','updated_at' => '2023-08-06 06:51:45'),
            array('name' => 'Wema Bank Plc','slug' => 'wema-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:59','updated_at' => '2023-08-06 06:51:59'),
            array('name' => 'Parallex Bank Limited','slug' => 'parallex-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:10','updated_at' => '2023-08-06 06:52:10'),
            array('name' => 'PremiumTrust Bank Limited','slug' => 'premiumtrust-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:21','updated_at' => '2023-08-06 06:52:21'),
            array('name' => 'Cooperative Bank of Kenya','slug' => 'cooperative-bank-of-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:45:41','updated_at' => '2023-11-02 10:45:41'),
            array('name' => 'Kenya Commercial Bank','slug' => 'kenya-commercial-bank','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:45:53','updated_at' => '2023-11-02 10:45:53'),
            array('name' => 'Equity Bank Kenya','slug' => 'equity-bank-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:09','updated_at' => '2023-11-02 10:46:09'),
            array('name' => 'National Bank of Kenya','slug' => 'national-bank-of-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:20','updated_at' => '2023-11-02 10:46:20'),
            array('name' => 'Absa Bank Kenya','slug' => 'absa-bank-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:31','updated_at' => '2023-11-02 10:46:31'),
            array('name' => 'Banque des États de l’Afrique Centrale','slug' => 'banque-des-etats-de-lafrique-centrale','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:21','updated_at' => '2023-11-02 10:47:21'),
            array('name' => 'Bangui Cheques Postaux','slug' => 'bangui-cheques-postaux','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:31','updated_at' => '2023-11-02 10:47:31'),
            array('name' => 'Banque Internationale pour le Centrafrique (BICA)','slug' => 'banque-internationale-pour-le-centrafrique-bica','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:41','updated_at' => '2023-11-02 10:47:41'),
            array('name' => 'Banque Populaire Maroco-Centrafricaine (BPMC)','slug' => 'banque-populaire-maroco-centrafricaine-bpmc','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:50','updated_at' => '2023-11-02 10:47:50'),
            array('name' => 'Ecobank','slug' => 'ecobank','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:59','updated_at' => '2023-11-02 10:47:59'),
            array('name' => 'United Bank for Africa','slug' => 'united-bank-for-africa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:22','updated_at' => '2023-11-02 10:48:22'),
            array('name' => 'Bank Of Africa Senegal','slug' => 'bank-of-africa-senegal','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:36','updated_at' => '2023-11-02 10:48:36'),
            array('name' => 'Ecobank Senegal SA','slug' => 'ecobank-senegal-sa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:46','updated_at' => '2023-11-02 10:48:46'),
            array('name' => 'Atlantic Bank Group','slug' => 'atlantic-bank-group','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:59','updated_at' => '2023-11-02 10:48:59'),
            array('name' => 'Banque Islamique du Senegal SA','slug' => 'banque-islamique-du-senegal-sa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:49:11','updated_at' => '2023-11-02 10:49:11'),
            array('name' => 'State Bank of India','slug' => 'state-bank-of-india','country' => 'India','status' => '1','created_at' => '2023-11-02 10:49:31','updated_at' => '2023-11-02 10:49:31'),
            array('name' => 'ICICI Bank','slug' => 'icici-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:49:43','updated_at' => '2023-11-02 10:49:49'),
            array('name' => 'Bank of Baroda','slug' => 'bank-of-baroda','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:02','updated_at' => '2023-11-02 10:50:02'),
            array('name' => 'Canara Bank','slug' => 'canara-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:13','updated_at' => '2023-11-02 10:50:13'),
            array('name' => 'Punjab National Bank','slug' => 'punjab-national-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:27','updated_at' => '2023-11-02 10:50:27'),
            array('name' => 'National Bank of Pakistan','slug' => 'national-bank-of-pakistan','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:50:47','updated_at' => '2023-11-02 10:50:47'),
            array('name' => 'Habib Bank Limited','slug' => 'habib-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:50:58','updated_at' => '2023-11-02 10:50:58'),
            array('name' => 'Allied Bank Limited','slug' => 'allied-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:11','updated_at' => '2023-11-02 10:51:11'),
            array('name' => 'Bank Alfalah','slug' => 'bank-alfalah','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:24','updated_at' => '2023-11-02 10:51:24'),
            array('name' => 'United Bank Limited','slug' => 'united-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:39','updated_at' => '2023-11-02 10:51:39'),
            array('name' => 'Dutch Bangla Bank','slug' => 'dutch-bangla-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:52:02','updated_at' => '2023-11-02 10:52:02'),
            array('name' => 'Islami Bank Bangladesh Ltd','slug' => 'islami-bank-bangladesh-ltd','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:52:22','updated_at' => '2023-11-02 10:52:29'),
            array('name' => 'BRAC Bank PLC','slug' => 'brac-bank-plc','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:53:48','updated_at' => '2023-11-02 10:53:48'),
            array('name' => 'Sonali Bank','slug' => 'sonali-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:53:59','updated_at' => '2023-11-02 10:53:59'),
            array('name' => 'AB Bank Limited','slug' => 'ab-bank-limited','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:54:11','updated_at' => '2023-11-02 10:54:11'),
            array('name' => 'Nepal Investment Bank','slug' => 'nepal-investment-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:34','updated_at' => '2023-11-02 10:54:34'),
            array('name' => 'Nepal SBI Bank','slug' => 'nepal-sbi-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:45','updated_at' => '2023-11-02 10:54:45'),
            array('name' => 'NIC ASIA Bank','slug' => 'nic-asia-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:58','updated_at' => '2023-11-02 10:54:58'),
            array('name' => 'Everest Bank','slug' => 'everest-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:55:13','updated_at' => '2023-11-02 10:55:13'),
            array('name' => 'Prabhu Bank','slug' => 'prabhu-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:55:31','updated_at' => '2023-11-02 10:55:31'),
            array('name' => 'BANQUE DE L\'UNION HAITIENNE','slug' => 'banque-de-lunion-haitienne','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:11','updated_at' => '2023-11-02 10:56:11'),
            array('name' => 'BANQUE DE LA REPUBLIQUE D\'HAITI','slug' => 'banque-de-la-republique-dhaiti','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:24','updated_at' => '2023-11-02 10:56:24'),
            array('name' => 'BANQUE DE PROMOTION COMMERCIALE ET INDUSTRIELLE S.A.','slug' => 'banque-de-promotion-commerciale-et-industrielle-sa','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:42','updated_at' => '2023-11-02 10:56:42'),
            array('name' => 'BANQUE NATIONALE DE CREDIT (BNC)','slug' => 'banque-nationale-de-credit-bnc','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:53','updated_at' => '2023-11-02 10:56:53'),
            array('name' => 'BANQUE NATIONALE DE PARIS','slug' => 'banque-nationale-de-paris','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:57:03','updated_at' => '2023-11-02 10:57:03'),
            array('name' => 'Afghanistan International Bank','slug' => 'afghanistan-international-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:57:52','updated_at' => '2023-11-02 10:57:52'),
            array('name' => 'Azizi Bank','slug' => 'azizi-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:04','updated_at' => '2023-11-02 10:58:04'),
            array('name' => 'Bank Alfalah Limited','slug' => 'bank-alfalah-limited','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:13','updated_at' => '2023-11-02 10:58:13'),
            array('name' => 'Banke Millie Afghan','slug' => 'banke-millie-afghan','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:21','updated_at' => '2023-11-02 10:58:21'),
            array('name' => 'Bank-e-Millie Afghan','slug' => 'bank-e-millie-afghan','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:29','updated_at' => '2023-11-02 10:58:29'),
            array('name' => 'Bank of Khartoum','slug' => 'bank-of-khartoum','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:58:51','updated_at' => '2023-11-02 10:58:51'),
            array('name' => 'Commercial Bank of Ethiopia','slug' => 'commercial-bank-of-ethiopia','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:01','updated_at' => '2023-11-02 10:59:01'),
            array('name' => 'Cooperative Bank of South Sudan','slug' => 'cooperative-bank-of-south-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:13','updated_at' => '2023-11-02 10:59:13'),
            array('name' => 'Qatar National Bank','slug' => 'qatar-national-bank','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:27','updated_at' => '2023-11-02 10:59:27'),
            array('name' => 'National Bank of Sudan','slug' => 'national-bank-of-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:40','updated_at' => '2023-11-02 10:59:40')
        
        );

        RemittanceBank::insert($remittance_banks);
    }
}
