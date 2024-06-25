<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Method for view profile index page
     * @return view
     */
    public function index(){
        $page_title     = "Profile Settings";

        return view('agent.sections.profile.index',compact(
            'page_title'
        ));
    }
    /**
     * Method for update profile information
     * @param Illuminate\Http\Request $request
     */
    public function update(Request $request){
        $validated = Validator::make($request->all(),[
            'firstname'     => "required|string|max:60",
            'lastname'      => "required|string|max:60",
            'country'       => "nullable|string|max:50",
            'phone'         => "nullable|string|max:20",
            'state'         => "nullable|string|max:50",
            'city'          => "nullable|string|max:50",
            'zip_code'      => "nullable|string",
            'address'       => "nullable|string|max:250",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ])->validate();

        $validated['full_mobile']   = remove_special_char($validated['phone']);
        $validated                  = Arr::except($validated,['agree','phone']);
        $validated['address']       = [
            'country'   => $validated['country'] ?? "",
            'state'     => $validated['state'] ?? "",
            'city'      => $validated['city'] ?? "",
            'zip'       => $validated['zip_code'] ?? "",
            'address'   => $validated['address'] ?? "",
        ];

        if($request->hasFile("image")) {
            $image = upload_file($validated['image'],'user-profile',auth()->user()->image);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'user-profile');
            delete_file($image['dev_path']);
            $validated['image']     = $upload_image;
        }

        try{
            auth()->user()->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Profile successfully updated!']]);
    }
}
