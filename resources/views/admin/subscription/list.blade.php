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
                        @if ($subscriptionData->count() > 0)
                        <h1 class="section-title">Subscription (<span>{{$subscriptionData->total()}}</span>)</h1>
                        @else
                        <!-- For no Users found -->
                        <div class="no-users">No Subscribed Users Yet</div>
                        @endif
                    </div>
                    @if ($subscriptionData->count() > 0)
                    <!--  Table start from here  -->
                    <div class="table-container table-container-subscription">
                        <div class="table-head table-head-user">
                            <div class="table-row table-row-user">
                                <div class="th">Name</div>
                                <div class="th">User Type</div>
                                <div class="th">Email Address</div>
                                <div class="th">Subscription Type</div>
                                <div class="th">Amount</div>
                                <div class="th">Purchased on</div>
                                <div class="th">Status</div>
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
                            $img = $subscription->profile_pic;
                            ?>
                            <!--  repeat this div  -->
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="{{$user->profile_pic}}" alt="user image" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>{{CustomHelper::fullName($user)}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">{{CustomHelper::getRoleName($user->role_id)}}<br />
                                    <span class="sm-code">{{$user->username}}</span>
                                </div>
                                <div class="td">{{$user->email}}</div>
                                <div class="td">@if (!empty($subscriptionPlan)) {{$subscriptionPlan->name}}  @else N/A @endif</div>
                                <div class="td">$ {{$subscription->price}}</div>
                                <div class="td">{{$purchasedDate}}</div>
                                <div class="td">{{CustomHelper::getSubscriptionStatus($subscription->status_id)}}</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ asset('assets/images/svg/3-dots-horizontal.svg')}}" alt="" class="3-dots-icon"
                                            id="inactive-icon">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item user-detail" data-id="{{$user->id}}">See Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('userSubscriptionList', ['id' => $user->id]) }}">Payment History</a></li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                    <!-- end table  -->
                    <div class="pagination-section">
                       {{ $subscriptionData->links() }}
                    </div>
                </div>
    @endsection
    @include('admin.layouts.partials.modal.user-details')
    @push('after-scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.user-detail', function(e){
                e.preventDefault();
                var id = $(this).attr("data-id");
                $.ajax({
                    url: 'user/'+ id,
                    type: 'get',
                    dataType: 'json',
                    success: function (msg) {
                        var middle_name = (msg.middle_name != null) ? msg.middle_name : '';
                        var status = (msg.status_id == 1) ? 2 : 1
                        var status_text = (status == 2) ? 'Deactivate' : 'Activate';
                        var date = moment.utc(msg.created_at).local().format();
                        if(msg.deleted_at == null){
                            $('#modal-deactivate').attr('data-id' , msg.id)
                            $('#modal-deactivate').attr('data-name' , msg.first_name)
                            $('#modal-deactivate').attr('data-status' , status)
                            $('#modal-deactivate').html(status_text + ' this user.')
                            $('#modal-delete').attr('data-id' , msg.id)
                            $('#modal-delete').attr('data-name' , msg.first_name)
                            $('#modal-deactivate').addClass('d-block')
                            $('#modal-delete').addClass('d-block')
                            $('#modal-deactivate').removeClass('d-none')
                            $('#modal-delete').removeClass('d-none')
                            $('.deactivate-para').addClass('d-block')
                            $('.deactivate-para').removeClass('d-none')
                        }else{
                            $('#modal-deactivate').addClass('d-none')
                            $('#modal-delete').addClass('d-none')
                            $('#modal-deactivate').removeClass('d-block')
                            $('#modal-delete').removeClass('d-block')
                            $('.deactivate-para').addClass('d-none')
                            $('.deactivate-para').removeClass('d-block')
                        }
                        $('.date').html('Joined on: ' +new Date(date).toLocaleString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        }));
                        $('.name').html(msg.first_name + ' ' + middle_name + ' ' + msg.last_name);
                        $('.name-id').html(msg.role + ', ' + msg.username);
                        (msg.location != null) ? $('.location').html(msg.location.name + ', ' + msg.location.zipcode) : $('.location').hide();
                        $('.phoneno').html('Phone Number: ' + msg.phone_no.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 ($3)"));
                        $('.email').html('Email: ' + msg.email);
                        $('.user-profile-right img').attr('src', msg.profile_pic)
                        $('#age').html('Age: <span>'+ msg.age + ' yrs </span>')
                        if (msg.doner_attribute != null) {
                            var inches = msg.doner_attribute.height;
                            var feet = Math.floor(inches / 12);
                            inches %= 12;
                            $('#height').html('Height: <span>' + feet + ' ft ' + inches + ' in </span>')
                            $('#weight').html('Weight: <span>' + msg.doner_attribute.weight + ' pounds </span>')
                            $('#race').html('Race: <span>' + msg.doner_attribute.race + '</span>')
                            $('#eye-colour').html('Hair Color: <span>' + msg.doner_attribute.eye_colour + '</span>')
                            $('#hair-colour').html('Eye Color: <span>' + msg.doner_attribute.hair_colour + '</span>')
                        }else{
                            $('#height').hide();
                            $('#weight').hide();
                            $('#race').hide();
                            $('#eye-colour').hide();
                            $('#hair-colour').hide();
                        }
                        if (msg.user_profile != null) {
                            $('#occupation').html('Occupation: <span>' + msg.user_profile.occupation + '</span>');
                            $('#bio').html( msg.user_profile.bio);
                        }else{
                            $('#occupation').hide();
                            $('#bio').hide();
                        }
                        if(msg.doner_photo_gallery){
                            var img = '';
                            msg.doner_photo_gallery.forEach(function(doner_photo_gallery) {
                                var path = doner_photo_gallery.file_url;
                                img = img.concat('<img src="'+path+'" alt="Image">');
                            });
                            $('.img-wrapper').html(img)
                        }else{
                            $('.img-wrapper').hide();
                        }
                        if(msg.doner_video_gallery != null){
                            var video = document.getElementsByTagName('video')[0];
                            var sources = video.getElementsByTagName('source');
                            $('.vedio-title').show();
                            $('.vedio-sec').show();
                            $('#user-role').html(msg.role)
                            sources[0].src = msg.doner_video_gallery.file_url;
                            sources[1].src = msg.doner_video_gallery.file_url;
                            video.load();
                        }else{
                            $('.vedio-title').hide();
                            $('.vedio-sec').hide();
                        }
                        $('#modalUserDetails').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });
        });
    </script>
@endpush