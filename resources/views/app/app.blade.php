<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>SiRuSmart</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('bo/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('bo/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('bo/css/toastr/toastr.min.css') }}" />
    @stack('style')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @auth
            <!-- Sidebar Start -->
            <aside class="left-sidebar">
                <!-- Sidebar scroll-->
                <div>
                    @include('navigations.navigation')
                    <!-- End Sidebar navigation -->
                </div>
                <!-- End Sidebar scroll-->
            </aside>
            <!--  Sidebar End -->
            <!--  Main wrapper -->
            <div class="body-wrapper">
                <!--  Header Start -->
                @include('navigations.topbar')
                @yield('content')
            </div>
        @endauth
        @guest
            @yield('content')
        @endguest
    </div>
    <script src="{{ asset('bo/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bo/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('bo/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('bo/js/app.min.js') }}"></script>
    <script src="{{ asset('bo/js/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('bo/libs/simplebar/dist/simplebar.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "3000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
        }
    </script>

    @stack('script')
    @include('components.alert')
</body>

</html>
