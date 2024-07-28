<div class="search-table">
    <table class="custom-table remittance-search-table">
        <thead>
            <tr>
                <th>{{ __("MTCN ID") }}</th>
                <th>{{ __("Sender Name") }}</th>
                <th>{{ __("Receiver Name") }}</th>
                <th>{{ __("Transaction Type") }}</th>
                <th>{{ __("Amount") }}</th>
                <th>{{ __("Status") }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions ?? [] as $key => $item)
                <tr>
                    <td>{{ $item->trx_id ?? '' }}</td>
                    <td>{{ $item->remittance_data->data->sender->fullname ?? '' }}</td>
                    <td>{{ $item->remittance_data->data->recipient->fullname ?? '' }}</td>
                    <td>{{ $item->remittance_data->data->transaction_type->name ?? '' }}</td>
                    <td>{{ get_amount($item->request_amount,$item->remittance_data->data->base_currency->code)}}<span>{{ get_amount($item->will_get_amount,$item->remittance_data->data->receiver_currency->code) ?? '' }}</span></td>
                    
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
                        <a href="{{ setRoute('admin.agent.send.remittance.details',$item->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                    </td>
                </tr>
            @empty
                @include('admin.components.alerts.empty',['colspan' => 8])
            @endforelse
        </tbody>
    </table>
</div>
