@extends('layouts.default')

@section('title', 'Master Sidebar Menu - Add')

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
    <li aria-current="page" class="breadcrumb-item active"><span>Master Sidebar Menu</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-12 stretch-card" style="position: relative;">
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

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Create Menu</h2>
        <form method="POST" action="{{ url('/access/management/master-menu/create') }}" id="form-access-menu">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <label>Menu Name</label>
                <input type="text" name="menu_name" required class="form-control" placeholder="Input Menu Name" value="{{ old('menu_name') }}">
                @error('menu_name')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-4">
                <input type="checkbox" name="is_parent" class="check-menu" id="parent-checkbox" disabled value="1">
                <label>&nbsp;&nbsp;Set as Parent Menu</label>
              </div>
              <div class="col-4">
                <input type="checkbox" name="is_group" class="check-menu" id="group-checkbox" disabled value="1">
                <label>&nbsp;&nbsp;Set as Child Of Group</label>
              </div>
              <div class="col-4">
                <input type="checkbox" name="is_single" class="check-menu" id="single-checkbox" disabled value="1">
                <label>&nbsp;&nbsp;Set as Single Menu</label>
              </div>
              <div class="checkbox-no-select" hidden>
                <small class="text-danger">* Please select at least one checkbox</small>
              </div>
            </div>
            <div class="row mb-3 group-menu-wrapper" hidden>
              <div class="col-12">
                <label>Insert To Group</label>
                <select class="form-control select2" style="width:100%" id="select_group" name="group_id">
                  <option value="">Select Group</option>
                  @if(isset($data_menu_group) && count($data_menu_group))
                    @foreach($data_menu_group as $menu)
                      <option value="{{ $menu->SEQ_ID }}">{{ $menu->MENU_NAME }}</option>
                    @endforeach
                  @endif
                </select>
                <div>
                  <small class="text-muted">* Choose only if the menu is the part of a group</small>
                </div>
              </div>
            </div>
            <div class="row mb-3 parent-menu-wrapper" hidden>
              <div class="col-12">
                <label>Insert To Parent</label>
                <select class="form-control select2" style="width:100%" id="select_parent" name="parent_id">
                  <option value="">Select Parent</option>
                  @if(isset($data_menu_parent) && count($data_menu_parent))
                    @foreach($data_menu_parent as $menu)
                      <option value="{{ $menu->SEQ_ID }}">{{ $menu->MENU_NAME }}</option>
                    @endforeach
                  @endif
                </select>
                <div>
                  <small class="text-muted">* Choose only if the menu is single menu after main parent</small>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row mb-3" id="route-url-wrapper">
              <div class="col-12">
                <label>Route URL</label>
                <input type="text" name="path" id="route-url" required class="form-control" placeholder="Input URL" value="{{ old('path') }}">
                @error('path')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
              </div>
            </div>
            <div class="row mb-3" id="route-name-wrapper">
              <div class="col-12">
                <label>Route Name</label>
                <input type="text" name="path_name" id="route-name" required class="form-control" placeholder="Input URL Alias" value="{{ old('path_name') }}">
                @error('path_name')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <label>Icon Class (Optional)</label>
                <input type="text" name="icon" class="form-control" placeholder="Input Icon Class (mdi-*)" value="{{ old('icon') }}">
                @error('icon')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <label>Iframe URL (Optional)</label>
                <input type="text" name="iframe_source" class="form-control" placeholder="Input Iframe URL" value="{{ old('iframe_source') }}">
                @error('iframe_source')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
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
<script src="//cdn.datatables.net/plug-ins/1.10.9/features/searchHighlight/dataTables.searchHighlight.min.js"></script>

<script type="text/javascript">
  $(document).ready( function () {
     $('.select2').select2({
      placeholder: "Select an option",
      allowClear: true
     });

     $('#parent-checkbox').prop('disabled', false)
     $('#group-checkbox').prop('disabled', false)
     $('#single-checkbox').prop('disabled', false)

     $('.btn-submit').prop('disabled', false)
     $('.btn-reset').prop('disabled', false)

  // END READY FUNCTION
  });

  $('#parent-checkbox').change(function(){
    if(this.checked){
      // $('#select_parent').prop('disabled', true)
      $('#route-url-wrapper').prop('hidden', true)
      $('#route-name-wrapper').prop('hidden', true)

      $('#route-url').prop('required', false)
      $('#route-name').prop('required', false)
      $('#route-url').prop('readonly', true)
      $('#route-name').prop('readonly', true)

      $('#group-checkbox').prop('disabled', true)
      $('#single-checkbox').prop('disabled', true)
    }
    else {
      $('#route-url-wrapper').prop('hidden', false)
      $('#route-name-wrapper').prop('hidden', false)

      $('#route-url').prop('required', true)
      $('#route-name').prop('required', true)
       $('#route-url').prop('readonly', false)
      $('#route-name').prop('readonly', false)

      // $('#select_parent').prop('disabled', false)
      $('#group-checkbox').prop('disabled', false)
      $('#single-checkbox').prop('disabled', false)
    }
    $('.checkbox-no-select').prop('hidden', true);
  });

  $('#group-checkbox').change(function(){
    if(this.checked){
      $('.group-menu-wrapper').prop('hidden', false)
      $('#select_group').prop('required', true)

      $('#parent-checkbox').prop('disabled', true)
      $('#single-checkbox').prop('disabled', true)
    }
    else {
      $('.group-menu-wrapper').prop('hidden', true)
      $('#select_group').prop('required', false)

      $('#parent-checkbox').prop('disabled', false)
      $('#single-checkbox').prop('disabled', false)
    }
    $('.checkbox-no-select').prop('hidden', true);
  });

  $('#single-checkbox').change(function(){
    if(this.checked){
      $('.parent-menu-wrapper').prop('hidden', false)
      $('#select_parent').prop('required', true)

      $('#parent-checkbox').prop('disabled', true)
      $('#group-checkbox').prop('disabled', true)
    }
    else {
      $('.parent-menu-wrapper').prop('hidden', true)
      $('#select_parent').prop('required', false)

      $('#parent-checkbox').prop('disabled', false)
      $('#group-checkbox').prop('disabled', false)
    }
    $('.checkbox-no-select').prop('hidden', true);
  });

  $('#form-access-menu').submit(function(e){
    var checked = 0;
    $.each($('.check-menu'), function(index, element){
      if(element.checked){
        checked = 1;
        return false;
      }
    });
    // Check if any was checked
    if(checked <= 0){
      e.preventDefault();
      $('.checkbox-no-select').prop('hidden', false);
      return false
    }
    else
      return true
  })


</script>
@endsection

