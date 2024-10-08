@extends('admin.layouts.login_base')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
        @if ($errors->has('error'))
        <div class="alert alert-warning" role="alert">
            <div class="alert-text">
                <span>
                    <img src="{{ asset('assets/images/svg/warning.svg') }}" alt="success alert icon"/>
                </span> {{$errors->first('error')}}
            </div>
            <div class="text-end">
                <img src="{{ asset('assets/images/svg/alert-cross.svg') }}" alt="alert icon"/>
            </div>
        </div>
        @endif
        <div class="signin-container">
            <!-- sign in start here -->
            <div class="app-logo">
              <img src=" {{ asset('assets/images/logo.svg') }}" alt="app logo" />
            </div>

            <div class="login-form" >
              <div class="signin-section">
                <h1 class="signin-title mb-5">Sign In</h1>
                <div class="login-form-wrapper">
                  <form class="login-container" method="post"  action="{{ route('login') }}">
                  {{ csrf_field() }}
                    <div class="form-floating input-height">
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') error @enderror" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email Address</label>
                        <span class="invalid-field text-right">
                           @error('email') {{ $message }} @enderror
                        </span>
                    </div>
                    <div class="form-floating input-height">
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') error @enderror" id="floatingPassword" placeholder="Password" onfocusout="password_check('floatingPassword')">
                        <label for="floatingPassword" class="floatingPasswordLabel">Password</label>
                        <span class="invalid-field text-right floatingPassword">
                           @error('password') {{ $message }} @enderror
                        </span>
                        <img src=" {{ asset('assets/images/svg/eye-open.svg') }}" class="eye-img" alt="Image">
                      </div>


                    <div class="login-button">
                      <button class="btn-primary btn-login" type="submit">
                        LOG IN
                      </button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- end signin container wrapper -->
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.6.1.min.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.39/moment-timezone-with-data-10-year-range.js"></script>
<script src="{{ asset('assets/js/password-common-function.js') }} "></script>
    <!-- end main wrapper -->
    <script type="text/javascript">
    $(".eye-img").click(function() {
        var input = $('#floatingPassword');
        if (input.attr("type") == "password") {
            $(this).attr('src', "{{ asset('assets/images/svg/eye-close.svg') }}");
            input.attr("type", "text");
        } else {
            $(this).attr('src', "{{ asset('assets/images/svg/eye-open.svg') }}");
            input.attr("type", "password");
        }
    });
    $(".text-end").click(function() {
        $('.alert').hide();
    });
    $(document).ready(function() {
        var timezone = moment.tz.guess();
        $.ajax({
              url: 'admin/update-timezone',
              type: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                  "timezone": timezone,
                },
              statusCode: {200: function (data) {}
              },
              error: function (xhr, textStatus, errorThrown) {
              }
              });
            });

            $(document).on('focus','input', function (e) {
              var id = $(this).attr('id');
              $('#'+id).siblings('span').hide();
              $('#'+id).removeClass('error')
            });
    </script>
@endsection