@extends('layouts.auth')
@section('title','Login Page')
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
              <form id="formLogin" class="pt-3" data-next-page="{{ url('/') }}" data-url-post="/auth/login" data-loader-file="/image/gif/cube.gif">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="username" class="form-control form-control-lg border-left-0" id="email" placeholder="Username" required>
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
                    <input type="password" name="password" class="form-control form-control-lg border-left-0" id="exampleInputPassword" placeholder="Password" required>
                  </div>
                </div>
                <div class="my-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
                </div>
                {{--<div class="my-2 text-right">
                  <div class="form-check">
                    <label class="form-check-label text-black">
                      <input type="checkbox" class="form-check-input">
                      Remember Me
                    <i class="input-helper"></i></label>
                  </div>
                  <a href="#" class="auth-link text-primary">Forgot password?</a>
                </div>--}}
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-flex flex-row">
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
    $('#formLogin').on('submit', function(e) {
      e.preventDefault()

      var form = $(this);
      var email= $("#email").val();

      jQuery.ajax({
        type: "POST",
        url: form.attr("data-url-post"),
        data: form.serialize(),
        beforeSend: function() {
          Swal.fire({
            title: "Loading...",
            text: "Please wait!",
            imageUrl: form.attr("data-loader-file"),
            imageWidth: 140,
            imageHeight: 140,
            showConfirmButton: false
          });
        },
        success: function(data) {
          if (data.success) {
            if (data.msg) {
              if (data.data.expiry_status.warning_expired_treshold == true) {
                  Swal.fire({
                    title: 'Your password is almost expired!',
                    text: data.msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: "Skip",
                    confirmButtonText: 'Yes, Renew now'
                  }).then((result) => {
                    if (result.value) {
                      window.location.href = '/user/change-password';
                    } else {
                      window.location.href = form.attr("data-next-page");
                    }
                  })
              } else {
                window.location.href = form.attr("data-next-page");
              }
            }
          } else {
             if(Object.keys(data).includes('data') && Object.keys(data.data).includes('code')){
              if (data.data.code == 405) {
                Swal.fire({
                  title: 'Your password is expired',
                  text: data.msg,
                  icon: 'warning',
                  showConfirmButton: true,
                  confirmButtonText: 'Continue'
                }).then((result) => {
                  
                  window.location.href = '/change-password?email='+email;
                  
                })
              } else if (data.data.code == 406) {
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
            else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message ? data.message : 'Cannot read status sent by server',
              });
            }
          }

        },
        error: function(err) {
          try {
            if ($("[name=username]").val().length + $("[name=password]").val().length < 2) {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Invalid Username and/or Password',
              });
            } else {
              console.log(err);
               Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try refreshing page',
              });
            }
          } catch(error){
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try refreshing page',
              });
          }
        }
      })
    });
  </script>
@endsection