<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $basic_settings->site_name }} {{ $page_title ?? '' }}</title>
    @include('partials.header-asset')
    @stack('css')
</head>

<body>

    
    <div id="preloader"></div>
    <div id="body-overlay" class="body-overlay"></div>

    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            Start Preloader
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        @include('frontend.partials.preloader')
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            End Preloader
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="page-wrapper bg_img" data-background="{{ asset('public/frontend/images/element/banner-bg.jpg') }}">
        @include('user.partials.side-nav')
        <div class="main-wrapper">
            <div class="main-body-wrapper">
                @include('user.partials.top-nav')
                @yield('content')
            </div>
        </div>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    @include('partials.footer-asset')
    @include('admin.partials.notify')
    @stack('script')
    <script>

        $(".logout-btn").click(function(){
            var actionRoute =  "{{ setRoute('user.logout') }}";
            var target      = 1;
            var message     = `Are you sure to <strong>Logout</strong>?`;

            openAlertModal(actionRoute,target,message,"Logout","POST");
        });


        var chart1 = $('#chart1');
        var chart_one_data = chart1.data('chart_one_data');
        var month_day = chart1.data('month_day');

        var options = {
          series: [{
              name: 'Pending',
              color: "#8358ff",
              data: chart_one_data.pending_data
          }, {
              name: 'Complete',
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

</body>

</html>