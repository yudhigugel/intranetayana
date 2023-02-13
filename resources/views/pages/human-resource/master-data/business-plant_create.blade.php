@extends('layouts.default')

@section('title', 'Create Business Unit')

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
      <li class="breadcrumb-item"><a href="../">Business Unit</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Create</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="card"> 
       <div class="card-body">
         <h4 class="card-title">Create Business Unit</h4> 
         <br/>
         <form class="forms-sample" id="apriForm" data-loader-file="/image/gif/cube.gif" data-url-post="/human-resource/master-data/business-unit/act/create">
          {{ csrf_field() }}
            <div class="form-group">
               <label for="COMPANY_NAME">Code</label>
               <input type="text" class="form-control" id="BUSINESS_UNIT_CODE" name="BUSINESS_UNIT_CODE" placeholder="Enter Business Unit Code..." required>
            </div>
            <div class="form-group">
               <label for="COMPANY_NAME">Name</label>
               <input type="text" class="form-control" id="BUSINESS_UNIT_NAME" name="BUSINESS_UNIT_NAME" placeholder="Enter Business Unit Name..." required>
            </div>
            <div class="form-group">
              <label for="BUSINESS_UNIT_DESCRIPTION">Description</label>
              <textarea class="form-control" id="BUSINESS_UNIT_DESCRIPTION" name="BUSINESS_UNIT_DESCRIPTION" rows="4"  placeholder="Enter Business Unit Description..." required></textarea>
            </div>
            <div class="form-group">
               <label for="COMPANY_CODE">Company Code Relation</label>
               <div id="searchbar">
                 <input type="text" class="typeahead form-control" id="COMPANY_CODE" name="COMPANY_CODE" placeholder="Search Company Code" data-role="tagsinput" >
               </div>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <a href="../" class="btn btn-light">Cancel</a>
         </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
     $('#apriForm').on('submit', function(e) {
      e.preventDefault()    
      var form = $(this);

      jQuery.ajax({
        type: "POST",
        url: form.attr("data-url-post"),
        data: form.serialize(),
        beforeSend: function() {
          Swal.fire({
            title: "Loading...",
            text: "Please wait!",
            imageUrl: form.attr("data-loader-file"),
            imageSize: '150x150',
            showConfirmButton: false
          });
        },
        success: function(data) {
          console.log(data);
          if (data.success) {
            if (data.msg) {
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: data.msg,
                }).then((result) =>{
                  window.location.href="/human-resource/master-data/business-unit/";
                });
                
            }
          } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.msg,
              })
            
          }

        },
        error: function(err) {
          
            console.log(err);
            swal("Error", err && err.responseJSON && err.responseJSON.message, "error");
          
        }
      })
    });
  </script>
  <script type="text/javascript">
    // var path = "/human-resource/master-data/business-unit/autocomplete";
    // $('input.typeahead').typeahead({
    //     source:  function (query, process) {
    //     return $.get(path, { query: query }, function (data) {
    //             return process(data);
    //         });
    //     }
    // });
    $(function(){
      $('#COMPANY_CODE').tokenInput("{{ url('human-resource/master-data/business-unit/getCompany')}}", { 
          theme: "facebook",
          hintText: "Search Company Code (e.g. KMS0)",
          noResultsText: "No result found.",
          searchingText: "Searching...",
          preventDuplicates: true
      }); 

    });

  </script>
@endsection

