@extends('agent.layouts.master')

@section('breadcrumb')
    @include('agent.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("agent.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
<div class="body-wrapper">
    <form action="{{ setRoute('agent.my.sender.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="add-sender-info">
            <div class="recipient-add-title">
                <div class="form-title">
                    <h3 class="title">{{ __($page_title) }}</h3>
                </div>
                <div class="register-user-checkbox">
                    <div class="register-user-search-box">
                        <input type="text" class="search-email me-2 d-none"  placeholder="{{ __("Enter email address") }}">
                        <label class="exist"></label>
                    </div>
                    <div class="register-checkbox">
                        <input type="checkbox" class="checkbox" name="register_user" id="register-user-checkbox">
                        <label for="register-user-checkbox">{{ __("Registered User") }}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("First Name") }} <span>*</span></label>
                    <input type="text" name="first_name" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Last Name") }} <span>*</span></label>
                    <input type="text" name="last_name" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label class="email">{{ __("Email Address") }}</label>
                    <input type="email" name="email" class="form--control" placeholder="{{ __("Enter Email") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Phone Number") }} <span>*</span></label>
                    <input type="number" name="phone" class="form--control" placeholder="{{ __("Enter Number") }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group unregistered-user-country">
                    <label>{{__("Country")}}<span>*</span></label>
                    <select class="form--control select2-auto-tokenize country-select" data-placeholder="Select Country" name="country"></select>
                </div>
                <div class="col-lg-6 col-md-6 mb-20 registered-user-country d-none">
                    <label>{{ __("Enter Country") }} <span>*</span></label>
                    <input type="text" name="country_name" class="form--control country" placeholder="{{ __("Enter Country") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("State") }} <span>*</span></label>
                    <input type="text" name="state" class="form--control" placeholder="{{ __("Enter State") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("City") }} <span>*</span></label>
                    <input type="text" name="city" class="form--control" placeholder="{{ __("Enter City") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("ZIP Code") }} <span>*</span></label>
                    <input type="text" name="zip_code" class="form--control" placeholder="{{ __("Enter ZIP Code") }}">
                </div>
                <div class="col-lg-4 col-md-6 mb-20">
                    <label>{{ __("Document Type") }}</label>
                    <select class="form--control nice-select" name="id_type">
                        <option selected disabled>{{ __("Select Document") }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_NID }}">{{ global_const()::DOCUMENT_TYPE_NID }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_PASSPORT }}">{{ global_const()::DOCUMENT_TYPE_PASSPORT }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}">{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}</option>
                    </select>
                </div>
                <div class="col-lg-4 md-6 mb-20">
                    <label>{{ __("Front Part") }}</label>
                    <div class="file-holder-wrapper">
                        <input type="file" class="file-holder" name="front_part" id="fileUpload" data-height="130" accept="image/*" data-max_size="20" data-file_limit="15" multiple>
                    </div>
                </div>
                <div class="col-lg-4 md-6 mb-20">
                    <label>{{ __("Back Part") }}</label>
                    <div class="file-holder-wrapper">
                        <input type="file" class="file-holder" name="back_part" id="fileUpload" data-height="130" accept="image/*" data-max_size="20" data-file_limit="15" multiple>
                    </div>
                </div>
            </div>
            <div class="add-btn pt-30">
                <button type="submit" class="btn--base w-100">{{ __("Add Now") }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
<script>
    getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
        $(document).ready(function(){

            $(".country-select").select2();

            $("select[name=country]").change(function(){
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);
        });
</script>

<script>
    $('.checkbox').on('change',function(){
        var checkboxData        = $(this).prop('checked');
        if(checkboxData == true){
            $('.search-email').removeClass('d-none');
            $('.unregistered-user-country').addClass('d-none');
            $('.registered-user-country').removeClass('d-none');
            $('.search-email').val('');
            $('.email').html(`<label class="email">{{ __("Email Address") }} <span>*</span></label>`);
            
        }else{
            $('.search-email').addClass('d-none');
            $('.unregistered-user-country').removeClass('d-none');
            $('.registered-user-country').addClass('d-none');
            $('.exist').html('');
            $('.email').html(`<label class="email">{{ __("Email Address") }}</label>`);
            var selectedValue   = $('.search-email');
            removeReadOnlyData(selectedValue);
        }
    });

    //search email
    $('.search-email').keyup(function(){
        var searchText      = $(this).val();
        var selectedValue   = $(this);
        getUserData(searchText,selectedValue);
    });

    //function for get user data
    function getUserData(search,selectedValue){
        var getUserDataURL      = "{{ setRoute('agent.get.user.data') }}";
        $.post(getUserDataURL,{search:search,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.user_data == null || response.data.user_data == ''){
                $('.exist').html(`<label class="user-not-found-sms">User does not exist!</label>`);
                removeReadOnlyData(selectedValue);
            }else{
                if(response.data.user_data.address == null || response.data.user_data.address == "") {
                    response.data.user_data.address = {};
                    var readOnlyDataStatus          = false;
                }else{
                    var readOnlyDataStatus          = true;
                }
                
                if(response.data.user_data.full_mobile == null || response.data.user_data.full_mobile == ''){
                    var readOnlyDataStatusPhone          = false;
                }else{
                    
                    var readOnlyDataStatusPhone     = true;
                }
                
                $(selectedValue).parents("form").find("input[name=country_name]").val(response.data.user_data.address.country ?? "").attr('readonly',readOnlyDataStatus);
                $(selectedValue).parents("form").find("input[name=email]").val(response.data.user_data.email).attr('readonly',true);
                $(selectedValue).parents("form").find("input[name=phone]").val(response.data.user_data.full_mobile ?? "").attr('readonly',readOnlyDataStatusPhone);
                $(selectedValue).parents("form").find("input[name=first_name]").val(response.data.user_data.firstname).attr('readonly',true);
                $(selectedValue).parents("form").find("input[name=last_name]").val(response.data.user_data.lastname).attr('readonly',true);
                $(selectedValue).parents("form").find("input[name=address]").val(response.data.user_data.address.address ?? "").attr('readonly',readOnlyDataStatus);
                $(selectedValue).parents("form").find("input[name=state]").val(response.data.user_data.address.state ?? "").attr('readonly',readOnlyDataStatus);
                $(selectedValue).parents("form").find("input[name=city]").val(response.data.user_data.address.city ?? "").attr('readonly',readOnlyDataStatus);
                $(selectedValue).parents("form").find("input[name=zip_code]").val(response.data.user_data.address.zip ?? "").attr('readonly',readOnlyDataStatus);
                
                
                
                $('.exist').html(`<label class="valid-user">Valid User</label>`);
            }
            

        });
    }
    //remove readonly data
    function removeReadOnlyData(selectedValue){
        $(selectedValue).parents("form").find("input[name=country_name]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=email]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=phone]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=first_name]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=last_name]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=address]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=state]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=city]").val("").removeAttr("readonly");
        $(selectedValue).parents("form").find("input[name=zip_code]").val("").removeAttr("readonly");
    }
</script>
@endpush