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
    <li class="breadcrumb-item"><a href="#">Human Resource</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Employee</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>

    <div class="card">

      <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
        @if(isset($message) && $message)
        <div class="alert alert-fill-danger alert-dismissable p-3 mb-3" role="alert">
          <i class="mdi mdi-alert-circle"></i>
          Oh snap! {{ $message }}
          <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        <h2 class="card-title"><i class="mdi mdi-account-search"></i> Find Employee</h2>
          <form method="POST" action="{{ url('/human-resource/employee-list/employee/search/data') }}" id="form-search-employee">
            {{ csrf_field() }}
            <div class="form-group">
              <div class="row mb-2">
                <div class="col-4">
                  <label>Select Company</label>
                  <select class="form-control select2" id="company_select" disabled name="COMPANY_CODE">
                    @if($data_company_count)
                    @foreach($data_company as $company)
                      <option value="" selected disabled></option>
                      <option value="{{ $company->COMPANY_CODE }}">{{ $company->COMPANY_CODE }} - {{ $company->COMPANY_NAME }}</option>
                    @endforeach
                    @else
                    <option value="" selected disabled></option>
                    @endif
                  </select>
                </div>
                <div class="col-4">
                  <label>Select Business Plant</label>
                  <select class="form-control select2" id="plant_select" disabled name="SAP_PLANT_ID">
                    <option value="" selected disabled></option>
                  </select>
                  <div class="pt-1">
                    <small class="text-muted loading-plant-select" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Finding Business Plant ...</small>
                  </div>
                </div>
                <div class="col-4">
                  <label>Select Business Territory</label>
                  <select class="form-control select2" id="territory_select" disabled name="TERRITORY_ID">
                    <option value="" selected disabled></option>
                  </select>
                  <div class="pt-1">
                    <small class="text-muted loading-territory-select" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Finding Business Territory ...</small>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-12">
                  <label>Search Employee By</label>
                  <div class="row">
                    <div class="col-2">
                      <select class="form-control select2-search-category" required id="search_category_select" name="search_by">
                        <option value="EMPLOYEE_NAME">NAME</option>
                        <option value="MIDJOB_TITLE_NAME">MID JOB TITLE</option>
                        <option value="JOB_TITLE">JOB TITLE</option>
                        <option value="SAP_COST_CENTER_ID">COST CENTER ID</option>
                        <option value="DEPARTMENT_NAME">DEPARTMENT NAME</option>
                        <option value="DIVISION_NAME">DIVISION NAME</option>
                      </select>
                    </div>
                    <div class="col-10">
                      <input type="text" class="form-control pt-3" placeholder="Search Terms Here..." name="search_terms" id="search_terms">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group text-left d-flex">
              <div class="pr-2">
                <button type="submit" class="btn btn-primary btn-search" disabled><i class="mdi mdi-magnify"></i> Search</button>
              </div>
              <div class="pr-2">
                <button type="reset" class="btn btn-danger btn-reset" disabled><i class="mdi mdi-history"></i> Reset</button>
              </div>
              {{-- <div class="ml-auto">
                <button type="button" class="btn btn-primary btn-view-all">View All Data</button>
              </div> --}}
            </div>
          </form>
          <div class="loading-employee" hidden>
            <small id="found-data-text"><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Now finding available employee ...</small>
          </div>
          <div class="found-data" hidden></div>

          <div class="table-responsive" id="table-wrapper" hidden style="overflow: auto;">
            <h4 id="active-search" hidden>Active search filter : <span id="active-search-text"></span></h4>
            <table style="white-space: nowrap;" id="table-employee" class="table table-bordered table-striped" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" style="width: 5%">NO</th>
                   <th class="all" style="width: 8%">PHOTO</th>
                   <th class="all" style="width: 7%">ASSIGNED <br> PLANT</th>
                   <th class="all" style="width: 8%">STATUS</th>
                   <th class="all text-left" style="width: 10%">OFFICE</th>
                   <th class="all" style="width: 8%">OLD ID</th>
                   <th class="all" style="width: 8%">CURRENT ID</th>
                   <th class="all text-center" style="width: 10%">NAME</th>
                   <th class="none text-center" style="width: 5%">MID JOB TITLE</th>
                   <th class="all text-center" style="width: 10%">JOB TITLE</th>
                   <th class="none text-center" style="width: 10.5%">COST CENTER</th>
                   <th class="none text-center" style="width: 8%">SUPERIOR ID</th>
                   <th class="none text-center" style="width: 10.5%">SUPERIOR NAME</th>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script> -->

<script type="text/javascript">
  $(document).ready( function () {
    try {
      $('.btn-search').prop('disabled', false);
      $('.btn-reset').prop('disabled', false);
      $('.btn-view-all').prop('disabled', false);
      $('#company_select').prop('disabled', false);

      setTimeout(function(){
        $('#form-search-employee').submit();
      }, 500);
    } catch (error){
      console.log('FAILED TO ENABLE BUTTON', error.message);
    }

    function format ( d ) {
      // `d` is the original data object for the row
      return '<table class="table table-bordered" cellpadding="5" cellspacing="0" border="0">'+
          '<tr style="background: #fff">'+
              '<td>DEPARTMENT</td>'+
              '<td>'+d.DEPARTMENT_NAME+'</td>'+
          '</tr>'+
          '<tr style="background: #fff">'+
              '<td>DIVISION</td>'+
              '<td>'+d.DIVISION_NAME+'</td>'+
          '</tr>'+
          '<tr style="background: #fff">'+
              '<td>DATE JOIN</td>'+
              '<td>'+d.DATE_JOIN_ASSIGNMENT+'</td>'+
          '</tr>'+
      '</table>';
    }

    $(document).on('click', '#table-employee td.details-control', function (e) {
        try{
          var tr = $(this).closest('tr');
          var row = $('#table-employee').DataTable().row( tr );
   
          if ( row.child.isShown() ) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          }
          else {
              // Open this row
              row.child( format(row.data()) ).show();
              tr.addClass('shown');
          }
        } catch(error) {
          console.log('Error while trying to expand data', error.message)
        }
    } );


    // Initialize select2 for all Class
    $('.select2').select2({
      placeholder: "Select an option",
      allowClear: true
    });

    $('.select2-search-category').select2({
      placeholder: "Search by",
      // allowClear: true
    });

    $('.select2, .select2-search-category').on('select2:select', function(){
      $('.found-data').html('')
      $('.found-data').prop('hidden', true);
      try{
        $('#table-employee').DataTable().destroy();
        $('#table-wrapper').prop('hidden', true)
      } catch(error){
        console.log('DATATABLE EMPLOYEE ERROR', error.message)
      }
    })

    // This function used when company value is cleared, not changed 
    $('#company_select').on('change', function(e){
      try {
        if(!$(this).val()){
          $('.loading-plant-select').prop('hidden', true);
          $('#plant_select').select2('destroy').html('<option value="" selected disabled></option>')
          $('#plant_select').prop('disabled', true);
          $('#plant_select').select2({
            placeholder: "Select an option",
            allowClear: true
          });

          $('#territory_select').select2('destroy').html('<option value="" selected disabled></option>')
          $('#territory_select').prop('disabled', true);
          $('#territory_select').select2({
            placeholder: "Select an option",
            allowClear: true
          });
          $('.found-data').html('')
          $('.found-data').prop('hidden', true);
          try{
            $('#table-employee').DataTable().destroy();
            $('#table-wrapper').prop('hidden', true)
          } catch(error){
            console.log('DATATABLE EMPLOYEE ERROR', error.message)
          }
          // END IF
        }
      } catch(error) {
        console.log('Something went wrong while clearing select', error.message);
      }
    })

    // Select Company
    $('#company_select').on('select2:select', function (e) {
      // Reinitialize all select if parent changed
      $('.loading-plant-select').prop('hidden', false);
      $('#plant_select').select2('destroy').html('<option value="" selected disabled></option>')
      $('#plant_select').prop('disabled', true);
      $('#plant_select').select2({
        placeholder: "Select an option",
        allowClear: true
      });
      $('.btn-search').prop('disabled', true);
      $('.btn-reset').prop('disabled', true);

      $('#territory_select').select2('destroy').html('<option value="" selected disabled></option>')
      $('#territory_select').prop('disabled', true);
      $('#territory_select').select2({
        placeholder: "Select an option",
        allowClear: true
      });

      var data = e.params.data.id || null;
      $.ajax({
        url : '/human-resource/employee-list/employee/filter/getPlant',
        type : 'GET',
        data : {'company_code': data},
        success : function(response){
          var newOption = [];
          if(response.hasOwnProperty('length') && response.length){
            $.each(response, function(index, data){
              newOption[index] = new Option(`${data.BUSINESS_UNIT_CODE} - ${data.BUSINESS_UNIT_NAME}`, data.BUSINESS_UNIT_CODE, false, false);
            });
          }

          setTimeout(function(){
            $('.loading-plant-select').prop('hidden', true);
            $('#plant_select').append(newOption).trigger('change');
            $('#plant_select').prop('disabled', false);
          },400)

        },
        error : function(xhr){
          $('.loading-plant-select').prop('hidden', true);
          Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
          console.log("EXCEPTION OCCURED IN COMPANY SELECT")
        },
        complete : function(){
          $('.btn-search').prop('disabled', false);
          $('.btn-reset').prop('disabled', false);
        }
      });
    });


    $('#plant_select').on('select2:select', function (e) {
      $('.loading-territory-select').prop('hidden', false);
      $('#territory_select').select2('destroy').html('<option value="" selected disabled></option>');
      $('#territory_select').prop('disabled', true);
      $('#territory_select').select2({
        placeholder: "Select an option",
        allowClear: true
      });
      $('.btn-search').prop('disabled', true);
      $('.btn-reset').prop('disabled', true);

      var data = e.params.data.id || null;
      $.ajax({
        url : '/human-resource/employee-list/employee/filter/getTerritory',
        type : 'GET',
        data : {'plant_code': data},
        success : function(response){
          var newOption = [];
          if(response.hasOwnProperty('length') && response.length){
            $.each(response, function(index, data){
              newOption[index] = new Option(`${data.TERRITORY_CODE} - ${data.TERRITORY_NAME}`, data.TERRITORY_ID, false, false);
            });
          }

          setTimeout(function(){
            $('.loading-territory-select').prop('hidden', true);
            $('#territory_select').append(newOption).trigger('change');
            $('#territory_select').prop('disabled', false);
          },400)

        },
        error : function(xhr){
          $('.loading-territory-select').prop('hidden', true);
          Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
          console.log("EXCEPTION OCCURED IN PLANT SELECT");
        },
        complete : function(){
          $('.btn-search').prop('disabled', false);
          $('.btn-reset').prop('disabled', false);
        }
      });

    });

    $('#form-search-employee').submit(function(e){
      e.preventDefault();
      $('.btn-search').prop('disabled', true);
      $('.btn-reset').prop('disabled', true);
      $('.btn-view-all').prop('disabled', true);
      $('.loading-employee').prop('hidden', false);
      try{
        $('#table-employee').DataTable().destroy();
        $('#table-wrapper').prop('hidden', true)
      } catch(error){
        console.log('DATATABLE EMPLOYEE ERROR', error.message)
      }


      var form = $(this)[0];
      var formData = new FormData(form);
      $.ajax({
        url : '/human-resource/employee-list/employee/search/data',
        data: formData,
        type : 'POST',
        processData: false,
        contentType: false,
        success : function(response){
          setTimeout(function(){
            $('#table-wrapper').prop('hidden', false);
            try{
              var table = $('#table-employee').DataTable({
                "responsive": true,
                "paging": true,
                "pageLength": 50,
                "bAutoWidth": false,
                "scrollX":true,
                // "fixedHeader": {
                //     header: true,
                //     footer: true,
                //     headerOffset: 53
                // },
                "buttons": [],
                "dom": '<"dt-buttons"Bfli>rtp',
                "data": response.data,
                "columns": [
                   { "data": "NUM_ORDER", className: 'details-control'},
                   { "data": "PHOTO"},
                   { "data": "SAP_PLANT_ID"},
                   { "data": "EMPLOYEE_STATUS"},
                   { "data": "TERRITORY_NAME", className: 'text-left'},
                   { "data": "OLD_EMPLOYEE_ID" },
                   { "data": "EMPLOYEE_ID" },
                   { "data": "EMPLOYEE_NAME", className: "text-left"},
                   { "data": "MIDJOB_TITLE_NAME", className: "text-left"},
                   { "data": "JOB_TITLE", className: 'text-left'},
                   { "data": "SAP_COST_CENTER_DESCRIPTION", className: "text-left"},
                   { "data": "SUPERIOR_ID", className: "text-left"},
                   { "data": "SUPERIOR_NAME", className: "text-left"}
                   // { "data": "ACTION"}
                ]
                // "columnDefs" : [{
                //   "targets" : 1 ,
                //   render : function ( url, type, full) {
                //     return '<img class="lozad" style="width: 25px; height: 25px" data-src="'+url+'"/>';
                //   }
                // }]
              });

              $('#table-wrapper').on( 'page.dt', function () {
                  setTimeout(function(){
                    Array.from(document.getElementsByTagName('img')).forEach(img => {
                      const src = img.getAttribute('data-src');
                      // console.log(src);
                      if (src) {
                        // Listen for both events:
                        img.addEventListener('load', imageLoaded);
                        img.addEventListener('error', imageError);
                        
                        // Just to simulate a slow network:
                        setTimeout(() => {
                          img.setAttribute('src', src);
                        }, 2000 + Math.random() * 2000);
                      }
                    });
                  },300);
              });
              
              setTimeout(function(){
                Array.from(document.getElementsByTagName('img')).forEach(img => {
                  const src = img.getAttribute('data-src');
                  // console.log(src);
                  if (src) {
                    // Listen for both events:
                    img.addEventListener('load', imageLoaded);
                    img.addEventListener('error', imageError);
                    
                    // Just to simulate a slow network:
                    setTimeout(() => {
                      img.setAttribute('src', src);
                    }, 1000 + Math.random() * 2000);
                  }
                });
              }, 300);
            } catch(error){
              Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
              console.log(error.message)
            }

            $('.loading-employee').prop('hidden', true);
            $('.btn-search').prop('disabled', false);
            $('.btn-reset').prop('disabled', false);
            $('.btn-view-all').prop('disabled', false);
          }, 500);
        },
        error : function(xhr){
          console.log("EXCEPTION OCCURED IN FORM SEARCH EMPLOYEE");
          setTimeout(function(){
            try{
              $('#table-employee').DataTable().destroy();
              $('#table-wrapper').prop('hidden', true)
            } catch(error){
              console.log('DATATABLE EMPLOYEE ERROR', error.message)
            }
            
            $('.loading-employee').prop('hidden', true);
            $('.btn-search').prop('disabled', false);
            $('.btn-reset').prop('disabled', false);
            $('.btn-view-all').prop('disabled', false);

            Swal.fire('Opps...', 'Something went wrong, please try again', 'error');
          }, 500);
        }
      });
      return false;
    })

    $(".btn-reset").click(function(){
      try{
        $("select.select2").val('');
        $("select.select2-search-category").val('EMPLOYEE_NAME').trigger('change');
        $('#plant_select').select2('destroy').html('<option value="" selected disabled></option>')
        $('#plant_select').prop('disabled', true);
        $('#territory_select').select2('destroy').html('<option value="" selected disabled></option>');
        $('#territory_select').prop('disabled', true);
        $("select.select2").select2({ 
          placeholder: "Select an option",
          allowClear: true });
        try{
          $('#table-employee').DataTable().destroy();
          $('#table-wrapper').prop('hidden', true)
        } catch(error){
          console.log('DATATABLE EMPLOYEE ERROR', error.message)
        }
      } catch(error){
        console.log(error.message);
      }
    });

    function imageLoaded(e) {
      updateImage(e.target, 'loaded');
    }

    function imageError(e) {
      updateImage(e.target, 'error');
    }

    function updateImage(img, classname) {
      // Add the right class:
      img.classList.add(classname);
      
      // Remove the data-src attribute:
      img.removeAttribute('data-src');
      
      // Remove both listeners:
      img.removeEventListener('load', imageLoaded);
      img.removeEventListener('error', imageError);
    }
    // END READY FUNCTION
  });

  // window.addEventListener('load', () => {
  //   console.log('loaded');
  //   Array.from(document.getElementsByTagName('img')).forEach(img => {
  //     const src = img.getAttribute('data-src');
  //     console.log(src);
  //     if (src) {
  //       // Listen for both events:
  //       img.addEventListener('load', imageLoaded);
  //       img.addEventListener('error', imageError);
        
  //       // Just to simulate a slow network:
  //       setTimeout(() => {
  //         img.setAttribute('src', src);
  //       }, 2000 + Math.random() * 2000);
  //     }
  //   });
  // });
</script>
@endsection

