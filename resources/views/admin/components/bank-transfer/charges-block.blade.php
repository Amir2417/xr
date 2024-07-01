<div class="custom-card mb-10">
    <div class="card-header">
        <h6 class="title">{{__($item->title)}}</h6>
    </div>
    <div class="card-body">
        <form class="card-form" method="POST" action="{{ $route ?? "" }}">
            @csrf
            @method("PUT")
            <input type="hidden" value="{{ $item->slug }}" name="slug">
            @if ($item->slug == global_const()::BANK_TRANSFER || $item->slug == global_const()::MOBILE_MONEY || $item->slug == global_const()::CASH_PICKUP)
            <div class="row">
                <div class="form-group">
                    @include('admin.components.form.input-text-rich',[
                        'label'         => __("Feature Text").'*',
                        'name'          => "feature_text",
                        'value'         => old("feature_text",$item->feature_text)
                    ])
                </div>
                <div class="{{ $item->agent_profit == true ?'col-xl-3 col-lg-3' : 'col-xl-4 col-lg-4' }} mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            @if ($item->title == global_const()::TRANSACTION_TYPE_BANK)
                                <h5 class="title">{{ __("Default Charges") }}</h5>
                            @else
                                <h5 class="title">{{ __("Charges") }}</h5>
                            @endif
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Fixed Charge") }}*</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_fixed_charge',get_amount($item->fixed_charge,null,4)) }}" name="{{$item->slug}}_fixed_charge">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Percent Charge") }}*</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_percent_charge',get_amount($item->percent_charge,null,4)) }}" name="{{$item->slug}}_percent_charge">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ $item->agent_profit == true ?'col-xl-3 col-lg-3' : 'col-xl-4 col-lg-4' }} mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            @if ($item->title == global_const()::TRANSACTION_TYPE_BANK)

                                <h5 class="title">{{ __("Default Range") }}</h5>

                            @else
                                <h5 class="title">{{ __("Range") }}</h5>
                            @endif
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Minimum Amount") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_min_limit',get_amount($item->min_limit,null,4)) }}" name="{{$item->slug}}_min_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Maximum Amount") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_max_limit',get_amount($item->max_limit,null,4)) }}" name="{{$item->slug}}_max_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ $item->agent_profit == true ?'col-xl-3 col-lg-3' : 'col-xl-4 col-lg-4' }} mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title">{{ __("Limit") }}</h5>
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Daily Transfer Limit") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_daily_limit',get_amount($item->daily_limit,null,4)) }}" name="{{$item->slug}}_daily_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Monthly Transfer Limit") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($item->slug.'_monthly_limit',get_amount($item->monthly_limit,null,4)) }}" name="{{$item->slug}}_monthly_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if( $item->agent_profit == true)
                <div class="col-xl-3 col-lg-3 mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title-agent">{{ __("Agent Profits") }}</h5>
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Fixed Commissions") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_agent_fixed_commissions',$data->agent_fixed_commissions) }}" name="{{$data->slug}}_agent_fixed_commissions">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Percent Commissions") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_agent_percent_commissions',$data->agent_percent_commissions) }}" name="{{$data->slug}}_agent_percent_commissions">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title">{{ __($item->title) . ' '. __('Intervals') }}</h5>
                            <button type="button" class="btn btn--base {{ $item->slug.'addUserData' }}">
                                <i class="la la-fw la-plus"></i>@lang('Add New')
                            </button>
                        </div>
                        <div class="card-inner-body {{ $item->slug.'addedField' }}">

                            @foreach (  $item->intervals ?? [] as $data)
                            <div class="row align-items-end {{ $item->slug.'user-data' }}">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Minimum Amount") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input" value="{{ get_amount($data->min_limit,null,4) }}" name="{{$item->slug}}_com_min_limit[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Maximum Amount") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input" value="{{ get_amount($data->max_limit,null,4) }}" name="{{$item->slug}}_com_max_limit[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Fixed Charge") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input" value="{{ get_amount($data->fixed,null,4)  }}" name="{{$item->slug}}_fixed[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Percent Charge") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input" value="{{ get_amount($data->percent,null,4) }}" name="{{$item->slug}}_percent[]" placeholder="0.00">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-1 col-xl-1 col-lg-1 form-group">
                                    <div class="input-group">
                                        <button class="btn btn--danger btn-lg {{ $item->slug.'removeBtn' }} w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="{{ $item->agent_profit == true ? 'col-xl-4 col-lg-4': 'col-xl-6 col-lg-6 '}} mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title">{{ __("Charges") }}</h5>
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Fixed Charge*") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_fixed_charge',$data->fixed_charge) }}" name="{{$data->slug}}_fixed_charge">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Percent Charge*") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_percent_charge',$data->percent_charge) }}" name="{{$data->slug}}_percent_charge">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ $item->agent_profit == true ? 'col-xl-4 col-lg-4': 'col-xl-6 col-lg-6 '}} mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title">{{ __("Range") }}</h5>
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6  form-group">
                                    <label>{{ __("Minimum amount") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_min_limit',$data->min_limit) }}" name="{{$data->slug}}_min_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Maximum amount") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_max_limit',$data->max_limit) }}" name="{{$data->slug}}_max_limit">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if( $item->agent_profit == true)
                <div class="col-xl-4 col-lg-4 mb-10">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h5 class="title-agent">{{ __("Agent Profits") }}</h5>
                        </div>
                        <div class="card-inner-body">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Fixed Commissions") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_agent_fixed_commissions',$data->agent_fixed_commissions) }}" name="{{$data->slug}}_agent_fixed_commissions">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-xl-6 col-lg-6 form-group">
                                    <label>{{ __("Percent Commissions") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form--control number-input" value="{{ old($data->slug.'_agent_percent_commissions',$data->agent_percent_commissions) }}" name="{{$data->slug}}_agent_percent_commissions">
                                        <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <div class="row mb-10-none">
                <div class="col-xl-12 col-lg-12 form-group">
                    @include('admin.components.button.form-btn',[
                        'text'          => __("Update"),
                        'class'         => "w-100 btn-loading",
                        'permission'    => "admin.trx.settings.charges.update",
                    ])
                </div>
            </div>
        </form>
    </div>
</div>
@push('script')
    <script>

        (function ($) {
            "use strict";

            $('.{{ $item->slug.'addUserData' }}').on('click', function () {
                var html = `
                <div class="row align-items-end {{ $item->slug.'user-data' }}">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Minimum Amount") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input"  name="{{$item->slug}}_com_min_limit[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Maximum Amount") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input"  name="{{$item->slug}}_com_max_limit[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Fixed Charge") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input" name="{{$item->slug}}_fixed[]" placeholder="0.00">
                                                <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 form-group">
                                            <label>{{ __("Percent Charge") }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form--control number-input"  name="{{$item->slug}}_percent[]" placeholder="0.00">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-1 col-xl-1 col-lg-1 form-group">
                                    <div class="input-group">
                                        <button class="btn btn--danger btn-lg {{ $item->slug.'removeBtn' }} w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>`;

                $('.{{ $item->slug.'addedField' }}').append(html);
            });

            $(document).on('click', '.{{ $item->slug."removeBtn" }}', function () {
                $(this).closest(".{{ $item->slug.'user-data' }}").remove();
            });

        })(jQuery);

    </script>
@endpush
