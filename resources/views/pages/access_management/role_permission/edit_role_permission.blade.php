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

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Edit Role Menu</h2>
        <form method="POST" id="form-rolemenu" autocomplete="off">
          {{ csrf_field() }}
          <div class="wrapper-form">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label>Select Role</label>
                  <select class="form-control select2" name="role" disabled id="role" required>
                    <option value="" selected>Select Role</option>
                    @if(isset($data_role) && count($data_role))
                      @foreach($data_role as $key => $value)
                        <option value="{{ $value->SEQ_ID }}" @if(isset($role_selected) && $role_selected == $value->SEQ_ID) selected @endif>{{ $value->ROLE_NAME }}</option>
                      @endforeach 
                    @endif
                  </select>
                </div>
                <div>
                  <div class="mb-2 assign-menu-error" hidden>
                      <small class="text-danger">* Please select at least one assigment</small>
                  </div>
                  <div class="col-12 mb-2">
                    <div class="row pt-2 px-3 bg-light">
                      <div class="col-4">
                        <h5>Menu & Submenu</h5>  
                      </div>
                      <div class="col-8">
                        <h5 class="ml-3">Permission Menu & Submenu</h5>
                      </div>
                    </div>
                  </div>
                </div>
                <fieldset class="form-group border border-2 p-3" style="max-height: 500px; overflow: auto;">
                  @if(isset($data_permission) && count($data_permission) && isset($data_menu_map))
                  <div class="col-12">
                    <!-- FOR MENU LOOP -->
                    @foreach($data_menu_map as $key => $parent)
                    <div class="row text-center">
                      <div class="col-12 text-left">
                        <h4 class="form-group"><input type="checkbox" name="menu[]" value="{{ $parent['SEQ_ID'] }}" class="mr-2 checkbox-title" data-unique="{{ $parent['SEQ_ID'] }}" @if(isset($assigned_rolemenu) && in_array($parent['SEQ_ID'], $assigned_rolemenu)) checked @endif class="mr-2 checkbox-title" data-mapping-key="{{ $key }}"><b>{{ $parent['MENU_NAME'] }}</b></h4>
                        @if(isset($parent['child']) && count($parent['child']))
                          @foreach($parent['child'] as $key => $child_1)
                            <div class="row align-items-center justify-content-center">
                              @if(isset($child_1['child']) && count($child_1['child']) > 0)
                              <div class="col-12">
                                <div class="mb-1">
                                  <a class="text-secondary d-block mb-1 pl-4">
                                    <input type="checkbox" name="menu[]" value="{{ $child_1['SEQ_ID'] }}" class="mr-1 checkbox-parent" data-unique="{{ $child_1['SEQ_ID'] }}" data-parent-id="{{ $child_1['PARENT_ID'] }}" @if(isset($assigned_rolemenu) && in_array($child_1['SEQ_ID'], $assigned_rolemenu)) checked @endif>
                                    <h6 class="menu-title d-inline-block" style="cursor: pointer" data-toggle="collapse" href="#icons-{{ $child_1['SEQ_ID'] }}">{{ $child_1['MENU_NAME'] }}
                                      @if(isset($child_1['child']) && count($child_1['child']))
                                      <i class="mdi mdi-chevron-down"></i>
                                      @endif
                                    </h6>
                                  </a>
                                  @if(isset($child_1['child']) && count($child_1['child']))
                                    @foreach($child_1['child'] as $key => $child_2)
                                      <div class="row align-items-center justify-content-center">
                                        <div class="col-4">
                                          <div class="collapse show pl-4 ml-4" id="icons-{{ $child_2['PARENT_ID'] }}">
                                            <ul class="nav flex-column sub-menu mb-2">
                                              <li><input type="checkbox" name="menu[]" value="{{ $child_2['SEQ_ID'] }}" class="mr-1 checkbox-child" data-parent-id="{{ $child_2['PARENT_ID'] }}" @if(isset($assigned_rolemenu) && in_array($child_2['SEQ_ID'], $assigned_rolemenu)) checked @endif><h6 class="menu-title d-inline-block" style="font-weight: lighter">{{ $child_2['MENU_NAME'] }}</h6></li>
                                            </ul>
                                          </div>
                                        </div>
                                        @foreach($data_permission as $permission)
                                        @php
                                          $role_permission_1 = $child_2['SEQ_ID'].'.'.$permission->SEQ_ID;
                                        @endphp
                                        <div class="collapse show col-2 text-center" id="icons-{{ $child_2['PARENT_ID'] }}">
                                          <label class="toggle-switch">
                                            <input @if(isset($data_permission_assigned) && in_array($role_permission_1, $data_permission_assigned)) checked @endif type="checkbox" name="permission[]" value="{{ $child_2['SEQ_ID'] }}.{{ $permission->SEQ_ID }}">
                                            <span class="toggle-slider round"></span>
                                          </label>
                                          <small class="ml-2">{{ $permission->PERMISSION_NAME }}</small>
                                        </div>
                                        @endforeach
                                      </div>
                                    @endforeach
                                  @endif
                                </div>
                              </div>
                              @elseif (isset($child_1['child']) && count($child_1['child']) < 1)
                                <div class="col-4">
                                  <div class="mb-1 offset-0 pl-4">
                                    <a class="text-secondary d-block mb-1">
                                      <input type="checkbox" name="menu[]" value="{{ $child_1['SEQ_ID'] }}" class="mr-1 checkbox-parent" data-unique="{{ $child_1['SEQ_ID'] }}" data-parent-id="{{ $child_1['PARENT_ID'] }}" @if(isset($assigned_rolemenu) && in_array($child_1['SEQ_ID'], $assigned_rolemenu)) checked @endif>
                                      <h6 class="menu-title d-inline-block" style="cursor: pointer" data-toggle="collapse" href="#icons-{{ $child_1['SEQ_ID'] }}">{{ $child_1['MENU_NAME'] }}
                                      </h6>
                                    </a>
                                  </div>
                                </div>
                                @foreach($data_permission as $permission)
                                @php
                                  $role_permission = $child_1['SEQ_ID'].'.'.$permission->SEQ_ID;
                                @endphp
                                <div class="col-2 text-center">
                                  <label class="toggle-switch">
                                    <input @if(isset($data_permission_assigned) && in_array($role_permission, $data_permission_assigned)) checked @endif type="checkbox" name="permission[]" value="{{ $child_1['SEQ_ID'] }}.{{ $permission->SEQ_ID }}">
                                    <span class="toggle-slider round"></span>
                                  </label>
                                  <small class="ml-2">{{ $permission->PERMISSION_NAME }}</small>
                                </div>
                                @endforeach
                              @endif
                            </div>
                          @endforeach
                        @else
                          <div class="mb-1 nav offset-1 pl-1"><small class="text-danger"> No Menu Available </small></div>
                        @endif
                      </div>
                    </div>
                    <hr class="mt-2 mb-4">
                    @endforeach
                  </div>
                  @else 
                    <div class="mb-1 nav offset-1 pl-1"><small class="text-danger"> No Menu or Permission Available </small></div>
                  @endif



                  {{--@if(isset($data_menu_map))
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
                  @endif --}}
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

