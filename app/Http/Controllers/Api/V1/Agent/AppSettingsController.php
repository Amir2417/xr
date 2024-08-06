<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\UsefulLink;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AppOnboardScreens;

class AppSettingsController extends Controller
{
    /**
     * Method for get the app settings data
     * @return response
     */
    public function appSettings(){
        //for user
        $basic_settings  = BasicSettings::orderBy("id")->get()->map(function($data){


            return [
                'id'                          => $data->id,
                'site_name'                   => $data->site_name,
                'base_color'                  => $data->base_color,
                'site_logo_dark'              => $data->site_logo_dark,
                'site_logo'                   => $data->site_logo,
                'site_fav_dark'               => $data->site_fav_dark,
                'site_fav'                    => $data->site_fav,
                'email_verification'          => $data->email_verification,
                'created_at'                  => $data->created_at,
            ];
        });
        $basic_seetings_image_paths = [
            'base_url'         => url("/"),
            'path_location'    => files_asset_path_basename("image-assets"),
            'default_image'    => files_asset_path_basename("default"),
        ];
        // splash screen

        $splash_screen   = AppSettings::orderBy("id")->get()->map(function($data){

            return [
                'id'                          => $data->id,
                'version'                     => $data->version,
                'splash_screen_image'         => $data->splash_screen_image,
                'created_at'                  => $data->created_at,
            ];
        });

        // onboard screen

        $onboard_screen   = AppOnboardScreens::where('type',PaymentGatewayConst::User)->where('status',true)->orderBy("id")->get()->map(function($data){

            return [
                'id'                           => $data->id,
                'title'                        => $data->title,
                'sub_title'                    => $data->sub_title,
                'image'                        => $data->image,
                'status'                       => $data->status,
                'last_edit_by'                 => $data->last_edit_by,
                'created_at'                   => $data->created_at,

            ];
        });

        // web links

        $about_page_link   = url('about');

        $privacy_policy = UsefulLink::where('slug','privacy-policy')->first();
        $privacy_policy_link = route('link',$privacy_policy->slug);

        $web_links =[
            [
                'name' => "About Us",
                'link' => $about_page_link,
            ],
            [
                'name' => "Privacy Policy",
                'link' => $privacy_policy_link,
            ]
        ];

        $screen_image_path    = [
            'base_url'                     => url("/"),
            'path_location'                => files_asset_path_basename("app-images"),
            'default_image'                => files_asset_path_basename("default"),
        ];
        
        $splash_screen_agent = AppSettings::get()->map(function($splash_screen){
            return[
                'id' => $splash_screen->id,
                'splash_screen_image' => $splash_screen->agent_splash_screen_image,
                'version' => $splash_screen->agent_version,
                'created_at' => $splash_screen->created_at,
                'updated_at' => $splash_screen->updated_at,
            ];
        })->first();

        $onboard_screen_agent = AppOnboardScreens::where('type',PaymentGatewayConst::Agent)->orderByDesc('id')->where('status',1)->get()->map(function($data){
            return[
                'id' => $data->id,
                'title' => $data->title,
                'sub_title' => $data->sub_title,
                'image' => $data->image,
                'status' => $data->status,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ];

        });
        
        $basic_setting = BasicSettings::first();
        
        $basic_settings_agent = [
            "site_name" =>  @$basic_setting->agent_site_name,
            "site_title" =>  @$basic_setting->agent_site_title,
            "base_color" =>  @$basic_setting->agent_base_color,
            "site_logo" =>  @$basic_setting->agent_site_logo,
            "site_logo_dark" =>  @$basic_setting->agent_site_logo_dark,
            "site_fav_dark" =>  @$basic_setting->agent_site_fav_dark,
            "site_fav" =>  @$basic_setting->agent_site_fav,
            "timezone" =>  @$basic_setting->timezone,
        ];

        //basic settings user
        $basic_settings_user  = [
            'basic_settings'               => $basic_settings,
            'splash_screen'                => $splash_screen,
            'onboard_screen'               => $onboard_screen,
            'web_links'                    => $web_links,
            'basic_seetings_image_paths'   => $basic_seetings_image_paths,
            'app_image_path'               => $screen_image_path,
        ];
        

       
        $agent_app_settings = [
            'splash_screen'     => $splash_screen_agent,
            'onboard_screen'    => $onboard_screen_agent,
            'basic_settings'    => $basic_settings_agent,
        ];
        
        $app_settings = [
            'agent'         => (object) $agent_app_settings,
            'user'          => (object) $basic_settings_user
        ];

        return Response::success(['App settings data fetch successfully.'],[
            'base_url'              => url("/"),
            "default_image"         => files_asset_path_basename("default"),
            "screen_image_path"     => files_asset_path_basename("app-images"),
            "logo_image_path"       => files_asset_path_basename("image-assets"),
            'app_settings'          => (object)$app_settings,
        ],200);

    }
    public function languages(){
        try{
            $api_languages = get_api_languages();
        }catch(Exception $e) {
            return Response::error([$e->getMessage()],[],500);
        }
        return Response::success([__("Language data fetch successfully!")],[
            'languages' => $api_languages,
        ],200);
    }
}
