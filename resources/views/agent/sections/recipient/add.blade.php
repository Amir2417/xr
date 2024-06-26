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
                <h3 class="title">{{ __($page_title) }}</h3>
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
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Select Country") }} <span>*</span></label>
                    <select class="form--control select2-basic" name="country">
                        @foreach ($receiver_country ?? [] as $item)
                            <option value="{{ $item->country }}">{{ $item->country }}</option>
                        @endforeach    
                    </select>
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
                <div class="col-lg-6 col-md-6 mb-20 mobile-field">
                    <div id="{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" class="cash-pickup-recipient recipient-single-item">
                        <label>{{ __("Mobile Method") }} <span>*</span></label>
                        <select class="form--control select2-basic mobile-list" name="mobile_name">   
    
                        </select>
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
    makePayment('.payment-select');
    function makePayment(element) {
        $(element).change(function(){
            showHidePaymentSection($(this));
        });

        $(document).ready(function(){
            showHidePaymentSection(element);
        });


        function showHidePaymentSection(element) {
            $(".recipient-single-item").hide();
            $("#"+$(element).val()+"-view").show();
        }
    }

    $('.payment-select').on('change',function(){
        var transactionType     = $(this).val();
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