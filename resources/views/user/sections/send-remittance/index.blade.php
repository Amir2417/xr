@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Send Remittance")])
@endsection

@section('content')

<div class="body-wrapper">
    <div class="row mb-20">
        <div class="col-xl-7 col-lg-7 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{__("Send Remittance")}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form method="POST" action="{{ setRoute('user.send.remittance.store') }}">
                             @csrf
                            <div class="col-lg-12">
                                <div class="banner-form">
                                    <div class="top mb-20">
                                        <p>{{__("Exchange Rate")}}</p>
                                        {{-- <h3 class="title">{{ get_amount($sender_currency->rate) }} {{ $sender_currency->code}} = {{ get_amount($receiver_currency->rate) }} {{ $receiver_currency->code}}</h3> --}}
                                    </div>
                                    <div class="col-12 pb-20">
                                        <div class="row">
                                            <h3 class="fs-6">{{__("You send exactly")}}</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="send_money" type="text" class="form--control w-100 number-input" name="send_money">
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="sender_currency">
                                                                <span class="custom-currency">--</span>
                                                            </div>
                                                        </div>
                                                        <div class="custom-select-wrapper">
                                                            <div class="custom-select-search-box">
                                                                <div class="custom-select-search-wrapper">
                                                                    <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                                                    <input type="text" class="form--control custom-select-search" placeholder="Search currency...">
                                                                </div>
                                                            </div>
                                                            <div class="custom-select-list-wrapper">
                                                                <ul class="custom-select-list">
                                                                    @foreach ($sender_currency as $item)
                                                                        <li class="custom-option" data-item='{{ json_encode($item) }}'>
                                                                            <img src="{{ get_image($item->flag,'currency-flag') }}" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">{{ $item->name }}</span>
                                                                            <span class="custom-currency">{{ $item->code }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                        <p><i class="las la-dot-circle"></i> {{ __("Fees & Charge") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="fees" id="charge">
                                            <p id="fees"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> {{ __("Amount will convert") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="convert_amount" id="convert--amount">
                                            <p id="convert-amount"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between pb-10">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> {{ __("Total Payable Amount") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="payable" id="payable--amount">
                                            <p id="payable"> </p>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4 pb-10">
                                        <div class="row">
                                            <h3 class="fs-6">{{__("Recipient gets")}}</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="receive_money" type="text" class="form--control w-100 number-input" name="receive_money">
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="receiver_currency">
                                                                <span class="custom-currency">--</span>
                                                            </div>
                                                        </div>
                                                        <div class="custom-select-wrapper">
                                                            <div class="custom-select-search-box">
                                                                <div class="custom-select-search-wrapper">
                                                                    <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                                                    <input type="text" class="form--control custom-select-search" placeholder="Search currency...">
                                                                </div>
                                                            </div>
                                                            <div class="custom-select-list-wrapper">
                                                                <ul class="custom-select-list">
                                                                    @foreach ($receiver_currency as $item)
                                                                        <li class="custom-option" data-item='{{ json_encode($item) }}'>
                                                                            <img src="{{ get_image($item->flag,'currency-flag') }}" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">{{ $item->name }}</span>
                                                                            <span class="custom-currency">{{ $item->code }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group transaction-type">
                                        <div class="transaction-title">
                                            <label>{{__("Receive Method")}}</label>
                                        </div>
                                        <div class="transaction-type-select">
                                            <select class="nice-select trx-type-select" name="type">
                                                @foreach ($transaction_settings as $item) 
                                                    <option class="custom-option" value="{{ $item->title }}" data-item='{{ json_encode($item) }}'>{{ $item->title ?? ''}}</option>
                                                @endforeach
                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-footer-content mt-10-none mb-20">
                                        <div class="note send-form-footer-note" id="feature-list">
                                            <div class="left-side">
                                                <p><i class="las la-dot-circle"></i></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn--base btn--base-e text-center w-100 ">{{ __("Send Now") }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-5 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Summary") }}</h4>
                </div>
                <div class="card-body">
                    <div class="preview-list-wrapper">
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{__("Sending Amount")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--success" id="sending_amount"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-exchange-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{__("Exchange Rate")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                {{-- <span>{{ get_amount($sender_currency->rate) }} {{ $sender_currency->code}} = {{ get_amount($receiver_currency->rate) }} {{ $receiver_currency->code}}</span> --}}
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-battery-half"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{__("Total Fees & Charges")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--warning" id="fees-and-charges"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="lab la-get-pocket"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Amount We’ll Convert") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--danger" id="convert_amount"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span><b>{{__("Will Get Amount")}}</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--info"><b id="get-amount"></b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $(".ad-select .custom-select-search").keyup(function(){
            var searchText = $(this).val().toLowerCase();
            var itemList =  $(this).parents(".ad-select").find(".custom-option");
            $.each(itemList,function(index,item){
                var text = $(item).find(".custom-currency").text().toLowerCase();
                var country = $(item).find(".custom-country").text().toLowerCase();
                var match = text.match(searchText);
                var countryMatch = country.match(searchText);
                if(match == null && countryMatch == null) {
                    $(item).addClass("d-none");
                }else {
                    $(item).removeClass("d-none");
                }
            });
        });
</script>
<script>

    $(document).ready(function () {
        var enterAmount = $('#send_money').val(100);
        run();
        runReverse();
    });
    $('.trx-type-select').on('change',function(){
        run();
        runReverse();
    });

    function run(){
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        
        var enterAmount = $('#send_money').val();
 
        $("#feature-list").html(selectedType.feature_text);
        

        function getCharges(selectedType,enterAmount){
            let findPercentCharge = enterAmount / 100;

            let fixedCharge = selectedType.fixed_charge;
            let percentCharge = selectedType.percent_charge;
            
            let totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            let payableAmount = enterAmount + totalCharge;
            
            if(enterAmount == "") enterAmount = 0;
            if (enterAmount != 0) {
                let convertAmount = enterAmount;
                

                let receivedMoney = convertAmount ;
                
                var intervals = selectedType.intervals;
                
                $.each(intervals,function(index,item){
                    
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        
                        totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        
                        convertAmount = parseFloat(enterAmount);

                        payableAmount = parseFloat(enterAmount) + totalCharge;
                        receivedMoney = convertAmount ;
                    }
                });


                $('#charge').val(totalCharge.toFixed(2));
                $('#convert--amount').val(convertAmount.toFixed(2));
                $('#payable--amount').val(payableAmount.toFixed(2));
                $('#payable').text(payableAmount.toFixed(2) + ' ' + );
                $('#receive_money').val(receivedMoney.toFixed(2));

                $('#sending_amount').text(enterAmount + ' ' + );
                $('#fees-and-charges').text(totalCharge.toFixed(2) + ' ' + );
                $('#convert_amount').text(convertAmount.toFixed(2) + ' ' + );
                $('#get-amount').text(receivedMoney.toFixed(2) + ' ' + );
            }else{
                $("#fees").text('');
                $('#convert-amount').text('');
                $('#payable').text('');
                $('#receive_money').val('');
                $('#sending_amount').text('');
                $('#fees-and-charges').text('');
                $('#convert_amount').text('');
                $('#get-amount').text('');
            }
        }
        getCharges(selectedType,enterAmount);
    }
    function runReverse(){
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        
        var receiveAmount = $('#receive_money').val();
        var enterAmount   = receiveAmount ;
 
        $("#feature-list").html(selectedType.feature_text);

        function getReverseCharges(selectedType,enterAmount){
            let findPercentCharge = enterAmount / 100;

            let fixedCharge = selectedType.fixed_charge;
            let percentCharge = selectedType.percent_charge;
            
            let totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            let payableAmount = enterAmount + totalCharge;
            
            if(enterAmount == "") enterAmount = 0;
            if (enterAmount != 0) {
                let convertAmount = enterAmount;
                
                let receivedMoney = convertAmount ;
                
                var intervals = selectedType.intervals;
                
                $.each(intervals,function(index,item){
                    
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        
                        totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        
                        convertAmount = parseFloat(enterAmount);
                        payableAmount = parseFloat(enterAmount) + totalCharge;
                        receivedMoney = convertAmount ;
                    }
                });


                
                $('#sending_amount').text(enterAmount.toFixed(2) + ' ' + );
                $("#fees").text('+'+totalCharge.toFixed(2) + ' ' + );
                $('#charge').val(totalCharge.toFixed(2));
                $('#convert--amount').val(convertAmount.toFixed(2));
                $('#convert-amount').text(convertAmount.toFixed(2) + ' ' + );
                $('#payable').text(payableAmount.toFixed(2) + ' ' + );
                $('#payable--amount').val(payableAmount.toFixed(2));
                $('#send_money').val(enterAmount.toFixed(2));
                $('#fees-and-charges').text(totalCharge.toFixed(2) + ' ' + );
                $('#convert_amount').text(convertAmount.toFixed(2) + ' ' + );
                $('#get-amount').text(receivedMoney.toFixed(2) + ' ' + );
            }else{
                $('#send_money').val('');
                $("#fees").text('');
                $('#convert-amount').text('');
                $('#payable').text('');
                $('#receive_money').val('');
                $('#sending_amount').text('');
                $('#fees-and-charges').text('');
                $('#convert_amount').text('');
                $('#get-amount').text('');
            }
        }
        getReverseCharges(selectedType,enterAmount);
    }
    $("#send_money").keyup(function(){
        run();
    });
    $("#receive_money").keyup(function(){
        runReverse();
    });

</script>
@endpush



