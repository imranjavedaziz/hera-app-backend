<!DOCTYPE html>
<html lang="en">
<head>
	<title>MBC | @if (isset($title)){{$title}} @endif</title>
    @include('admin.layouts.partials.admin.head')
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