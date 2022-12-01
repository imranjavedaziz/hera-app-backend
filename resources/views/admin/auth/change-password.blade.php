@extends('admin.layouts.login_base')
@section('content')
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 p-0">
          <div class="alert alert-warning" id="alert-msg-box" role="alert" style="@if ($errors->has('error')) display: block @else display: none @endif">
              <div class="alert-text">
                  <span>
                      <img src="{{ asset('assets/images/svg/warning.svg') }}" alt="success alert icon"/>
                  </span> <span id="alert-error-msg"> @if ($errors->has('error')) {{$errors->first('error')}} @endif </span>
              </div>
              <div class="text-end">
                  <img src="{{ asset('assets/images/svg/alert-cross.svg') }}" alt="alert icon"/>
              </div>
          </div>
          <div class="signin-container">
            <!-- sign in start here -->
            <div class="app-logo">
              <img src="{{ asset('/assets/images/logo.png')}}" alt="app logo" />
            </div>
            <div class="passward-cross">
              <a href="{{ route('userList') }}" title="All Users">
                <img src="{{ asset('/assets/images/svg/cross-big.svg')}}" alt="cross img" />
              </a>
            </div>
            <div class="login-form" action="#">
              <div class="signin-section">
                <h1 class="signin-title">Change Password</h1>
                <div class="login-form-wrapper">
                  <form class="login-container" id="changePassword" action="{{  route('update-password')}}" method="POST" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-floating mb-5 position-relative">
                        <input type="password" class="form-control @error('current_password') error @enderror" id="floatingPassword" placeholder="Current Password" maxlength="20" name="current_password" value="{{ old('current_password') }}" onfocusout="password_check('floatingPassword')">
                        <label for="floatingPassword" class="floatingPasswordLabel">Current Password</label>
                        <span class="invalid-field text-right floatingPassword">
                          @error('current_password') {{ $message }} @enderror
                        </span>
                        <img src="{{ asset('/assets/images/svg/eye-open.svg')}}" class="eye-img" alt="Image">
                    </div>
                    <div class="form-floating mb-5 position-relative">
                      <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="floatingNewPassword" placeholder="New Password" maxlength="20" name="new_password" value="{{ old('new_password') }}" onfocusout="password_check('floatingNewPassword')">
                      <label for="floatingNewPassword" class="floatingNewPasswordLabel">New Password</label>
                      <span class="invalid-field text-right floatingNewPassword">
                        @error('new_password') {{ $message }} @enderror
                      </span>
                    </div>
                    <div class="form-floating position-relative">
                      <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password" maxlength="20" name="confirm_password" value="{{ old('confirm_password') }}" onfocusout="password_check('floatingConfirmPassword')">
                      <label for="floatingConfirmPassword" class="floatingConfirmPasswordLabel">Confirm Password</label>
                      <span class="invalid-field text-right floatingConfirmPassword">
                        @error('confirm_password') {{ $message }} @enderror
                      </span>
                    </div>
                    <div class="login-button">
                      <button class="btn-primary btn-login" type="submit">
                        SAVE
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
    <!-- end main wrapper -->
@endsection


@push('after-scripts')
  <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }} "></script>
  <script src="{{ asset('assets/js/change-password.js') }} "></script>
@endpush