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
    <form action="{{ setRoute('agent.recipient.update',$recipient->slug) }}" method="post">
        @csrf
        <div class="agent-recipient-area">
            <div class="select-recipient-type">
                <div class="recipient-add-title">
                    <div class="form-title">
                          <h3 class="title">{{ __($page_title) }}</h3>
                    </div>
                    
                </div>
                <div class="row mb-20-none pt-30">
                    <div class="col-lg-12 col-md-12 mb-20">
                        <label>{{ __("Transaction Type") }} <span>*</span></label>
                        <select id="transactionType" class="payment-select nice-select" name="method">                         
                            <option value="{{ $recipient->method }}">{{ __($recipient->method) }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-20-none pt-20">          
                <div class="col-lg-6 col-md-6 mb-20 registered-user-country">
                    <label>{{ __("Country") }} <span>*</span></label>
                    <input type="text" name="country" class="form--control country" placeholder="{{ __("Enter Country") }}" value="{{ old('country',$recipient->country) }}">
                </div>            
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Email Address") }} <span>*</span></label>
                    <input type="email" name="email" class="form--control" placeholder="{{ __('Enter Email') }}" value="{{ old('email',$recipient->email) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Phone Number") }} <span>*</span></label>
                    <input type="number" name="phone" class="form--control" placeholder="{{ __("Enter Number") }}" value="{{ old('phone',$recipient->phone) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("First Name") }} <span>*</span></label>
                    <input type="text" name="first_name" class="form--control" placeholder="{{ __("Enter Name") }}" value="{{ old('phone',$recipient->first_name) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Last Name") }} <span>*</span></label>
                    <input type="text" name="last_name" class="form--control" placeholder="{{ __("Enter Name") }}" value="{{ old('phone',$recipient->last_name) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Address") }} <span>*</span></label>
                    <input type="text" name="address" class="form--control" placeholder="{{ __("Enter Address") }}" value="{{ old('phone',$recipient->address) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("State") }} <span>*</span></label>
                    <input type="text" name="state" class="form--control" placeholder="{{ __("Enter State") }}" value="{{ old('phone',$recipient->state) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("City") }} <span>*</span></label>
                    <input type="text" name="city" class="form--control" placeholder="{{ __("Enter City") }}" value="{{ old('phone',$recipient->city) }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("ZIP Code") }} <span>*</span></label>
                    <input type="text" name="zip_code" class="form--control" placeholder="{{ __("Enter ZIP Code") }}" value="{{ old('phone',$recipient->zip_code) }}">
                </div>
                @if ($recipient->method == global_const()::TRANSACTION_TYPE_BANK)
                    <div class="col-lg-6 col-md-6 mb-20">
                        <div class="">
                            <label>{{ __("Select Bank") }} <span>*</span></label>
                            <select class="form--control select2-basic bank-list" name="bank_name">
                            </select>
                        </div>
                    </div>   
                    <div class="col-lg-12 col-md-12 mb-20">
                        <div class="">
                            <label>{{ __("IBAN Number") }} <span>*</span></label>
                            <input type="text" name="iban_number" class="form--control" placeholder="{{ __("Enter Account Number") }}" value="{{ old('iban_number',$recipient->iban_number) }}">
                        </div>
                    </div>
                @elseif ($recipient->method == global_const()::TRANSACTION_TYPE_MOBILE)
                    <div class="col-lg-6 col-md-6 mb-20 mobile-field">
                        <div>
                            <label>{{ __("Mobile Method") }} <span>*</span></label>
                            <select class="form--control select2-basic mobile-list" name="mobile_name">   
        
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 mb-20 mobile-field">
                        <div>
                            <label>{{ __("Account Number") }} <span>*</span></label>
                            <input type="text" name="account_number" class="form--control" placeholder="{{ __("Enter Account Number") }}" value="{{ old('account_number',$recipient->account_number) }}">
                        </div>
                    </div>
                @else
                    <div class="col-lg-6 col-md-6 mb-20 cash-pickup-field">
                        <div>
                            <label>{{ __("Pickup Point") }} <span>*</span></label>
                            <select class="form--control select2-basic pickup-point" name="pickup_point"> 
        
                            </select>
                        </div>
                    </div>
                @endif
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
        var country             = $(".country").val();
        var transactionType     = $("select[name=method]").val();
        var bankTransfer        = "{{ global_const()::TRANSACTION_TYPE_BANK }}";
        var cashPickup          = "{{ global_const()::TRANSACTION_TYPE_CASHPICKUP }}";

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
        var bankTransfer        = "{{ global_const()::TRANSACTION_TYPE_BANK }}";
        var cashPickup          = "{{ global_const()::TRANSACTION_TYPE_CASHPICKUP }}";
        $('.bank-list').html('');
        $('.mobile-list').html('');
        $('.pickup-point').html('');
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
                var bankName        = "{{ $recipient->bank_name }}";
                var selectedBank    = (bankName === value.name) ? 'selected' : ''; 
                $('.bank-list').append(`<option value="${value.name}" ${selectedBank}>${value.name}</option>`);
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
                var pickupPointName         = "{{ $recipient->pickup_point }}";
                var selectedPickupPoint     = (pickupPointName === item.address) ? 'selected' : '';
                $('.pickup-point').append(`<option value="${item.address}" ${selectedPickupPoint}>${item.address}</option>`);
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
                var mobileName      = "{{ $recipient->mobile_name }}";
                var selectedMobile  = (mobileName === value.name) ? 'selected' : '';
                $('.mobile-list').append(`<option value="${value.name}" ${selectedMobile}>${value.name}</option>`);
            });
        });
    }
</script>
@endpush