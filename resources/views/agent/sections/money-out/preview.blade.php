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
    <div class="agent-money-out">
        <div class="row mb-20-none">
            <div class="col-lg-7 col-md-6 mb-20">
                <div class="agent-preview">
                    <form action="{{ setRoute('agent.money.out.confirm',$temporary_data->identifier) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="agent-preview-area">
                            <div class="preview-area-title pb-10">
                                <h3 class="title"> {{ __("Receiving Method Information") }}</h3>
                            </div>
                            <div class="card-form">
                                <div class="row">
                                    <p>{!! $gateway->desc !!}</p>
                                    @include('agent.components.payment-gateway.generate-dy-input',['input_fields' => array_reverse($gateway->input_fields)])
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <button type="submit" class="btn--base mt-10 w-100"><span class="w-100">{{ __("Continue") }}</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 mb-20">
                <div class="agent-preview">
                    <div class="agent-preview-area">
                        <div class="preview-area-title pb-10">
                            <h3 class="title"> {{ __("Summary") }}</h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection