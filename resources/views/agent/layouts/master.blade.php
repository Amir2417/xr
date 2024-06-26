<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $basic_settings->sitename(__($page_title??'')) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @include('agent.partials.header-assets')
    @stack('css')
</head>
<body class="{{ selectedLangDir() ?? "ltr"}}">


    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start body overlay
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="body-overlay" class="body-overlay"></div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End body overlay
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="page-wrapper bg_img" data-background="{{ asset('public/frontend') }}/images/element/banner-bg.jpg">
        @include('agent.partials.side-nav')
        
        <div class="main-wrapper">
            <div class="main-body-wrapper">
                @include('agent.partials.top-nav')
                @yield('content')
                
            </div>
        </div>
    </div>
    
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Dashboard
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    
    @include('agent.partials.footer-assets')
    @stack('script')
 
    
</body>



</html>
