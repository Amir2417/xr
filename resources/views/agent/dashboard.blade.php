@extends('agent.layouts.master')

@section('breadcrumb')
    @include('agent.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("agent.dashboard"),
        ]
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="body-wrapper">
        <div class="agent-dashboard">
            <div class="row mt-20 mb-20-none">
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>{{ __("Current Balance") }}</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase"> {{ get_amount($agent_wallet->balance,get_default_currency_code()) }}</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>{{ __('Profit Balance') }} </h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase"> {{ get_amount(@$profit_balance,get_default_currency_code()) }}</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-recycle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>Total Remittance</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">03</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-calculator"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>Complete Remittance</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">01</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-redo-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>Ongoing Remittance</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">01</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-spinner"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-20">
                    <div class="dashbord-user">
                        <div class="dashboard-content">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <div class="top-content">
                                        <h3>Canceled Transactions</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">01</span>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="las la-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-chart pt-40">
            <div class="dashboard-header-wrapper">
                <h4 class="title">Transactions Overview</h4>
            </div>
            <div class="chart-container">
                <div id="chart" class="chart"></div>
            </div>
        </div>

        <div class="dashboard-list-area mt-60">
            <div class="log-type d-flex justify-content-between align-items-center mb-40">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">Transaction Log</h4>
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
    
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@endsection
@push('script')
<script>
    var options = {
        series: [{
        name: 'Net Profit',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
      }, {
        name: 'Revenue',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
      }, {
        name: 'Free Cash Flow',
        data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
      }],
        chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
      },
      yaxis: {
        title: {
          text: '$ (thousands)'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return "$ " + val + " thousands"
          }
        }
      }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
</script>
@endpush