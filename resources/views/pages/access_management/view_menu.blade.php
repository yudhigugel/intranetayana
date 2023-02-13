@extends('layouts.default')

@section('title', 'Master Sidebar Menu - View')

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
    <li class="breadcrumb-item"><a href="{{ route('mastermenu.list') }}">Access Management</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Master Sidebar Menu</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-6 stretch-card" style="position: relative;">
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

        <h2 class="card-title"><i class="mdi mdi-plus"></i> View Menu</h2>
        <form method="POST" id="form-access-menu">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <label>Menu Name</label>
                <input type="text" name="menu_name" readonly class="form-control" placeholder="Empty Menu Name" value="{{ isset($data_edit->MENU_NAME) ? $data_edit->MENU_NAME : '' }}">
                @error('menu_name')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
              </div>
            </div>
            <div class="row mb-3" id="route-url-wrapper">
              <div class="col-12">
                <label>Route URL</label>
                <input type="text" name="path" id="route-url" readonly class="form-control" placeholder="Empty URL" value="{{ isset($data_edit->PATH) ? $data_edit->PATH : '' }}">
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
                <input type="text" name="path_name" id="route-name" readonly class="form-control" placeholder="Empty URL Alias" value="{{ isset($data_edit->ROUTE_NAME) ? $data_edit->ROUTE_NAME : '' }}">
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
                <input type="text" name="icon" class="form-control" readonly placeholder="Empty Icon Class (mdi-*)" value="{{ isset($data_edit->ICON) ? $data_edit->ICON : '' }}">
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
                <input type="text" name="iframe_source" class="form-control" readonly placeholder="Empty Iframe URL" value="{{ isset($data_edit->IFRAME_SOURCE) ? $data_edit->IFRAME_SOURCE : '' }}">
                @error('iframe_source')
                  <small class="text-danger mb-2 mt-1 d-block">
                    <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                  </small>
                @enderror
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

     $.each($('.check-menu'), function(index, element){
        if(!element.checked){
          $(element).prop('disabled', true)
        }
        else {
          if(element.getAttribute('id') == 'group-checkbox'){
            $('.group-menu-wrapper').prop('hidden', false)
            $('#select_group').prop('required', true)

            $('#parent-checkbox').prop('disabled', true)
            $('#single-checkbox').prop('disabled', true)
          }
          else if(element.getAttribute('id') == 'single-checkbox'){
            $('.parent-menu-wrapper').prop('hidden', false)
            $('#select_parent').prop('required', true)

            $('#parent-checkbox').prop('disabled', true)
            $('#group-checkbox').prop('disabled', true)
          }
        }
      });

      $(document).on('click', '.btn-delete', function(){
        try {
          var url = $(this).data('url-delete');
        }catch(error){ var url = "{{ url()->current() }}" }
        Swal.fire({
          // title: 'Active / Deactivate Access Role',
          title: 'Delete Menu',
          html: "<span style='line-height:1.5'>Are you sure want to delete this Menu ? <br>Menu will be completely removed from list<span>",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Sure'
        }).then((result) => {
          if (result.isConfirmed) {
            location.href = url;
          }
        })
      });

  // END READY FUNCTION
  });

  $('#parent-checkbox').change(function(){
    if(this.checked){
      // $('#select_parent').prop('disabled', true)
      $('#group-checkbox').prop('disabled', true)
      $('#single-checkbox').prop('disabled', true)
    }
    else {
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

  // $('#form-access-menu').submit(function(e){
  //   var checked = 0;
  //   $.each($('.check-menu'), function(index, element){
  //     if(element.checked){
  //       checked = 1;
  //       return false;
  //     }
  //   });
  //   // Check if any was checked
  //   if(checked <= 0){
  //     e.preventDefault();
  //     $('.checkbox-no-select').prop('hidden', false);
  //     return false
  //   }
  //   else
  //     return true
  // })
</script>
@endsection

