@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Transactions Log")])
@endsection

@section('content')


<div class="body-wrapper">
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Transactions Log") }}</h4>
        </div>
        <div id="transaction-results">
            @forelse ($transactions as $item)
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="dashboard-list-user-icon">
                                    <i class="las la-arrow-up"></i>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? ''}}</h4>
                                    <span class="sub-title text--danger">{{ $item->remittance_data->type ?? '' }}
                                        
                                        <span class="badge badge--warning ms-2">
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
                                        </span>
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base">{{ get_amount($item->will_get_amount) ?? '' }} {{ $item->remittance_data->receiver_currency ?? '' }}</h4>
                            <h6 class="exchange-money">{{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency ?? '' }}</h6>
                        </div>
                    </div>
                    <div class="preview-list-wrapper">
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("MTCN Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->trx_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Transaction Type") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->type ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-university"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Method Name") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->method_name ?? 'N/A'}}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-user-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Account Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->account_number ?? 'N/A'}}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-coins"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Sender Amount") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency ?? '' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-exchange-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Exchange Rate") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->sender_ex_rate }} {{ $item->remittance_data->sender_currency }} = {{ $item->remittance_data->receiver_ex_rate }} {{ $item->remittance_data->receiver_currency }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-battery-half"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Fees & Charges") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ get_amount($item->fees ?? '') }} {{ $item->remittance_data->sender_currency ?? '' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-gifts"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Sending Purpose") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->sending_purpose ?? '' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-universal-access"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Source Of Fund") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->source ?? '' }}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-comment-dollar"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Payment Method") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->currency->name ?? '' }}</span>
                            </div>
                        </div>
                        @if ($item->remittance_data->currency->rate ?? false)
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-exchange-alt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{ __("Payment Method Exchange Rate") }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ $item->remittance_data->sender_ex_rate }} {{ $item->remittance_data->sender_currency }} = {{ $item->remittance_data->currency->rate / $item->remittance_data->sender_base_rate }} {{ $item->remittance_data->currency->code }}</span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Payable Amount") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ get_amount($item->payable) }} {{ $item->remittance_data->currency->code ?? '' }}</span>
                            </div>
                        </div>
                        <div class="receipt-download" style="text-align: center; padding-top: 20px;">
                            <a href="{{ setRoute('download.pdf',$item->trx_id) }}" class="btn btn--base">{{ __("Download Receipt") }}</a>
                            <input type="hidden" name="" class="box" value="{{ setRoute('share.link',$item->trx_id) }}">
                            <div class="btn btn--base copy">{{ __("Copy Link") }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-primary text-center">
                {{ __("No Transaction Found!") }}
            </div>
            @endforelse
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
<script>
    $(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var baseURL = "{{ url('/') }}";
    
        $(".form--control").on("input", function() {
            var searchValue = $(this).val();
            $.ajax({
                url: "{{ route('user.transaction.search') }}",
                method: "GET",
                data: { search_term: searchValue },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    var transactions = response.transactions;
                    
                    updateTransactionsOnPage(response);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('Error:', errorThrown);
                }
            });
            
        });

        function updateTransactionsOnPage(response) {
            var transactionResults = $("#transaction-results");
            transactionResults.empty();

            var transactions = response.transactions;
            
            var senderCurrency = response.sender_currency;
            var receiverCurrency = response.receiver_currency;
            if(transactions.length > 0){
                transactions.forEach(function(transaction) {
                    var transactionHtml = createTransactionHtml(transaction, senderCurrency, receiverCurrency);
                    transactionResults.append(transactionHtml);
                });
            }else{
                var transactionHtml = createNoData();
                transactionResults.append(transactionHtml);
            }
            
            
        }

        function createTransactionHtml(transaction, senderCurrency, receiverCurrency) {
            var transactionHtml = `
            <div class="dashboard-list-wrapper">
                <div class="dashboard-list-item-wrapper">
                    <div class="dashboard-list-item sent">
                        <div class="dashboard-list-left">
                            <div class="dashboard-list-user-wrapper">
                                <div class="dashboard-list-user-icon">
                                    <i class="las la-arrow-up"></i>
                                </div>
                                <div class="dashboard-list-user-content">
                                    <h4 class="title">${transaction.remittance_data.first_name} ${transaction.remittance_data.middle_name} ${transaction.remittance_data.last_name   }</h4>
                                    <span class="sub-title text--danger">${transaction.remittance_data.type   }
                                        <span class="badge badge--warning ms-2">
                                            ${getStatusBadge(transaction.status)}
                                        </span>
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-list-right">
                            <h4 class="main-money text--base">${parseFloat(transaction.will_get_amount).toFixed(2)} ${senderCurrency.code}</h4>
                            <h6 class="exchange-money">${parseFloat(transaction.request_amount).toFixed(2)} ${receiverCurrency.code} </h6>
                        </div>
                    </div>
                    <div class="preview-list-wrapper">
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("MTCN Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.trx_id   }</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Transaction Type") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remittance_data.type   }</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-university"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Method Name") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remittance_data.method_name }</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-user-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Account Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remittance_data.account_number}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-exchange-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Exchange Rate") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${parseFloat(senderCurrency.rate).toFixed(2) } ${senderCurrency.code } = ${parseFloat(receiverCurrency.rate).toFixed(2)} ${receiverCurrency.code}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-battery-half"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Fees & Charge") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${parseFloat(transaction.fees).toFixed(2) } ${senderCurrency.code}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Payable Amount") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${parseFloat(transaction.payable).toFixed(2)} ${ transaction.remittance_data.currency.code}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-gifts"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Sending Purpose") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remittance_data.sending_purpose}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-universal-access"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Source Of Fund") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remittance_data.source}</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-comment-dollar"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Payment Mathod") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>${transaction.remark}</span>
                            </div>
                        </div>
                        <div class="receipt-download" style="text-align: center; padding-top: 20px;">
                            <a href="${baseURL + '/download-pdf/'+transaction.trx_id}" class="btn btn--base">Download Receipt</a>
                        </div>
                    </div>
                </div>
            </div>`;
               
            return transactionHtml;
                
        }
        function createNoData(){
            var transactionHtml = `
            <div class="alert alert-primary text-center">
            No Record Found!
        </div>`;
            
            return transactionHtml;
            
        }
        function getStatusBadge(status) {
            if (status === {{ global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT }}) {
                return "{{ __("Review Payment") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_PENDING }}) {
                return "{{ __("Pending") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT }}) {
                return "{{ __("Confirm Payment") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_HOLD }}) {
                return "{{ __("On Hold") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_SETTLED }}) {
                return "{{ __("Settled") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_COMPLETE }}) {
                return "{{ __("Completed") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_CANCEL }}) {
                return "{{ __("Canceled") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_FAILED }}) {
                return "{{ __("Failed") }}";
            } else if (status === {{ global_const()::REMITTANCE_STATUS_REFUND }}) {
                return "{{ __("Refunded") }}";
            } else {
                return "{{ __("Delayed") }}";
            }
        }

    });
</script>
@endpush