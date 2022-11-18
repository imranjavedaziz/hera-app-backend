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
                            $message = strlen($inquiry->message) > 70 ? substr($inquiry->message,0,70)."..." : $inquiry->message;
                            $issue_id = 'HR00'.$inquiry->id;
                            if($inquiry->user){
                                $img = $inquiry->user->profile_pic;
                            }else{
                                $img = '/assets/images/svg/user-icon.svg';
                            }
                        @endphp
                        <!--  repeat this div  -->
                        <div class="table-row table-row-data open-detail-modal" data-id="{{$inquiry->id}}">
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
                            <div class="td">{{CustomHelper::dateTimeZoneConversion($inquiry->created_at,$timezone)}}</div>
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
            var id;
            const currentYear = new Date().getFullYear(); // 2020
            const previousYear =  currentYear-1;
            const currentMonth = new Date().getMonth(); // 2020
            $("#month_select").val(currentMonth+1).change();
            $('#year_select').append('<option value=' + previousYear + '>' +previousYear+ '</option><option selected value=' + currentYear + '>' +currentYear+ '</option>')
            $(document).on('click', '.open-detail-modal', function(e){
                console.log('hello');
                e.preventDefault();
                id = $(this).attr("data-id");
                var img = '/assets/images/svg/user-icon.svg';
                console.log(id);
                $.ajax({
                    url: 'inquiry/'+ id,
                    type: 'get',
                    dataType: 'json',
                    success: function (msg) {
                        var date = moment.utc(msg.created_at).local().format();
                        if(msg.user != null){
                            img = msg.user.profile_pic;
                        }
                        $('#enquiry_id').html('HR00' + id);
                        $('.profile-logo img').attr('src', img);
                        $('.profile-title').html(msg.name + ', <span>' + msg.role + '</span>');
                        $('.profile-mail').html(msg.email);
                        $('.profile-phone').html(msg.country_code + ' ' + msg.phone_no.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2 ($3)"));
                        $('#inquiry_date').html(new Date(date).toLocaleString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        }));
                        $('.desc').html(msg.message);
                        if(msg.admin_reply != null){
                            var reply_date = moment.utc(msg.replied_at).local().format();
                            $('.replies').show()
                            $('.thanks').show()
                            $('.replied_note').show();
                            $('.replies span').html(new Date(reply_date).toLocaleString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }))
                            $('.thanks').html(msg.admin_reply)
                            $('.inquiries-search-sec').hide();
                            $('.reply_note').hide();
                            $('.reply-btn').html('REPLIED');
                        }else{
                            $('.replies').hide()
                            $('.thanks').hide()
                            $('.replied_note').hide();
                            $('.inquiries-search-sec').show();
                            $('.reply_note').show();
                            $('.reply-btn').html('REPLY');
                        }
                        $('.reply-input').val('')
                        $('.required_error').hide();
                        $('#modalInquiriesDetails').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            $(".reply-input").on("keydown", function(e){
                console.log('hello input')
                var admin_reply = $('.reply-input').val();
                if(admin_reply &&  admin_reply.length > 1000){
                    $('.required_error').show();
                    $('.required_error').html('You can send reply in maximum 1000 character');
                }else{
                    $('.required_error').hide();
                }
            });

            $(document).on('click', '.reply-btn', function(e){
                e.preventDefault();
                var admin_reply = $('.reply-input').val();
                if(!admin_reply){
                    $('.required_error').show();
                    $('.required_error').html('Please Enter message.');
                    return false;
                }
                if(admin_reply &&  admin_reply.length > 1000){
                    $('.required_error').show();
                    $('.required_error').html('You can send reply in maximum 1000 character');
                    return false;
                }

                $.ajax({
                    url: 'inquiry/reply/'+id,
                    type: 'put',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "admin_reply": admin_reply,
                    },
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    complete:function () {
                        $('.loader').hide();
                    },
                    statusCode: {
                        200: function (msg) {
                            var reply_date = moment.utc(msg.data.replied_at).local().format();
                            console.log(msg.data.admin_reply);
                            $('.replies').show()
                            $('.replies span').html(new Date(reply_date).toLocaleString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }))
                            $('.thanks').show()
                            $('.thanks').html(msg.data.admin_reply)
                            $('.replied_note').show();
                            $('.inquiries-search-sec').hide();
                            $('.reply_note').hide();
                            $('.reply-btn').html('REPLIED');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            $(document).on('click', '#generate_csv', function(e){
                e.preventDefault();
                var month_value = $('#month_select').find(":selected").val();
                var month = $('#month_select').find(":selected").text();
                var year = $('#year_select').find(":selected").text();
                const date_month_short = new Date();
                date_month_short.setMonth(month_value - 1);
                $.ajax({
                    url: 'inquiry/export',
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "month": month_value,
                        "year": year,
                    },
                    beforeSend: function () {
                        $('#modalExportCsv').modal('hide');
                        $('.loader').show();
                    },
                    complete:function () {
                        $('.loader').hide();
                    },
                    statusCode: {
                        200: function (msg) {
                            console.log(msg);
                            var headers = {
                                sno: 'S.No.',
                                name: 'Name',
                                email: "Email",
                                issue_id: "Issue Id",
                                user_type: "User Type",
                                issue: "Issue",
                                date: "Date",
                                adminrply: "Admin's Reply",
                            };

                            var itemsFormatted = [];

                            // format the data
                            msg.data.forEach((item, i) => {
                                var date_export = moment.utc(item.created_at).local().format();
                                itemsFormatted.push({
                                    sno: i+1,
                                    name: item.name,
                                    email: item.email,
                                    issue_id: "HR00"+item.id,
                                    user_type: item.role,
                                    issue: item.message.replace(/,/g, ''),
                                    date: new Date(date_export).toLocaleString('en-US', {
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric'
                                    }).replace(/,/g, ''),
                                    adminrply: (item.admin_reply) ? item.admin_reply.replace(/,/g, '') : '',
                                });
                            });

                            var fileTitle = 'inquiry_data_'+date_month_short.toLocaleString('en-US', { month: 'short' })+'-'+year.toString().substr(-2); // or 'my-unique-title'

                            exportCSVFile(headers, itemsFormatted, fileTitle);
                            
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });

            });

            function convertToCSV(objArray) {
                var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
                var str = '';

                for (var i = 0; i < array.length; i++) {
                    var line = '';
                    for (var index in array[i]) {
                        if (line != '') line += ','

                        line += array[i][index];
                    }

                    str += line + '\r\n';
                }

                return str;
            }

            function exportCSVFile(headers, items, fileTitle) {
                if (headers) {
                    items.unshift(headers);
                }

                // Convert Object to JSON
                var jsonObject = JSON.stringify(items);

                var csv = convertToCSV(jsonObject);

                var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                if (navigator.msSaveBlob) { // IE 10+
                    navigator.msSaveBlob(blob, exportedFilenmae);
                } else {
                    var link = document.createElement("a");
                    if (link.download !== undefined) { // feature detection
                        // Browsers that support HTML5 download attribute
                        var url = URL.createObjectURL(blob);
                        link.setAttribute("href", url);
                        link.setAttribute("download", exportedFilenmae);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }
            }

        });
    </script>
@endpush