@extends('layouts.default')

@section('title', 'Group Role (Assign Menu) - Add')

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
    <li aria-current="page" class="breadcrumb-item active"><span>Group Role (Assign Menu)</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-10 stretch-card" style="position: relative;">
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

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Add Role Menu</h2>
        <form method="POST" action="{{ url('/access/management/master-rolemenu/create') }}" id="form-rolemenu" autocomplete="off">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label>Select Role</label>
                  <select class="form-control select2" name="role" disabled id="role" required>
                    <option value="" selected>Select Role</option>
                    @if(isset($data_role) && count($data_role))
                      @foreach($data_role as $key => $value)
                        <option value="{{ $value->SEQ_ID }}">{{ $value->ROLE_NAME }}</option>
                      @endforeach 
                    @endif
                  </select>
                </div>
                <fieldset class="form-group">
                  <div class="mb-2 assign-menu-error" hidden>
                    <small class="text-danger">* Please select at least one assigment</small>
                  </div>
                  @if(isset($data_menu_map))
                    <div class="row">
                    @foreach($data_menu_map as $key => $parent)
                      <div class="col-lg-3 mb-3">
                        <h6 class="mb-2"><input type="checkbox" name="menu[]" value="{{ $parent['SEQ_ID'] }}" class="mr-2 checkbox-title" data-unique="{{ $parent['SEQ_ID'] }}">{{ $parent['MENU_NAME'] }}</h6>
                        @if(isset($parent['child']) && count($parent['child']))
                          @foreach($parent['child'] as $key => $child_1)
                            <div class="mb-1 offset-1 pl-2">
                              <a class="text-secondary d-block mb-1">
                                <input type="checkbox" name="menu[]" value="{{ $child_1['SEQ_ID'] }}" class="mr-1 checkbox-parent" data-unique="{{ $child_1['SEQ_ID'] }}" data-parent-id="{{ $child_1['PARENT_ID'] }}">
                                <small class="menu-title" style="cursor: pointer;font-size: 85%" data-toggle="collapse" href="#icons-{{ $child_1['SEQ_ID'] }}">{{ $child_1['MENU_NAME'] }}
                                  @if(isset($child_1['child']) && count($child_1['child']))
                                  <i class="mdi mdi-chevron-down"></i>
                                  @endif
                                </small>
                              </a>
                            @if(isset($child_1['child']) && count($child_1['child']))
                              @foreach($child_1['child'] as $key => $child_2)
                                <div class="collapse show offset-2 ml-3 pl-2" id="icons-{{ $child_2['PARENT_ID'] }}">
                                  <ul class="nav flex-column sub-menu">
                                    <li><input type="checkbox" name="menu[]" value="{{ $child_2['SEQ_ID'] }}" class="mr-1 checkbox-child" data-parent-id="{{ $child_2['PARENT_ID'] }}"><small class="menu-title" style="font-size: 90%">{{ $child_2['MENU_NAME'] }}</small></li>
                                  </ul>
                                </div>
                              @endforeach
                            @endif
                            </div>
                          @endforeach
                        @else
                          <div class="mb-1 nav offset-1 pl-1"><small class="text-danger"> No Menu Available </small></div>
                        @endif
                      </div>
                    @endforeach
                    </div>
                  @else
                  <h6><i class="mdi mdi-alert-circle text-danger"></i>&nbsp;&nbsp;No Menu Available</h6>
                  @endif
                  <!-- <label>Select Menu</label>
                  <select class="form-control select2" name="menu" id="menu" multiple>
                    @if(isset($data_role) && count($data_role))
                      @foreach($data_menu as $key => $value)
                        <option value="{{ $value->SEQ_ID }}">{{ $value->MENU_NAME }}</option>
                      @endforeach
                    @endif
                  </select> -->
                </fieldset>
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
<!-- <script src="//cdn.datatables.net/plug-ins/1.10.9/features/searchHighlight/dataTables.searchHighlight.min.js"></script> -->

<script type="text/javascript">
  $(document).ready( function () {
     $('.select2').select2({
      placeholder: "Select an option",
      allowClear: true
     });

     $('.btn-submit').prop('disabled', false);
     $('.btn-reset').prop('disabled', false);
     $('#role').prop('disabled', false);
     // checkParentSelected($('.checkbox-parent'));

  // END READY FUNCTION
  });
  function checkParentSelected(element=null){
    var counter_parent = 0;
    var check_checked = $.each(element, function(index, element){
        if(this.checked)
          counter_parent++;
    });
    if(counter_parent > 0)
      return true;
    return false
  }

  $(document).on('change', $('input[type="checkbox"]'), function(){
    try{
      $('.assign-menu-error').prop('hidden', true) 
    } catch(error) {console.log(error)}
  });

  $(document).on('change', '.checkbox-title', function(){
    try{
      var value = this.value;
      if(this.checked)
        $(`.checkbox-parent[data-parent-id="${value}"]`).prop('checked', true).trigger('change');
      else
        $(`.checkbox-parent[data-parent-id="${value}"]`).prop('checked', false).trigger('change');
    } catch(error){console.log(`Cannot set checkbox on parent ${error}, ${this.value}`)}
  });

  $(document).on('change', '.checkbox-parent', function(){
    try{
      var value = this.value;
      var title_id = $(this).data('parent-id');
      if(this.checked){
        try{
          $(`.checkbox-child[data-parent-id="${value}"]`).prop('checked', true);
        }catch(error){}
        // $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', true);
      }
      else{
        try{
        $(`.checkbox-child[data-parent-id="${value}"]`).prop('checked', false);
        }catch(error){}
        // $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', false);
      }

      if(checkParentSelected($(`.checkbox-parent[data-parent-id="${title_id}"]`))) {
        try{
          $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', true);
        } catch(error){}
      }
      else{ 
        try{
          $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', false);
        } catch(error){}
      }

    } catch(error){console.log(`Cannot set checkbox on child ${error}, ${this.value}`)}
  });

  $(document).on('change', '.checkbox-child', function(){
    try{
      var value = this.getAttribute('data-parent-id');
      var parent = $(`.checkbox-parent[data-unique="${value}"]`).data('parent-id') ? $(`.checkbox-parent[data-unique="${value}"]`) : 0;
      var title_id = $(`.checkbox-parent[data-unique="${value}"]`).data('parent-id') ? $(`.checkbox-parent[data-unique="${value}"]`).data('parent-id') : 0;
      var check_child_length = $(`.checkbox-child[data-parent-id="${value}"]`).length;
      var counter = 0;
      var check_checked = $.each($(`.checkbox-child[data-parent-id="${value}"]`), function(index, element){
        if(this.checked)
          counter++;
        return counter;
      });

      if(this.checked && counter > 0){
        $(`.checkbox-parent[data-unique="${value}"]`).prop('checked', true);
      }
      else if(!this.checked && counter > 1){
        $(`.checkbox-parent[data-unique="${value}"]`).prop('checked', true);
      }
      else if(counter == 1){
        $(`.checkbox-parent[data-unique="${value}"]`).prop('checked', true);
      }
      else{
        $(`.checkbox-parent[data-unique="${value}"]`).prop('checked', false);
      }

      if(checkParentSelected(parent))
          $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', true);
      else 
          $(`.checkbox-title[data-unique="${title_id}"]`).prop('checked', false);


    } catch(error){console.log(`Cannot set checkbox on child ${error}, ${this.value}`)}
  });

  $('#form-rolemenu').submit(function(e){
    try {
      var total_selected = 0;
      $.each($('input[type="checkbox"]'), function(){
        if(this.checked){
          total_selected = 1;
          return false;
        }
      });

      if(total_selected == 0){
        $('.assign-menu-error').prop('hidden', false);
        e.preventDefault();
        return false;
      }

      $('.assign-menu-error').prop('hidden', true);
    } catch(error) {console.log(error)}

    return true;
  });
</script>
@endsection

