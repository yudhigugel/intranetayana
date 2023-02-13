<!DOCTYPE html>
<!-- saved from url=(0100)https://www.urbanui.com/dashflat/template/demo/horizontal-default-light/pages/samples/error-404.html -->
<html lang="en">
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Intranet AYANA</title>
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
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center text-center error-page bg-dark">
        <div class="row flex-grow">
          <div class="col-lg-7 mx-auto text-white">
            <div class="row align-items-center d-flex flex-row">
              <div class="col-lg-6 text-lg-right pr-lg-4">
                <h1 class="display-1 mb-0 text-white"><strong>403</strong></h1>
              </div>
              <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                <h2 class="text-white">FORBIDDEN!</h2>
                <h3 class="font-weight-light text-white">
                @if(Session::has('invalid_payload') && Session::get('invalid_payload'))
                  {{ Session::get('invalid_payload') }}
                @else
                  {{ "You're restricted to access this page due to invalid or expired credentials." }}
                @endif
                </h3>
              </div>
            </div>
            <div class="row mt-5">
              {{-- <div class="col-12 text-center mt-xl-2">
                <a class="text-white font-weight-medium" href="#">Regenerate Link</a>
              </div> --}}
            </div>
            <div class="mt-5 offset-md-1">
              <div class="text-center">
              <span class="d-block">Copyright © 1980 - {{Date('Y')}}.
                <a href="https://www.midplaza.com/" target="_blank" class="text-white"><strong>MidPlaza Holding</strong></a>. All rights reserved. </span>
              <div class="pt-1 float-none d-block mt-1 mt-sm-0">All data in this site is confidential and can be used only for Internal Use and under Employee Regulations.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
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
  <script src="/js/vendor/sweetalert2.min.js"></script>
  <script src="{{asset('/js/app/sites/side_menu/index.js')}}?{{time()}}"></script>
  <!-- <script src="{{ asset('js/vendor/bootstrap3-typeahead.min.js') }}"></script>  -->
  <script src="{{ asset('js/vendor/jquery.tokeninput.js') }}"></script>



</body></html>