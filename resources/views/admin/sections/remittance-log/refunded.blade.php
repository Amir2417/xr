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
                    'name'  => 'user_search',
                ])
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __("MTCN ID") }}</th>
                        <th>{{ __("S. Name") }}</th>
                        <th>{{ __("R. Name") }}</th>
                        <th>{{ __("T. Type") }}</th>
                        <th>{{ __("Amount") }}</th>
                        <th>{{ __("P. Method") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($transactions ?? [] as $key => $item)
                        <tr>
                            <td>{{ $item->trx_id ?? '' }}</td>
                            <td>{{ $item->remittance_data->sender_name ?? '' }}</td>
                            <td>{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? '' }}</td>
                            <td>{{ $item->remittance_data->type ?? '' }}</td>
                            <td>{{ get_amount($item->payable) ?? '' }} {{ $sender_currency->code ?? '' }} <span>{{ get_amount($item->will_get_amount) ?? '' }} {{ $receiver_currency->code ?? '' }}</span></td>
                            
                            <td>{{ $item->remark ?? '' }}</td>
                            <td>
                                @if ($item->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                    <span>{{ __("Review Payment") }}</span> 
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_PENDING)
                                    <span>{{ __("Pending") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                    <span>{{ __("Confirm Payment") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_HOLD)
                                    <span>{{ __("On Hold") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_SETTLED)
                                    <span>{{ __("Settled") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                                    <span>{{ __("Completed") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_CANCEL)
                                    <span>{{ __("Canceled") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_FAILED)
                                    <span>{{ __("Failed") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_REFUND)
                                    <span>{{ __("Refunded") }}</span>
                                @else
                                    <span>{{ __("Delayed") }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ setRoute('admin.send.remittance.details',$item->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                                
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 8])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($transactions) }}
</div>

@endsection