<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AYANA Intranet - @yield('title')</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('asset-2020/dashflat-layout/css/font-awesome.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/vendors/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
  <!-- End plugin css for this page -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('template/css/vertical-layout-light/custom.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('asset-2020/layout/css/custom.css') }}?v={{ time() }}">
  <!-- endinject -->
  <link rel="stylesheet" href="/css/vendor/sweetalert2.min.css">
  <link rel="shortcut icon" href="{{ asset('ayana-favicon.png') }}" />  
  <link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
  <link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">

  @yield('custom_source_css')
  <!-- Custom css for this page-->
  <style>
    .navbar .navbar-brand-wrapper .brand-logo-mini img {
      width: 50% !important;
      height: auto;
    }

    .table-container-h,
    .table-responsive {
      background:
        linear-gradient(to right, white 30%, rgba(255,255,255,0)),
        linear-gradient(to right, rgba(255,255,255,0), white 70%) 0 100%,
        radial-gradient(farthest-side at 0% 50%, rgba(0,0,0,.1), rgba(0,0,0,0)),
        radial-gradient(farthest-side at 100% 50%, rgba(0,0,0,.1), rgba(0,0,0,0)) 0 100%;
      background-repeat: no-repeat;
      background-color: white;
      background-size: 40px 100%, 40px 100%, 14px 100%, 14px 100%;
      background-position: 0 0, 100%, 0 0, 100%;
      background-attachment: local, local, scroll, scroll;
    }

  </style>
  @yield('styles')
  <!-- End custom js for this page-->
</head>
<script type="text/javascript">
    var env = "{{ env('APP_ENV') }}";
</script>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('partials._navbar')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <!-- <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme">
            <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
          </div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme">
            <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
          </div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles primary"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div> -->
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      @include('partials._sidebar')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('partials._footer')
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


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
  <script type="text/javascript" src="/js/vendor/moment.min.js"></script>
  <script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
  <script src="/template/js/jquery-ui-datepicker.js"></script>
  <script src="/js/vendor/sweetalert2.min.js"></script>
  <script src="{{asset('/js/app/sites/side_menu/index.js')}}?{{time()}}"></script>
  <script src="{{asset('/js/app/sites/sap/index.js')}}?{{time()}}"></script>
  <script src="{{asset('/js/app/sites/report_kms/cancellation/index.js')}}?{{time()}}"></script>
  <script src="{{asset('/js/app/sites/report_simphony/index.js')}}?{{time()}}"></script>
  <script src="{{asset('/js/app/sites/dashboards/index.js')}}?{{time()}}"></script>

  <!-- <script src="{{ asset('js/vendor/bootstrap3-typeahead.min.js') }}"></script>  -->
  <script src="{{ asset('js/vendor/jquery.tokeninput.js') }}"></script>
  <script>
    window.backend_url = "{{ config('intranet.url_backend') }}";
</script>
  @include('partials._js')
  @yield('scripts')
{{-- <script src="{{asset('/js/2020/app/role.js')}}?{{time()}}"></script> --}}
</body>

</html>
