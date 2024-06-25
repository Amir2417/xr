@php
    $app_local  = get_default_language_code();
    $default    = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug       = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::AGENT_LOGIN_SECTION);
    $login      = App\Models\Admin\SiteSections::getData($slug)->first();
@endphp
@extends('agent.layouts.user_auth')


@section('content')
   
<section class="agent-access">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="agent-login-form">
                    <form class="account-form" action="{{ setRoute('agent.login.submit') }}" method="POST">
                        @csrf
                        <div class="agent-logo-icon">
                            <img src="{{ get_logo_agent($basic_settings) }}" alt="logo">
                        </div>
                        <div class="form-header">
                            <h3 class="title">{{ @$login->value->language->$app_local->heading ?? @$login->value->language->$default->heading }}</h3>
                            <p>{{ @$login->value->language->$app_local->sub_heading ?? @$login->value->language->$default->sub_heading }}</p>
                        </div>
                        <div class="form-input-fild">
                            <div class="row mb-10-none">
                                <div class="form-group col-lg-12 mb-10">
                                    <label>{{ __("Email Address") }}</label>
                                    <input type="email" name="credentials" class="form--control" placeholder="{{ __("Enter Email Address") }}">
                                </div>
                                <div class="form-group show_hide_password col-lg-12 mb-10">
                                    <label>{{ __("Password") }}</label>
                                    <input type="password" name="password" class="form--control" placeholder="{{ __("Enter Password") }}..">
                                    <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="forgot-item text-end pt-2">
                                <label><a href="{{ setRoute('agent.password.forgot') }}" class="text--base">{{ __("Forgot Password?") }}</a></label>
                            </div>
                            <div class="account-btn">
                                <button type="submit" class="btn--base w-100">{{ __("Login Now") }}</button>
                            </div>
                            <div class="footer-text">
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; {{ __("Don’t Have An Account?") }}
                                    <a href="{{ setRoute('agent.register') }}" class="text--base">{{ __("Register Now") }}</a>
                                    &mdash;
                                </p>
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; {{ __("Back To") }}
                                    <a href="{{ setRoute('index') }}" class="text--base">{{ __("Home") }}</a>
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





