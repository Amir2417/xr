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
                        <h4 class="title">Send Remittance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form>       
                                <div class="banner-form">
                                    <div class="top mb-20">
                                        <p>Exchange Rate</p>
                                        <h3 class="title exchange_rate">1 USD = 1.52  AUD</h3>
                                    </div>
                                    <div class="col-12 pb-20">
                                        <div class="row">
                                            <h3 class="fs-6">You send exactly</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="send_money" type="text" class="form--control w-100 number-input" name="send_money" value="100">
                                                    
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="sender_currency" class="sender_currency">
                                                                <img src="assets/images/flag/us.svg" alt="img">
                                                                <span class="custom-currency">USD</span>
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
                                                                    <li class="custom-option active">
                                                                        <img src="assets/images/flag/us.svg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">United States dollar<span>
                                                                        <span class="custom-currency">USD</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                        <img src="assets/images/flag/cn.svg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Canadian dollar</span>
                                                                        <span class="custom-currency">CAD</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                        <img src="assets/images/flag/gm.svg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">British pound</span>
                                                                        <span class="custom-currency">GBP</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                        <img src="assets/images/flag/eth.webp" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Euro</span>
                                                                        <span class="custom-currency">EUR</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                        <img src="assets/images/flag/btc.jpg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Japanese yen</span>
                                                                        <span class="custom-currency">JPY</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                            <img src="assets/images/flag/gm.svg" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">Won</span>
                                                                            <span class="custom-currency">KRW</span>
                                                                    </li>
                                                                    <li class="custom-option">
                                                                        <img src="assets/images/flag/gm.svg">
                                                                        <span class="custom-country">Swedish krona</span>
                                                                        <span class="custom-currency">SEK</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                        <p><i class="las la-dot-circle"></i> Fees &amp; Charges</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="fees" id="charge">
                                            <p id="fees">- 3.00 USD</p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> Amount will convert</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="convert_amount" id="convert--amount">
                                            <p id="convert-amount">97.00 US</p>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4 pt-20">
                                        <div class="row">
                                            <h3 class="fs-6">Recipient gets</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="receive_money" type="text" class="form--control w-100 number-input" name="receive_money" value="120">
                                                    
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="receiver_currency" class="receiver_currency" value="120">
                                                                <img src="assets/images/flag/au.svg" alt="">
                                                                <span class="custom-currency">AUD</span>
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
                                                                                                                                                                                                              <li class="custom-option active">
                                                                        <img src="assets/images/flag/au.svg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Nigerian naira</span>
                                                                        <span class="custom-currency">NGN</span>
                                                                    </li>
                                                                                                                                                                                                              <li class="custom-option">
                                                                            <img src="assets/images/flag/eth.webp" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">Kenyan shilling</span>
                                                                            <span class="custom-currency">KES</span>
                                                                    </li>
                                                                                                                                                                                                              <li class="custom-option">
                                                                        <img src="assets/images/flag/br.svg" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Central African CFA franc</span>
                                                                         <span class="custom-currency">XAF</span>
                                                                    </li>
                                                                                                                                                                                                              <li class="custom-option">
                                                                         <img src="assets/images/flag/tn.svg">
                                                                        <span class="custom-country">West African CFA franc</span>
                                                                        <span class="custom-currency">XOF</span>
                                                                    </li>
                                                                                                                                                                                                              <li class="custom-option">
                                                                        <img src="assets/images/flag/cn.svg">
                                                                        <span class="custom-country">Indian rupee</span>
                                                                        <span class="custom-currency">INR</span>
                                                                    </li>
                                                                                                                                                                                                              <li class="custom-option">
                                                                        <img src="assets/images/flag/flags.png" alt="flag" class="custom-flag">
                                                                        <span class="custom-country">Pakistani rupee</span>
                                                                        <span class="custom-currency">PKR</span>


                                                                                                                                                                                                              </li>                                                                                                               
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
                                            <label>Sender</label>
                                            <div class="input-fild">
                                                <select class="nice-select trx-type-select" name="type">
                                                    <option class="custom-option">David Huk</option>
                                                    <option class="custom-option">Biden Abet</option>
                                                    <option class="custom-option">Nita</option>
                                                </select>
                                                <div class="add-sender">
                                                    <a href="add-sender.html" class="btn"><i class="las la-plus"></i> Add Sender</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-footer-content mt-10-none mb-20">
                                        <div class="sender-input">
                                            <label>Recipient</label>
                                            <div class="input-fild">
                                                <select class="nice-select trx-type-select" name="type">
                                                    <option class="custom-option">David Huk</option>
                                                    <option class="custom-option">Biden Abet</option>
                                                    <option class="custom-option">Nita</option>
                                                </select>
                                                <div class="add-sender">
                                                    <a href="add-recipient.html" class="btn"><i class="las la-plus"></i> Add Recipient</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group transaction-type mb-20">             
                                        <div class="transaction-title">
                                            <label>Receiving Method</label>
                                        </div>
                                        <div class="transaction-type-select">
                                            <select class="nice-select trx-type-select" name="type">
                                                    
                                                    <option class="custom-option">Bank Transfer</option>
                                                    <option class="custom-option">Mobile Money</option>
                                                    <option class="custom-option">Cash Pic Up</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <a href="#" type="button" class="btn--base w-100">Send</a>
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
                        <h4 class="title">Summary</h4>
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
                                            <span>Sending Amount</span>
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
                                            <i class="las la-exchange-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>Exchange Rate</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>1 USD = 462.88 NGN</span>
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
                                    <span class="text--warning">3.00 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="lab la-get-pocket"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>Amount We’ll Convert</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--danger">0.57 USD</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-money-check-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span><b>Will Get Amount</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--info"><b>44899.36 NGN</b></span>
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
                <h4 class="title">Send Remittance Log</h4>
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