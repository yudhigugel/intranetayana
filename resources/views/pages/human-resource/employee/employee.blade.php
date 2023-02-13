@extends('layouts.default')

@section('title', 'Employee List')

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
    <li class="breadcrumb-item"><a href="#">Home</a></li> 
    <li class="breadcrumb-item"><a href="/human-resource/employee-list/employee">Human Resource</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Employee</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card">
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          <div id="breakpoint">
             <table id="table-employee" class="table table-bordered table-striped" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" width="5%">NO</th>
                   <th class="all" width="5%">PLANT</th>
                   <th class="all" width="10%">OFFICE</th>
                   <th class="all" width="12%">EMPLOYEE ID</th>
                   <th class="all text-center">EMPLOYEE NAME</th>
                   <th class="none text-center" width="15%">MID JOB TITLE</th>
                   <th class="all text-center" width="15%">JOB TITLE</th>
                   <th class="none text-center">COST CENTER</th>
                   <!-- <th class="none">ACTION</th> -->
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
        var table = $('#table-employee').DataTable(
        {
            "responsive": true,
            "dom": '<"dt-buttons"Bfli>rtp',
            "ajax": {
              "type" : "GET",
              "url" : "/human-resource/employee-list/employee/filter/getData",
              "data" : {!! json_encode(app('request')->input(), JSON_FORCE_OBJECT) !!},
              dataSrc: function(data){
                // console.log(data)
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
               { "data": "NUM_ORDER"},
               { "data": "SAP_PLANT_ID"},
               { "data": "TERRITORY"},
               { "data": "EMPLOYEE_ID" },
               { "data": "EMPLOYEE_NAME", className: "text-left"},
               { "data": "MIDJOB_TITLE_NAME", className: "text-left"},
               { "data": "JOB_TITLE_NAME", className: 'text-left'},
               { "data": "COST_CENTER", className: "text-left"},
               // { "data": "ACTION"}
            ],
            "buttons": [
               // 'colvis',
               'copyHtml5',
               'csvHtml5',
               'excelHtml5',
               'print'
            ],
            initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            });
           }
        });

        // $('#table-employee thead th').each( function () {
        //   var title = $(this).text();
        //   $(this).html(`<p style="margin:5px">${title}</p><input class="form-control p-2" type="text" placeholder=".."/>` );
        // });

    // END TRY BLOCK
    } catch(error) {
      console.log(error.message);
      Swal.fire('Something went wrong', error.message, 'error')
    }
   });
</script>
@endsection

