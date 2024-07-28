@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ],
        
    ], 'active' => __("Log Details")])
@endsection

@section('content')
<div class="row mb-30-none">
    <div class="col-lg-12 mb-30">
        <div class="transaction-area">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="title"><i class="fas fa-credit-card text--base me-2"></i>{{ __("Payment Summary") }}</h4>
            </div>
            
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("MTCN Number") }} <span>{{ $transaction->trx_id ?? ''  }}</span> </li>
                        <li>{{ __("Payment Method") }} <span>{{ $transaction->remittance_data->data->payment_gateway->name ?? ''  }}</span> </li>
                        <li>{{ __("Request Amount") }}<span>{{ get_amount($transaction->request_amount,$transaction->remittance_data->data->base_currency->currency) }}</span></li>
                        <li>{{ __("Exchange Rate") }}<span>{{ get_amount($transaction->remittance_data->data->base_currency->rate) }} {{ $transaction->remittance_data->data->base_currency->currency}} = {{ get_amount($transaction->exchange_rate) }} {{ $transaction->remittance_data->data->payment_gateway->currency}}</span></li>
                        <li>{{ __("Total Fees & Charges") }}<span>{{ get_amount($transaction->fees) ?? "" }} {{ $transaction->remittance_data->data->base_currency->currency }}</span></li>
                        <li>{{ __("Payable Amount") }} <span>{{ get_amount($transaction->payable) ?? '' }} {{ $transaction->remittance_data->data->payment_gateway->currency}}</span> </li>
                        <li>{{ __("Payment Status") }}
                            @if ($transaction->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                <span>{{ __("Review Payment") }}</span> 
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_PENDING)
                                <span>{{ __("Pending") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                <span>{{ __("Confirm Payment") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_HOLD)
                                <span>{{ __("On Hold") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_SETTLED)
                                <span>{{ __("Settled") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                                <span>{{ __("Completed") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_CANCEL)
                                <span>{{ __("Canceled") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_FAILED)
                                <span>{{ __("Failed") }}</span>
                            @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_REFUND)
                                <span>{{ __("Refunded") }}</span>
                            @else
                                <span>{{ __("Delayed") }}</span>
                            @endif
                            
                        </li>
                        <li>{{ __("Date") }} <span>{{ $transaction->created_at->format("d-m-Y") }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if ($transaction->status != global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT && $transaction->status != global_const()::REMITTANCE_STATUS_CANCEL)
    <form action="{{ setRoute('admin.agent.money.in.logs.status.update',$transaction->trx_id) }}" method="post">
        @csrf
        <div class="col-lg-12 mb-30">
            <div class="transaction-area">
                <h4 class="title"><i class="fas fa-spinner text--base me-2"></i>{{ __("Progress of Remittance Transactions") }}</h4>
                <div class="content pt-0">
                    <div class="radio-area">
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-1" value="1" @if($transaction->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT) checked @endif name="status">
                                <label for="level-1">{{ __("Review Payment") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-2" value="2" @if($transaction->status == global_const()::REMITTANCE_STATUS_PENDING) checked @endif name="status">
                                <label for="level-2">{{ __("Pending") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-3" value="3" @if($transaction->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT) checked @endif name="status">
                                <label for="level-3">{{ __("Confirm Payment") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-4" value="4" @if($transaction->status == global_const()::REMITTANCE_STATUS_HOLD) checked @endif name="status">
                                <label for="level-4">{{ __("On Hold") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-5" value="5" @if($transaction->status == global_const()::REMITTANCE_STATUS_SETTLED) checked @endif name="status">
                                <label for="level-5">{{ __("Settled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-6" value="6" @if($transaction->status == global_const()::REMITTANCE_STATUS_COMPLETE) checked @endif name="status">
                                <label for="level-6">{{ __("Completed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-7" value="7" @if($transaction->status == global_const()::REMITTANCE_STATUS_CANCEL) checked @endif name="status">
                                <label for="level-7">{{ __("Canceled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-8" value="8" @if($transaction->status == global_const()::REMITTANCE_STATUS_FAILED) checked @endif name="status">
                                <label for="level-8">{{ __("Failed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-9" value="9" @if($transaction->status == global_const()::REMITTANCE_STATUS_REFUND) checked @endif name="status">
                                <label for="level-9">{{ __("Refunded") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-10" value="10" @if($transaction->status == global_const()::REMITTANCE_STATUS_DELAYED) checked @endif name="status">
                                <label for="level-10">{{ __("Delayed") }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => "Update",
                        ])
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
    
    
</div>
@endsection
@push('script')
    <script>
        $('.copy').on('click',function(){
            
            let input = $('.box').val();
            navigator.clipboard.writeText(input)
            .then(function() {
                
                $('.copy').text("Copied");
            })
            .catch(function(err) {
                console.error('Copy failed:', err);
            });
        });
    </script>
@endpush