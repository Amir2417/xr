<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\AppOnboardScreens;

class AppSettingsController extends Controller
{
    /**
     * Method for get the app settings data
     * @return response
     */
    public function appSettings(){
       
        
        $splash_screen_agent = AppSettings::get()->map(function($splash_screen){
            return[
                'id' => $splash_screen->id,
                'splash_screen_image' => $splash_screen->agent_splash_screen_image,
                'version' => $splash_screen->agent_version,
                'created_at' => $splash_screen->created_at,
                'updated_at' => $splash_screen->updated_at,
            ];
        })->first();
        
        $basic_settings = BasicSettings::first();
        
        $basic_settings_agent = [
            "site_name" =>  @$basic_settings->agent_site_name,
            "site_title" =>  @$basic_settings->agent_site_title,
            "base_color" =>  @$basic_settings->agent_base_color,
            "site_logo" =>  @$basic_settings->agent_site_logo,
            "site_logo_dark" =>  @$basic_settings->agent_site_logo_dark,
            "site_fav_dark" =>  @$basic_settings->agent_site_fav_dark,
            "site_fav" =>  @$basic_settings->agent_site_fav,
            "timezone" =>  @$basic_settings->timezone,
        ];
        

       
        $agent_app_settings = [
            'splash_screen'     => $splash_screen_agent,
            'basic_settings'    => $basic_settings_agent,
        ];
        
        $app_settings = [
            'agent'         => (object) $agent_app_settings,
        ];

        return Response::success(['App settings data fetch successfully.'],[
            'base_url'              => url("/"),
            "default_image"         => files_asset_path_basename("default"),
            "screen_image_path"     => files_asset_path_basename("app-images"),
            "logo_image_path"       => files_asset_path_basename("image-assets"),
            'app_settings'          => (object)$app_settings,
        ],200);

    }
}
