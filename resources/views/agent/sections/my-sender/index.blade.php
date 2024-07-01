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
            <a href="{{ setRoute('agent.my.sender.create') }}" class="btn--base">+ {{ __("Add New Sender") }} </a>
        </div>
        @forelse ($my_senders ?? [] as $item)
        <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item receive d-flex justify-content-between">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-content">
                            <h5 class="title">{{ @$item->first_name }} {{ @$item->last_name }}</h5>
                        </div>
                    </div>
                </div>
                <div class="dashboard-list-button">
                    <a href="add-sender.html" class="btn edit-modal-button recipient-btn"><i class="las la-pencil-alt"></i></a>
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
                        <span>{{ @$item->first_name }} {{ @$item->last_name }}</span>
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
                        <span>{{ @$item->country }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="lab la-centercode"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Zip Code") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--danger">{{ @$item->zip_code }}</span>
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
                        <span>{{ @$item->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-primary text-center">
            {{ __("My Sender Not Found!") }}
        </div>
        @endforelse
    </div>
    {{ get_paginate($my_senders) }}
</div>
@endsection