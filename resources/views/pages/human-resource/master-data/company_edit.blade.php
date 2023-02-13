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
      <li class="breadcrumb-item"><a href="../">Company</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Edit</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="card"> 
       <div class="card-body">
         <h4 class="card-title">Edit Company : {{ $data[0]->COMPANY_NAME }}</h4> 
         <br/>
         <form class="forms-sample" id="apriForm" data-loader-file="/image/gif/cube.gif" data-url-post="/human-resource/master-data/company/act/edit">
          {{ csrf_field() }}
          <input type="hidden" name="COMPANY_ID" value="{{ $data[0]->COMPANY_ID }}">
            <div class="form-group">
               <label for="COMPANY_NAME">Company Code</label>
               <input type="text" class="form-control" id="COMPANY_NAME" placeholder="Company Name" value="{{ $data[0]->COMPANY_CODE }}" disabled="">
            </div>
            <div class="form-group">
               <label for="COMPANY_NAME">Company Name</label>
               <input type="text" class="form-control" id="COMPANY_NAME" name="COMPANY_NAME" placeholder="Company Name" value="{{ $data[0]->COMPANY_NAME }}">
            </div>
            <div class="form-group">
                      <label for="COMPANY_DESCRIPTION">Textarea</label>
              <textarea class="form-control" id="COMPANY_DESCRIPTION" name="COMPANY_DESCRIPTION" rows="4">{{ $data[0]->COMPANY_DESCRIPTION }}</textarea>
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
                  location.reload();
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
@endsection

