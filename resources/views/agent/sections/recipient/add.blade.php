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
    <div class="agent-recipient-area">
        <div class="select-recipient-type">
            <h3 class="title">Add New Recipient</h3>
            <div class="row mb-20-none pt-30">
                <div class="col-lg-12 col-md-12 mb-20">
                    <label>Transaction Type <span>*</span></label>
                    <select id="transactionType" class="payment-select nice-select">                         
                        <option value="bt">Bank Transfer</option>
                        <option value="mm">Mobile Money</option>
                        <option value="cp">Cash Pickup </option>
                    </select>
                </div>
            </div>
        </div>
        <div id="bt-view" class="add-bank-recipient recipient-single-item">
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Select Country <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">United State</option>
                        <option value="1">United Kingdom</option>
                        <option value="2">Australia</option>
                    </select>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Email Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter Email">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Phone Number <span>*</span></label>
                    <input type="number" class="form--control" placeholder="Enter Number">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>First Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Last Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>State <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>City <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter City">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>ZIP Code <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter ZIP Code">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Select Bank <span>*</span></label>
                    <select class="form--control nice-select">
                        <option value="">American Bank</option>
                        <option value="1">Kingdom Bank</option>
                    </select>
                </div>
            </div>
            <div class="add-btn pt-30">
                <a href="#" type="button" class="btn--base w-100">Add Now</a>
            </div>
        </div>
        <div id="mm-view" class="add-mobile-recipient recipient-single-item">
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Select Country <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">United State</option>
                        <option value="1">United Kingdom</option>
                        <option value="2">Australia</option>
                    </select>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Email Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter Email">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Phone Number <span>*</span></label>
                    <input type="number" class="form--control" placeholder="Enter Number">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>First Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Last Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>State <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>City <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter City">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>ZIP Code <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter ZIP Code">
                </div>
            </div>
            <div class="add-btn pt-30">
                <a href="#" type="button" class="btn--base w-100">Add Now</a>
            </div>
        </div>
        <div id="cp-view" class="cash-pickup-recipient recipient-single-item">
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Select Country <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">United State</option>
                        <option value="1">United Kingdom</option>
                        <option value="2">Australia</option>
                    </select>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Email Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter Email">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Phone Number <span>*</span></label>
                    <input type="number" class="form--control" placeholder="Enter Number">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>First Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Last Name <span>*</span></label>
                    <input type="text" class="form--control" placeholder="Enter Name">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Address <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>State <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter State">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>City <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter City">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>ZIP Code <span>*</span></label>
                    <input type="email" class="form--control" placeholder="Enter ZIP Code">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>Pickup Point <span>*</span></label>
                    <select class="form--control nice-select">                         
                        <option value="">Los Angeles</option>
                        <option value="1">New York</option>
                        <option value="2">Chicago</option>
                    </select>
                </div>
            </div>
            <div class="add-btn pt-30">
                <a href="#" type="button" class="btn--base w-100">Add Now</a>
            </div>
        </div>
    </div>
</div>
@endsection