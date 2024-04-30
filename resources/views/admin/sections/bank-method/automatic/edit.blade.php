@extends('admin.layouts.master')

@push('css')
<style>
    .highlight {
        background-color: #f1f1f1;
        padding: 2px 10px;
        font-weight: bold;         
    }
</style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
    <div class="custom-card mb-2">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.bank.method.automatic.update',$bank_method_automatic->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row mb-10-none">
                    <div class="col-xl-12   col-lg-12 form-group">
                        <label>{{ __("Name") }}*</label>
                        <input type="text" readonly class="form--control text-capitalize" name="api_method" value="{{ $bank_method_automatic->config->name }}">

                    </div>
                    <div class="col-xl-12 col-lg-12 form-group configForm" id="flutterwave">
                        <div class="row" >
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>{{ __("Secret Key") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-key"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_secret_key" value="{{ @$bank_method_automatic->config->flutterwave_secret_key }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>{{ __("Secret Hash") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-hashtag"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_secret_hash" value="{{ @$bank_method_automatic->config->flutterwave_secret_hash }}">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Base Url") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-link"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_url" value="{{ @$bank_method_automatic->config->flutterwave_url }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Update"),
                            'permission'    => "admin.bank.method.automatic.update"
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __("Flutterwave supported Countries and Bank Lists") }}</h6>
        </div>
        <div class="card-body">
            <div class="row mb-30-none">
                <!-- First Column -->
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('NG');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Nigeria: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="nigeria-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-nigeria">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list nigeria">
                            
                            @foreach ($banks as $item)
                                <li class="nigeria-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('GH');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Ghana: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="ghana-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-ghana">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list ghana">
                            
                            @foreach ($banks as $item)
                                <li class="ghana-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('KE');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Kenya: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="text" class="form--control" placeholder="Search here...">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list">
                            
                            @foreach ($banks as $item)
                                <li>{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
        
                <!-- Second Column -->
                {{-- <div class="col-lg-4">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Bank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ghana</td>
                                <td>
                                    <!-- Placeholder content -->
                                    Ghana Bank Name
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
        
                <!-- Third Column -->
                {{-- <div class="col-lg-4">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Bank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ghana</td>
                                <td>
                                    <!-- Placeholder content -->
                                    Ghana Bank Name
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </div>
        
    </div>
@endsection
@push('script')
<script>
    $('#search-nigeria').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.nigeria-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.nigeria-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            highlightMatchingBanks(bank_array, text);
        }

    });
    $('#search-ghana').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.ghana-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.nigeria-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            highlightMatchingBanks(bank_array, text);
        }

    });
    function highlightMatchingBanks(array, text) {
        const lowerTerm = text.toLowerCase();

      
        const bankListItems = $('.bank-list li.nigeria-bank-list'); 

        
        bankListItems.removeClass('highlight'); 

        
        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.nigeria-bank-list').hasClass('highlight')) {
                $('.bank-list.nigeria').animate({
                    scrollTop: $('.bank-list li.nigeria-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    
</script>
    <script>
        (function ($) {
            "use strict";
            var method = '{{ @$bank_method_automatic->config->name}}';
            if (!method) {
                method = 'flutterwave';
            }

            apiMethod(method);
            $('select[name=api_method]').on('change', function() {
                var method = $(this).val();
                apiMethod(method);
            });

            function apiMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'other') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

        })(jQuery);

    </script>
    
@endpush
