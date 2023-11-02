@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Add Recipient")])
@endsection


@section('content')
<div class="body-wrapper">
    <div class="row mb-20-none">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Add Recipient") }}</h4>
                </div>
                <div class="card-body">
                    <form class="card-form add-recipient-item" action="{{ setRoute('user.recipient.store',$temporary_data->identifier) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="trx-inputs bt-view" style="display: block;">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('First Name').'*',
                                        'type'            => 'text',
                                        'name'            => 'first_name',
                                        'value'           => old('first_name'),
                                        'placeholder'     => __("Enter First Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Middle Name'),
                                        'type'            => 'text',
                                        'name'            => 'middle_name',
                                        'value'           => old('middle_name'),
                                        'placeholder'     => __("Enter Middle Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Last Name').'*',
                                        'type'            => 'text',
                                        'name'            => 'last_name',
                                        'value'           => old('last_name'),
                                        'placeholder'     => __("Enter Last Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Email'),
                                        'type'            => 'email',
                                        'name'            => 'email',
                                        'value'           => old('email'),
                                        'placeholder'     => __("Enter Email")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 form-group">
                                    <label>{{ __("Country") }}<span>*</span></label>
                                    <select class="form--control select2-basic" name="country">
                                        <option selected disabled>{{ __("Select Country") }}</option>
                                        @foreach ($receiver_currency as $item)
                                            <option value="{{ $item->country }}">{{ $item->country }} </option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('City'),
                                        'type'            => 'text',
                                        'name'            => 'city',
                                        'value'           => old('city'),
                                        'placeholder'     => __("Enter City")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('State'),
                                        'type'            => 'text',
                                        'name'            => 'state',
                                        'value'           => old('state'),
                                        'placeholder'     => __("Enter State")."..."
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Zip Code'),
                                        'type'            => 'text',
                                        'name'            => 'zip_code',
                                        'value'           => old('zip_code'),
                                        'placeholder'     => __("Enter Zip Code")."..."
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Phone'),
                                        'type'            => 'number',
                                        'name'            => 'phone',
                                        'value'           => old('phone'),
                                        'placeholder'     => __("Enter Phone")."..."
                                    ])
                                </div>
                                <div class="form-group transaction-type">
                                    <label>{{ __("Transaction Type") }} <span>*</span></label>
                                    <select class="form--control trx-type-select select2-basic" name="method">
                                        <option value="{{ global_const()::RECIPIENT_METHOD_BANK }}">{{ global_const()::TRANSACTION_TYPE_BANK }}</option>
                                        <option value="{{ global_const()::RECIPIENT_METHOD_MOBILE }}">{{ global_const()::TRANSACTION_TYPE_MOBILE }}</option>
                                    </select>
                                </div>
                                <div class="trx-inputs {{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" style="display: none;">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>{{ __("Mobile Method") }}<span>*</span></label>
                                            <select class="form--control select2-basic" name="mobile_name">
                                                <option selected disabled>{{ __("Select Method") }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>{{ __("Account Number") }}<span>*</span></label>
                                            <input type="number" class="form--control" name="account_number" placeholder="{{ __("Enter Number") }}...">
                                        </div>
                                    </div>
                                </div>
                                <div class="trx-inputs {{ global_const()::RECIPIENT_METHOD_BANK }}-view" style="display: block;">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            <label>{{ __("Bank Name") }}*</label>
                                            <select class="form--control select2-basic" name="bank_name">
                                                <option selected disabled>{{ __("Select Bank") }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                            @include('admin.components.form.input',[
                                                'label'           => __('IBAN Number').'*',
                                                'type'            => 'text',
                                                'name'            => 'iban_number',
                                                'value'           => old('iban_number'),
                                                'placeholder'     => __("Enter IBAN Number")."..."
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.form.textarea',[
                                        'label'           => __('Address'),
                                        'name'            => 'address',
                                        'placeholder'     => __('Write Here').'...'
                                    ])
                                </div>
                                <div class="document-id ptb-30">
                                    <div class="input-document">
                                        <div class="row">
                                            <div class="col-lg-4 pb-20">
                                                <label class="title">{{ __("Document type") }}</label>
                                                <select class="nice-select" name="document_type">
                                                    <option selected disabled>{{ __("Select Document Type") }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_NID }}">{{ global_const()::DOCUMENT_TYPE_NID }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}">{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_PASSPORT }}">{{ global_const()::DOCUMENT_TYPE_PASSPORT }}</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <label>{{ __("Front Part") }}</label>
                                                <div class="file-holder-wrapper">
                                                    <input type="file" class="file-holder" name="front_image" id="fileUpload" data-height="130" accept="image/*" data-max_size="20" data-file_limit="15">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <label>{{ __("Back Part") }}</label>
                                                <div class="file-holder-wrapper">
                                                    <input type="file" class="file-holder" name="back_image" id="fileUpload" data-height="130" accept="image/*" data-max_size="20" data-file_limit="15">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <button type="submit" class="btn--base w-100">{{ __("Confirm") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $(".trx-type-select").change(function () {
        var recipientsWrapper = $(".add-recipient-item");
        var inputItems = recipientsWrapper.find("input,select,textarea");
        var selectValue = $(this).val();
        $.each(inputItems, function (index, item) {
            if (selectValue == "" || selectValue == null) {
                $(item).prop("readonly", true);
            } else {
                $(item).prop("readonly", false);
            }
        });

        if (selectValue != "") {
            $(this).parents(".transaction-type").siblings(".trx-inputs").slideUp();
            $(this).parents(".transaction-type").siblings("." + $(this).val() + "-view").slideDown();
        }
    });
</script>
<script>
    var getMobileMethod = "{{ setRoute('user.get.mobile.method') }}";

    $('select[name="country"]').on('change',function(){
        $("select[name=mobile_name]").html('');
        getMobile($(this));
    });
    
    function getMobile(select){
        var country = $(select).val();
        if(country == "" || country == null){
            return false; 
        }
        $.post(getMobileMethod,{country:country,_token:"{{ csrf_token() }}"},function(response){
            var option = '';
            if(response.data.country.length > 0){
                $.each(response.data.country,function(index,item){
                    option += `<option value="${item.name}">${item.name}</option>`
                });
                $("select[name=mobile_name]").html(option);
                $("select[name=mobile_name]").select2();
            }
        }).fail(function(response){
            var errorText = response.responseJSON;
        });
    }
</script>
<script>
    var getBankName = "{{ setRoute('user.get.bank.name') }}";

    $('select[name="country"]').on('change',function(){
        $("select[name=bank_name]").html('');
        getBank($(this));
    });
    
    function getBank(select){
        var country = $(select).val();
        if(country == "" || country == null){
            return false;
        }
        $.post(getBankName,{country:country,_token:"{{ csrf_token() }}"},function(response){
            var option = '';
            if(response.data.country.length > 0){
                $.each(response.data.country,function(index,item){
                    console.log(item.name);
                    option += `<option value="${item.name}">${item.name}</option>`

                });
                $("select[name=bank_name]").html(option);
                $("select[name=bank_name]").select2();
            }
        }).fail(function(response) {

            var errorText = response.responseJSON;

        });
    }
</script>
    
@endpush