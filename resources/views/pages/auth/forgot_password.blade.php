@extends('layouts.default')

@section('title', 'Forgot Password Account')

@section('extra_inline_styles')
  .overlay {  
    display: none;
    justify-content: center;
    align-items: flex-start;
    position: absolute;
    z-index: 2;
    opacity: 0;
    background: rgba(255, 255, 255, 0.86);
    transition: opacity 200ms ease-in-out;
    margin: -15px 0 0 0;
    top: 15px;
    left: 0;
    width:100%;
    height: 100%;
  }
  .overlay.in {
    opacity: 1;
    display: flex;
  }
  .fl-scrolls {
    bottom:0;
    height:35px;
    overflow:auto;
    position:fixed;
  }
  .fl-scrolls div {
    height:1px;
    overflow:hidden;
  }
  .fl-scrolls div:before {
    content:""; /* fixes #6 */
  }
  .fl-scrolls-hidden {
    bottom:9999px;
  }
  .sticky {
    position: fixed;
    top: 40px;
    z-index: 99;
    box-shadow: 0px 3px 7px -5px #878787;
  }
  .sticky + .main-wrapper {
    padding-top: 255px !important;
  }
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Access Management</a></li>
        <li class="breadcrumb-item active" aria-current="page"><span>Forgot Password Account</span></li>
    </ol>
  </nav>
  <div class="row">
   <div class="col-md-6">
     <div class="alert alert-fill-primary" role="alert">
        <i class="mdi mdi-alert-circle"></i>
        For security reason, password will expired every 90 days since it's first created or modified
      </div>
   </div>
  </div>

  <div class="row">
   <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title">Forgot password user</h4>
            <form id="formReset" class="pt-3" data-next-page="{{ url('/') }}" data-url-post="{{ url('/auth/act/forgot-password') }}" data-loader-file="/image/gif/cube.gif">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name="email" class="form-control form-control-lg border-left-0" id="email" placeholder="Email" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" name="new_password" class="form-control form-control-lg border-left-0" id="new_password" placeholder="New Password" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Confirm New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text border-right-0">
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
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Submit New Password</button>
                </div>
              </form>
         </div>
      </div>
   </div>
  </div>
@endsection

@section('scripts')
<script>
  function validate_password_format(password){
      var special = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
      if(!special.test(password)){
        return false;
      }

      /* VALIDASI NUMBER*/
      var contain_number=/\d/.test(password);
      if(!contain_number){
        return false; 
      }

      /* VALIDASI uppercase */
      var capital= /[A-Z]/;
      if (!capital.test(password)){
        return false;
      }

      if(password.length<8){
        return false
      }

      return true;
  }


    $('#formReset').on('submit', function(e) {
      e.preventDefault()

      var form = $(this);
      var password=$("#new_password").val();
      var confirm_password=$("#confirm_new_password").val();

      if(password!==confirm_password){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'New password does not match!',
        })
        return false;
      }


      var password_format=validate_password_format(password);
      
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
            swal("Error", "Something went wrong when trying to contact server", "error");
        }
      })
    });
</script>
@endsection