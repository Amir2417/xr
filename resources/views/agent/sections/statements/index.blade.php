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
    <div class="banking-statement">
        <form class="card-form" method="GET" action="{{ setRoute('agent.statements.filter') }}">
            <div class="row mb-10-none">
                <div class="col-lg-3 col-md-6 mb-10">
                    <label>{{ __("Filter by Period") }}</label>
                    @php
                        $time_period   = request()->get('time_period');
                    @endphp
                    <select class="select2-basic" name="time_period">
                        <option selected disabled>{{ __("Select One") }}</option>
                        <option value="{{ global_const()::LAST_ONE_WEEKS }}" @if ($time_period == global_const()::LAST_ONE_WEEKS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_WEEKS , " "))  }}</option>
                        <option value="{{ global_const()::LAST_TWO_WEEKS }}" @if ($time_period == global_const()::LAST_TWO_WEEKS) selected @endif>{{ __(remove_special_char(global_const()::LAST_TWO_WEEKS , " ")) }}</option>
                        <option value="{{ global_const()::LAST_ONE_MONTHS }}" @if ($time_period == global_const()::LAST_ONE_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_MONTHS , " ")) }}</option>
                        <option value="{{ global_const()::LAST_TWO_MONTHS }}" @if ($time_period == global_const()::LAST_TWO_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_TWO_MONTHS , " ")) }}</option>
                        <option value="{{ global_const()::LAST_THREE_MONTHS }}" @if ($time_period == global_const()::LAST_THREE_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_THREE_MONTHS , " ")) }}</option>
                        <option value="{{ global_const()::LAST_SIX_MONTHS }}" @if ($time_period == global_const()::LAST_SIX_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_SIX_MONTHS , " ")) }}</option>
                        <option value="{{ global_const()::LAST_ONE_YEARS }}" @if ($time_period == global_const()::LAST_ONE_YEARS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_YEARS , " ")) }}</option>
                        <option value="{{ global_const()::SPECIFIC_DATES }}" @if ($time_period == global_const()::SPECIFIC_DATES) selected @endif>{{ __(remove_special_char(global_const()::SPECIFIC_DATES , " ")) }}</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-10">
                    <label>{{ __("Start Date") }}</label>
                    <input type="date" class="form--control" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-lg-3 col-md-6 mb-10">
                    <label>{{ __("End Date") }}</label>
                    <input type="date" class="form--control" name="end_date" value="{{ old('end_date') }}">
                </div>
                <div class="col-lg-3 col-md-6 mb-10">
                    <label>{{ __("Status") }}</label>
                    @php
                        $status   = request()->get('status');
                    @endphp
                    <select class="form--control select2-basic" name="status">
                        <option selected disabled>{{ __("Select Status") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_ALL }}" @if ($status == global_const()::REMITTANCE_STATUS_ALL) selected @endif>{{ __(global_const()::REMITTANCE_STATUS_ALL) }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT }}" @if ($status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT) selected @endif>{{ __("Review Payment") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_PENDING }}" @if ($status == global_const()::REMITTANCE_STATUS_PENDING) selected @endif>{{ __("Pending") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT }}" @if ($status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT) selected @endif>{{ __("Confirm Payment") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_HOLD }}" @if ($status == global_const()::REMITTANCE_STATUS_HOLD) selected @endif>{{ __("On Hold") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_SETTLED }}" @if ($status == global_const()::REMITTANCE_STATUS_SETTLED) selected @endif>{{ __("Settled") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_COMPLETE }}" @if ($status == global_const()::REMITTANCE_STATUS_COMPLETE) selected @endif>{{ __("Complete") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_CANCEL }}" @if ($status == global_const()::REMITTANCE_STATUS_CANCEL) selected @endif>{{ __("Cancel") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_FAILED }}" @if ($status == global_const()::REMITTANCE_STATUS_FAILED) selected @endif>{{ __("Failed") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_REFUND }}" @if ($status == global_const()::REMITTANCE_STATUS_REFUND) selected @endif>{{ __("Refunded") }}</option>
                        <option value="{{ global_const()::REMITTANCE_STATUS_DELAYED }}" @if ($status == global_const()::REMITTANCE_STATUS_DELAYED) selected @endif>{{ __("Delayed") }}</option>
                    </select>
                </div>
                <div class="filtaring-area">
                    <div class="filter-btn">
                        <button type="submit" class="btn--base"><i class="las la-filter"></i> {{ __("Filter Data") }}</button>
                    </div>
                    @if (isset($transactions) && count($transactions) > 0)
                        <input type="hidden" class="submit_type">
                        <div class="pdf-btn-wrapper">
                            <button type="button" class="btn--base pdf-button"><i class="las la-file-pdf"></i> {{ __("PDF") }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @if(isset($transactions))
        @if ($transactions->isNotEmpty())
        <div class="table-area mt-4">
            <div class="table-wrapper">
                <div class="table-header">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>{{ __("MTCN Number") }}</th>
                                    <th>{{ __("Type") }}</th>
                                    <th>{{ __("Sending Amount") }}</th>
                                    <th>{{ __("Payable Amount") }}</th>
                                    <th>{{ __("Status") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $item )
                                    <tr>
                                        <td>{{ $item->trx_id ?? ''}}</td>
                                        <td>{{ $item->type ?? ''}}</td>
                                        <td>{{ get_amount($item->request_amount,get_default_currency_code()) }} </td>
                                        <td>{{ get_amount($item->payable,get_default_currency_code()) }}</td>
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
                                    </tr>
                                @empty
                                <div class="alert alert-primary text-center">
                                    {{ __("No data found!") }}
                                </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-primary text-center">
                {{ __("No data found!") }}
            </div>
        @endif
        
    
    @endif
</div>
@endsection
@push('script')
<script>
    $(document).on("click",".pdf-button", function() {
        $(this).parents("form").find(".submit_type").attr("name","submit_type").val("EXPORT");
        $(this).parents("form").submit();
    });
</script>
@endpush