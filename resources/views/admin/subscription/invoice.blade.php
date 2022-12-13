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
                        <?php
                            $purchasedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_start)->format('M d, Y');
                            $billedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_end)->format('M d, Y');
                            ?>
                            <div class="bill-title">Subscription Purchased</div>
                            <div class="bill-amount">{{$subscriptionDetail->subscriptionPlan->name}}</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Transaction ID</div>
                            <div class="bill-amount">{{$subscriptionDetail->original_transaction_id}}</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Billed on</div>
                            <div class="bill-amount">{{$billedDate}}</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title">Invoice Period</div>
                            <div class="bill-amount">{{$purchasedDate}} - {{$billedDate}}</div>
                        </div>
                        <div class="cell bill-bg">
                            <div class="bill-title">Amount Paid</div>
                            <div class="bill-amount">${{$subscriptionDetail->price}}</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title bill-weight mb-25">The payment is made via @if ($subscriptionDetail->device_type == 'ios') Apple @else Google @endif Subscription. Subscription will renew automatically on {{$billedDate}} at the current rate of US${{$subscriptionDetail->price}} a {{$subscriptionDetail->subscriptionPlan->interval}}.</div>
                        </div>
                        <div class="cell-bottom">
                            <div class="logo-bill">
                                <img src="{{ asset('assets/images/logo.png')}}" alt="Profile-logo">
                            </div>
                            <div class="bill-desc">You have received this email as a registered user of HERA Application. If you have any questions, please fill the Support Form using our mobile application or email us at <a href="mailto:help@hera.com">help@hera.com</a></div>
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