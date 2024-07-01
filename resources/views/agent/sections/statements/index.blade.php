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
    <div class="banking-statement">
        <div class="row mb-10-none">
            <div class="col-lg-3 col-md-6 mb-10">
                <label>Filter by Period</label>
                <select class="nice-select">
                    <option value="0">Last 1 Month</option>
                    <option value="1">Last 3 Month</option>
                    <option value="2">Last 6 Month</option>
                    <option value="3">Last 1 Year</option>
                    <option value="4">Select Specific Date</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 mb-10">
                <label>Start Date</label>
                <input type="date" class="form--control" >
            </div>
            <div class="col-lg-3 col-md-6 mb-10">
                <label>End Date</label>
                <input type="date" class="form--control" >
            </div>
            <div class="col-lg-3 col-md-6 mb-10">
                <label>Filter by Period</label>
                <select class="nice-select">
                    <option value="1">All</option>
                        <option value="2">Pending</option>
                        <option value="3">Blocked</option>
                        <option value="4">Returned</option>
                        <option value="7">Rejected</option>
                </select>
            </div>
            <div class="filtaring-area">
                <div class="filter-btn">
                    <button type="button" class="btn--base"><i class="las la-filter"></i> Filter Data</button>
                </div>
                <div class="dawonload-statement">
                    <a href="#0" class="btn--base dawonload-btn"><i class="las la-download"></i> Excel</a>
                    <a href="#0" class="btn--base dawonload-btn"><i class="las la-download"></i> PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection