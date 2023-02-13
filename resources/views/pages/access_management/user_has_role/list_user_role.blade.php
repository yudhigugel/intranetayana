@extends('layouts.default')

@section('title', 'Company Code')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
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
<div class="modal fade" id="modal-role" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">Access Management</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>User Has Role</span></li></ol>
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

          <div id="breakpoint">
             <table id="dynamic-table" class="table table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" style="width: 10%">NO</th>
                   <th class="all" style="width: 50%">EMPLOYEE NAME</th>
                   <th class="all" style="width: 40%">ROLE ASSIGNED</th>
                   {{--<th class="all" style="width: 10%">ACTION</th>--}}
                  </tr>
               </thead>
             </table>
          </div>
        </div>
    </div>
  </div>
</div>
{{ @csrf_field() }}
@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(document).ready( function () {
    var table = $('#dynamic-table').DataTable(
    {
      "responsive": true,
      "dom": '<"dt-buttons"Bfli>rtp',
      "ajax": {
        "type" : "GET",
        "url" : "/access/management/user-has-role/getData",
        dataSrc: function(data){
          if(data.length == 0){
            return [];
          }
          else {
            return data.data;
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
          Swal.fire({
            title: "Oops..",
            text: error,
            icon: "error",
            showConfirmButton: true
          });
        }
      },
      "language": {
         "processing": ""
      },
      "paging": true,
      "autoWidth": true,
      'info':true,
      "fixedHeader": true,
      "processing": true,
      "serverSide": true,
      "order": [[0, 'asc']],
      "columns": [
         { "data": "NUM_ORDER"},
         { "data": "EMPLOYEE_NAME", className: 'text-left'},
         { "data": "ROLE", className: 'text-left'}
      ],
      "buttons": [
         // 'colvis',
         'copyHtml5',
         'csvHtml5',
         'excelHtml5',
         'print'
      ]
    });
  });

  // $(document).on('click', '.btn-delete', function(){
  //   try {
  //     var url = $(this).data('url-delete');
  //   }catch(error){ var url = "{{ url()->current() }}" }
  //   Swal.fire({
  //     title: 'Delete Permission',
  //     html: "<span style='line-height:1.5'>Are you sure want to delete the permission ? <br>User having this permission will not be able to access related menu<span>",
  //     icon: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'Yes, Sure'
  //   }).then((result) => {
  //     if (result.isConfirmed) {
  //       location.href = url;
  //     }
  //   })
  // });
</script>
@endsection

