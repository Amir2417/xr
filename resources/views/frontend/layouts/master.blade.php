<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $current_url = URL::current();
    @endphp

    @if($current_url == setRoute('index'))
        <title>{{$basic_settings->site_name ?? ''}}  - {{ $basic_settings->site_title ?? "" }}</title>
    @else
        <title>{{$basic_settings->site_name ?? ''}}  {{ $page_title ?? '' }}</title>
    @endif
    
    @include('partials.header-asset')
    @stack('css')
</head>

<body class="{{ get_default_language_dir() }}">
    
    @include('frontend.partials.preloader')


    <div id="body-overlay" class="body-overlay"></div>

    @include('frontend.partials.header')

    @yield('content')




    


   @include('frontend.partials.footer')



    @include('partials.footer-asset')
    @include('admin.partials.notify')
    @include('frontend.partials.extensions.tawk-to')
    @stack('script')
    
    <script>
        $("#country_selector").countrySelect({
            defaultCountry: "us",
            responsiveDropdown: true,
        });
        $("#country_selector2").countrySelect({
            defaultCountry: "ng",
            responsiveDropdown: true,
        });
        $("#country_selector3").countrySelect({
            defaultCountry: "canada",
            responsiveDropdown: true,
        });
    </script>
    <script>
        var swiper = new Swiper(".client-slider", {
            slidesPerView: 3,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                speed: 1000,
                delay: 3000,
            },
            speed: 1000,
            breakpoints: {
                '480': {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                '768': {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                '991': {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
            },
        });
        var swiper = new Swiper(".mySwiper2", {
            slidesPerView: 5,
            spaceBetween: 40,
            freeMode: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false
            },
            breakpoints: {
                '480': {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                '768': {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                '820': {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
                '912': {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
            },
        });
    </script>
</body>

</html>