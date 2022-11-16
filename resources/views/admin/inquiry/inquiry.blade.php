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
                            $message = strlen($inquiry->message) > 80 ? substr($inquiry->message,0,80)."..." : $inquiry->message;
                            $issue_id = 'HR00'.$inquiry->id;
                        @endphp
                        <!--  repeat this div  -->
                        <div class="table-row" id="open-detail-modal" data-id="{{$inquiry->id}}">
                            <div class="td">
                                <div class="user-title">
                                    <div class="user-img">
                                        <div>
                                            <img src="/assets/images/svg/user-small-img.png" alt="user image"/>
                                        </div>
                                    </div>
                                    <div class="user-title-info">
                                        <h5>{{$inquiry->name}}</h5>
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
                $('#modalInquiriesDetails').modal('show');
            });
        });
    </script>
@endpush