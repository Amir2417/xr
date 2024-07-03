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
                <div class="dashboard-header-wrapper">
                    <h3 class="title">{{ $page_title }}</h3>
                </div>
                <form class="card-form">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 form-group text-center">
                            <div class="exchange-area">
                                <code class="d-block text-center"><span>Exchange Rate</span> 1.00 USD = 1.00 USD</code>
                            </div>
                        </div>
                        <div class="col-lg-12 amount-input mb-20">
                            <label>{{ __("Amount") }}</label>
                            <input type="number" name="amount" class="form--control" placeholder="0.00">
                            <div class="curreny">
                                <p>{{ get_default_currency_code() }}</p>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 mb-20">
                            <label>{{ __("Payment Gateway") }}<span>*</span></label>
                            <select class="nice-select" name="payment_gateway">
                                <option selected disabled>{{ __("Select Gateway") }}</option>
                                @foreach ($payment_gateway as $item)
                                    <option value="{{ $item->id  }}"
                                        data-currency="{{ $item->currency_code }}"
                                        data-rate="{{ $item->rate }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <div class="note-area">
                                <code class="d-block">Limit : 10.00 - 100000 USD</code>
                                <code class="d-block">Charge : 2.00 USD</code>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12">
                        <button type="submit" class="btn--base w-100">Money Out</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 col-md-6 mb-20">
                <div class="custom-card">
                    <div class="dashboard-header-wrapper">
                        <h3 class="title">Summary</h3>
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
                                            <span>Enter Amount</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--base">100.00 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>Fees & Charges</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning">2.00 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="lab la-get-pocket"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>Will Get</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--danger">98.00 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span><b>Total Payable</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--info"><b>100.00 USD</b></span>
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
                <h4 class="title">Money Out Log</h4>
            </div>
            <div class="view-more-log">
                <a href="transaction.html" type="button" class="btn--base">View More</a>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item sent">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">David Huk</h4>
                                <span class="sub-title text--danger">Sent <span
                                        class="badge badge--warning ms-2">Pending</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-list-right">
                        <h4 class="main-money text--base">100.00 USD</h4>
                        <h6 class="exchange-money">152.00 AUD</h6>
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
                                    <span>Transaction Type</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>Bank Transfer</span>
                        </div>
                    </div>
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
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-comment-dollar"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Sending Amount</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>100 USD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Exchange Rate</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>1 USD = 1.52.00 AUD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Fees & Charge</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>3.00 USD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Will Get Amount</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>152.00 AUD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-comment-dollar"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Sender Name</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>Albert Afraid</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item sent">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">David Huk</h4>
                                <span class="sub-title text--danger">Sent <span
                                        class="badge badge--success ms-2">Success</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-list-right">
                        <h4 class="main-money text--base">100.00 USD</h4>
                        <h6 class="exchange-money">152.00 AUD</h6>
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
                                    <span>Transaction Type</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>Bank Transfer</span>
                        </div>
                    </div>
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
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-comment-dollar"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Sending Amount</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>100 USD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Exchange Rate</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>1 USD = 1.52.00 AUD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Fees & Charge</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>3.00 USD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Will Get Amount</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>152.00 AUD</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-comment-dollar"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>Sender Name</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>Albert Afraid</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection