@isset($transactions)
    @forelse ($transactions as $item)
    <div class="dashboard-list-wrapper">
        <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item sent">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-icon">
                            <i class="las la-arrow-up"></i>
                        </div>
                        <div class="dashboard-list-user-content">
                            @if (@$item->type == payment_gateway_const()::MONEYIN)
                                <h4 class="title">{{ __('Money In using') }} {{ @$item->currency->name }}</h4>
                            @elseif (@$item->type == payment_gateway_const()::MONEYOUT)
                                <h4 class="title">{{ __('Money Out using') }} {{ @$item->currency->name }}</h4>
                            @endif
                            <span class="sub-title text--danger">{{ @$item->attribute }} 
                                <span class="badge badge--warning ms-2">
                                    @if ($item->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                        <span>{{ __("Review Payment") }}</span> 
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_PENDING)
                                        <span>{{ __("Pending") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                        <span>{{ __("Confirm Payment") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_HOLD)
                                        <span>{{ __("On Hold") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_SETTLED)
                                        <span>{{ __("Settled") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                                        <span>{{ __("Completed") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_CANCEL)
                                        <span>{{ __("Canceled") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_FAILED)
                                        <span>{{ __("Failed") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_REFUND)
                                        <span>{{ __("Refunded") }}</span>
                                    @else
                                        <span>{{ __("Delayed") }}</span>
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="dashboard-list-right">
                    <h4 class="main-money text--base">{{ get_amount(@$item->request_amount,@$item->remittance_data->data->base_currency->currency) }}</h4>
                    <h6 class="exchange-money">{{ get_amount(@$item->payable,@$item->remittance_data->data->payment_gateway->currency) }}</h6>
                </div>
            </div>
            <div class="preview-list-wrapper">
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-receipt"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Transaction Type") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $item->type }}</span>
                    </div>
                </div>
                @if($item->type == payment_gateway_const()::TYPESENDREMITTANCE)
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-university"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Bank Name</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>American Express</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-user-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>IBAN Number</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>1234 0000 56789</span>
                        </div>
                    </div>
                @endif
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-comment-dollar"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Sending Amount") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount(@$item->request_amount,@$item->remittance_data->data->base_currency->currency) }}</span>
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
                        <span>{{ get_amount(@$item->remittance_data->data->base_currency->rate,@$item->remittance_data->data->base_currency->currency) }} = {{ get_amount(@$item->remittance_data->data->payment_gateway->rate,@$item->remittance_data->data->payment_gateway->currency) }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-battery-half"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Fees & Charge") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount(@$item->remittance_data->data->total_charge,@$item->remittance_data->data->base_currency->currency) }}</span>
                    </div>
                </div>
                @if (@$item->type == payment_gateway_const()::MONEYIN)
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Will Get Amount") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ get_amount(@$item->request_amount,@$item->remittance_data->data->base_currency->currency) }}</span>
                        </div>
                    </div>
                @endif
                
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-comment-dollar"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Sender Name") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ @$item->agent->fullname }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-primary text-center">
            {{ __("No data found!") }}
        </div>
    @endforelse
    {{ get_paginate($transactions) }}
@endisset