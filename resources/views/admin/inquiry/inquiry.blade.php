@extends('admin.layouts.admin_base')
@section('content')
<!-- start main wrapper -->
    <div class="main-right-wrapper">
        <div class="dashboard-container">
            <div class="user-management-header">
                <div id="deactivate-msg-box" class="alert alert-success" role="alert" style=" display: none">
                    <div class="alert-text">
                        <span>
                            <img src="{{ asset('assets/images/svg/check.svg')}}" alt="check icon" />
                        </span> <span id="deactivate-msg"></span>
                    </div>
                    <div class="text-end">
                        <img src="{{ asset('assets/images/svg/alert-cross.svg')}}" alt="alert icon" />
                    </div>
                </div>
                <div class="export-csv">
                    <button type="button" class="btn-primary btn-logout" data-bs-toggle="modal" data-bs-target="#modalExportCsv" ><img src="/assets/images/svg/download.svg" alt="download icon" />EXPORT CSV</button>
                </div>
                <div class="btn-group user-btn-group ms-auto">
                    <span>
                        <img src="/assets/images/svg/user-icon.svg" alt="user-logo" /></span>
                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
                        </li>
                    </ul>
                </div>
            </div>
            <h1 class="section-title">Inquiries 
                (<span>{{$inquiries->total()}}</span>)
            </h1>
            <!-- For no Users found -->
            @if($inquiries->count() == 0)
                <div class="no-users">No Inquiries Yet</div>
            @endif
        </div>
        <!--  Table start from here  -->
        @if (!empty($inquiries) && $inquiries->count() > 0)
            <div class="table-container table-container-inquiries">
                <div class="table-head table-head-user">
                    <div class="table-row table-row-user">
                        <div class="th">Name</div>
                        <div class="th">Email Address</div>
                        <div class="th">Issue ID</div>
                        <div class="th">User Type</div>
                        <div class="th">Issue</div>
                        <div class="th">Date</div>
                        <div class="th"></div>
                    </div>
                </div>
                <div class="table-body table-body-user">
                    @foreach($inquiries as $inquiry)
                        @php
                            $joinDate = \Carbon\Carbon::parse($inquiry->created_at)->format('M d, Y');
                            $message = strlen($inquiry->message) > 70 ? substr($inquiry->message,0,70)."..." : $inquiry->message;
                            $issue_id = 'HR00'.$inquiry->id;
                            if($inquiry->user){
                                $img = $inquiry->user->profile_pic;
                            }else{
                                $img = '/assets/images/svg/user-icon.svg';
                            }
                        @endphp
                        <!--  repeat this div  -->
                        <div class="table-row" id="open-detail-modal" data-id="{{$inquiry->id}}">
                            <div class="td">
                                <div class="user-title">
                                    <div class="user-img">
                                        <div>
                                            <img src="{{$img}}" alt="user image"/>
                                        </div>
                                    </div>
                                    <div class="user-title-info">
                                        <h5>{{$inquiry->name}}</h5>
                                        @if(!$inquiry->user)
                                            <p>Guest User</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="td">{{$inquiry->email}}</div>
                            <div class="td">{{$issue_id}}</div>
                            <div class="td">{{$inquiry->role}}</div>
                            <div class="td">{{$message}}</div>
                            <div class="td">{{$joinDate}}</div>
                            <div class="td"><img src="/assets/images/svg/send.svg" alt="Send Image"/></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <!-- end table  -->
        <div class="pagination-section">
            {{ $inquiries->links() }}
        </div>
    </div>
    @include('admin.layouts.partials.modal.inquiry-details')
    @include('admin.layouts.partials.modal.inquiry-export')
@endsection

@push('after-scripts')
    <script type="text/javascript">
        $('.dropdown').click(function () {
            $(this).attr('tabindex', 1).focus();
            $(this).toggleClass('active');
            $(this).find('.dropdown-menu').slideToggle(300);
        });
        $('.dropdown').focusout(function () {
            $(this).removeClass('active');
            $(this).find('.dropdown-menu').slideUp(300);
        });
        $('.dropdown .dropdown-menu li').click(function () {
            $(this).parents('.dropdown').find('span').text($(this).text());
            $(this).parents('.dropdown').find('input').attr('value', $(this).attr('id'));
        });
        /*End Dropdown Menu*/


        $('.dropdown-menu li').click(function () {
          var input = '<strong>' + $(this).parents('.dropdown').find('input').val() + '</strong>',
              msg = '<span class="msg">Hidden input value: ';
          $('.msg').html(msg + input + '</span>');
        }); 
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '#open-detail-modal', function(e){
                console.log('hello');
                e.preventDefault();
                var id = $(this).attr("data-id");
                console.log(id);
                $.ajax({
                    url: 'user/'+ id,
                    type: 'get',
                    dataType: 'json',
                    success: function (msg) {
                        console.log(msg);
                        var middle_name = (msg.middle_name != null) ? msg.middle_name : '';
                        console.log(middle_name);
                        var status = (msg.status_id == 1) ? 2 : 1
                        var status_text = (status == 2) ? 'Deactivate' : 'Activate';
                        console.log(status_text);
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
                        $('.date').html('Joined on: ' +new Date(msg.created_at).toLocaleString('en-US', {
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
                            sources[0].src = msg.doner_video_gallery.file_url;
                            sources[1].src = msg.doner_video_gallery.file_url;
                            video.load();
                        }else{
                            $('.vedio-title').hide();
                            $('.vedio-sec').hide();
                        }
                        $('#modalInquiriesDetails').modal('show');
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