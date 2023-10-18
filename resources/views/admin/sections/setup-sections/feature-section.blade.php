@php
    $default_lang_code   = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.css') }}">
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
    ], 'active' => __("Feature Section")])
@endsection

@section('content')

<div class="custom-card">
    <div class="card-header">
        <h6 class="title">{{ __($page_title) }}</h6>
    </div>
    <div class="card-body">
        <form class="card-form" action="{{ setRoute('admin.setup.sections.section.update',$slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center mb-10-none">
                <div class="col-xl-12 col-lg-12">
                    <div class="product-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach ($languages as $item)
                                    <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#{{$item->name}}" type="button" role="tab" aria-controls="{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                @endforeach
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($languages as $item)
                                @php
                                    $lang_code = $item->code;
                                @endphp
                                <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="{{ $item->name }}" role="tabpanel" aria-labelledby="english-tab">
                                    <div class="form-group">
                                        @include('admin.components.form.input',[
                                            'label'     => "Site Title*",
                                            'name'      => $lang_code . "_title",
                                            'value'     => old($lang_code . "_title",$data->value->language->$lang_code->title ?? "")
                                        ])
                                    </div>
                                    <div class="form-group">
                                        @include('admin.components.form.textarea',[
                                            'label'     => "Heading*",
                                            'name'      => $lang_code . "_heading",
                                            'value'     => old($lang_code . "_heading",$data->value->language->$lang_code->heading ?? "")
                                        ])
                                    </div>                                                                  
                                </div>
                            @endforeach                          
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    @include('admin.components.button.form-btn',[
                        'class'         => "w-100 btn-loading",
                        'text'          => "Submit",
                        'permission'    => "admin.setup.sections.section.update"
                    ])
                </div>
            </div>
        </form>
    </div>
</div>
<div class="table-area mt-15">
    <div class="table-wrapper">
        <div class="table-header justify-content-end">
            <div class="table-btn-area">
                <a href="#feature-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Item") }}</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data->value->items ?? [] as $key => $item)
                        <tr data-item="{{ json_encode($item) }}">
                            <td>
                                <ul class="user-list">
                                    <li><img src="{{ get_image($item->image ?? null,'site-section')}}" alt=""></li>
                                </ul>
                            </td>
                            <td> {{ $item->language->$system_default_lang->item_title ?? "" }} </td>
                            <td>
                                <button class="btn btn--base edit-modal-button"><i class="las la-pencil-alt"></i></button>
                                <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 4])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.components.modals.site-section.add-feature-item')

{{-- @dd($languages) --}}


<div id="feature-edit" class="mfp-hide large">
    <div class="modal-data">
        <div class="modal-header px-0">
            <h5 class="modal-title">{{ __("Edit Items") }}</h5>
        </div>
        <div class="modal-form-data">
            <form class="modal-form" method="POST" action="{{ setRoute('admin.setup.sections.section.item.update',$slug) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="target" value="{{ old('target') }}">
                <div class="row mb-10-none mt-3">
                    <div class="language-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach ($languages as $item)
                                    <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="modal-{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#modal-{{$item->name}}" type="button" role="tab" aria-controls="modal-{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                @endforeach
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($languages as $item)
                                @php
                                    $lang_code = $item->code;
                                @endphp
                                <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="modal-{{ $item->name }}" role="tabpanel" aria-labelledby="modal-{{$item->name}}-tab">
                                    <div class="form-group">
                                        @include('admin.components.form.input',[
                                            'label'     => "Title*",
                                            'name'      => $lang_code . "_item_title_edit",
                                            'value'     => old($lang_code . "_item_title_edit",$data->value->language->$lang_code->item_title ?? "")
                                        ])
                                    </div>
                                    <div class="form-group">
                                        @include('admin.components.form.textarea',[
                                            'label'     => "Description *",
                                            'name'      => $lang_code . "_description_edit",
                                            'value'     => old($lang_code . "_description_edit",$data->value->language->$lang_code->description ?? "")
                                        ])
                                    </div> 
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        @include('admin.components.form.input-file',[
                            'label'             => "Section Image:",
                            'name'              => "image",
                            'class'             => "file-holder",
                            'old_files_path'    => files_asset_path("site-section"),
                            'old_files'         => old("old_image"),
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('script')

<script src="{{ asset('public/backend/js/fontawesome-iconpicker.js') }}"></script>
<script>
    // icon picker
    $('.icp-auto').iconpicker();
</script>
<script>
    
    openModalWhenError("feature-add","#feature-add");
    openModalWhenError("feature-edit","#feature-edit");

    var default_language = "{{ $default_lang_code }}";
    var system_default_language = "{{ $system_default_lang }}";
    var languages = "{{ $languages_for_js_use }}";
    languages = JSON.parse(languages.replace(/&quot;/g,'"'));

    // edit item modal show with value
    $('.edit-modal-button').click(function(){
        var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
        var editModal = $("#feature-edit");

        editModal.find("form").first().find("input[name=target]").val(oldData.id);

        $.each(languages,function(index,item){
            editModal.find("input[name="+item.code+"_item_title_edit]").val((oldData.language[item.code] == undefined ) ? '' : oldData.language[item.code].item_title);
            editModal.find("textarea[name="+item.code+"_description_edit]").val((oldData.language[item.code] == undefined ) ? '' : oldData.language[item.code].description);  
        });
        
        editModal.find("input[name=image]").attr("data-preview-name",oldData.image);
        fileHolderPreviewReInit("#feature-edit input[name=image]");
        openModalBySelector("#feature-edit");
    });

    //delete item modal show
    $('.delete-modal-button').click(function(){
        var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
        var actionRoute = "{{ setRoute('admin.setup.sections.section.item.delete',$slug) }}";
        var target      = oldData.id;
        var message     = `Are you sure to <strong>delete</strong> this item?`;

        openDeleteModal(actionRoute,target,message);
    });


</script>

@endpush