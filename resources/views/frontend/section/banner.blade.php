@php
    $app_local    = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Banner
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner bg_img" data-background="{{ get_image($banner->value->image ?? null, 'site-section') }}">
    <div class="container mx-auto">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-12 col-12 banner-content my-auto">
                <h3 class="title">{{ $banner->value->language->$app_local->heading ?? "" }}</h3>
                <p>{{ $banner->value->language->$app_local->sub_heading ?? "" }}</p>
                <div class="d-flex">
                    <div class="me-5">
                        <a href="{{ setRoute('user.register') }}" class="btn--base mb-4 mb-lg-0 mb-md-0">{{ $banner->value->language->$app_local->button_name ?? "" }}</a>
                    </div>
                    <div class="video-wrapper mt-2">
                        <a class="video-icon" data-rel="lightcase:myCollection"
                            href="{{ $banner->value->language->$app_local->video_link ?? ""}}">
                            <i class="las la-play"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-8 col-12">
                <div class="banner-form">
                    <form method="POST" action="{{ setRoute('frontend.request.send.money') }}">
                        @csrf
                        <div class="top">
                            <p>{{__("Exchange Rate")}}</p>
                            <h3 class="title">{{ get_amount($sender_currency->rate) }} {{ $sender_currency->code }} = {{ get_amount($receiver_currency->rate) }} {{ $receiver_currency->code}}</h3>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <h3 class="fs-6">{{ __("You send exactly") }}</h3>
                                <div class="col-12 from-cruncy pb-4">
                                    <input id="send_money" type="text" class="form--control w-100 number-input" name="send_money">
                                    <div class="cruncy">
                                        <img src="{{ get_image($sender_currency->flag ?? null ,'currency-flag')}}" alt="image">  {{ $sender_currency->code }}
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="exchange-charge d-flex justify-content-between">
                                <div class="left-side">
                                    <p><i class="las la-dot-circle"></i>{{ __("Fees & Charge") }}</p>
                                </div>
                                <div class="right-side">
                                    <input type="hidden" name="fees" id="charge">
                                    <p id="fees"></p>
                                </div>
                            </div>
                            <div class="exchange-charge d-flex justify-content-between">
                                <div class="left-side">
                                    <p><i class="las la-dot-circle"></i>{{ __("Amount We’ll Convert") }}</p>
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
                                    <p id="payable">  </p>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="row">
                                    <h3 class="fs-6">{{ __("Recipient gets") }}</h3>
                                    <div class="col-12 from-cruncy">
                                        <input id="receive_money" type="text" class="form--control w-100 number-input" name="receive_money">
                                        <div class="cruncy">
                                            <img src="{{ get_image($receiver_currency->flag ?? null ,'currency-flag')}}" alt="image">{{$receiver_currency->code}}
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
                                        {{-- <option selected disabled>Select Method</option> --}}
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
                                <button  class="btn--base btn--base-e text-center w-100 ">{{ __("Send Money") }}</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@push('script')

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
                let receivedMoney = convertAmount * '{{ $receiver_currency->rate }}';
                var intervals = selectedType.intervals;

                $.each(intervals,function(index,item){
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        convertAmount = parseFloat(enterAmount);
                        payableAmount = parseFloat(enterAmount) + totalCharge;
                        receivedMoney = convertAmount * '{{ $receiver_currency->rate }}'; 
                    }
                });

                $("#fees").text('+'+totalCharge.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#convert-amount').text(convertAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#payable').text(payableAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#payable--amount').val(payableAmount.toFixed(2));
                $('#receive_money').val(receivedMoney.toFixed(2));
                $('#sending_amount').text(enterAmount + ' ' + '{{ $sender_currency->code }}');
                $('#fees-and-chages').text(totalCharge.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#convert_amount').text(convertAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#get-amount').text(receivedMoney.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#charge').val(totalCharge.toFixed(2));
                $('#convert--amount').val(convertAmount.toFixed(2));
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
        var enterAmount   = receiveAmount / '{{ $receiver_currency->rate }}';
    
        $("#feature-list").html(selectedType.feature_text);

        function getReverseCharges(selectedType,enterAmount){
            // console.log("amount",enterAmount);
            let findPercentCharge = enterAmount / 100;

            let fixedCharge = selectedType.fixed_charge;
            let percentCharge = selectedType.percent_charge;
            
            let totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            let payableAmount = enterAmount + totalCharge;
            
            if(enterAmount == "") enterAmount = 0;
            if (enterAmount != 0) {
                let convertAmount = enterAmount;
                let receivedMoney = convertAmount * '{{ $receiver_currency->rate }}';
                var intervals = selectedType.intervals;
                $.each(intervals,function(index,item){
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        convertAmount = parseFloat(enterAmount);
                        payableAmount = parseFloat(enterAmount) + totalCharge;
                        receivedMoney = convertAmount * '{{ $receiver_currency->rate }}';
                    }
                });


                $('#sending_amount').text(enterAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $("#fees").text('+'+totalCharge.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#convert-amount').text(convertAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#payable').text(payableAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#send_money').val(enterAmount.toFixed(2));
                $('#fees-and-charges').text(totalCharge.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#convert_amount').text(convertAmount.toFixed(2) + ' ' + '{{ $sender_currency->code }}');
                $('#get-amount').text(receivedMoney.toFixed(2) + ' ' + '{{ $receiver_currency->code }}');
            }else{
                $("#fees").text('');
                $('#send_money').val('');
                $('#convert-amount').text('');
                $('#payable').text('');
                $('#receive_money').val('');
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