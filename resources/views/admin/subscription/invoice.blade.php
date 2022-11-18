@extends('admin.layouts.invoice_base')
@section('content')
<div class="container-fluid">
      <div class="row">
        <div class="col-12 p-0">
            <div class="payment-container">
                <!-- sign in start here -->
                <div class="payment-wrapper">
                    <div class="app-logo">
                        <img src=" {{ asset('assets/images/logo.png')}} " alt="app logo" />
                    </div>
                    <h2>Payment Invoice</h2>
                    <div class="sub-profile-wrapper mb-30">
                        <div class="profile-logo">
                            <img src="{{$user->profile_pic}}" alt="Profile-logo">
                        </div>
                        <div class="profile-detail">
                            <div class="profile-title">{{CustomHelper::fullName($user)}}</div>
                            <div class="profile-email">{{$user->email}}</div>
                        </div>
                    </div>
                    <div class="billing-wrapper">
                        <div class="cell bill-bg">
                            <div class="bill-title bill-weight">Billing Details</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Subscription Purchased</div>
                            <div class="bill-amount">6 Month Commitment (PREMIUM)</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Transaction ID</div>
                            <div class="bill-amount">HERA084749</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Billed on</div>
                            <div class="bill-amount">8 May 2020</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title">Invoice Period</div>
                            <div class="bill-amount">9 Apr 2020 - 8 May 2020</div>
                        </div>
                        <div class="cell bill-bg">
                            <div class="bill-title">Amount Paid</div>
                            <div class="bill-amount">$299.00</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title bill-weight mb-25">The payment is made via Apple Subscription. Subscription will renew automatically on 9 May 2020 at the current rate of US$299.00 a month.</div>
                        </div>
                        <div class="cell-bottom">
                            <div class="logo-bill">
                                <img src="{{ asset('assets/images/logo.png')}}" alt="Profile-logo">
                            </div>
                            <div class="bill-desc">You have received this email as a registered user of HERA Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:help@hera.com">help@hera.com</a></div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- end signin container wrapper -->
        </div>
    </div>
  </div>
  <!-- end main wrapper -->
@endsection