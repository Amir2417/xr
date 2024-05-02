@extends('user.layouts.master')

@push('css')
    
@endpush

@section('content')
<div class="body-wrapper">
    <div class="coupon-code-item">
        <div class="row mb-30-none">
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 mb-30">
                <div class="coupon-code-area">
                    <div class="coupon-type">
                        <h3 class="title">New User Bonus</h3>
                        <div class="transaction-table-container">
                            <table class="transaction-table">
                              <thead>
                                <tr>
                                  <th>Coupon Name</th>
                                  <th>Maximum Used</th>
                                  <th>Remaining</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                     <div class="cupone-name">
                                        {{ @$bonus->coupon_name }}  <i class="las la-copy"></i>
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
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 mb-30">
                <div class="coupon-code-area">
                    <div class="coupon-type">
                        <h3 class="title">Admin Bonus</h3>
                        <div class="transaction-table-container">
                            <table class="transaction-table">
                              <thead>
                                <tr>
                                  <th>Coupon Name</th>
                                  <th>Maximum Used</th>
                                  <th>Remaining</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                     <div class="cupone-name">
                                        Mukto <i class="las la-copy"></i>
                                     </div>
                                  </td>
                                  <td>5</td>
                                  <td>2</td>
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

@endpush