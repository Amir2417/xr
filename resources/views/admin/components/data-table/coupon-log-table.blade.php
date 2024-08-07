<table class="custom-table coupon-search-table">
    <thead>
        <tr>
            <th>{{ __("MTCN ID") }}</th>
            <th>{{ __("Coupon Name") }}</th>
            <th>{{ __("Sender Name") }}</th>
            <th>{{ __("Receiver Name") }}</th>
            <th>{{ __("Transaction Type") }}</th>
            <th>{{ __("Amount") }}</th>
            <th>{{ __("Payment Method") }}</th>
            <th>{{ __("Status") }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions ?? [] as $key => $item)
            <tr>
                <td>{{ $item->transaction->trx_id ?? '' }}</td>
                <td>{{ $item->coupon->name ?? $item->user_coupon->coupon_name }}</td>
                <td>{{ $item->transaction->remittance_data->sender_name ?? '' }}</td>
                <td>{{ $item->transaction->remittance_data->first_name ?? '' }} {{ $item->transaction->remittance_data->middle_name ?? '' }} {{ $item->transaction->remittance_data->last_name ?? '' }}</td>
                <td>{{ $item->transaction->remittance_data->type ?? '' }}</td>
                <td>{{ get_amount($item->transaction->request_amount) ?? '' }} {{ $item->transaction->remittance_data->sender_currency ?? '' }} <span>{{ get_amount($item->transaction->will_get_amount,$item->transaction->remittance_data->receiver_currency) ?? '' }}</span></td>
                
                <td>{{ $item->transaction->remark ?? '' }}</td>
                <td>
                    @if ($item->transaction->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                        <span>{{ __("Review Payment") }}</span> 
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_PENDING)
                        <span>{{ __("Pending") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                        <span>{{ __("Confirm Payment") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_HOLD)
                        <span>{{ __("On Hold") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_SETTLED)
                        <span>{{ __("Settled") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                        <span>{{ __("Completed") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_CANCEL)
                        <span>{{ __("Canceled") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_FAILED)
                        <span>{{ __("Failed") }}</span>
                    @elseif ($item->transaction->status == global_const()::REMITTANCE_STATUS_REFUND)
                        <span>{{ __("Refunded") }}</span>
                    @else
                        <span>{{ __("Delayed") }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ setRoute('admin.coupon.log.details',$item->transaction->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty',['colspan' => 8])
        @endforelse
    </tbody>
</table>