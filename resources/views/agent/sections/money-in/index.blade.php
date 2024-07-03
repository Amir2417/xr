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
    <div class="agent-money-in">
        <div class="row mb-20-none">
            <div class="col-lg-7 col-md-6 mb-20">
                <div class="dashboard-header-wrapper">
                    <h3 class="title">{{ __($page_title) }}</h3>
                </div>
                <form action="{{ setRoute('agent.moneyin.submit') }}" method="POST">
                    @csrf
                    <div class="money-in-card">
                        <div class="money-in-area">
                            <div class="card-logo">
                                <img src="{{ get_logo_agent($basic_settings) }}" alt="img">
                            </div>
                            <div class="row mb-20-none">
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label>{{ __("Payment Gateway") }} <span>*</span></label>
                                    <select class="form--control select2-basic" name="payment_gateway">
                                        
                                        @foreach ($payment_gateway as $item)
                                        <option 
                                                value="{{ $item->id  }}"
                                                data-currency="{{ $item->currency_code }}"
                                                data-rate="{{ $item->rate }}"
                                        >{{ $item->name ?? '' }} @if ($item->gateway->isManual())
                                            (Manual)
                                        @endif</option>
                                        @endforeach
                                        
                                    </select>
                                </div>
                                <div class="col-lg-12 amount-input mb-20">
                                    <label>{{ __("Amount") }}</label>
                                    <input type="number" name="amount" class="form--control amount number-input" placeholder="{{ __("Enter amount") }}">
                                    <div class="curreny">
                                        <p>{{ __(get_default_currency_code()) }}</p>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <div class="note-area">
                                        <code class="d-block text--base limit">{{ __("Limit") }} : {{ get_amount(@$transaction_settings->min_limit) }} - {{ get_amount(@$transaction_settings->max_limit) }} {{ get_default_currency_code() }}</code>
                                        <code class="d-block text--base charge"></code>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="money-in-btn pt-5">
                                <button type="submit" class="btn--base w-100">{{ __("Send") }}</a>
                            </div>
                        </div>
                    </div>
                </form>                
            </div>
            <div class="col-lg-5 col-md-6 mb-20">
                <div class="custom-card">
                    <div class="dashboard-header-wrapper">
                        <h3 class="title">{{ __("Summary") }}</h3>
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
                                            <span>{{ __("Enter Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--base enter-amount"></span>
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
                                    <span class="text--base exchange-rate"></span>
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
                                            <span>{{ __("Receive Amount") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--danger receive-amount"></span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span><b>{{ __("Total Payable") }}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--info total-payable"><b></b></span>
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
                <h4 class="title">Money In Log</h4>
            </div>
            
        </div>

        @include('agent.components.transaction-logs.index',compact('transactions'))
    </div>
</div>
@endsection
@push('script')
    <script>
        $("select[name=payment_gateway]").on('change',function(){
            var amount                  = $(this).val();
            var paymentGatewayRate      = $("select[name=payment_gateway] :selected").attr("data-rate");
            var paymentGatewayCurrency  = $("select[name=payment_gateway] :selected").attr("data-currency");
            feesAndChargesCalculation(amount,paymentGatewayRate,paymentGatewayCurrency);
        });

        $(".amount").keyup(function(){
            var amount                  = $(this).val();
            var paymentGatewayRate      = $("select[name=payment_gateway] :selected").attr("data-rate");
            var paymentGatewayCurrency  = $("select[name=payment_gateway] :selected").attr("data-currency");
            feesAndChargesCalculation(amount,paymentGatewayRate,paymentGatewayCurrency);
        });

        //fees and charges calculation
        function feesAndChargesCalculation(amount,paymentGatewayRate,paymentGatewayCurrency){
            var currency        = "{{ get_default_currency_code() }}";
            var rate            = "{{ get_default_currency_rate() }}";
            var fixedCharge     = "{{ $transaction_settings->fixed_charge }}";
            var percentCharge   = ("{{ $transaction_settings->percent_charge }}"/ 100) * amount;
            var totalCharge     = (parseFloat(fixedCharge) + parseFloat(percentCharge));
            var totalAmount     = parseFloat(amount) + parseFloat(totalCharge);
            var payableAmount   =  parseFloat(totalAmount) * paymentGatewayRate;

            $('.charge').html(`<code class="d-block text--base charge">Charge : ${totalCharge.toFixed(2)} ${currency}</code>`);
            $('.enter-amount').text(amount + ' ' + currency);
            $('.exchange-rate').text(parseFloat(rate).toFixed(2) + ' ' + currency + ' ' + '=' + ' ' + parseFloat(paymentGatewayRate).toFixed(2) + ' ' + paymentGatewayCurrency);
            $('.total-charge').text(totalCharge.toFixed(2) + ' ' + currency);
            $('.receive-amount').text(amount + ' ' + currency);
            $('.total-payable').text(payableAmount.toFixed(2) + ' ' + paymentGatewayCurrency);          
        }
    </script>
@endpush