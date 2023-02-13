@extends('layouts.default')

@section('title', 'Group Role (Assign Access) - Edit')

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
    <li aria-current="page" class="breadcrumb-item active"><span>Group Role (Assign Access)</span></li></ol>
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

        @elseif(session('message'))
        <div class="alert alert-fill-danger alert-dismissable p-3 mb-3" role="alert">
          <i class="mdi mdi-alert-circle"></i>
          {{ session('message') }}
          <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> 
        </div>
        @endif

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Assign Menu Access To Role</h2>
        <form method="POST" action="" id="form-rolemenu" autocomplete="off">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label>Select Access Type</label>
                  <select class="form-control select2" name="access" disabled id="access" required>
                    <option value="">Select Access</option>
                    @if(isset($data_access) && count($data_access))
                      @foreach($data_access as $key => $value)
                        <option value="{{ $value->SEQ_ID }}" data-referer="{{ $refer_to_id }}" selected>{{ $value->MENU_ACCESS_NAME }}</option>
                      @endforeach 
                    @endif
                  </select>
                  <div class="loading-selected-data" hidden>
                    <small id="found-data-text"><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Loading data ...</small>
                  </div>
                </div>
                <div class="form-group access-choices">
                </div>
                <div class="form-group">
                  <label>Assign To Role</label>
                  <select class="form-control select2" name="role" disabled id="role" required>
                    <option value="">Pilih Role</option>
                    @if(isset($data_role) && count($data_role))
                      @foreach($data_role as $key => $value)
                        <option value="{{ $value->SEQ_ID }}" @if(isset($role_selected) && $role_selected == $value->SEQ_ID) selected @endif>{{ $value->ROLE_NAME }}</option>
                      @endforeach 
                    @endif
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-group mb-2 text-left d-flex">
              <div class="pr-2">
                <button type="submit" class="btn btn-primary btn-submit" disabled><i class="mdi mdi-check"></i> Submit</button>
              </div>
              {{--<div class="pr-2">
                <button type="reset" class="btn btn-danger btn-reset" disabled><i class="mdi mdi-history"></i> Reset</button>
              </div>--}}
            </div>
            <div>
              <a class="btn-delete" data-url-delete="{{ route('masteraccessrole.remove', [$access_selected, $role_selected]) }}">
                {{--<small class="text-danger" style="cursor: pointer"><i class="fa fa-lock"></i>&nbsp;&nbsp;Active / Deactive Access Role</small>--}}
                <small class="text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Access Role</small>
              </a>
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
     $('.btn-reset').prop('disabled', false);
     $('#access').prop('disabled', false);
     $('#role').prop('disabled', false);

     var dataSelected = {
       "id": $('#access').val() ? $('#access').val() : null,
       "text": $('#access option:selected').text() ? $('#access option:selected').text() : "Unknown data",
     };
     // console.log(dataSelected);

     $('#access').trigger({
        type: 'select2:select',
        params: {
            data: dataSelected
        }
    });

  // END READY FUNCTION
  });

  $(document).on('select2:select', '#access', function(e){
    try{
      $("#dynamicSelect").select2('destroy'); 
      $('#dynamicSelect').remove();
      $('#general-access').remove();
      $('.access-choices').html();
    } catch(error){ console.log(error)}

    // Try to lookup value selected
    var lookup_value = typeof e.params.data.id === "undefined" ? 0 : e.params.data.id;
    var lookup_text = typeof e.params.data.text === "undefined" ? '' : e.params.data.text.toString().toLowerCase();
    if(lookup_text == 'general' || lookup_text == 'public') {
      var input_type = document.createElement("input");
      input_type.id = 'general-access';
      input_type.type = 'hidden';
      input_type.name = 'access_selected[]';
      input_type.value = 0;
      $('.access-choices').append(input_type);
      return;
    }


    $('.loading-selected-data').prop('hidden', false);
    $.ajax({
      url : "{{ route('masteraccessrole.add') }}",
      type : 'POST',
      data : {"_token": "{{ csrf_token() }}", "access_type": lookup_value},
      success : function(res){
        // console.log(res);
        if(!res.data && !(typeof res.data == 'object'))
          Swal.fire('Opps...', 'Something went wrong, please try again or refresh page', 'error');

        var selectList = document.createElement("select");
        selectList.id = "dynamicSelect";
        selectList.style.width = "100%";
        selectList.name = "access_selected[]";
        selectList.multiple = true;
        selectList.required = true;

        var array = typeof res.data[0] === "undefined" ? [] : res.data[0];
        // Set Default Data

        //Create and append the options
        var data_referer = $('#access option:selected').data('referer') ? $('#access option:selected').data('referer') : [];
        for (var i = 0; i < array.length; i++) {
            var option = document.createElement("option");
            var primary_SEQ = typeof array[i].SEQ_ID === "undefined" ? " " : array[i].SEQ_ID;
            var title = typeof array[i].TITLE === "undefined" ? '' : array[i].TITLE;
            var desc = typeof array[i].DESCRIPTION === "undefined" ? " " : " - " + array[i].DESCRIPTION;

            if(primary_SEQ && title && desc || primary_SEQ && title){
              option.value = primary_SEQ;
              option.text = `${title} ${desc}`;
              // console.log(data_referer.includes(primary_SEQ.toString()));
              if(data_referer.includes(primary_SEQ) || data_referer.includes(primary_SEQ.toString()))
                option.selected = true;
              selectList.appendChild(option);
            }
        }
        $('.access-choices').append(selectList);
        $(selectList).select2({'placeholder':`Pilih data disini`});

      },
      error : function(xhr){
        console.log("EXCEPTION OCCURED IN ACCESS ROLE", xhr);
        Swal.fire('Opps...', 'Something went wrong, please try again or refresh page', 'error');
      },
      complete : function(){
        $('.loading-selected-data').prop('hidden', true);
      }
    })
  });

  $(document).on('click', '.btn-delete', function(){
    try {
      var url = $(this).data('url-delete');
    }catch(error){ var url = "{{ url()->current() }}" }
    Swal.fire({
      // title: 'Active / Deactivate Access Role',
      title: 'Delete Access Role',
      html: "<span style='line-height:1.5'>Are you sure want to delete this Access Role ? <br>Access Role will be not available for all user assigned to it<span>",
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
</script>
@endsection

