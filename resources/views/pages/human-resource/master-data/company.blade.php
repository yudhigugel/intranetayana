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
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Human Resource</a></li>
      <li class="breadcrumb-item"><a href="#">Master Data</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Company</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card">
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          <div id="breakpoint">
             <table id="dynamic-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" width="10%">CODE</th>
                   <th class="all" width="25%">NAME</th>
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
              "url" : "/human-resource/master-data/company/getData",
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
            "columns": [
               { "data": "ACTION", className: 'text-left'},
               { "data": "NAME", className: 'text-left'}
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

      //   $('#dynamic-table').on('click', '.btn-delete[data-remote]', function (e) {
      //     e.preventDefault();
      //     $.ajaxSetup({
      //         headers: {
      //             'X-CSRF-TOKEN': $('input[name ="_token"]').val()
      //         }
      //     });
      //     var url = $(this).data('remote');

      //     $.ajax({
      //         url: url,
      //         type: 'GET',
      //         dataType: 'json',
      //         data: {method: '_DELETE', submit: true}
      //     }).always(function (data) {
      //        console.log(data);
      //         if (data.success) {
      //           if (data.msg) {
      //               Swal.fire({
      //                 icon: 'success',
      //                 title: 'Success!',
      //                 text: data.msg,
      //               }).then((result) =>{
      //                 location.reload();
      //               });

      //           }
      //         } else {
      //             Swal.fire({
      //               icon: 'error',
      //               title: 'Oops...',
      //               text: data.msg,
      //             })

      //         }
      //         // $('#datatable-member').DataTable().draw(false);
      //     });
      // });
   });
</script>
@endsection

