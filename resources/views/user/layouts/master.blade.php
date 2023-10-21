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

<body class="{{ get_default_language_dir() }}">

    
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
    </script>

</body>

</html>