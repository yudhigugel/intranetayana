@extends('layouts.default')

@section('title', 'Generate Contract')

@section('custom_source_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/css/vendor/cropme.min.css">

@endsection

@section('extra_inline_styles')

@endsection


@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><span>Generate Contract</span></li>
    </ol>
  </nav>
  <div class="row">
   <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <form id="form" class="forms-sample" enctype="multipart/form-data" method="POST" data-url-post="{{ url('generate_contract/process') }}" data-loader-file="{{ url('/image/gif/cube.gif') }}" >
              {{ csrf_field() }}
              <div class="col-md-6 float-left">
                <div class="card">
                  <div class="card-header">
                    Document Information
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="first">Year</label>
                          <input required type="text" class="form-control" placeholder="e.g. 2021" name="contract_year">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="first">Contract Title</label>
                          <input required type="text" class="form-control" placeholder="" name="contract_title" >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="last">Contract Number</label>
                          <input required type="text" class="form-control" placeholder="" name="contract_number">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="last">Booking Code</label>
                          <input required type="text" class="form-control" placeholder="" name="booking_code">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="company">Select Resort</label>
                          <select name="resort" id="cmbResort" class="form-control js-example-basic-multiple"  multiple="multiple">
                            @foreach($data['list_resort'] as $lr)
                            <option value="{{$lr->RESORT}}">{{$lr->RESORT}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="company">Select Rate</label>
                          <select name="package" class="form-control js-example-basic-multiple2" id="package" multiple="multiple" disabled>
                          </select>
                          <small style="color:red;" class="alert-resort">Please select resort first</small>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="company">Contract Start Date</label>
                          <input  type="date" class="form-control" name="contract_date_start" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="phone">Contract End Date</label>
                          <input  type="date" class="form-control" id="date_end" name="contract_date_end" required>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 float-left">
                <div class="card">
                  <div class="card-header">
                    Agent Information
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="first">Agent Name</label>
                          <input required type="text" class="form-control" placeholder="e.g. Bali Travel Agent" name="agent_name" >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="first">Agent Contact Name</label>
                          <input required type="text" class="form-control" placeholder="e.g. John Doe" name="agent_contact_name" >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="first">Agent Contact Title</label>
                          <input required type="text" class="form-control" placeholder="e.g. Sales Supervisor" name="agent_contact_title" >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="first">Agent Contact Email</label>
                          <input required type="text" class="form-control" placeholder="e.g. name@company.com" name="agent_contact_email" >
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 float-left">
              <div class="form-group" style="margin-top:10px;">
               <button type="submit" class="btn btn-primary btn-block" style="">Submit</button>
              </div>
            </div>

            </form>
         </div>
      </div>
   </div>
  </div>
@endsection

@section('scripts')
<script src="/template/vendors/select2/select2.min.js"></script>

<script type="text/javascript">
  (function($) {
  'use strict';
  $(".js-example-basic-multiple").select2();
  $(".js-example-basic-multiple2").select2();
  })(jQuery);

  function ajax_resort(){

  }
  $('#cmbResort').on('select2:select select2:unselect', function (e) {
      var resort = $('#cmbResort').select2('data').map(function(elem){ 
        return elem.text 
      });
      
      if(resort.length>0){
        jQuery.ajax({
          type: "GET",
          url: '/generate_contract/ajaxResort',
          data: {resort : resort},
          beforeSend: function() {
            $("#package").attr('disabled','');
            
          },
          success: function(data) {
            $("#package").removeAttr('disabled');
            $(".alert-resort").hide();
            $("#package").html(data);

          },
          error: function(err) {
              console.log(err);
          }
        });  
      }else{
        $("#package").attr('disabled','');
        $(".alert-resort").show();
        $("#package").html('');

      }

  }); 


</script>
<script>
    $('#form').on('submit', function(e) {
      e.preventDefault();
      var url_post=$("#form").attr('data-url-post');
      var loader=$("#form").attr('data-loader-file');
      var form = new FormData(this);

      //append data resort
      var resort = $('#cmbResort').select2('data').map(function(elem){ 
        return elem.text 
      });
      var package = $('#package').select2('data').map(function(elem){ 
        return elem.id
      });
      //======================

      form.append('resort',resort);
      form.append('package',package);

      jQuery.ajax({
        type: "POST",
        url: url_post,
        data: form,
        cache:false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          Swal.fire({
            title: "Loading...",
            text: "Please wait!",
            imageUrl: loader,
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
            });

          }

        },
        error: function(err) {

            console.log(err);
            Swal.fire({
              icon: "Error",
              title: "Oops...",
              text : err && err.responseJSON && err.responseJSON.message
            });

        }
      })
    });
</script>
@endsection
