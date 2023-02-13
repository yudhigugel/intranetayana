@extends('layouts.auth')
@section('title','Change Password')
@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <a href="/">
                  <img width="100" src="{{ asset('image/ayana_logo.png') }}" alt="logo" />
                </a>
              </div>
              <h4>Change Password</h4>
              <form id="formReset" class="pt-3" data-next-page="{{ url('/') }}" data-url-post="/auth/act/change-password" data-loader-file="/image/gif/cube.gif">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="email" class="form-control form-control-lg border-left-0" id="emaill" placeholder="Email" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" name="password" class="form-control form-control-lg border-left-0" id="password" placeholder="Password" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" name="new_password" class="form-control form-control-lg border-left-0" id="new_password" placeholder="New Password" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Confirm New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" name="confirm_new_password" class="form-control form-control-lg border-left-0" id="confirm_new_password" placeholder="Confirm Password" required>
                  </div>
                </div>
                 <div class="form-group">
                 <small style="color:red;">Password must at least contain 1 uppercase letter, 1 special character and minimal 8 characters long</small>
               </div>
                <div class="my-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">CHANGE PASSWORD</button>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg2 d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">&copy; {{Date('Y')}} Ayana.id</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
@endsection

@section('scripts')
<script>
     $('#formReset').on('submit', function(e) {
      e.preventDefault()

      var form = $(this);
      var old_password=$("#password").val();
      var new_password=$("#new_password").val();
      var confirm_password=$("#confirm_new_password").val();

      if(new_password!==confirm_password){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'New password does not match!',
        })
        return false;
      }

      var password_format=validate_password_format(new_password);
      
      if(!password_format){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Incorrect password format, please follow the instruction',
        })
        return false;
      }

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
                  window.location.href= form.attr("data-next-page");
                });
                
            }
          } else {
           if (data.code == 'old_password') {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.msg,
              })
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.msg,
              })
            }
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