<div class="search-table">
    <table class="custom-table remittance-search-table">
        <thead>
            <tr>
                <th>{{ __("MTCN ID") }}</th>
                <th>{{ __("Amount") }}</th>
                <th>{{ __("Payment Method") }}</th>
                <th>{{ __("Status") }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions ?? [] as $key => $item)
                <tr>
                    <td>{{ $item->trx_id ?? '' }}</td>
                    
                    <td>{{ get_amount($item->request_amount,$item->remittance_data->data->base_currency->currency)}}</td>
                    <td>{{ $item->remittance_data->data->payment_gateway->name ?? '' }}</td>
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
                    <td>
                        <a href="{{ setRoute('admin.agent.money.in.logs.details',$item->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                    </td>
                </tr>
            @empty
                @include('admin.components.alerts.empty',['colspan' => 5])
            @endforelse
        </tbody>
    </table>
</div>
