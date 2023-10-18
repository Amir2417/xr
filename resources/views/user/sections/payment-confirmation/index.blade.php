@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Payment Confirmation")])
@endsection

@section('content')

<div class="body-wrapper">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="payment-area text-center mt-40">
                <div class="payment-loader pb-40">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>
                </div>
                <h4 class="title">{{ __("Your Payment Is Successfully Completed") }}</h4>
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="input-group pt-4">
                            <input type="text" class="form--control box" value="{{ setRoute('share.link',$transaction->trx_id) }}" readonly>
                            <div class="input-group-text copy"><i class="las la-copy"></i></div>
                        </div>
                        <div class="payment-recipt pt-4">
                            <div class="receipt-dawonload">
                                <a href="{{ setRoute('download.pdf',$transaction->trx_id) }}" class="btn--base"> <i class="las la-file-pdf"></i> Download Receipt PDF<i class="las la-arrow-down"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="conformation-footer pt-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="payment-conformation-footer">
                                <a href="{{ setRoute('user.send.remittance.index') }}" class="btn--base w-100"> Send Another Remittance</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="payment-conformation-footer">
                                <a href="{{ setRoute('user.dashboard') }}" class="btn--base w-100">Go To Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
    <script>
        $('.copy').on('click',function(){
            
            let input = $('.box').val();
            navigator.clipboard.writeText(input)
            .then(function() {
                
                $('.copy').text("Copied");
            })
            .catch(function(err) {
                console.error('Copy failed:', err);
            });
        });
    </script>
@endpush
