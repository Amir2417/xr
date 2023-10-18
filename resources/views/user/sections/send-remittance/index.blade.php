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
                                        <h3 class="title exchange_rate">--</h3>
                                        <input type="hidden" name="sender_ex_rate" class="sender-ex-rate">
                                        <input type="hidden" name="sender_base_rate" class="sender-base-rate">
                                        <input type="hidden" name="receiver_ex_rate" class="receiver-ex-rate">
                                    </div>
                                    <div class="col-12 pb-20">
                                        <div class="row">
                                            <h3 class="fs-6">{{__("You send exactly")}}</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="send_money" type="text" class="form--control w-100 number-input" name="send_money">
                                                    <select class="form--control nice-select sender-currency" name="sender_currency">
                                                        @foreach ($sender_currency as $item)
                                                            <option value="{{ $item->code }}"
                                                                data-code="{{ $item->code }}"
                                                                data-symbol="{{ $item->symbol }}"
                                                                data-rate="{{ $item->rate }}"
                                                                data-name="{{ $item->country }}">{{ $item->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                    <select class="form--control nice-select receiver-currency" name="receiver_currency">
                                                        @foreach ($receiver_currency as $item)
                                                            <option value="{{ $item->code }}"
                                                                data-code="{{ $item->code }}"
                                                                data-symbol="{{ $item->symbol }}"
                                                                data-rate="{{ $item->rate }}"
                                                                data-name="{{ $item->country }}">{{ $item->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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

    $(document).ready(function () {
        var enterAmount = $('#send_money').val(100);
        run();
        
    });
    $('.trx-type-select').on('change',function(){
        run();
    });
    $('.sender-currency').on('change',function(){
        run();
    });
    $('.receiver-currency').on('change',function(){ 
        runReverse();
    });
    function run(){
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        
        var enterAmount = $('#send_money').val();
 
        $("#feature-list").html(selectedType.feature_text);

        function acceptVar() {
           
            var senderCurrency          = $("select[name=sender_currency] :selected").attr("data-code");
            var senderCurrencyRate      = $("select[name=sender_currency] :selected").attr("data-rate");
            var receiverCurrency        = $("select[name=receiver_currency] :selected").attr("data-code");
            var receiverCurrencyRate    = $("select[name=receiver_currency] :selected").attr("data-rate");
            return {
                senderCurrency:senderCurrency,
                senderCurrencyRate:senderCurrencyRate,
                receiverCurrency:receiverCurrency,
                receiverCurrencyRate:receiverCurrencyRate
            };
        }

        function getCharges(selectedType,enterAmount){
            let findPercentCharge       = enterAmount / 100;
            var senderCurrencyRate      = acceptVar().senderCurrencyRate;
            var senderCurrency          = acceptVar().senderCurrency;
            var receiverCurrency        = acceptVar().receiverCurrency;
            var receiverCurrencyRate    = acceptVar().receiverCurrencyRate;
            var senderRate              = senderCurrencyRate / senderCurrencyRate;
            var recieverRate            = receiverCurrencyRate / senderCurrencyRate;


            let fixedCharge             = selectedType.fixed_charge;
            let percentCharge           = selectedType.percent_charge;
            
            let totalPercentCharge      = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            var totalChargeAmount  = totalCharge * senderCurrencyRate;

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
                        totalChargeAmount  = totalCharge * senderCurrencyRate;

                        convertAmount = parseFloat(enterAmount);
                        recieverRate            = receiverCurrencyRate / senderCurrencyRate;
                        payableAmount = parseFloat(enterAmount) + totalChargeAmount;
                        receivedMoney = convertAmount * recieverRate;
                    }
                });

                $("#fees").text('+' + parseFloat(totalChargeAmount ).toFixed(2) + " " + senderCurrency);
                $("#convert-amount").text(parseFloat(convertAmount) + " " + senderCurrency);
                $('#payable').text(parseFloat(payableAmount).toFixed(2) + " " + senderCurrency);
                $("#convert_amount").text(parseFloat(convertAmount) + " " + senderCurrency);
                $('#sending_amount').text(enterAmount + " " + senderCurrency);
                $('#fees-and-charges').text(parseFloat(totalChargeAmount ).toFixed(2) + " " + senderCurrency);
                $('#get-amount').text(parseFloat(receivedMoney).toFixed(2) + " " + receiverCurrency);
                $('.exchange_rate').text(parseFloat(senderRate).toFixed(2) + " " + senderCurrency + " = " + parseFloat(recieverRate).toFixed(2) + " " + receiverCurrency);

                $('#receive_money').val(receivedMoney.toFixed(2));
                $('#charge').val(totalChargeAmount.toFixed(2));
                $('#convert--amount').val(convertAmount.toFixed(2));
                $('#payable--amount').val(payableAmount.toFixed(2));
                $('.sender-ex-rate').val(parseFloat(senderRate).toFixed(2));
                $('.sender-base-rate').val(parseFloat(senderCurrencyRate).toFixed(2));
                $('.receiver-ex-rate').val(parseFloat(recieverRate).toFixed(2));
                
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
 
        $("#feature-list").html(selectedType.feature_text);
        function acceptVar() {
           
           var senderCurrency          = $("select[name=sender_currency] :selected").attr("data-code");
           var senderCurrencyRate      = $("select[name=sender_currency] :selected").attr("data-rate");
           var receiverCurrency        = $("select[name=receiver_currency] :selected").attr("data-code");
           var receiverCurrencyRate    = $("select[name=receiver_currency] :selected").attr("data-rate");
           return {
               senderCurrency:senderCurrency,
               senderCurrencyRate:senderCurrencyRate,
               receiverCurrency:receiverCurrency,
               receiverCurrencyRate:receiverCurrencyRate
           };
        }
        function getReverseCharges(selectedType,receiveAmount){
            var senderCurrencyRate      = acceptVar().senderCurrencyRate;
            var senderCurrency          = acceptVar().senderCurrency;
            var receiverCurrency        = acceptVar().receiverCurrency;
            var receiverCurrencyRate    = acceptVar().receiverCurrencyRate;
            var senderRate              = senderCurrencyRate / senderCurrencyRate;
            var recieverRate            = receiverCurrencyRate / senderCurrencyRate;
            let fixedCharge             = selectedType.fixed_charge;
            let findPercentCharge       = (receiveAmount / receiverCurrencyRate) / 100;
            let percentCharge           = selectedType.percent_charge;
            var senderAmount            = receiveAmount / recieverRate;;
            
            let totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            var totalChargeAmount  = totalCharge * senderCurrencyRate;
            let payableAmount = senderAmount + totalCharge;
            
            if(senderAmount == "") senderAmount = 0;
            if (senderAmount != 0) {
                let convertAmount = senderAmount;
                
                let receivedMoney = convertAmount ;
                
                var intervals = selectedType.intervals;
                
                $.each(intervals,function(index,item){
                    
                    if(parseFloat(senderAmount) >= item.min_limit  && parseFloat(senderAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        
                        
                        totalPercentCharge  = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        
                        totalCharge         = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        totalChargeAmount   = totalCharge * senderCurrencyRate;
                        convertAmount       = parseFloat(senderAmount);
                        payableAmount       = parseFloat(senderAmount) + totalChargeAmount;
                        
                        recieverRate        = receiverCurrencyRate / senderCurrencyRate;
                        receivedMoney       = convertAmount * recieverRate;
                    }
                });


                
                $('#sending_amount').text(parseFloat(senderAmount).toFixed(2) + " " + senderCurrency);
                $("#fees").text('+' + parseFloat(totalChargeAmount).toFixed(2) + " " + senderCurrency);
                $("#convert-amount").text(parseFloat(senderAmount).toFixed(2) + " " + senderCurrency);
                $('#payable').text(parseFloat(payableAmount).toFixed(2) + " " + senderCurrency);
                $('#charge').val(parseFloat(totalChargeAmount).toFixed(2));
                $('#convert--amount').val(parseFloat(senderAmount).toFixed(2));
                $('#payable--amount').val(parseFloat(payableAmount).toFixed(2));
                $('#send_money').val(parseFloat(senderAmount).toFixed(2));
                $('#fees-and-charges').text(totalChargeAmount.toFixed(2) + " " + senderCurrency);
                $('#convert_amount').text(parseFloat(senderAmount).toFixed(2) + " " + senderCurrency);
                $('#get-amount').text(receivedMoney.toFixed(2) + " " + receiverCurrency);
                $('.exchange_rate').text(parseFloat(senderRate).toFixed(2) + " " + senderCurrency + " = " + parseFloat(recieverRate).toFixed(2) + " " + receiverCurrency);

                
                $('.sender-ex-rate').val(parseFloat(senderRate).toFixed(2));
                $('.sender-base-rate').val(parseFloat(senderCurrencyRate).toFixed(2));
                $('.receiver-ex-rate').val(parseFloat(recieverRate).toFixed(2));
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
        getReverseCharges(selectedType,receiveAmount);
    }
    $("#send_money").keyup(function(){
        run();
    });
    $("#receive_money").keyup(function(){
        runReverse();
    });

</script>
@endpush



