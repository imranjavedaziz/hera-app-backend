@extends('admin.layouts.admin_base')
@section('content')
    <div class="main-right-wrapper">
        <div class="dashboard-container">
            <div class="user-management-header">
                <div id="deactivate-msg-box" class="alert alert-success" role="alert" style=" @if(session()->get('flash_success')) display: block @else display: none @endif">
                <div class ="alert-boxes">    
                <div class="alert-text">
                        <span>
                            <img src="{{ asset('assets/images/svg/check.svg')}}" alt="check icon" />
                        </span> <span id="deactivate-msg">@if(session()->get('flash_success')) {{ session()->get('flash_success') }} @endif</span>
                    </div>
                    <div class="text-end">
                        <img src="{{ asset('assets/images/svg/alert-cross.svg')}}" alt="alert icon" />
                    </div>
                </div>
            </div>
            <div class="export-csv">
                    <button type="button" class="btn-outline btn-download">DOWNLOAD SAMPLE FILE</button>

                    <button type="button" class="btn-primary btn-import"><img src="/assets/images/svg/icon-export.svg" alt="download icon" />BULK UPLOAD
                    <input class="input-file-style" type="file" id="formFile">

                    </button>
            </div>

            @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @include('admin.layouts.partials.modal.login-user-dropdown')
            </div>
            @if ($userData->count() > 0)
            <h1 class="section-title">All Users (<span>{{$userData->total()}}</span>)</h1>
            @else
            <!-- For no Users found -->
            <div class="no-users d-none">No Users Yet</div>
            @endif
            <div class="export-csv">
                    <button type="button" class="btn-primary btn-logout" data-bs-toggle="modal" data-bs-target="#modalExportCsv" ><img src="/assets/images/svg/download.svg" alt="download icon" />Import Users</button>
            </div>
            @include('admin.layouts.partials.modal.import-users')
        </div>
        <!--  Table start from here  -->
        <div class="table-container table-container-user-modal">
            <div class="table-head table-head-user">
                <div class="table-row table-row-user">
                    <div class="td-user-left">
                        <div class="th">Name</div>
                        <div class="th">Phone</div>
                        <div class="th">Email Address</div>
                        <div class="th">Location</div>
                        <div class="th">User Type</div>
                    </div>
                    <div class="td-user-right">
                        <div class="th">Status</div>
                        <div class="th">Action</div>
                    </div>
                </div>
            </div>
            <div class="table-body table-body-user">
                @if (!empty($userData) && $userData->count() > 0)
                    @foreach($userData as $user)
                        @php
                            $img = $user->profile_pic;
                        @endphp
                        <!--  repeat this div  -->
                        <div class="table-row" @if($user->status_id == 5) disabled @endif>
                            <div class="td-user-left @if($user->status_id == 5) delete-user-opacity @else open-detail-modal @endif delete-user-opacity{{$user->id}} open-detail-modal{{$user->id}}" data-id="{{$user->id}}">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="{{$img}}" alt="user image" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>{{CustomHelper::fullName($user)}}</h5>
                                            <p>Joined: {{CustomHelper::dateTimeZoneConversion($user->created_at,$timezone)}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">{{$user->country_code}} {{$user->phone_no}}</div>
                                <div class="td">{{$user->email}}</div>
                                <div class="td">{{CustomHelper::getLocation($user->id)}}</div>
                                <div class="td">{{CustomHelper::getRoleName($user->role_id)}}<br />
                                    <span class="sm-code">{{$user->username}}</span>
                                </div>
                            </div>
                            <div class="td-user-right">
                                <div class="td">
                                        <span class="@if($user->status_id == 1) d-block @else d-none @endif" id="active-user{{$user->id}}">
                                            Active
                                        </span>
                                        <span class="inactive-span text-danger @if($user->status_id == 1) d-none @else d-block @endif" id="inactive-user{{$user->id}}">
                                            @if($user->status_id == 5) Deleted @else Inactive @endif <br>
                                            <span>
                                                @if($user->deactivated_by == 1 || $user->deleted_by == 1) 
                                                    (By Admin) 
                                                @else
                                                    (By User) 
                                                @endif
                                            </span>
                                        </span>
                                </div>
                                @if($user->status_id != 5)
                                    <div class="td delete-user{{$user->id}}">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src=" {{ asset('assets/images/svg/3-dots-horizontal.svg')}}" alt="" class="3-dots-icon"
                                                id="inactive-icon">
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @if($user->role_id != 2 && $user->status_id == 1)
                                                <li><a class="dropdown-item send-message" href="{{ route('user.chat', ['id' => $user->id]) }}">Send Message</a></li>
                                            @endif
                                            @if($user->deleted_at == null)
                                                <li><a class="dropdown-item modal-deactivate modal-deactivate{{$user->id}}" href="#" type="button" data-id="{{$user->id}}" data-name="{{CustomHelper::fullName($user)}}" data-status="@if($user->status_id == 1) 2 @else 1 @endif">@if($user->status_id == 1) Deactivate @else Activate @endif User</a></li>
                                                <li><a class="dropdown-item modal-delete modal-delete{{$user->id}}" href="#" type="button" data-name="{{CustomHelper::fullName($user)}}" data-id="{{$user->id}}">Delete User</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
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
    @include('admin.layouts.partials.modal.user-details')
    @include('admin.layouts.partials.modal.user-deactivate')
    @include('admin.layouts.partials.modal.user-delete')
@endsection

@push('after-scripts')

    <script src="{{ asset('assets/lightbox/lightboxed.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            @if(session()->get('flash_success'))
                $("#deactivate-msg-box").delay(3000).fadeOut();
            @endif
            $(document).on('click', '.open-detail-modal', function(e){
                e.preventDefault();
                var id = $(this).attr("data-id");
                $.ajax({
                    url: 'user/'+ id,
                    type: 'get',
                    dataType: 'json',
                    success: function (msg) {
                        var middle_name = (msg.middle_name != null) ? msg.middle_name : '';
                        var status = (msg.status_id == 1) ? 2 : 1
                        var status_text = (status == 2) ? 'Temporarily Deactivate' : 'Activate';
                        var date = moment.utc(msg.created_at).local().format();
                        if(msg.deleted_at == null){
                            $('#modal-deactivate').attr('data-id' , msg.id)
                            $('#modal-deactivate').attr('data-name' , msg.first_name+' '+middle_name+' '+msg.last_name)
                            $('#modal-deactivate').attr('data-status' , status)
                            $('#modal-deactivate').html(status_text + ' this user')
                            $('#modal-delete').attr('data-id' , msg.id)
                            $('#modal-delete').attr('data-name' , msg.first_name+' '+middle_name+' '+msg.last_name)
                            $('#modal-deactivate').addClass('d-block')
                            $('#modal-delete').addClass('d-block')
                            $('#modal-deactivate').removeClass('d-none')
                            $('#modal-delete').removeClass('d-none')
                            $('#deactivate-para-text').html(status_text.toLowerCase())
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
                            $('#height').show();
                            $('#weight').show();
                            $('#race').show();
                            $('#eye-colour').show();
                            $('#hair-colour').show();
                            $('#mother-ethnicity').show();
                            $('#father-ethnicity').show();
                            var inches = msg.doner_attribute.height;
                            var feet = Math.floor(inches / 12);
                            var inch = inches % 12;
                            $('#height').html('Height: <span>' + feet + ' ft ' + inch + ' in </span>')
                            $('#weight').html('Weight: <span>' + msg.doner_attribute.weight + ' pounds </span>')
                            $('#race').html('Race: <span>' + msg.doner_attribute.race + '</span>')
                            $('#eye-colour').html('Eye Color: <span>' + msg.doner_attribute.eye_colour + '</span>')
                            $('#hair-colour').html('Hair Color: <span>' + msg.doner_attribute.hair_colour + '</span>')
                            $('#mother-ethnicity').html('Mother Ethnicity: <span>' + msg.doner_attribute.mother_ethnicity + '</span>')
                            $('#father-ethnicity').html('Father Ethnicity: <span>' + msg.doner_attribute.father_ethnicity + '</span>')
                        }else{
                            $('#height').hide();
                            $('#weight').hide();
                            $('#race').hide();
                            $('#eye-colour').hide();
                            $('#hair-colour').hide();
                            $('#mother-ethnicity').hide();
                            $('#father-ethnicity').hide();
                        }
                        if (msg.user_profile != null) {
                            var occupation = (msg.user_profile.occupation != null) ? msg.user_profile.occupation : 'N/A';
                            $('#occupation').html('Occupation: <span>' + occupation + '</span>');
                            $('#bio').html( msg.user_profile.bio);
                            $('#occupation').show();
                            $('#bio').show();
                        }else{
                            $('#occupation').hide();
                            $('#bio').hide();
                        }
                        if(msg.doner_photo_gallery){
                            $('.img-wrapper').show();
                            var img = '';
                            msg.doner_photo_gallery.forEach(function(doner_photo_gallery) {
                                var path = doner_photo_gallery.file_url;
                                img = img.concat('<img class="lightboxed" rel="group1" src="'+path+'" alt="Image" data-link="'+path+'" data-width="560" data-height="315" >');
                            });
                            $('.img-wrapper').html(img)
                            jQuery( '.lightboxed' ).lightboxed();
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

            $(document).on('click', '.modal-deactivate', function(e){
                e.preventDefault();
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                var status = $(this).attr("data-status");
                var status_text = (status == 2) ? 'deactivate' : 'activate';
                $('#deactivate-btn-text').attr('data-id' , id)
                $('#deactivate-btn-text').attr('data-status' , status)
                $('#deactive-name').html(status_text.charAt(0).toUpperCase() + status_text.slice(1) + '<br> ' + name);
                $('#deactivate-btn-text').html(status_text.toUpperCase());
                $('#status-text').html(status_text);
                $('#modalUserDetails').modal('hide');
                $('#modalDeactivated').modal('show');
            });

            $(document).on('click', '#deactivate-btn-text', function(event){
                var id = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                let deactivated_by = 0;
                if((status == 2)){
                    deactivated_by = 1;
                }
                $.ajax({
                    url: 'user/change-status/'+id,
                    type: 'put',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "status_id": status,
                        "deactivated_by": deactivated_by,
                    },
                    beforeSend: function () {
                        $('#modalDeactivated').modal('hide');
                        $('.loader').show();
                    },
                    complete:function () {
                        $('.loader').hide();
                    },
                    statusCode: {
                        200: function (data) {
                            $('#deactivate-msg').html(data.message);
                            $("#deactivate-msg-box").show();
                            $("#deactivate-msg-box").delay(3000).fadeOut(800);
                            if(status == 2){
                                $("#inactive-user"+id).html('Inactive<br><span>(By Admin)</span>');
                                $("#inactive-user"+id).removeClass("d-none");
                                $("#inactive-user"+id).addClass("d-block");
                                $("#active-user"+id).removeClass("d-block");
                                $("#active-user"+id).addClass("d-none");
                                $(".modal-deactivate"+id).html("Activate User");
                                $(".send-message").addClass("d-none");
                            }else{
                                $("#active-user"+id).removeClass("d-none");
                                $("#active-user"+id).addClass("d-block");
                                $("#inactive-user"+id).removeClass("d-block");
                                $("#inactive-user"+id).addClass("d-none");
                                $(".modal-deactivate"+id).html("Deactivate User");
                                $(".send-message").removeClass("d-none");
                            }
                            var status_replace = (status == 2) ? "1" : "2"
                            $('.modal-deactivate'+id).attr('data-status' , status_replace)
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            $(document).on('click', '.modal-delete', function(e){
                e.preventDefault();
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                $('#delete-btn-text').attr('data-id' , id)
                $('#delete-name').html('Delete <br>' + name);
                $('#modalUserDetails').modal('hide');
                $('#modalDeleted').modal('show');
            });

            $(document).on('click', '#delete-btn-text', function(event){
                var id = $(this).attr("data-id");
                $.ajax({
                    url: 'user/delete/'+id,
                    type: 'delete',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        $('#modalDeleted').modal('hide');
                        $('.loader').show();
                    },
                    complete:function () {
                        $('.loader').hide();
                    },
                    statusCode: {
                        200: function (data) {
                            $('#deactivate-msg').html(data.message);
                            $("#deactivate-msg-box").show();
                            $("#deactivate-msg-box").delay(3000).fadeOut(800);
                            $("#inactive-user"+id).html('Deleted<br><span>(By Admin)</span>');
                            $("#inactive-user"+id).removeClass("d-none");
                            $("#inactive-user"+id).addClass("d-block");
                            $("#active-user"+id).removeClass("d-block");
                            $("#active-user"+id).addClass("d-none");
                            $(".delete-user"+id).addClass("d-none");
                            $(".open-detail-modal"+id).removeClass("open-detail-modal");
                            $(".delete-user-opacity"+id).addClass("delete-user-opacity");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            $('.close-btn-details').click(function() {
                $('#user-video-gallery')[0].pause();
            });
        });

    </script>
@endpush
