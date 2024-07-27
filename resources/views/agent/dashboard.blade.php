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
                                        <h3>{{ __("Total Remittance") }}</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">{{ @$total_send_remittance }}</span>
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
                                        <h3>{{ __("Confirm Remittance") }}</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">{{ @$total_confirm_send_remittance }}</span>
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
                                        <h3>{{ __("Canceled Transactions") }}</h3>
                                    </div>
                                    <div class="user-count">
                                        <span class="text-uppercase">{{ @$total_canceled_transactions }}</span>
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
                <h4 class="title">{{ __("Transactions Overview") }}</h4>
            </div>
            <div class="chart-container">
                <div id="chart" class="chart"></div>
            </div>
        </div>
        
        <div class="dashboard-list-area mt-60">
            <div class="log-type d-flex justify-content-between align-items-center mb-40">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Transaction Log") }}</h4>
                </div>
                <div class="view-more-log">
                    <a href="{{ setRoute('agent.transaction.logs.index') }}" type="button" class="btn--base">{{ __("View More") }}</a>
                </div>
            </div>
            @include('agent.components.transaction-logs.index',compact('transactions'))
        </div>
    </div>
    
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@endsection
@push('script')
<script>
    var chartData = @json($data);
    var options = {
        series: [{
        name: '{{ __("Send Remittance") }}',
        data: chartData.send_remittance
      }, {
        name: '{{ __("Money In") }}',
        data: chartData.money_in
      }, {
        name: '{{ __("Money OUT") }}',
        data: chartData.money_out
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
          text: ''
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return "$ " + val 
          }
        }
      }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
</script>
@endpush