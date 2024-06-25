@extends('agent.layouts.user_auth')


@section('content')
<section class="kyc-verifaction">
    <div class="container">
        <div class="kyc-form">
            <div class="kyc-header pb-40">
               <h3 class="title">{{ __("KYC Form") }}</h3>
               <p>{{ __("Please input all the fild for sign in to your account to get access to your dashboard.") }}</p>
            </div>
            <div class="row mb-20-none">
                <div class="col-xl-4 col-lg-4 col-md-4 form-group">
                    @include('admin.components.form.input',[
                        'name'          => "firstname",
                        'label'         => __('First Name'),
                        'placeholder'   => __("First Name"),
                        'value'         => old("firstname"),
                    ])
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 form-group">
                    @include('admin.components.form.input',[
                        'name'          => "lastname",
                        'label'         => __('Last Name'),
                        'placeholder'   => __("Last Name"),
                        'value'         => old("lastname"),
                    ])
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 form-group">
                    @include('admin.components.form.input',[
                        'name'          => "store_name",
                        'label'         => __('Store Name'),
                        'placeholder'   => __("Store Name"),
                        'value'         => old("store_name"),
                    ])
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Email Address") }}</label>
                     <input type="email" name="email" class="form--control" placeholder="Email Address">
                 </div>
                 <div class="col-lg-4 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Phone Number") }}</label>
                     <input type="text" name="phone" class="form--control" placeholder="Phone Number">
                 </div>
                 <div class="col-lg-4 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Select Country") }}</label>
                     <select name="country" class="form--control country-select select2-auto-tokenize" > </select>
                 </div>
                 <div class="col-lg-6 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Select City") }}</label>
                     <select class="form--control select2-basic city-select" name="city">
                                        
                     </select>
                 </div>
                 <div class="col-lg-6 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Zip Code") }}</label>
                     <input type="text" class="form--control" placeholder="Zip Code">
                 </div>
                 @if($basic_settings->agent_kyc_verification)
                    @include('agent.components.register-kyc',compact("kyc_fields"))
                @endif
                 <div class="form-group show_hide_password col-lg-6 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Password") }}</label>
                     <input type="password" class="form--control" placeholder="Enter Password..">
                     <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                 </div>
                 <div class="form-group show_hide_password-2 col-lg-6 col-md-6 col-sm-6 mb-20">
                     <label>{{ __("Confirm Password") }}</label>
                     <input type="password" class="form--control" placeholder="Enter Password..">
                     <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                 </div>
            </div>
             <div class="form-group text-center pt-5">
                <a href="agent-dashboard.html" class="btn--base btn w-100">{{ __("Continue") }}</a>
             </div>
             <div class="footer-text">
                 <p class="d-block text-center mt-3 create-acc">
                     &mdash; {{ __("Back To") }}
                     <a href="{{ setRoute('index') }}" class="text--base">{{ __("Home") }}</a>
                     &mdash;
                 </p>
             </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
    $(document).ready(function(){
        $(".country-select").select2();
        $("select[name=country]").on('change',function(){
            var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
            placePhoneCode(phoneCode);
            var countryName = $(this).val();
            getCityName(countryName);
        });
        countrySelect(".country-select",$(".country-select").siblings(".select2"));
    });
    // function for get city
    function getCityName(countryName){
        var getCityURL = "{{ setRoute('get.city.name') }}";
        $(".city-select").html('');
        $.post(getCityURL,{countryName:countryName,_token:"{{ csrf_token() }}"},function(response){
            $.each(response.data.city,function(index,item){
                $(".city-select").append('<option value="' + item.name + '" ' + ' >' + item.name + '</option>');
            });
        });
    }

</script>

@endpush
