<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ayana Intranet | @yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" type="image/x-icon" href="/atlant/html/img/favicon.ico" />
    <link rel="stylesheet" href="/asset-2020/login-form-new/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/asset-2020/login-form-new/css/vertical-layout-light.style.css">
    <link rel="stylesheet" href="/css/vendor/sweetalert2.min.css">
    <style>
        .auth .login-half-bg {
            background: url("{{ asset('image/bg_login.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
         .auth .login-half-bg2 {
            background: url("{{ asset('image/bg_login2.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .auth-form-transparent .brand-logo{
            margin-bottom: 2rem;
        }
     <style>
       

    </style>
</head>

<body class="sidebar-fixed">
    @yield('content')
    <!-- container-scroller -->
    <script src="/asset-2020/login-form/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="/js/vendor/sweetalert2.min.js"></script>
    <script src="/js/custom.js"></script>
    @yield('scripts')
</body>

</html>
