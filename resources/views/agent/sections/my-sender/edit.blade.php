@extends('agent.layouts.master')

@section('breadcrumb')
    @include('agent.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("agent.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
<div class="body-wrapper">
    <form action="{{ setRoute('agent.my.sender.update',$my_sender->slug) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="add-sender-info">
            <div class="recipient-add-title">
                <div class="form-title">
                    <h3 class="title">{{ __($page_title) }}</h3>
                </div>
            </div>
            <div class="row mb-20-none pt-20">
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("First Name") }} <span>*</span></label>
                    <input type="text" name="first_name" value="{{ @$my_sender->first_name }}" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Last Name") }} <span>*</span></label>
                    <input type="text" name="last_name" value="{{ @$my_sender->last_name }}" class="form--control" placeholder="{{ __("Enter Name") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label class="email">{{ __("Email Address") }}</label>
                    <input type="email" name="email" value="{{ @$my_sender->email }}" class="form--control" placeholder="{{ __("Enter Email") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("Phone Number") }} <span>*</span></label>
                    <input type="number" name="phone" value="{{ @$my_sender->phone }}" class="form--control" placeholder="{{ __("Enter Number") }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group unregistered-user-country">
                    <label>{{__("Country")}}<span>*</span></label>
                    <select class="form--control select2-auto-tokenize country-select" data-placeholder="Select Country" name="country" data-old="{{ old('country',$my_sender->country ?? "") }}"></select>
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("State") }} <span>*</span></label>
                    <input type="text" name="state" value="{{ @$my_sender->state }}" class="form--control" placeholder="{{ __("Enter State") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("City") }} <span>*</span></label>
                    <input type="text" name="city" value="{{ @$my_sender->city }}" class="form--control" placeholder="{{ __("Enter City") }}">
                </div>
                <div class="col-lg-6 col-md-6 mb-20">
                    <label>{{ __("ZIP Code") }} <span>*</span></label>
                    <input type="text" name="zip_code" value="{{ @$my_sender->zip_code }}" class="form--control" placeholder="{{ __("Enter ZIP Code") }}">
                </div>
                <div class="col-lg-4 col-md-6 mb-20">
                    <label>{{ __("Document Type") }}</label>
                    <select class="form--control nice-select" name="id_type">
                        <option selected disabled>{{ __("Select Document") }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_NID }}" @if(@$my_sender->id_type == global_const()::DOCUMENT_TYPE_NID) selected @endif>{{ global_const()::DOCUMENT_TYPE_NID }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_PASSPORT }}" @if(@$my_sender->id_type == global_const()::DOCUMENT_TYPE_PASSPORT) selected @endif>{{ global_const()::DOCUMENT_TYPE_PASSPORT }}</option>
                        <option value="{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}" @if(@$my_sender->id_type == global_const()::DOCUMENT_TYPE_DRIVING_LICENCE) selected @endif>{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}</option>
                    </select>
                </div>
                <div class="col-lg-4 md-6 mb-20">
                    <div class="file-holder-wrapper">
                        @include('admin.components.form.input-file',[
                            'label'             => __("Front Part"),
                            'class'             => "file-holder",
                            'name'              => "front_part",
                            'old_files'         => $my_sender->front_part,
                            'old_files_path'    => files_asset_path('my-sender'),
                        ])
                    </div>
                </div>
                <div class="col-lg-4 md-6 mb-20">
                    <div class="file-holder-wrapper">
                        @include('admin.components.form.input-file',[
                            'label'             => __("Back Part"),
                            'class'             => "file-holder",
                            'name'              => "back_part",
                            'old_files'         => $my_sender->back_part,
                            'old_files_path'    => files_asset_path('my-sender'),
                        ])
                    </div>
                </div>
            </div>
            <div class="add-btn pt-30">
                <button type="submit" class="btn--base w-100">{{ __("Add Now") }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
<script>
    getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
        $(document).ready(function(){

            $(".country-select").select2();

            $("select[name=country]").change(function(){
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);
        });
</script>


@endpush