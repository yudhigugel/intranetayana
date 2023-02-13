@extends('layouts.default')

@section('title', 'Custom Plant Access - Add')

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
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">Access Management</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Custom Plant Access</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-9 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>

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

        @error('employee')
          <div class="alert alert-fill-danger alert-dismissable p-3 mb-3" role="alert">
            <i class="mdi mdi-alert-circle"></i> &nbsp;{{ $message }}
            <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @enderror

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Add Custom Plant Access</h2>
        <form method="POST" action="{{ url('/access/management/custom-plant-access/create') }}" id="form-custom-access" autocomplete="off">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label>Select Employee</label>
                  <select class="form-control select2" name="employee" id="employee" required>
                    <option value="" selected></option>
                    @if(isset($data['employee']) && count($data['employee']))
                      @foreach($data['employee'] as $key_emp => $value_emp)
                        <option value="{{ isset($value_emp['EMPLOYEE_ID']) ? $value_emp['EMPLOYEE_ID'] : '' }}">{{ isset($value_emp['EMPLOYEE_ID']) ? $value_emp['EMPLOYEE_ID'] : 'Unknown' }} - {{ isset($value_emp['EMPLOYEE_NAME']) ? $value_emp['EMPLOYEE_NAME'] : 'Unknown' }}</option>
                      @endforeach 
                    @endif
                  </select>
                  <div class="loading-selected-data" hidden>
                    <small id="found-data-text"><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Loading data ...</small>
                  </div>
                </div>
                <div class="form-group">
                  <label>Select Menu</label>
                  <select class="form-control select2" name="menu" id="menu" required>
                    <option value="" selected></option>
                    @if(isset($data['menu']) && count($data['menu']))
                      @foreach($data['menu'] as $key => $value)
                        <option value="{{ isset($value['SEQ_ID']) ? $value['SEQ_ID'] : '' }}">{{ isset($value['MENU_NAME']) ? $value['MENU_NAME'] : '' }}</option>
                      @endforeach 
                    @endif
                  </select>
                </div>
                <div class="form-group">
                  <label>Select Plant</label>
                  <select class="form-control select2" multiple name="plant[]" id="plant" required>
                    @if(isset($data['plant']) && count($data['plant']))
                      @foreach($data['plant'] as $key_plant => $value_plant)
                        <option value="{{ isset($value_plant->SAP_PLANT_ID) ? $value_plant->SAP_PLANT_ID : '' }}">{{ isset($value_plant->SAP_PLANT_ID) ? $value_plant->SAP_PLANT_ID : 'Unknown' }} - {{ isset($value_plant->SAP_PLANT_NAME) ? $value_plant->SAP_PLANT_NAME : 'Unknown' }}</option>
                      @endforeach 
                    @endif
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-group text-left d-flex">
              <div class="pr-2">
                <button type="submit" class="btn btn-primary btn-submit" disabled><i class="mdi mdi-check"></i> Submit</button>
              </div>
              {{--<div class="pr-2">
                <button type="reset" class="btn btn-danger btn-reset" disabled><i class="mdi mdi-history"></i> Reset</button>
              </div>--}}
            </div>
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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>
<script src="//bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
<!-- <script src="//cdn.datatables.net/plug-ins/1.10.9/features/searchHighlight/dataTables.searchHighlight.min.js"></script> -->

<script type="text/javascript">
  $(document).ready( function () {
     $('.select2').select2({
      placeholder: "Select an option",
      allowClear: true
     });

     $('.btn-submit').prop('disabled', false)
     $('.btn-reset').prop('disabled', false)

  // END READY FUNCTION
  });
</script>
@endsection

