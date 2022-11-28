@extends('admin.layouts.login_base')
@section('content')
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 p-0">
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
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Current Password" maxlength="10" name="current_password">
                        <label for="floatingPassword">Current Password</label>
                        <span class="invalid-field text-right">Required</span>
                        <img src="{{ asset('/assets/images/svg/eye-open.svg')}}" class="eye-img" alt="Image">
                    </div>
                    <div class="form-floating mb-5 position-relative">
                      <input type="password" class="form-control" id="floatingPassword2" placeholder="New Password" name="new_password">
                      <label for="floatingPassword">New Password</label>
                      <span class="invalid-field text-right">Required</span>
                  </div>
                  <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="floatingPassword3" placeholder="Confirm Password" name="confirm_password">
                    <label for="floatingPassword">Confirm Password</label>
                    <span class="invalid-field text-right">Required</span>
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