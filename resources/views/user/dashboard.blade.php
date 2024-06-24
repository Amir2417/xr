@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="row mt-20">
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Total Send Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ get_amount($data['total_amount']) ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Total Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['total_remittances'] ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Completed Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase"> {{ formatNumberInkNotation($data['complete']) }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-cloud-upload-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Ongoing Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['ongoing'] ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Canceled Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['cancel'] }}</span>
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
    <div class="chart-area mt-40">
        <div class="row mb-20-none">
            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-20">
                <div class="chart-wrapper">
                    <div class="dashboard-header-wrapper">
                        <h4 class="title">{{ __("Total Transactions Chart") }}</h4>
                    </div>
                    <div class="chart-container">
                        <div id="chart1" data-chart_one_data="{{ json_encode($data['chart_one_data']) }}" data-month_day="{{ json_encode($data['month_day']) }}" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area ptb-40">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Send Remittance Log") }}</h4>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn">
                    <a href="{{ setRoute('user.transaction.index') }}" class="btn--base">{{ __("View More") }}</a>
                </div>
            </div>
        </div>
        @include('user.components.transaction-log.index',[
            'data'  => $transactions
        ])
    </div>
</div>
@endsection
@push('script')

<script>
    $('.copy').on('click',function(){

        let input = $('.box').val();
        navigator.clipboard.writeText(input)
        .then(function() {

            $('.copy').text("Copied");
        })
        .catch(function(err) {
            console.error('Copy failed:', err);
        });
    });
</script>
<script>
    var chart1 = $('#chart1');
    var chart_one_data = chart1.data('chart_one_data');
    var month_day = chart1.data('month_day');

    var options = {
      series: [{
          name: '{{ __("Pending") }}',
          color: "#8358ff",
          data: chart_one_data.pending_data
      }, {
          name: '{{ __("Complete") }}',
          data: chart_one_data.complete_data
      }],
      chart: {
          height: 350,
          type: 'area',
          toolbar: {
              show: false
          },
      },
      dataLabels: {
          enabled: false
      },
      stroke: {
          curve: 'smooth'
      },
      xaxis: {
          type: 'datetime',
          categories: month_day,
      },
      tooltip: {
          x: {
              format: 'dd/MM/yy HH:mm'
          },
      },
  };

  var chart = new ApexCharts(document.querySelector("#chart1"), options);
  chart.render();


  var options = {
      series: [{
          data: [44, 55, 41, 64, 22, 43, 21],
          color: "#8358ff"
      }, {
          data: [53, 32, 33, 52, 13, 44, 32]
      }],
      chart: {
          type: 'bar',
          toolbar: {
              show: false
          },
          height: 350
      },
      plotOptions: {
          bar: {
              horizontal: true,
              dataLabels: {
                  position: 'top',
              },
          }
      },
      dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
              fontSize: '12px',
              colors: ['#fff']
          }
      },
      stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
      },
      tooltip: {
          shared: true,
          intersect: false
      },
      xaxis: {
          categories: [2001, 2002, 2003, 2004, 2005, 2006, 2007],
      },
  };

  var chart = new ApexCharts(document.querySelector("#chart2"), options);
  chart.render();
</script>
@endpush
