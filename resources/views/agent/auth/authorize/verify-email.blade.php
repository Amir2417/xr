@extends('agent.layouts.user_auth')

@push('css')

@endpush

@section('content')
<section class="verification-otp ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-xl-6 col-lg-8 col-md-10 col-sm-12">
                <div class="verification-otp-area">
                    <div class="account-wrapper otp-verification">
                        <div class="account-logo text-center">
                            <a href="{{ setRoute('index') }}" class="site-logo">
                                <img src="{{ get_logo_agent($basic_settings) }}" alt="logo">
                            </a>
                        </div>
                        <div class="verification-otp-content pt-3">
                            <h3 class="title text-center">{{ __("OTP Verification") }}</h3>
                        <p class="d-block text-center">{{ __("Please enter the code. We sent a") }} <strong>{{ __("6-digit") }}</strong> {{ __("code to your email.") }}</p>
                        </div>
                        <form class="account-form pt-20" action="{{ setRoute('agent.verify.code',$token) }}" method="POST">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group text-center">
                                    <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(1)'
                                        maxlength=1 required>
                                    <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(2)'
                                        maxlength=1 required>
                                    <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(3)'
                                        maxlength=1 required>
                                    <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(4)'
                                        maxlength=1 required>
                                    <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(5)'
                                        maxlength=1 required>
                                        <input class="otp" type="text" name="code[]" oninput='digitValidate(this)' onkeyup='tabChange(6)'
                                        maxlength=1 required>
                                </div>
                                <div class="col-lg-12 form-group ">
                                    <div class="time-area">{{ __("You can resend the code after") }} <span id="time"></span></div>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="btn--base btn w-100">{{ __("Submit") }}</button>
                                </div>
                            </div>
                            <div class="footer-text">
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; {{ __("Back To") }}
                                    <a href="{{ setRoute('index') }}" class="text--base">{{ __("Home") }}</a>
                                    &mdash;
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

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
<script>
    function resetTime (second = 60) {
        var coundDownSec = second;
        var countDownDate = new Date();
        countDownDate.setMinutes(countDownDate.getMinutes() + 120);
        var x = setInterval(function () {  // Get today's date and time
            var now = new Date().getTime();  // Find the distance between now and the count down date
            var distance = countDownDate - now;  // Time calculations for days, hours, minutes and seconds  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * coundDownSec)) / (1000 * coundDownSec));
            var seconds = Math.floor((distance % (1000 * coundDownSec)) / 1000);  // Output the result in an element with id="time"
            document.getElementById("time").innerHTML =seconds + "s ";  // If the count down is over, write some text

            if (distance < 0 || second < 2 ) {
                // alert();
                clearInterval(x);
                // document.getElementById("time").innerHTML = "RESEND";
                document.querySelector(".time-area").innerHTML = "Didn't get the code? <a href='{{ setRoute('agent.resend.code') }}' class='text--danger'>Resend</a>";
            }

            second--
        }, 1000);
    }

    resetTime();
</script>


@endpush
