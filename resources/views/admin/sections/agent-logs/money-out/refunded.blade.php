@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
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
    ], 'active' => __("Refunded Logs")])
@endsection

@section('content')

<div class="table-area">
    <div class="table-wrapper">
        <div class="table-header">
            <h5 class="title">{{ __($page_title) }}</h5>
            <div class="table-btn-area">
                @include('admin.components.search-input',[
                    'name'  => 'refunded_search',
                ])
            </div>
        </div>
        <div class="table-responsive">
            @include('admin.components.data-table.agent.money-out-table',[
                'data'  => $transactions
            ])
            
        </div>
    </div>
    {{ get_paginate($transactions) }}
</div>

@endsection
@push('script')
    <script>
        itemSearch($("input[name=refunded_search]"),$(".search-table"),"{{ setRoute('admin.agent.money.out.logs.refunded.search') }}",1);
    </script>
@endpush