<!-- favicon -->
<link rel="shortcut icon" href="{{ get_fav_agent($basic_settings) }}" type="image/x-icon">
<!-- fontawesome css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/fontawesome-all.css') }}">
<!-- bootstrap css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/bootstrap.css') }}">
<!-- swipper css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/swiper.css') }}">
<!-- lightcase css links -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/lightcase.css') }}">
<!-- line-awesome-icon css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/line-awesome.css') }}">
<!-- animate.css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/animate.css') }}">
<!-- Aos CSS -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/aos.css') }}">
<!-- nice-select -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/nice-select.css') }}">
<!-- country-select -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/countrySelect.css') }}">
<!-- select2-select css link -->
<link rel="stylesheet" href="{{ asset('public/backend/css/select2.css') }}">
<!-- main style css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/style.css') }}">
<!-- Fileholder CSS CDN -->
<link rel="stylesheet" href="https://appdevs.cloud/cdn/fileholder/v1.0/css/fileholder-style.css" type="text/css">

@php
    $color = @$basic_settings->agent_base_color ?? '#723eeb';
@endphp

<style>
    :root {
        --primary-color: {{$color}};
    }

</style>
