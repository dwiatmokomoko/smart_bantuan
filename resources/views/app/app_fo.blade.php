<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Deerhost Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Deerhost, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartPBI: BPJS PBI</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800,900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('fo/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/flaticon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('fo/css/smartpbi-theme-fo.css') }}" type="text/css">
    @stack('style')
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas__menu__overlay"></div>
    @include('navigations.navigation_fo')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('navigations.topbar_fo')
    <!-- Header End -->

    @yield('content_fo')

    <!-- Footer Section Begin -->
    @include('navigations.footer_fo')
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{{ asset('fo/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('fo/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('fo/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('fo/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('fo/js/main.js') }}"></script>
    <script src="{{ asset('bo/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    @stack('script')

    <!-- Sticky Navbar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerSection = document.querySelector('.header-section');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    headerSection.classList.add('scrolled');
                } else {
                    headerSection.classList.remove('scrolled');
                }
            });
        });
    </script>
</body>

</html>
