@extends('layouts.default')

@section('title', 'Guest Folio')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
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
      <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li> 
      <li class="breadcrumb-item"><a href="/folio">POS</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Guest Folio</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card"> 
       {{-- <div class="card-body main-wrapper pb-0 bg-white" id="header">
              <div class="px-0">
                  <div class="d-flex justify-content-between mb-3 border-bottom">
                      <h4 class="card-title mx-auto text-center">
                          <img src="{{ url('/image/ayana_logo.png')}}" style="height:100px;width:auto;margin:10px auto;display:table;">
                          <span>Guest Folio Information</span>
                      </h4>
                  </div>
              </div>
        </div> --}}
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          <div id="breakpoint">
             <table id="table-folio" class="table table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" width="10%">CONF NO</th>
                   <th class="all" width="5%">WINDOW</th>
                   <th class="all" width="15%">COMPANY CODE</th>
                   <th class="all" width="8%">RESORT</th>
                   <th class="none text-center" width="20%">GUEST NAME</th>
                   <!-- <th class="none" width="10%">ROOM CLASS</th> -->
                   <th class="none" width="20%">ARRIVAL</th>
                   <th class="all" width="20%">DEPARTURE</th>
                  </tr>
               </thead>
             </table>
          </div>
        </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(document).ready( function () {
    try {
        var table = $('#table-folio').DataTable(
        {
            "responsive": true,
            "dom": '<"dt-buttons"Bfli>rtp',
            "ajax": {
              "type" : "GET",
              "url" : "/folio/get_data",
              dataSrc: function(data){
                if(data.length == 0){
                  return [];
                }
                else {
                  return data.data;
                }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                var error = jqXHR.responseJSON.message || jqXHR.responseJSON.exception || "Cannot read data sent from server, please check and retry again";
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
            "columns": [
               { "data": "CONF_NO", className: 'text-center'},
               { "data": "WINDOW" },
               { "data": "COMPANY_CODE", className: 'text-center'},
               { "data": "RESORT", className: 'text-center'},
               { "data": "GUEST_NAME", className: 'text-left'},
               // { "data": "ROOM_CLASS" },
               { "data": "ARRIVAL"},
               { "data": "DEPARTURE"}
            ],
            "buttons": [
               // 'colvis',
               'copyHtml5',
               'csvHtml5',
               'excelHtml5',
               'print'
            ]
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
          if ( typeof( onoff ) == 'undefined' ) {
          onoff = true;
          }
          this.oApi._fnProcessingDisplay( oSettings, onoff );
        };
    // END TRY BLOCK
    } catch(error) {
      console.log(error.message);
      Swal.fire('Something went wrong', error.message, 'error')
    }
   });
</script>
@endsection

