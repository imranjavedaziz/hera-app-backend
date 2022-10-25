<!DOCTYPE html>
<html lang="en">
<head>
 @include('layouts.partials.admin.head')
</head>
<body>
    @if(Auth::user())
      <!-- haeder -->
       @include('layouts.partials.admin.sidebar')
      <!-- haeder -->
    @endif
  <!-- this is the common container for all the files -->
  <div class="main-container">
    @if(Auth::user())

      <!-- START FULL WIDTH -->
      <div class="full-width">
        <!-- sidebar -->
         @include('layouts.partials.admin.sidebar')
        <!-- sidebar -->
    @else
      <div class="login-main-box">  
    @endif
     
      @yield('content')

    @if(Auth::user())
     <!-- remove copyright section -->
     @else
     <div class="copyright">Copyright © <?= date("Y"); ?>  Vesti.com. All Rights Reserved.</div>
    @endif 
       
  </div>
</div>
  <!-- container -->
  @include('layouts.partials.admin.footer')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
@include("admin.layouts.partials.admin.sidebar")
</head>
<body>
    <!-- start main wrapper -->
    <div class="container-fluid">
        <div class="main-wrapper">
            <div class="row">
                <!-- left nav bar -->
                @include('admin.layouts.partials.admin.sidebar')
                <!-- End left nav bar -->
                @yield('content')
            </div>
        </div>
    </div>
    <!-- end container fluid -->
    @include('admin.layouts.partials.admin.footer')
</body>

</html>