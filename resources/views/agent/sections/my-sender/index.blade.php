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
            <a href="add-sender.html" class="btn--base">+ Add New Sender </a>
         </div>
         <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item receive d-flex justify-content-between">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-profile">
                            <img src="assets/images/client/client-1.jpg">
                        </div>
                        <div class="dashboard-list-user-content">
                            <h5 class="title">Alin Alva</h5>
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
                                <span>Name</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>Alin Alva</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-globe"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Country</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>USA</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="lab la-centercode"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Zip Code</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--danger">96884</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-envelope"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Email</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>demo@email.com</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item receive d-flex justify-content-between">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-profile">
                            <img src="assets/images/client/client-2.jpg">
                        </div>
                        <div class="dashboard-list-user-content">
                            <h5 class="title">David Summer</h5>
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
                                <span>Name</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>Alin Alva</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-globe"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Country</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>USA</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="lab la-centercode"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Zip Code</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--danger">96884</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-envelope"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Email</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>demo@email.com</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item receive d-flex justify-content-between">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-profile">
                            <img src="assets/images/client/client-3.jpg">
                        </div>
                        <div class="dashboard-list-user-content">
                            <h5 class="title">Silpa Sikrim</h5>
                        </div>
                    </div>
                </div>
                <div class="dashboard-list-button">
                    <a href="add-sender.html" class="btn  edit-modal-button recipient-btn"><i class="las la-pencil-alt"></i></a>
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
                                <span>Name</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>Alin Alva</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-globe"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Country</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>USA</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="lab la-centercode"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Zip Code</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span class="text--danger">96884</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-envelope"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>Email</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>demo@email.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection