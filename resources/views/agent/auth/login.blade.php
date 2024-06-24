
@extends('agent.layouts.user_auth')


@section('content')
   
<section class="agent-access">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="agent-login-form">
                    <form>
                        <div class="agent-logo-icon">
                            <img src="{{ get_logo_agent($basic_settings) }}" alt="logo">
                        </div>
                        <div class="form-header">
                            <h3 class="title">Log in and Stay Connected</h3>
                            <p>Our secure login process ensures the confidentiality of your information. Log in today and stay connected to your finances, anytime and anywhere.</p>
                        </div>
                        <div class="form-input-fild">
                            <div class="row mb-10-none">
                                <div class="form-group col-lg-12 mb-10">
                                    <label>Email Address</label>
                                    <input type="email" class="form--control" placeholder="Enter Email Address">
                                </div>
                                <div class="form-group show_hide_password col-lg-12 mb-10">
                                    <label>Password</label>
                                    <input type="password" class="form--control" placeholder="Enter Password..">
                                    <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="forgot-item text-end pt-2">
                                <label><a href="#" class="text--base">Forgot Password?</a></label>
                            </div>
                            <div class="account-btn">
                                <button type="button" class="btn--base w-100">Login Now</button>
                            </div>
                            <div class="footer-text">
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; Don’t Have An Account?
                                    <a href="agent-signup.html" class="text--base">Register Now</a>
                                    &mdash;
                                </p>
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; Back To
                                    <a href="{{ setRoute('index') }}" class="text--base">Home</a>
                                    &mdash;
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection





