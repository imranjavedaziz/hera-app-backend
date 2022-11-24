@extends('admin.layouts.admin_base')
@section('content')
            <div class="main-right-wrapper">
                <div class="dashboard-container">
                    <div class="user-management-header">
                        <div class="btn-group user-btn-group ms-auto">
                            <span><img src="{{ asset('assets/images/svg/user-icon.svg')}}" alt="user-logo" /></span>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                        <div class="back-sec"><button type="submit" class="btn-hidden"><img src="{{ asset('assets/images/svg/left-arrow.svg')}}" alt="Left arrow" /> Back</button></div>
                </div>
                    <div class="subscription-wrapper">
                        <div class="sub-profile-wrapper">
                            <div class="profile-logo">
                                <img src="{{$users->profile_pic}}" alt="Profile-logo">
                            </div>
                            <div class="profile-detail">
                                <div class="profile-title">{{CustomHelper::fullName($users)}}, <span>{{CustomHelper::getRoleName($users->role_id)}}</span></div>
                                <div class="profile-email">{{$users->email}}</div>
                            </div>
                        </div>
                        <div class="next-purchased">
                        <?php
                            $purchasedDate = !empty($activeSubscription) ? \Carbon\Carbon::parse($activeSubscription->current_period_start)->format('M d, Y') : 'N/A';
                            $lastDate = !empty($activeSubscription) ? \Carbon\Carbon::parse($activeSubscription->current_period_end)->format('M d, Y') : 'N/A';
                        ?>
                            <div class="purchase">Purchased On: <span> {{$purchasedDate}}</span></div>
                            <div class="next-due text-danger">Next Due On: <span>{{$lastDate}}</span></div>
                        </div>
                    </div>
                    <!--  Table start from here  -->
                    <div class="table-container table-container-subdetail">
                        <div class="table-head table-head-user">
                            <div class="table-row table-row-user">
                                <div class="th">Subscription Type</div>
                                <div class="th">Billing Amount</div>
                                <div class="th">Billed On</div>
                                <div class="th">Transaction ID</div>
                                <div class="th">Amount Received</div>
                                <div class="th">Payment</div>
                                <div class="th">Action</div>
                            </div>
                        </div>
                        <div class="table-body table-body-user">
                        @if (!empty($subscriptionData) && $subscriptionData->count() > 0)
                            @foreach($subscriptionData as $subscription)
                            <?php
                            $user = $subscription->user;
                            $subscriptionPlan = $subscription->subscriptionPlan;
                            $purchasedDate = \Carbon\Carbon::parse($subscription->current_period_start)->format('M d, Y');
                            ?>
                            <!--  repeat this div  -->
                            <div class="table-row">
                                <div class="td text-bold">{{$subscriptionPlan->name}}</div>
                                <div class="td">${{$subscription->price}}</div>
                                <div class="td">{{$purchasedDate}}</div>
                                <div class="td">#{{$subscription->original_transaction_id}}</div>
                                <div class="td">${{$subscription->price}}</div>
                                <div class="td">Paid</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src=" {{ asset('assets/images/svg/icon-dark-more.svg')}}" alt="" class="3-dots-icon"
                                            id="inactive-icon">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{ route('showInvoice', ['id' => $subscription->id, 'userId' => $users->id]) }}">See Invoice</a></li>
                                        <li><a class="dropdown-item" href="{{ route('downloadInvoice', ['id' => $subscription->id, 'userId' => $users->id]) }}">Download Invoice</a></li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- end table  -->
                    <div class="pagination-section">
                    {{ $subscriptionData->links() }}
                    </div>
                </div>
    <!-- end container fluid -->
@endsection
@push('after-scripts')
<script type="text/javascript">
$(document).ready(function () {
    $(".back-sec").click(function() {
        window.location.href = "/admin/subscription";
    });
});
</script>
@endpush