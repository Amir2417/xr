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
    <div class="agent-recipient-area">
        <div class="select-recipient-type">
            <h3 class="title">{{ __($page_title) }}</h3>
            <div class="row mb-20-none pt-30">
                <div class="col-lg-12 col-md-12 mb-20">
                    <label>Transaction Type <span>*</span></label>
                    <select id="transactionType" class="payment-select nice-select">                         
                        <option value="{{ global_const()::RECIPIENT_METHOD_BANK }}">{{ __(global_const()::TRANSACTION_TYPE_BANK) }}</option>
                        <option value="{{ global_const()::RECIPIENT_METHOD_MOBILE }}">{{ __(global_const()::TRANSACTION_TYPE_MOBILE) }}</option>
                        <option value="{{ global_const()::RECIPIENT_METHOD_CASH }}">{{ __(global_const()::TRANSACTION_TYPE_CASHPICKUP) }} </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-20-none pt-20">
            <div class="col-lg-6 col-md-6 mb-20">
                <label>Select Country <span>*</span></label>
                <select class="form--control nice-select">                         
                    <option value="">United State</option>
                    <option value="1">United Kingdom</option>
                    <option value="2">Australia</option>
                </select>
            </div>
            
            <div class="col-lg-6 col-md-6 mb-20">
                <label>Email Address <span>*</span></label>
                <input type="email" class="form--control" placeholder="Enter Email">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>Phone Number <span>*</span></label>
                <input type="number" class="form--control" placeholder="Enter Number">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>First Name <span>*</span></label>
                <input type="text" class="form--control" placeholder="Enter Name">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>Last Name <span>*</span></label>
                <input type="text" class="form--control" placeholder="Enter Name">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>Address <span>*</span></label>
                <input type="email" class="form--control" placeholder="Enter State">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>State <span>*</span></label>
                <input type="email" class="form--control" placeholder="Enter State">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>City <span>*</span></label>
                <input type="email" class="form--control" placeholder="Enter City">
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <label>ZIP Code <span>*</span></label>
                <input type="email" class="form--control" placeholder="Enter ZIP Code">
            </div>
            <div class="col-lg-6 col-md-6 mb-20 bank-field">
                <div id="{{ global_const()::RECIPIENT_METHOD_BANK }}-view" class="add-bank-recipient recipient-single-item">
                    <label>Select Bank <span>*</span></label>
                    <select class="form--control nice-select">
                        <option value="">American Bank</option>
                        <option value="1">Kingdom Bank</option>
                    </select>
                </div>
            </div>   
            <div class="col-lg-6 col-md-6 mb-20 mobile-field">
                <div id="{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" class="cash-pickup-recipient recipient-single-item">
                    <label>Mobile Method <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">Los Angeles</option>
                        <option value="1">New York</option>
                        <option value="2">Chicago</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-20 cash-pickup-field">
                <div id="{{ global_const()::RECIPIENT_METHOD_CASH }}-view" class="cash-pickup-recipient recipient-single-item">
                    <label>Pickup Point <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">Los Angeles</option>
                        <option value="1">New York</option>
                        <option value="2">Chicago</option>
                    </select>
                </div>
            </div>
        </div>
        
        {{-- <div id="{{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" class="add-mobile-recipient recipient-single-item">
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Select Country <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">United State</option>
                        <option value="1">United Kingdom</option>
                        <option value="2">Australia</option>
                    </select>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Email Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter Email">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Phone Number <span>*</span></label>
                    <input type="number" class="form--control" placeholder="Enter Number">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>First Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Last Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>State <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>City <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter City">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>ZIP Code <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter ZIP Code">
                </div>
            </div>
            <div class="add-btn pt-30">
                <a href="#" type="button" class="btn--base w-100">Add Now</a>
            </div>
        </div> --}}
        <div class="add-btn pt-30">
            <a href="#" type="button" class="btn--base w-100">Add Now</a>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
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

        if(transactionType == cashPickup){
            $('.bank-field').addClass('d-none');
            $('.mobile-field').addClass('d-none');
            $('.cash-pickup-field').removeClass('d-none');
        }else if(transactionType == bankTransfer){
            $('.bank-field').removeClass('d-none');
        }else{
            $('.bank-field').addClass('d-none');
            $('.cash-pickup-field').addClass('d-none');
            $('.mobile-field').removeClass('d-none');
        }
    })
</script>
@endpush