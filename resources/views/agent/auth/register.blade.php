@php
    $app_local  = get_default_language_code();
    $default    = App\Constants\LanguageConst::NOT_REMOVABLE;
    $slug       = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::AGENT_REGISTER_SECTION);
    $register   = App\Models\Admin\SiteSections::getData($slug)->first();
@endphp
@extends('agent.layouts.user_auth')

@section('content')
<section class="agent-access">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="agent-login-form">
                    <form action="{{ route('agent.send.code') }}" method="POST">
                        @csrf
                        <div class="agent-logo-icon">
                            <img src="{{ get_logo_agent($basic_settings) }}" alt="logo">
                        </div>
                        <div class="form-header">
                            <h3 class="title">{{ @$register->value->language->$app_local->heading ?? @$register->value->language->$default->heading }}</h3>
                            <p>{{ @$register->value->language->$app_local->sub_heading ?? @$register->value->language->$default->sub_heading }}</p>
                        </div>
                        <div class="form-input-fild">
                            <div class="row mb-10-none">
                                <div class="form-group col-lg-12 mb-10">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form--control" placeholder="Enter Email Address">
                                </div>
                                @if($basic_settings->agent_agree_policy)
                                    <div class="form-group">
                                        <div class="custom-check-group">
                                            @php
                                                $data = App\Models\Admin\UsefulLink::where('type',global_const()::USEFUL_LINK_PRIVACY_POLICY)->first();
                                            @endphp
                                            <input type="checkbox" id="level-1" name="agree" required>
                                            <label for="level-1">{{ __("I have agreed with") }} <a href="{{ setRoute('link',$data->slug) }}">{{ __("Terms Of Use & Privacy Policy") }}</a></label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="account-btn">
                                <button type="submit"  class="btn--base w-100">{{ __("Registration") }}</button>
                            </div>
                            <div class="footer-text">
                                <p class="d-block text-center mt-3 create-acc">
                                    &mdash; {{ __("Already Have An Account?") }}
                                    <a href="{{ setRoute('agent.login') }}" class="text--base">{{ __("Login Now") }}</a>
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

@push('script')
@endpush
