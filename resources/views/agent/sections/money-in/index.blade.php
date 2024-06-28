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
                    <h3 class="title">Money In</h3>
                </div>
                <div class="money-in-card">
                    <div class="money-in-area">
                        <div class="card-logo">
                            <img src="{{ get_logo_agent($basic_settings) }}" alt="img">
                        </div>
                        <div class="row mb-20-none">
                            <div class="col-lg-12 mb-20">
                                <label>Email Address</label>
                                <input type="email" class="form--control" placeholder="Enter Email">
                            </div>
                            <div class="col-lg-12 amount-input mb-20">
                                <label>Amount</label>
                                <input type="number" class="form--control" placeholder="0.00">
                                <div class="curreny">
                                    <p>USD</p>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <div class="note-area">
                                    <code class="d-block text--base">Limit : 10.00 - 100000 USD</code>
                                    <code class="d-block text--base">Charge : 2.00 USD</code>
                                </div>
                            </div>
                        </div>
                        
                        <div class="money-in-btn pt-5">
                            <a href="preview-page.html" type="button" class="btn--base w-100">Send</a>
                        </div>
                    </div>
                </div>
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
                                    <span class="text--base">100 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>Total Fees & Charges</span>
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
                                            <span>Receive Amount</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--danger">100</span>
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
                                    <span class="text--info"><b>102.00 USD</b></span>
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