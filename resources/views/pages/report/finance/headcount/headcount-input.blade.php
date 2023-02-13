@extends('layouts.default')

@section('title', 'Employee Search')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">

<style type="text/css">
.dt-buttons .dataTables_length{
  float:left;
}
.dt-buttons .dataTables_filter{
  float:right;
}
.table,
.dataTables_wrapper{
  position: relative;
}
.dataTables_info{
  position: absolute;
  bottom: 1em;
}
.overlay {
  display: none;
  visibility: hidden;
  position: absolute;
  bottom: 1em;
}
.overlay.in {
  display: block !important;
  visibility: visible !important;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">Access Management</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Menu Access Management</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-9 stretch-card" style="position: relative;">
    <div class="card">
      <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
        @if(session('message') && isset(session('message')['type']))
        <div class="alert alert-fill-{{ session('message')['type'] }} alert-dismissable p-3 mb-3" role="alert">
          @if(session('message')['type'] == 'success')
          <i class="mdi mdi-check"></i>
          @else
          <i class="mdi mdi-alert-circle"></i>
          @endif

          {{ session('message')['msg'] }}
          <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Headcount PNL Report</h2>
        <form method="POST" id="form-headcount" autocomplete="off" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label>Upload Data</label>
                  <input type="file" name="headcount-file" class="form-control" required="">
                  <div class="mt-2">
                    <small class="text-primary"><i class="fa fa-info-circle"></i>&nbsp; Max Upload Size : 4Mb</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-group text-left d-flex">
              <div class="pr-2">
                <button type="submit" class="btn btn-primary btn-submit" disabled><i class="mdi mdi-check"></i> Submit</button>
              </div>
              <div class="pr-2">
                <button type="reset" class="btn btn-danger btn-reset" disabled><i class="mdi mdi-history"></i> Reset</button>
              </div>
            </div>
          </div>
          <div class="overlay">
            <img style="max-width: 30px" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script src="//bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
<!-- <script src="//cdn.datatables.net/plug-ins/1.10.9/features/searchHighlight/dataTables.searchHighlight.min.js"></script> -->

<script type="text/javascript">
  $(document).ready( function () {
     $('.select2').select2({
      placeholder: "Select an option",
      allowClear: true
     });

     $('.btn-submit').prop('disabled', false);
     $('.btn-reset').prop('disabled', false);

  // END READY FUNCTION
  });

  $(document).on('submit', '#form-headcount', function(e){
    $('.btn-submit').prop('disabled', true);
    $('.btn-reset').prop('disabled', true);
    $('.overlay').addClass('in');
  });
</script>
@endsection

