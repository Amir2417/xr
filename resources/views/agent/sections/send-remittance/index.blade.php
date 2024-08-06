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
    <div class="agent-sending-remittance">
        <div class="row mb-20-none">
            <div class="col-xl-7 col-lg-7 mb-20">
                <div class="custom-card mt-10">
                    <div class="dashboard-header-wrapper">
                        <h4 class="title">{{ __($page_title) }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form action="{{ setRoute('agent.send.remittance.submit') }}" method="POST">
                                @csrf       
                                <div class="banner-form">
                                    <div class="top mb-20">
                                        <p>{{ __("Exchange Rate") }}</p>
                                        <h3 class="title exchange-rate"></h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-12 amount-input mb-20">
                                                <label>{{ __("You send exactly") }}</label>
                                                <input type="number" name="amount" class="form--control amount" placeholder="0.00">
                                                <div class="curreny">
                                                    <p>{{ get_default_currency_code() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                        <p><i class="las la-dot-circle"></i> {{ __("Fees & Charges") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <p id="fees"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> {{ __("Amount will convert") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <p class="convert-amount"></p>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4 pt-20">
                                        <div class="row">
                                            <label>{{ __("Recipient gets") }}</label>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="receive-money" type="text" class="form--control w-100 number-input" readonly>
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="receiver_currency" class="receiver_currency">
                                                                <img src="{{ get_image(@$receiver_currency_first->flag,'currency-flag') }}" alt="">
                                                                <span class="custom-currency">{{ @$receiver_currency_first->code }}</span>
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
                                                                        <li class="custom-option
                                                                            @if($item->code == $receiver_currency_first->code)
                                                                            active
                                                                            @endif" data-item='{{ json_encode($item) }}'>
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
                                    <div class="form-footer-content mt-10-none mb-20">
                                        <div class="sender-input">
                                            <label>{{ __("Sender") }}</label>
                                            <div class="input-fild">
                                                <select class="select2-basic trx-type-select" name="sender">
                                                    <option selected disabled>{{ __("Select Sender") }}</option>
                                                    @forelse ($senders ?? [] as $item)
                                                        <option class="custom-option" value="{{ $item->id }}">{{ $item->fullname }}</option>
                                                    @empty
                                                        <option disabled>{{ __("No data found") }}</option>
                                                    @endforelse
                                                </select>
                                                <div class="add-sender">
                                                    <a href="{{ setRoute('agent.my.sender.create') }}" class="btn"><i class="las la-plus"></i> {{ __("Add Sender") }}</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-footer-content mt-10-none mb-20">
                                        <div class="sender-input">
                                            <label>{{ __("Recipient") }}</label>
                                            <div class="input-fild">
                                                <select class="select2-basic trx-type-select recipient-data-info" id="recipient-data" name="recipient">
                                                    
                                                </select>
                                                <div class="add-sender">
                                                    <a href="{{ setRoute('agent.recipient.create') }}" class="btn"><i class="las la-plus"></i> {{ __("Add Recipient") }}</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group transaction-type mb-20">             
                                        <div class="transaction-title">
                                            <label>{{ __("Receiving Method") }}</label>
                                        </div>
                                        <div class="transaction-type-select">
                                            <select class="nice-select trx-type-select" name="transaction_type"> 
                                                @forelse ($transaction_settings as $item)
                                                    <option class="custom-option" value="{{ $item->id }}" data-item='{{ json_encode($item) }}'>{{ __($item->title ?? '')}}</option>
                                                @empty
                                                    <option>{{ __("No data found") }}</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn--base w-100">{{ __("Send") }}</a>
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
                                            <span>{{ __("Sending Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--base sending-amount"></span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-exchange-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Exchange Rate") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="exchange-rate"></span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Total Fees & Charges") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning total-charge"></span>
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
                                    <span class="text--danger convert-amount"></span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span><b>{{ __("Will Get Amount") }}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--info receive-amount"><b></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area mt-60">
        <div class="log-type d-flex justify-content-between align-items-center mb-40">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __("Send Remittance Log") }}</h4>
            </div>
            {{-- <div class="view-more-log">
                <a href="transaction.html" type="button" class="btn--base">View More</a>
            </div> --}}
        </div>

        @include('agent.components.transaction-logs.index',compact('transactions'))
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
        $(".amount").val(100);
        var amount              = $('.amount').val();
        var transactionType     = JSON.parse($("select[name=transaction_type] :selected").attr('data-item'));
        getRecipientData(transactionType.title);
        run(amount,JSON.parse(selectedActiveItem("input[name=receiver_currency]")),transactionType);
    });

    $(".amount").keyup(function(){
        var amount              = $(this).val(); 
        var transactionType     = JSON.parse($("select[name=transaction_type] :selected").attr('data-item'));
        run(amount,JSON.parse(selectedActiveItem("input[name=receiver_currency]")),transactionType);
    });

    $(document).on("click",".custom-option",function() {
        
        var transactionType     = JSON.parse($("select[name=transaction_type] :selected").attr('data-item'));
        var amount              = $('.amount').val();
        if(amount == '' || amount == null){
            amount  = 0;
        }
        getRecipientData(transactionType.title);
        run(amount,JSON.parse(selectedActiveItem("input[name=receiver_currency]")),transactionType);
    });

    $("select[name=transaction_type]").on("change",function() {
        
        var transactionType     = JSON.parse($("select[name=transaction_type] :selected").attr('data-item'));
        var amount              = $('.amount').val();
        if(amount == '' || amount == null){
            amount  = 0;
        }
        getRecipientData(transactionType.title);
        run(amount,JSON.parse(selectedActiveItem("input[name=receiver_currency]")),transactionType);
    });
    // get receiver currency data
    function selectedActiveItem(input) {
        var adSelect        = $(input).parents(".ad-select");
        var selectedItem    = adSelect.find(".custom-option.active");

        if(selectedItem.length > 0) {
            return selectedItem.attr("data-item");
        }
        return false;
    }

    // for run function
    function run(amount,receiver,transactionType){
        var baseRate            = "{{ get_default_currency_rate() }}";
        var baseCurrency        = "{{ get_default_currency_code() }}";
        var fixedCharge         = transactionType.fixed_charge;
        var percentCharge       = (parseFloat(transactionType.percent_charge) * parseFloat(amount)) / 100;
        var totalCharge         = parseFloat(fixedCharge) + parseFloat(percentCharge);
        var exchangeRate        = receiver.rate;
        var convertAmount       = amount - totalCharge;
        var receiveAmount       = convertAmount * exchangeRate;

        if(amount != 0){
            var intervals       = transactionType.intervals;
            $.each(intervals,function(index,item){
                if(parseFloat(amount) >= item.min_limit  && parseFloat(amount) <= item.max_limit) {
                    fixedCharge     = item.fixed;
                    percentCharge   = (parseFloat(item.percent) * parseFloat(amount)) / 100;
                    totalCharge     = parseFloat(fixedCharge) + parseFloat(percentCharge);
                    convertAmount   = amount - totalCharge;
                    payableAmount   = parseFloat(amount) + parseFloat(totalCharge);
                    receiveAmount   = convertAmount * exchangeRate;
                }
            });
            $("#fees").text("-" + parseFloat(totalCharge) + " " + baseCurrency);
            $(".convert-amount").text(parseFloat(convertAmount.toFixed(2)) + " " + baseCurrency);
            $(".exchange-rate").text(parseFloat(baseRate) + " " + baseCurrency + " " + "=" + " " + parseFloat(exchangeRate) + " " + receiver.code);
            $("#receive-money").val(parseFloat(receiveAmount.toFixed(2)));
            $('.sending-amount').text(amount + " " + baseCurrency);
            $('.total-charge').text(totalCharge + " " + baseCurrency);
            $(".receive-amount").text(parseFloat(receiveAmount.toFixed(2)) + " " + receiver.code);
            $(".receiver_currency").val(receiver.id);
        }else{
            $("#fees").text("");
            $(".convert-amount").text("");
            $(".exchange-rate").text("");
            $("#receive-money").val("");
            $('.sending-amount').text("");
            $('.total-charge').text("");
            $(".receive-amount").text("");
            $(".receiver_currency").val("");
        }

    }
    // get recipient data
    function getRecipientData(transactionType){
        var getRecipientURL     = "{{ route('agent.get.recipient.data') }}";
        $(".recipient-data-info").val('');
        $.post(getRecipientURL,{method:transactionType,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.recipient_data == null || response.data.recipient_data == ''){
                $('.recipient-data-info').html('<option value="" disabled>No Recipient Aviliable</option>');
            }else{
                $('.recipient-data-info').html('<option value="" disabled>Select Recipient</option>');
            }
            
            $.each(response.data.recipient_data, function (key, value) {
                $(".recipient-data-info").append(`<option class="custom-option" value="${value.id}">${value.fullname}</option>`);
            });
        });
    }

</script>
@endpush