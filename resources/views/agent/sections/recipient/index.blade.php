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
    <div class="my-sender-area">
        <div class="add-recipient-btn text-end pb-3">
            <a href="{{ setRoute('agent.recipient.create') }}" class="btn--base">+ {{ __("Add New Recipient") }} </a>
        </div>
        @foreach ($recipients ?? [] as $item)
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item receive d-flex justify-content-between">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">{{ $item->first_name }} {{ $item->last_name }}</h4>
                                <span class="badge badge--warning">{{ __($item->method) ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-list-button">
                        <a href="add-recipient.html" class="btn edit-modal-button recipient-btn"><i class="las la-pencil-alt"></i></a>
                        <button type="button" class="btn delete-recipient delate-btn" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="las la-trash-alt"></i></button>
                    </div>
                </div>
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-user"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Name") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->first_name ?? '' }} {{ $item->last_name ?? '' }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-envelope"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Email") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->email ?? 'N/A'}} </span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-globe"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Country") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->country }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-map-marked-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("City & State") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->city ?? 'N/A'}} ({{ $item->state ?? 'N/A' }})</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-phone"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Phone") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                    @if ($item->method == global_const()::TRANSACTION_TYPE_BANK)
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-university"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Bank Name") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->bank_name ?? '' }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($item->method == global_const()::TRANSACTION_TYPE_MOBILE)
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-university"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Mobile Name") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->mobile_name ?? '' }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($item->method == global_const()::TRANSACTION_TYPE_CASHPICKUP)
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-university"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Pickup Point") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->pickup_point ?? '' }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($item->method == global_const()::TRANSACTION_TYPE_BANK || $item->method == global_const()::TRANSACTION_TYPE_MOBILE)
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-user-circle"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Account Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->iban_number ?? $item->account_number }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection