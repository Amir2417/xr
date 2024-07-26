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
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ $page_title }}</h4>
        </div>
        @include('agent.components.transaction-logs.index',compact('transactions'))
    </div>
</div>
@endsection
@push('script')
<script>
    itemSearch($("input[name=search_text]"),$(".transaction-log-results"),"{{ setRoute('agent.transaction.logs.search') }}",1);
</script>
@endpush