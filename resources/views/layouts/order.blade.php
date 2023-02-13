<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Primamedix - @yield('title')</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('asset-2020/dashflat-layout/css/font-awesome.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
  <!-- End plugin css for this page -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('template/css/horizontal-layout-light/style.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('asset-2020/layout/css/custom.css') }}?v={{ time() }}">
  <!-- endinject -->
  <link rel="stylesheet" href="/css/vendor/sweetalert2.min.css">
  <link rel="shortcut icon" href="{{ asset('logoBiznet.png') }}" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.6.55/css/materialdesignicons.min.css" integrity="sha512-75gimAGx0NOGihrwAtl3xq9SUgq1a3NtIe9fTBtrHOajqMEHCNLZI/BMVE9oMSG3ms5sVira5A2coilfdmxfjA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="{{ asset('css/app/custom_layout.css') }}?v={{ time() }}">
  <style>
    .content-wrapper {
      width: 100%
    }
  </style>
  <!-- Custom css for this page-->
  @yield('styles')
  <!-- End custom js for this page-->
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <div class="horizontal-menu">
      @include('partials._navbar_horizontal')
    </div>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>


        @include('partials._footer')

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->

        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>


  <!-- base:js -->
  <script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->

  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('asset-2020/dashflat-layout/js/template-custom.js') }}"></script>
  <script src="{{ asset('template/js/settings.js') }}"></script>
  <script src="{{ asset('template/js/todolist.js') }}"></script>
  <script src="{{ asset('template/js/tooltips.js') }}"></script>
  <script src="{{ asset('template/js/popover.js') }}"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="{{ asset('template/vendors/flot/jquery.flot.js') }}"></script>
  <script src="{{ asset('template/vendors/flot/jquery.flot.resize.js') }}"></script>
  <script src="{{ asset('template/vendors/flot/curvedLines.js') }}"></script>
  <script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('template/vendors/progressbar.js/progressbar.min.js') }}"></script>
  <script src="{{ asset('template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  {{-- <script src="/js/vendor/sweetalert2.min.js"></script> --}}
  {{-- <script src="{{asset('/js/app/sites/side_menu/index.js')}}?{{time()}}"></script> --}}
  <script>
    window.api_token = <?php echo json_encode(Session::get('api_token')) ?>;
    window.backend_url = "{{ config('intranet.url_backend') }}";
  </script>
  @include('partials._js')
  <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
  @yield('scripts')
  {{-- <script src="{{asset('/js/2020/app/role.js')}}?{{time()}}"></script> --}}
</body>

</html>