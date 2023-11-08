<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Subscribe;
use Illuminate\Support\Str;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Models\Admin\Journal;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\ContactRequest;
use App\Models\Admin\UsefulLink;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\CountrySection;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\TransactionSetting;
use App\Models\Admin\WebJournalCategory;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller{
    public function index(){
        $transaction_settings       = TransactionSetting::where('status',true)->get();
        $sender_currency            = Currency::where('status',true)->where('sender',true)->get();
        $receiver_currency          = Currency::where('status',true)->where('receiver',true)->get();
        $sender_currency_first      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency_first    = Currency::where('status',true)->where('receiver',true)->first();
        $default_currency           = Currency::where('status',true)->where('default',true)->first();
        $banner_section_slug        = Str::slug(SiteSectionConst::BANNER_SECTION);
        $banner                     = SiteSections::getData($banner_section_slug)->first();
        $country_section            = CountrySection::where('key',SiteSectionConst::COUNTRY_SECTION)->first();
        $feature_section_slug       = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $feature                    = SiteSections::getData($feature_section_slug)->first();
        $how_its_work_slug          = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $how_its_work               = SiteSections::getData($how_its_work_slug)->first();
        $testimonial_slug           = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $testimonial                = SiteSections::getData($testimonial_slug)->first();
        $choose_section_slug        = Str::slug(SiteSectionConst::CHOOSE_US_SECTION);
        $choose                     = SiteSections::getData($choose_section_slug)->first();
        $app_download_slug          = Str::slug(SiteSectionConst::APP_DOWNLOAD_SECTION);
        $app_download               = SiteSections::getData($app_download_slug)->first();
        $journal_section_slug       = Str::slug(SiteSectionConst::JOURNAL_SECTION);
        $journal_section            = SiteSections::getData($journal_section_slug)->first();
        $journals                   = Journal::where('status',true)->get();
        $brand_section_slug         = Str::slug(SiteSectionConst::BRAND_SECTION);
        $brand                      = SiteSections::getData($brand_section_slug)->first();
        $footer_section_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                     = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug             = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe                  = SiteSections::getData($subscribe_slug)->first();
        $useful_link                = UsefulLink::where('status',true)->get();
        $message                = Session::get('message');

        return view('frontend.index',compact(
            'transaction_settings',
            'sender_currency',
            'receiver_currency',
            'default_currency',
            'banner',
            'country_section',
            'feature',
            'how_its_work',
            'testimonial',
            'choose',
            'app_download',
            'journal_section',
            'journals',
            'brand',
            'footer',
            'subscribe',
            'useful_link',
            'sender_currency_first',
            'receiver_currency_first',
            'message'
        ));
    }
    public function about(){
        $page_title           = "| About Page";
        $about_section_slug   = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $about                = SiteSections::getData($about_section_slug)->first();
        $choose_section_slug  = Str::slug(SiteSectionConst::CHOOSE_US_SECTION);
        $choose               = SiteSections::getData($choose_section_slug)->first();
        $brand_section_slug   = Str::slug(SiteSectionConst::BRAND_SECTION);
        $brand                = SiteSections::getData($brand_section_slug)->first();
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();

        return view('frontend.pages.about',compact(
            'page_title',
            'about',
            'choose',
            'brand',
            'footer',
            'subscribe',
            'useful_link',
            
        ));
    }

    public function howItWorks(){
        $page_title = "| How It Works Page";
        $how_its_work_slug    = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $how_its_work         = SiteSections::getData($how_its_work_slug)->first();
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();
        return view('frontend.pages.how-it-works',compact(
            'page_title',
            'how_its_work',
            'footer',
            'subscribe',
            'useful_link',
        ));
    }

    public function webJournal(){
        $page_title           = "| Web Journal Page";
        $journal_section_slug = Str::slug(SiteSectionConst::JOURNAL_SECTION);
        $journal_section      = SiteSections::getData($journal_section_slug)->first();
        $journals             = Journal::where('status',true)->get();
        
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();


        return view('frontend.pages.web-journal',compact(
            'page_title',
            'journal_section',
            'journals',
            'footer',
            'subscribe',
            'useful_link',
        ));
    }
    /**
     * Method for journal details
     * @param string $slug
     * @return view
     */
    public function journalDetails($slug){
        $page_title           = "| Journal Details";
        $journal              = Journal::where('slug',$slug)->first();
        if(!$journal) abort(404);
        $category             = WebJournalCategory::withCount('journals')->get();
        $recent_posts         = Journal::where('status',true)->where('slug', '!=',$slug)->get();
        $contact_section_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact              = SiteSections::getData($contact_section_slug)->first();
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();

        return view('frontend.pages.journal-details',compact(
            'page_title',
            'journal',
            'category',
            'recent_posts',
            'contact',
            'footer',
            'subscribe',
            'useful_link',
        ));

    }
    public function contact(){
        $page_title           = "| Contact Page";
        $contact_section_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact              = SiteSections::getData($contact_section_slug)->first();
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();

        return view('frontend.pages.contact-us',compact(
            'page_title',
            'contact',
            'footer',
            'subscribe',
            'useful_link',
        ));
    }
    /**
     * Method for show use-ful link page 
     * @param string $slug
     * @return view
     */
    public function link($slug){
        $link                       = UsefulLink::where('slug',$slug)->first();
        $useful_link                = UsefulLink::where('status',true)->get();
        $footer_section_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                     = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug             = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe                  = SiteSections::getData($subscribe_slug)->first();


        return view('frontend.pages.link',compact(
            'link',
            'useful_link',
            'footer',
            'subscribe'
        ));
    }
    /**
     * Method for store subscribe user
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function subscribe(Request $request){
        $validator    = Validator::make($request->all(),[
            'email'   => "required|string|email|max:255|unique:subscribes",
        ]);
        if($validator->fails()) return redirect('/#subscribe-form')->withErrors($validator)->withInput();
        $validated = $validator->validate();
        try{
            Subscribe::create([
                'email'         => $validated['email'],
                'created_at'    => now(),
            ]);
        }catch(Exception $e){
            return redirect('/#subscribe-form')->with(['error' => ['Failed to subscribe. Try again']]);
        }
        return redirect(url()->previous() .'/#subscribe-form')->with(['success' => ['Subscription successful!']]);
    }
    /**
     * Method for contact request
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function contactRequest(Request $request) {

        $validator        = Validator::make($request->all(),[
            'name'        => "required|string|max:255|unique:contact_requests",
            'email'       => "required|string|email|max:255|unique:contact_requests",
            'subject'     => "required|string",
            'message'     => "required|string|max:5000",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $validated = $validator->validate();
        try{
            ContactRequest::create([
                'name'            => $validated['name'],
                'email'           => $validated['email'],
                'subject'         => $validated['subject'],
                'message'         => $validated['message'],
                'created_at'      => now(),
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Failed to Contact Request. Try again']]);
        }
        return back()->with(['success' => ['Contact Request successfully send!']]);
    }
    /**
     * Method for apply coupon
     */
    public function couponApply(Request $request){
        
        $validator      = Validator::make($request->all(),[
            'coupon'    => 'required',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all());
        }
        $user   = auth()->user();
        $coupons = Coupon::where('status', true)->get();

        $matchingCoupon = $coupons->first(function ($coupon) use ($request) {
            return $coupon->name === $request->coupon;
        });

        if($matchingCoupon){
            if(auth()->check() == true){
                if($user->coupon_status  == 0){
                    return Response::success(['Coupon Applied Successfully'],['coupon' => $matchingCoupon],200);
                   
                }else{
                    return Response::error(['Already Applied the coupon']);
                }
            }else{
                
                return Response::error(['Please Login First']);
            }
        }else{
            return Response::error(['Coupon not found!']);
        }  
    }
}
