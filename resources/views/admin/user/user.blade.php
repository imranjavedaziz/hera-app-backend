@extends('admin.layouts.admin_base')
@section('content')
 <div class="main-right-wrapper">
                    <div class="dashboard-container">
                        <div class="user-management-header">
                            <div class="alert alert-success" role="alert" style="display:none;">
                                <div class="alert-text">
                                    <span>
                                        <img src="{{ asset('assets/images/svg/check.svg')}}" alt="check icon" />
                                    </span> User has been Deactivated.
                                </div>
                                <div class="text-end">
                                    <img src="{{ asset('assets/images/svg/alert-cross.svg')}}" alt="alert icon" />
                                </div>
                            </div>
                            <div class="btn-group user-btn-group ms-auto">
                                <span>
                                    <img src="{{ asset('assets/images/svg/user-icon.svg')}}" alt="user-logo" /></span>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @if ($userData->count() > 0)
                        <h1 class="section-title">All Users (<span>{{$userData->total()}}</span>)</h1>
                        @else
                        <!-- For no Users found -->
                        <div class="no-users d-none">No Users Yet</div>
                        @endif
                    </div>
                    <!--  Table start from here  -->
                    <div class="table-container table-container-user">
                        <div class="table-head table-head-user">
                            <div class="table-row table-row-user">
                                <div class="th">Name</div>
                                <div class="th">Phone</div>
                                <div class="th">Email Address</div>
                                <div class="th">Location</div>
                                <div class="th">User Type</div>
                                <div class="th">Status</div>
                                <div class="th">Action</div>
                            </div>
                        </div>
                        <div class="table-body table-body-user">
                        @if (!empty($userData) && $userData->count() > 0)
                            @foreach($userData as $user)
                            <?php
                            $joinDate = \Carbon\Carbon::parse($user->created_at)->format('M d, Y');
                            $img = $user->profile_pic;
                            ?>
                            <!--  repeat this div  -->
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="{{$img}}" alt="user image" data-bs-toggle="modal" data-bs-target="#modalUserDetails" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>{{CustomHelper::fullName($user)}}</h5>
                                            <p>Joined: {{$joinDate}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">{{$user->country_code}} {{$user->phone_no}}</div>
                                <div class="td">{{$user->email}}</div>
                                <div class="td">{{CustomHelper::getLocation($user->id)}}</div>
                                <div class="td">{{CustomHelper::getRoleName($user->role_id)}}<br />
                                    <span class="sm-code">{{$user->username}}</span></div>
                                <div class="td">{{CustomHelper::getStatusName($user->status_id)}}</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src=" {{ asset('assets/images/svg/3-dots-horizontal.svg')}}" alt="" class="3-dots-icon"
                                            id="inactive-icon">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Send Message</a></li>
                                        <li><a class="dropdown-item" href="#">Activate User</a></li>
                                        <li><a class="dropdown-item" href="#" type="button"  data-bs-toggle="modal" data-bs-target="#modalDeactivated">Deactivate User</a></li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- end table  -->
                    <div class="pagination-section">
                    {{ $userData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection