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
.absolute-profile{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
input[readonly],
textarea[readonly]{
  background: #fff !important;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">Human Resource</a></li> 
    <li class="breadcrumb-item"><a href="#">Employee</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Details</span></li></ol>

</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 col-12" style="position: relative;">
     @if(isset($data_found) && $data_found)
      <div class="wrapper">
        <div class="main-body">
          <div class="row">
            <div class="col-lg-4">
              <div class="card mb-2 shadow-sm">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <div style="height: 110px; width: 110px; position: relative;" class="rounded-circle p-1 bg-primary">
                      <img src="{{ $data->IMAGE_PHOTO ? asset('upload/profile_photo/'.$data->IMAGE_PHOTO) : asset('image/default-avatar.png') }}" onerror="this.onerror=null; this.src='/image/default-avatar.png';" alt="Employee Profile" class="rounded-circle p-1 absolute-profile" width="110">
                    </div>
                    <div class="mt-3">
                      <h4>
                        @if(isset($data->EMPLOYEE_STATUS_ASSIGNMENT) && $data->EMPLOYEE_STATUS_ASSIGNMENT == 1)
                        <i class="fa fa-check-circle text-success"></i>&nbsp;
                        @endif
                        {{ ucfirst($data->EMPLOYEE_NAME) }}</h4>
                      <p class="text-secondary mb-1">{{ $data->MIDJOB_TITLE_NAME }}</p>
                      <p class="text-muted font-size-sm">
                        {{ $data->REGION ? $data->REGION.", " : ''}}
                        {{ $data->CITY ? $data->CITY.", " : ''}}
                        {{ $data->COUNTRY ? $data->COUNTRY : 'ID'}}
                      </p>
                    </div>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                      <p class="mb-0"><i class="fa fa-building fa-2x text-danger" style="font-size: 1.5em"></i>&emsp; {{ ucfirst($data->SAP_PLANT_NAME) }}</p>
                      <span class="text-secondary"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                      <p class="mb-0"><i class="fa fa-phone fa-2x text-primary" style="font-size: 1.5em"></i>&emsp; {{ $data->MOBILE_NUMBER_1 }}</p>
                      <span class="text-secondary"></span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card mb-2 shadow-sm">
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Employee ID</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->EMPLOYEE_ID }}" readonly>
                    </div>
                  </div>
                  {{--<div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ strtolower($data->EMAIL) }}" readonly>
                    </div>
                  </div>--}}
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Cost Center ID</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->SAP_COST_CENTER_ID }}" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Cost Center Desc.</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->SAP_COST_CENTER_DESCRIPTION }}" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Department</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->DEPARTMENT_NAME }}" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Division</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->DIVISION_NAME }}" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Territory / Office</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="{{ $data->TERRITORY_NAME }}" readonly>
                    </div>
                  </div>
                  {{--<div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <!-- <input type="text" class="form-control" value=""> -->
                      <textarea class="form-control">{{ $data->STREET_1 }}</textarea readonly>
                    </div>
                  </div>--}}
                  {{-- <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                      <input type="button" class="btn btn-primary px-4" value="Save Changes">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     @endif
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

