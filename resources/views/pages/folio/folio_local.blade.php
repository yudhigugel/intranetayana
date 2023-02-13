@extends('pages.report.report_sandbox')
@section('title', 'Guest Folio')

@section('extra_inline_styles')
.dt-buttons .dataTables_length{
  float:left;
}
.dt-buttons .dataTables_filter{
  float:right;
}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endsection

@section('main_menu_breadcrumb', 'POS')
@section('sub_menu_breadcrumb', 'Folio Detail')
@section('menu_breadcrumb', 'Guest Folio Information')

@section('content')
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
        <div class="card-body px-0 pb-0 border-bottom">
          <div class="px-4">
            <div class="d-flex justify-content-between mb-2">
              <h4 class="card-title ml-2">Guest Folio Information</h4>
            </div>
          </div>
        </div>
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          <div id="breakpoint">
             <table id="table-folio" class="table table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" width="10%">CONF NO</th>
                   <th class="all" width="15%">COMPANY CODE</th>
                   <th class="all" width="10%">RESORT</th>
                   <th class="none text-center" width="20%">GUEST NAME</th>
                   <th class="none" width="15%">ROOM CLASS</th>
                   <th class="none">ARRIVAL</th>
                   <th class="all">DEPARTURE</th>
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
      $('#table-folio').DataTable(
         {
            "responsive": true,
            "dom": '<"dt-buttons"Bfli>rtp',
            "ajax": {
              "type" : "GET",
              "url" : "/folio/get_local_data",
              dataSrc: function(data){
                if(data.length == 0){
                  return [];
                }
                else {
                  return data.data;
                }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown)
              }
            },
            "paging": true,
            "autoWidth": true,
            'info':false,
            "fixedHeader": true,
            "columns": [
               { "data": "CONF_NO", className: 'text-center' },
               { "data": "COMPANY_CODE", className: 'text-center' },
               { "data": "RESORT", className: 'text-center' },
               { "data": "GUEST_NAME", className: 'text-left' },
               { "data": "ROOM_CLASS" },
               { "data": "ARRIVAL" },
               { "data": "DEPARTURE" }
            ],
            "buttons": [
               // 'colvis',
               'copyHtml5',
               'csvHtml5',
               'excelHtml5',
               'print'
            ]
         }
      );
   });
</script>
@endsection

