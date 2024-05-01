@extends('admin.layouts.master')

@push('css')

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
            <form class="card-form" action="{{ setRoute('admin.new.user.bonus.update') }}" method="POST">
                @csrf
                @method("PUT")
                <input type="hidden" name="id" value="{{ old('id',@$bonus->id) }}">
                <div class="row mb-10-none">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                        @include('admin.components.form.switcher', [
                            'label'         => 'Status*',
                            'value'         => old('staus',@$bonus->status),
                            'name'          => "staus",
                            'options'       => ['Enable' => 1 , 'Disable' => 0]
                        ])
                    </div>
                    <div class="d-flex">
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __("Price") }}*</label>
                            <input type="text" class="form--control" name="price" placeholder="{{ __("Enter Price") }}" value="{{ old('price',@$bonus->price) }}">
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group ms-1">
                            <label>{{ __("Maximum Used") }}*</label>
                            <input type="text" class="form--control" name="max_used" placeholder="{{ __("Enter Maximum Used") }}" value="{{ old('max_used',@$bonus->max_used) }}">
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Update"),
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')

    
@endpush