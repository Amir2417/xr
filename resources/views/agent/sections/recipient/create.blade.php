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
    <form action="{{ setRoute('agent.recipient.store') }}" method="post">
        @csrf
        <div class="agent-recipient-area">
            <div class="select-recipient-type">
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
                <div class="row mb-20-none pt-30">
                    <div class="col-lg-12 col-md-12 mb-20">
                        <label>{{ __("Transaction Type") }} <span>*</span></label>
                        <select id="transactionType" class="payment-select nice-select" name="method">                         
                            <option value="{{ global_const()::RECIPIENT_METHOD_BANK }}">{{ __(global_const()::TRANSACTION_TYPE_BANK) }}</option>
                            <option value="{{ global_const()::RECIPIENT_METHOD_MOBILE }}">{{ __(global_const()::TRANSACTION_TYPE_MOBILE) }}</option>
                            <option value="{{ global_const()::RECIPIENT_METHOD_CASH }}">{{ __(global_const()::TRANSACTION_TYPE_CASHPICKUP) }} </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20 unregistered-user-country">
                    <label>{{ __("Select Country") }} <span>*</span></label>
                    <select class="form--control select2-basic" name="country">
                        @foreach ($receiver_country ?? [] as $item)
                            <option value="{{ $item->country }}">{{ $item->country }}</option>
                        @endforeach    
                    </select>
                    
                </div>            
                <div class="col-lg-6 col-md-6 mb-20 registered-user-country d-none">
                    <label>{{ __("Enter Country") }} <span>*</span></label>
                    <input type="text" name="country_name" class="form--control country" placeholder="{{ __("Enter Country") }}">
                </div>            
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Email Address") }} <span>*</span></label>
                    <input type="email" name="email" class="form--control" placeholder="{{ __('Enter Email') }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Phone Number") }} <span>*</span></label>
                    <input type="number" name="phone" class="form--control" placeholder="{{ __("Enter Number") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("First Name") }} <span>*</span></label>
                    <input type="text" name="first_name" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Last Name") }} <span>*</span></label>
                    <input type="text" name="last_name" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Address") }} <span>*</span></label>
                    <input type="text" name="address" class="form--control" placeholder="{{ __("Enter Address") }}">
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
                <div class="col-lg-6 col-md-6 mb-20 bank-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_BANK }}-view" class="add-bank-recipient recipient-single-item">
                        <label>{{ __("Select Bank") }} <span>*</span></label>
                        <select class="form--control select2-basic bank-list" name="bank_name">
                        </select>
                    </div>
                </div>   
                <div class="col-lg-12 col-md-12 mb-20 bank-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_BANK }}-view" class="add-bank-recipient recipient-single-item">
                        <label>{{ __("IBAN Number") }} <span>*</span></label>
                        <input type="text" name="iban_number" class="form--control" placeholder="{{ __("Enter Account Number") }}">
                    </div>
                </div>   
                <div class="col-lg-6 col-md-6 mb-20 mobile-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" class="cash-pickup-recipient recipient-single-item">
                        <label>{{ __("Mobile Method") }} <span>*</span></label>
                        <select class="form--control select2-basic mobile-list" name="mobile_name">   
    
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 mb-20 mobile-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" class="cash-pickup-recipient recipient-single-item">
                        <label>{{ __("Account Number") }} <span>*</span></label>
                        <input type="text" name="account_number" class="form--control" placeholder="{{ __("Enter Account Number") }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-20 cash-pickup-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_CASH }}-view" class="cash-pickup-recipient recipient-single-item">
                        <label>{{ __("Pickup Point") }} <span>*</span></label>
                        <select class="form--control select2-basic pickup-point" name="pickup_point"> 
    
                        </select>
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
    $(document).ready(function(){
        var country             = $("select[name=country]").val();
        var transactionType     = $("select[name=method]").val();
        var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
        var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";

        if(transactionType == bankTransfer){
            getBankList(transactionType,country);
        }
    });

    $('.checkbox').on('change',function(){
        var checkboxData        = $(this).prop('checked');
        if(checkboxData == true){
            $('.search-email').removeClass('d-none');
            $('.unregistered-user-country').addClass('d-none');
            $('.registered-user-country').removeClass('d-none');
            $('.search-email').val('');
            $('.bank-list').html('');
        }else{
            $('.search-email').addClass('d-none');
            $('.unregistered-user-country').removeClass('d-none');
            $('.registered-user-country').addClass('d-none');
            $('.exist').html('');
            var selectedValue   = $('.search-email');
            removeReadOnlyData(selectedValue);

            var transactionType     = $('.payment-select').val();
            var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";
            var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
            var country             = $("select[name=country]").val();

            if(transactionType == cashPickup){
                $('.bank-field').addClass('d-none');
                $('.mobile-field').addClass('d-none');
                $('.cash-pickup-field').removeClass('d-none');
                getPickupPointsList(transactionType,country);
            }else if(transactionType == bankTransfer){
                getBankList(transactionType,country);
                $('.bank-field').removeClass('d-none');
            }else{
                $('.bank-field').addClass('d-none');
                $('.cash-pickup-field').addClass('d-none');
                $('.mobile-field').removeClass('d-none');
                getMobileMethodList(transactionType,country);
            }
        }
    });

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
                
                var transactionType     = $("select[name=method]").val();
                var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
                var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";
                var countryName         = $(".country").val();
                if(countryName != null || countryName != ''){
                    var country             = countryName ?? '';

                    if(transactionType == bankTransfer){
                        getBankList(transactionType,country);
                    }else if(transactionType == cashPickup){
                        getPickupPointsList(transactionType,country);
                    }else{
                        getMobileMethodList(transactionType,country);
                    }
                }
                
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

    makePayment('.payment-select');

    function makePayment(element) {
        
        $(element).change(function(){
            showHidePaymentSection($(this));
        });

        
        $(document).ready(function(){
            showHidePaymentSection($(element));
        });

        
        function showHidePaymentSection(element) {
            
            $(".recipient-single-item").hide();

            
            var selectedMethod = $(element).val();
            $("#" + selectedMethod + "-view").show();

            
            if (selectedMethod === "{{ global_const()::RECIPIENT_METHOD_BANK }}") {
                $(".bank-field").show(); 
                $(".mobile-field").hide(); 
                $(".cash-pickup-field").hide();
                
                
                $("#{{ global_const()::RECIPIENT_METHOD_BANK }}-view input[name='iban_number']").closest('.recipient-single-item').show();
            } else if (selectedMethod === "{{ global_const()::RECIPIENT_METHOD_MOBILE }}") {
                $(".bank-field").hide(); 
                $(".mobile-field").show(); 
                $(".cash-pickup-field").hide();
                $("#{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view input[name='account_number']").closest('.recipient-single-item').show();
            } else if (selectedMethod === "{{ global_const()::RECIPIENT_METHOD_CASH }}") {
                $(".bank-field").hide(); 
                $(".mobile-field").hide(); 
                $(".cash-pickup-field").show(); 
            } 
        }
    }


    $('.payment-select').on('change',function(){

        var transactionType     = $(this).val();
        var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";
        var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
        if($('.checkbox').prop('checked') == true){
            var country             = $(".country").val();
        }else{
            var country             = $("select[name=country]").val();
        }
        
        if(transactionType == cashPickup){
            $('.bank-field').addClass('d-none');
            $('.mobile-field').addClass('d-none');
            $('.cash-pickup-field').removeClass('d-none');
            getPickupPointsList(transactionType,country);
        }else if(transactionType == bankTransfer){
            getBankList(transactionType,country);
            $('.bank-field').removeClass('d-none');
        }else{
            $('.bank-field').addClass('d-none');
            $('.cash-pickup-field').addClass('d-none');
            $('.mobile-field').removeClass('d-none');
            getMobileMethodList(transactionType,country);
        }
    });

    //country wise name get
    $("select[name=country]").on('change',function(){
        var country             = $(this).val();
        var transactionType     = $("select[name=method]").val();
        var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
        var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";

        if(transactionType == bankTransfer){
            getBankList(transactionType,country);
        }else if(transactionType == cashPickup){
            getPickupPointsList(transactionType,country);
        }else{
            getMobileMethodList(transactionType,country);
        }

    });
    //country wise name get
    $(".country").keyup(function(){
        var country             = $(this).val();
        var transactionType     = $("select[name=method]").val();
        var bankTransfer        = "{{ global_const()::RECIPIENT_METHOD_BANK }}";
        var cashPickup          = "{{ global_const()::RECIPIENT_METHOD_CASH }}";

        if(transactionType == bankTransfer){
            getBankList(transactionType,country);
        }else if(transactionType == cashPickup){
            getPickupPointsList(transactionType,country);
        }else{
            getMobileMethodList(transactionType,country);
        }

    });

    // function for get bank list
    function getBankList(transactionType,country){
        var bankListURL     = "{{ setRoute('agent.recipient.get.bank.list') }}";
        $('.bank-list').html('');
        $.post(bankListURL,{country:country,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.bank_list == null || response.data.bank_list == ''){
                $('.bank-list').html('<option value="" disabled>No Bank Aviliable</option>');
            }else{
                $('.bank-list').html('<option value="" disabled>Select Bank</option>');
            }
            $.each(response.data.bank_list,function(key,value){
                $('.bank-list').append('<option value="'+ value.name + '"' + '>' + value.name + '</option>');
            });
        });
    }

    //function for get pickup points list
    function getPickupPointsList(transactionType,country){
        var pickupPointURL  = "{{ setRoute('agent.recipient.get.pickup.point.list') }}";
        $('.pickup-point').html('');
        $.post(pickupPointURL,{country:country,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.pickup_points == null || response.data.pickup_points == ''){
                $('.pickup-point').html(`<option disabled>Pickup Points not available</option>`);
            }else{
                $('.pickup-point').html(`<option disabled>Select Pickup Point</option>`);
            }
            $.each(response.data.pickup_points,function(key,item){
                $('.pickup-point').append(`<option value="${item.address}">${item.address}</option>`);
            });
        });
    }

    //function for get mobile money list
    function getMobileMethodList(transactionType,country){
        var mobileMoneyListURL  = "{{ setRoute('agent.recipient.get.mobile.method.list') }}";
        $('.mobile-list').html('');
        $.post(mobileMoneyListURL,{country:country,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.mobile_methods == null || response.data.mobile_methods == ''){
                $('.mobile-list').html(`<option disabled>Mobile Method not available</option>`);
            }else{
                $('.mobile-list').html(`<option disabled>Select Mobile Method</option>`);
            }
            $.each(response.data.mobile_methods,function(key,value){
                $('.mobile-list').append(`<option value="${value.name}">${value.name}</option>`);
            });
        });
    }
</script>
@endpush