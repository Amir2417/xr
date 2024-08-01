<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Agent\MySender;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MySenderController extends Controller
{
    /**
     * Method for show my sender data 
     * @return response
     */
    public function index(){
        $my_senders     = MySender::auth()->orderBy('id','desc')->get()
                            ->map(function($data){
                return [
                    'slug'          => $data->slug,
                    'name'          => $data->fullname,
                    'email'         => $data->email,
                    'country'       => $data->country,
                    'zip_code'      => $data->zip_code
                ];
        });

        return Response::success(['Sender data fetch successfully.'],[
            'my_senders'        => $my_senders
        ],200);
    }
    /**
     * Method for check user
     * @param Illuminate\Http\Request $request
     */
    public function checkUser(Request $request){
        $validator          = Validator::make($request->all(),[
            'email'         => 'required|email'
        ]);
        if($validator->fails()) return Response::validation(['error' => $validator->errors()->all()]);

        $validated          = $validator->validate();
        $user_data          = User::where('email',$validated['email'])->first();
        if(!$user_data) return Response::error(['User not exists!'],[],400);
        $data               = [
            'first_name'    => $user_data->firstname,
            'last_name'     => $user_data->lastname,
            'email'         => $user_data->email,
            'phone'         => $user_data->full_mobile,
            'country'       => $user_data->address->country,
            'state'         => $user_data->address->state,
            'city'          => $user_data->address->city,
            'zip_code'      => $user_data->address->zip,
        ];

        return Response::success(['User data fetch successfully.'],[
            'user_data'     => $data
        ],200);
    }
    /**
     * Method for store new my sender information
     * @param Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator  = Validator::make($request->all(),[
            'first_name'        =>'required',
            'last_name'         =>'required',
            'email'             =>'required_if:register_user,true',
            'country'           =>'required',
            'city'              =>'required',
            'state'             =>'required',
            'zip_code'          =>'required',
            'phone'             =>'required',
            'id_type'           =>'nullable',
            'front_part'        =>'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            'back_part'         =>'nullable|image|mimes:png,jpg,webp,jpeg,svg',
        ]);
        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }
        $validated              = $validator->validated();
        $validated['slug']      = Str::uuid();
        $validated['agent_id']  = auth()->user()->id;
        $validated['country']   = $validated['country'];
        
        if($request->hasFile('front_part')){
            $image = upload_file($validated['front_part'],'my-sender');
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'my-sender');
            chmod(get_files_path('my-sender') . '/' . $upload_image, 0644);
            $validated['front_part']     = $upload_image;
            
        }
        if($request->hasFile('back_part')){
            $image = upload_file($validated['back_part'],'my-sender');
            
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'my-sender');
            chmod(get_files_path('my-sender') . '/' . $upload_image, 0644);
            $validated['back_part']     = $upload_image;
            
        }
        $data = MySender::auth()->where('email',$request->email)->orWhere('phone',$request->phone)->exists();
        if($data){
            return Response::error(['Sender already exists!'],[],400);
        }
        try{
            $sender = MySender::create($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Sender information stored successfully.'],[
            'sender'    => $sender
        ],200);
    }
    /**
     * Method for update sender information
     * @param Illuminate\Http\Request $request
     */
    public function update(Request $request){
        $my_sender          = MySender::auth()->where('slug',$request->slug)->first();
        if(!$my_sender){
            return back()->with(['error' => ['Sorry! Data not found.']]);
        }
        $validator          = Validator::make($request->all(),[
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'nullable',
            'phone'         => 'required',
            'country'       => 'required',
            'state'         => 'required',
            'city'          => 'required',
            'zip_code'      => 'required',
            'id_type'       => 'nullable',
            'front_part'    => 'nullable',
            'back_part'     => 'nullable',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated              = $validator->validate();
        $validated['agent_id']  = auth()->user()->id;
        
        if($request->hasFile('front_part')){
            $image = upload_file($validated['front_part'],'my-sender');
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'my-sender');
            delete_file($image['dev_path']);
            chmod(get_files_path('my-sender') . '/' . $upload_image, 0644);
            $validated['front_part']     = $upload_image;
            
        }
        if($request->hasFile('back_part')){
            $image = upload_file($validated['back_part'],'my-sender');
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'my-sender');
            delete_file($image['dev_path']);
            chmod(get_files_path('my-sender') . '/' . $upload_image, 0644);
            $validated['back_part']     = $upload_image;
        }

        $data = MySender::auth()->whereNot('id',$my_sender->id)->where(function($q) use($validated){
            $q->where('email',$validated['email'])->orWhere('phone',$validated['phone']);
        })->exists();
        if($data){
            return Response::error(['Sender already exists!'],[],400);
        }
        
        try{
            $my_sender->update($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Sender information updated successfully.'],[
            'sender'    => $my_sender
        ],200);
    }
    /**
     * Method for delete sender information
     * @param Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $my_sender          = MySender::auth()->where('slug',$request->slug)->first();
        if(!$my_sender){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        try{
            $my_sender->delete();
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Sender information deleted successfully.'].[],200);
    }
}
