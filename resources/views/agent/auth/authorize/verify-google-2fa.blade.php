
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title ?? $basic_settings->agent_site_name }}</title>
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

    @stack('css')
</head>

<body>
    <div id="body-overlay" class="body-overlay"></div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Account
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="account forgot">
    <div class="account-area">
        <div class="account-wrapper change-form">
            <div class="account-logo text-center">
                <a href="{{ setRoute('index') }}" class="site-logo">
                    <img src="{{ get_logo_agent($basic_settings) }}" alt="logo">
                </a>
            </div>
            <h3 class="title">{{ __("Two Factor Authorization") }}</h3>
            <p>{{ __("Please enter your authorization code to access dashboard") }}</p>
            <form method="POST" class="account-form" action="{{ setRoute('agent.authorize.google.2fa.submit') }}">
                @csrf
                <div class="row ml-b-20">
                    <div class="col-lg-12 form-group">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(1)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(2)" maxlength="2" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(3)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(4)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(5)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(6)" maxlength="1" required="">
                    </div>
                    <div class="col-lg-12 form-group text-center">
                        <button type="submit" class="btn--base w-100">{{ __("Verify Mail") }}</button>
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="account-item">
                            <label>{{ __("Already Have An Account?") }} <a href="{{ setRoute('agent.login') }}" class="account-control-btn">{{ __("Login Now") }}</a></label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Account
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('partials.footer-asset')
@include('admin.partials.notify')
</body>
</html>

@push('script')
    <script>
          let digitValidate = function (ele) {
            ele.value = ele.value.replace(/[^0-9]/g, '');
        }

        let tabChange = function (val) {
            let ele = document.querySelectorAll('.otp');
            if (ele[val - 1].value != '') {
                ele[val].focus()
            } else if (ele[val - 1].value == '') {
                ele[val - 2].focus()
            }
        }
    </script>
@endpush
