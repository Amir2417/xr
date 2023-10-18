<?php

namespace Database\Seeders\Admin;

use App\Constants\GlobalConst;
use App\Models\Admin\UsefulLink;
use Illuminate\Database\Seeder;

class UsefulLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type'    => GlobalConst::USEFUL_LINK_PRIVACY_POLICY,
                'title'      => '{"language":{"en":{"title":"Privacy Policy"},"bn":{"title":"Li\u00ean k\u1ebft h\u1eefu \u00edch"}}}',
                'slug'       => 'privacy-policy',
                'url'        => 'privacy-policy',
                'content'    => '{"language":{"en":{"content":"<h2><strong>Privacy Policy for XRemit<\\/strong><\\/h2><p>At XRemit, we understand the importance of privacy and are committed to protecting the personal information of our users. We believe in being transparent about our data collection and usage practices, and this privacy policy outlines what information we collect, how we use it, and who we share it with.<\\/p><h3><strong>Information Collection:<\\/strong><\\/h3><p>XRemit collects the following types of personal information from its users:<\\/p><ul><li>Name<\\/li><li>Phone Number<\\/li><li>Profile image<\\/li><\\/ul><p>The information is collected when the user creates an account with XRemit or updates their profile information. The profile image is optional, and the user has the choice of whether or not to upload it.<\\/p><h3><strong>Information Use:<\\/strong><\\/h3><p>XRemit uses the personal information collected from its users for the following purposes:<\\/p><ul><li>To provide a personalized experience for the user<\\/li><li>To send notifications and updates about the app<\\/li><li>To improve the app and its features based on user feedback and usage patterns<\\/li><li>To comply with legal obligations and to resolve disputes<\\/li><\\/ul><h3><strong>Information Sharing:<\\/strong><\\/h3><p>XRemit does not share any of the personal information collected from its users with third parties, except in the following cases:<\\/p><ul><li>When required by law<\\/li><li>To protect the rights and safety of XRemit and its users<\\/li><li>To enforce our terms of service<\\/li><\\/ul><h3><strong>Data Security:<\\/strong><\\/h3><p>XRemit takes the security of its users\' personal information seriously and has implemented appropriate technical and organizational measures to protect it. However, please note that no data transmission or storage can be guaranteed to be 100% secure.<\\/p><h3><strong>Contact Information:<\\/strong><\\/h3><p>If you have any questions or concerns regarding XRemit privacy policy or the information we collect, you can contact us at:<\\/p><ul><li>Email: support@XRemit.com<\\/li><\\/ul><h3><strong>Changes to Privacy Policy:<\\/strong><\\/h3><p>XRemit reserves the right to modify this privacy policy at any time. We will notify our users of any significant changes by posting a notice on our app or website and by updating the \\"Last Updated\\" date at the top of this policy. We encourage our users to regularly review this privacy policy to stay informed about how we are protecting their personal information.<\\/p>"},"bn":{"content":"<h2><strong>Privacy Policy for XRemit<\\/strong><\\/h2><p>At XRemit, we understand the importance of privacy and are committed to protecting the personal information of our users. We believe in being transparent about our data collection and usage practices, and this privacy policy outlines what information we collect, how we use it, and who we share it with.<\\/p><h3><strong>Information Collection:<\\/strong><\\/h3><p>XRemit collects the following types of personal information from its users:<\\/p><ul><li>Name<\\/li><li>Phone Number<\\/li><li>Profile image<\\/li><\\/ul><p>The information is collected when the user creates an account with XRemit or updates their profile information. The profile image is optional, and the user has the choice of whether or not to upload it.<\\/p><h3><strong>Information Use:<\\/strong><\\/h3><p>XRemit uses the personal information collected from its users for the following purposes:<\\/p><ul><li>To provide a personalized experience for the user<\\/li><li>To send notifications and updates about the app<\\/li><li>To improve the app and its features based on user feedback and usage patterns<\\/li><li>To comply with legal obligations and to resolve disputes<\\/li><\\/ul><h3><strong>Information Sharing:<\\/strong><\\/h3><p>XRemit does not share any of the personal information collected from its users with third parties, except in the following cases:<\\/p><ul><li>When required by law<\\/li><li>To protect the rights and safety of XRemit and its users<\\/li><li>To enforce our terms of service<\\/li><\\/ul><h3><strong>Data Security:<\\/strong><\\/h3><p>XRemit takes the security of its users\' personal information seriously and has implemented appropriate technical and organizational measures to protect it. However, please note that no data transmission or storage can be guaranteed to be 100% secure.<\\/p><h3><strong>Contact Information:<\\/strong><\\/h3><p>If you have any questions or concerns regarding XRemit privacy policy or the information we collect, you can contact us at:<\\/p><ul><li>Email: support@XRemit.com<\\/li><\\/ul><h3><strong>Changes to Privacy Policy:<\\/strong><\\/h3><p>XRemit reserves the right to modify this privacy policy at any time. We will notify our users of any significant changes by posting a notice on our app or website and by updating the \\"Last Updated\\" date at the top of this policy. We encourage our users to regularly review this privacy policy to stay informed about how we are protecting their personal information.<\\/p>"}}}',
                'status'     => true,
                'editable'   => false,
                'created_at' => now(),
            ]
        ];
        UsefulLink::insert($data);
    }
}
