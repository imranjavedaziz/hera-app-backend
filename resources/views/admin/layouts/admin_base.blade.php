
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MBC | @if (isset($title)){{$title}} @endif</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico')}}" sizes="32x32">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
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
    @yield('script' ) 
</body>

</html>