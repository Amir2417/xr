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
                <h4 class="title">Profitable Log</h4>
            </div>
        </div>
        <div class="profit-log">
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    <div class="date"><p>09</p></div>
                                    <div class="month text--base"><p>February</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">Money In</h4>
                                    <span class="badge badge--success">#AD2022JAN5</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>1.50 USD</h4>
                            <h6 class="exchange-money">50.00 USD</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    <div class="date"><p>08</p></div>
                                    <div class="month text--base"><p>February</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">Send Remittance</h4>
                                    <span class="badge badge--success">#AD2022JAN5</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>1.50 USD</h4>
                            <h6 class="exchange-money">50.00 USD</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    <div class="date"><p>05</p></div>
                                    <div class="month text--base"><p>February</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">Money In</h4>
                                    <span class="badge badge--success">#AD2022JAN5</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>2.00 USD</h4>
                            <h6 class="exchange-money">100.00 USD</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    <div class="date"><p>02</p></div>
                                    <div class="month text--base"><p>February</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">Money Out</h4>
                                    <span class="badge badge--success">#AD2022JAN5</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>1.50 USD</h4>
                            <h6 class="exchange-money">50.00 USD</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="trx-time">
                                    <div class="date"><p>01</p></div>
                                    <div class="month text--base"><p>February</p></div>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">Money In</h4>
                                    <span class="badge badge--success">#AD2022JAN5</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base"><span>+</span>2.00 USD</h4>
                            <h6 class="exchange-money">100.00 USD</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
