@extends('user.layouts.master')


@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("My Coupons")])
@endsection

@section('content')
    <div class="body-wrapper">
      <div class="coupon-code-item">
          <div class="row mb-30-none">
              <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 mb-30">
                  <div class="coupon-code-area">
                      <div class="coupon-type">
                          <h3 class="title">{{ __("New User Bonus") }}</h3>
                          <div class="transaction-table-container">
                              <table class="transaction-table">
                                <thead>
                                  <tr>
                                    <th>{{ __("Coupon Name") }}</th>
                                    <th>{{ __("Maximum Used") }}</th>
                                    <th>{{ __("Remaining") }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <div class="cupone-name">
                                        <span id="coupon-text">{{ @$bonus->coupon_name }}</span>
                                        <i class="las la-copy copy-coupon"></i>
                                      </div>
                                    </td>
                                    <td>{{ @$bonus->new_user_bonus->max_used }}</td>
                                    <td>{{ @$bonus->new_user_bonus->max_used }}</td>
                                  </tr>
                                </tbody>
                              </table>
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
  $('.copy-coupon').on('click',function(){
    var copyText    = document.getElementById("coupon-text").textContent;
    var tempTextArea = document.createElement('textarea');
    tempTextArea.value  = copyText;
    document.body.appendChild(tempTextArea);
    tempTextArea.select();
    tempTextArea.setSelectionRange(0,99999);
    document.execCommand('copy');
    document.body.removeChild(tempTextArea);

    throwMessage('success',['Copied: ' + copyText]);
  });
</script>
@endpush