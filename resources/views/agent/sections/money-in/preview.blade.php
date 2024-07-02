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
    <div class="preview-page">
        <div class="row justify-content-center mt-30">
            <div class="col-lg-8 col-md-10">
                <div class="agent-preview">
                    <form action="{{ setRoute('agent.moneyin.confirm') }}" method="post">
                        @csrf
                        <input type="hidden" name="identifier" value="{{ $temporary_data->identifier }}">
                        <div class="agent-preview-area">
                            <div class="preview-area-title pb-10">
                                <h3 class="title"> {{ __("Preview Page") }}</h3>
                            </div>
                            <div class="preview-list-wrapper">
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-envelope"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Payment Method") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--info">{{ @$temporary_data->data->payment_gateway->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-money-check-alt"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Amount") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--success">{{ get_amount(@$temporary_data->data->amount,@$temporary_data->data->base_currency->currency) }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-battery-half"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Fess & Charge") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--danger">{{ get_amount(@$temporary_data->data->total_charge,@$temporary_data->data->base_currency->currency) }}</span>
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
                                        <span class="text--danger">{{ get_amount(@$temporary_data->data->base_currency->rate,@$temporary_data->data->base_currency->currency) }} = {{ get_amount(@$temporary_data->data->payment_gateway->rate,@$temporary_data->data->payment_gateway->currency) }}</span>
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
                                        <span class="text--danger">{{ get_amount(@$temporary_data->data->receive_amount,@$temporary_data->data->base_currency->currency) }}</span>
                                    </div>
                                </div>
                            
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-money-check-alt"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span class="last">{{ __("Payable Amount") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--danger last">{{ get_amount(@$temporary_data->data->payable_amount,@$temporary_data->data->payment_gateway->currency) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="conformation-btn pt-5">
                                <button type="submit" class="btn--base w-100">{{ __("Confirm") }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection