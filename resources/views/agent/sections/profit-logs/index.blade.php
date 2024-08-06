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
    <div class="dashboard-list-area mt-30">
        <div class="log-type d-flex justify-content-between align-items-center mb-20">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ @$page_title }}</h4>
            </div>
        </div>
        <div class="profit-log">
            @forelse (@$transactions ?? [] as $item)
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    @php
                                        $date       = \Carbon\Carbon::parse($item->created_at)->format('d');
                                        $month       = \Carbon\Carbon::parse($item->created_at)->format('F');
                                    @endphp
                                    <div class="date"><p>{{ @$date }}</p></div>
                                    <div class="month text--base"><p>{{ @$month }}</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">{{ __(@$item->transaction->type) }}</h4>
                                    <span class="badge badge--success">{{ @$item->transaction->trx_id }}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>{{ get_amount(@$item->total_commissions,get_default_currency_code()) }}</h4>
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
        </div>
    </div>
</div>
@endsection
