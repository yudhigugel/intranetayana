@extends('layouts.default')

@section('title', 'Change Password')

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
        <li class="breadcrumb-item"><a href="/report">Account</a></li>
        <li class="breadcrumb-item active" aria-current="page"><span>Change Password</span></li>
    </ol>
  </nav>
  <div class="row">
   <div class="col-md-12">
     <div class="alert alert-fill-primary" role="alert">
        <i class="mdi mdi-alert-circle"></i>
        For security reason, your password will expired every 90 days. Your current password is valid until {{ date('d F Y',strtotime(app('user_login')->DATE_EXPIRED_PASSWORD)) }}
      </div>

   </div> 
   <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title">Change password form</h4>
            <form id="form" class="forms-sample" data-url-post="{{ url('user/act/change-password') }}" data-loader-file="{{ url('/image/gif/cube.gif') }}"    >
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputPassword1">Old Password</label>
                  <input type="password" class="form-control" id="old_password"  name="old_password" required>
               </div>
               <div class="form-group mt-10">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
               </div>
               <div class="form-group">
                  <label for="exampleInputConfirmPassword1">Confirm Password</label>
                  <input type="password" class="form-control" id="confirm_password"  name="confirm_password" required>
               </div>
               <div class="form-group">
                 <small style="color:red;">Password must at least contain 1 uppercase letter, 1 special character and minimal 8 characters long</small>
               </div>
               <div class="form-group" style="margin-top:10px;">
               <button type="submit" class="btn btn-primary mr-2" style="">Submit</button>
               <button class="btn btn-light">Cancel</button>
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


    $('#form').on('submit', function(e) {
      e.preventDefault()

      var form = $(this);
      var old_password=$("#old_password").val();
      var password=$("#password").val();
      var confirm_password=$("#confirm_password").val();

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