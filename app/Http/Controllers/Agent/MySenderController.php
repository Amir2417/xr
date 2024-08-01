<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Agent\MySender;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MySenderController extends Controller
{
    /**
     * Method for view my sender page
     * @return view`
     */
    public function index(){
        $page_title     = 'My Sender';
        $my_senders     = MySender::auth()->orderBy('id','desc')->paginate(15);

        return view('agent.sections.my-sender.index',compact(
            'page_title',
            'my_senders'
        ));
    }
    /**
     * Method for create new my sender
     * @return view
     */
    public function create(){
        $page_title     = 'Add New Sender';

        return view('agent.sections.my-sender.create',compact(
            'page_title'
        ));
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
            'country'           =>'required_if:register_user,false',
            'country_name'      =>'required_if:register_user,true',
            'city'              =>'required',
            'state'             =>'required',
            'zip_code'          =>'required',
            'phone'             =>'required',
            'id_type'           =>'nullable',
            'front_part'        =>'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            'back_part'         =>'nullable|image|mimes:png,jpg,webp,jpeg,svg',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated              = $validator->validated();
        $validated['slug']      = Str::uuid();
        $validated['agent_id']  = auth()->user()->id;
        $validated['country']   = $validated['country_name'] ?? $validated['country'];
        if($request->hasFile('front_part') || $request->hasFile('back_part')){
            $validated['front_part'] = $this->imageValidate($request,"front_part",null);
            $validated['back_part'] = $this->imageValidate($request,"back_part",null);  
        }

        if(MySender::auth()->where('email',$validated['email'])->orWhere('phone',$validated['phone'])->exists()){
            throw ValidationException::withMessages([
                'name'  => "Sender already exists!",
            ]);
        }

        try{
            MySender::create($validated);
        }catch(Exception $e){
            return back()->withErrors(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('agent.my.sender.index')->with(['success' => ['My Sender Created Successfully.']]);

    }
    /**
     * Method for edit my sender information
     * @param $slug
     */
    public function edit($slug){
        $page_title     = 'Edit Sender';
        $my_sender      = MySender::auth()->where('slug',$slug)->first();
        if(!$my_sender){
            return back()->with(['error' => ['Sorry! Data not found.']]);
        }
        return view('agent.sections.my-sender.edit',compact(
            'page_title',
           'my_sender'
        ));
    }
    /**
     * Method for update my sender information
     * @param $slug
     * @param Illuminate\Http\Request $request
     */
    public function update(Request $request,$slug){
        $my_sender          = MySender::auth()->where('slug',$slug)->first();
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
            'front_part'    => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            'back_part'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated              = $validator->validate();
        $validated['agent_id']  = auth()->user()->id;
        if($request->hasFile('front_part') || $request->hasFile('back_part')){
            $validated['front_part'] = $this->imageValidate($request,"front_part",null);
            $validated['back_part'] = $this->imageValidate($request,"back_part",null);  
        }

        if(MySender::auth()->whereNot('id',$my_sender->id)->where(function($q) use($validated){
            $q->where('email',$validated['email'])->orWhere('phone',$validated['phone']);
        })->exists()){
            throw ValidationException::withMessages([
                'name'  => "Sender already exists!",
            ]);
        }
        
        try{
            $my_sender->update($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('agent.my.sender.index')->with(['success' => ['My Sender Information Updated Successfully.']]);
    }
    /**
     * Method for delete sender information
     * @param $slug
     * @param Illuminate\Http\Request $request
     */
    public function delete($slug,Request $request){
        $my_sender = MySender::auth()->where('slug',$slug)->first();
        if(!$my_sender){
            return back()->with(['error' => ['Sorry! Data not found.']]);
        }
        try{
            $my_sender->delete();
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['My Sender Information Deleted Successfully.']]);
    }
    /**
     * Method for validate image 
     */
    public function imageValidate($request,$input_name,$old_image = null) {
        if($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name),[
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request,$input_name);
            $upload = upload_files_from_path_dynamic($image,'my-sender',$old_image);
            return $upload;
        }
        return false;
    }
}
