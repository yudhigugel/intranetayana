@extends('layouts.default')

@section('title', 'Change Profile')

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
        <li class="breadcrumb-item"><a href="/report">Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page"><span>Change Profile</span></li>
    </ol>
  </nav>
  <div class="row">
    @if($data->PROFILE_UPDATED==0)
   <div class="col-md-12">
     <div class="alert alert-fill-primary" role="alert">
        <i class="mdi mdi-alert-circle"></i>
        Update your profile details below.
      </div>
   </div>
   @endif
   <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title">Change profile form</h4>
            <form id="form" class="forms-sample" enctype="multipart/form-data" method="POST" data-url-post="{{ url('user/act/change-profile') }}" data-loader-file="{{ url('/image/gif/cube.gif') }}" >
              {{ csrf_field() }}
              <input type="hidden" name="EMPLOYEE_ID" value="{{$data->EMPLOYEE_ID}}">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Employee ID</label>
                    <input type="text" class="form-control" id="EMPLOYEE_ID" disabled value="{{ $data->EMPLOYEE_ID}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="EMAIL">Email</label>
                    <input type="text" class="form-control" id="EMAIL"  name="EMAIL" value="{{ $data->EMAIL }}" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                    <label for="EMPLOYEE_NAME">Full Name</label>
                    <input type="text" class="form-control" id="EMPLOYEE_NAME"  name="EMPLOYEE_NAME" value="{{ $data->EMPLOYEE_NAME }}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="BIRTH_DATE">Birth Date</label>
                    <input type="text" class="form-control" id="BIRTH_DATE" name="BIRTH_DATE"  value="{{ date('m/d/Y',strtotime($data->BIRTH_DATE)) }}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                    <label for="CITY">City</label>
                    <input type="text" class="form-control" id="CITY" name="CITY"  value="{{ $data->CITY }}" >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ZIPCODE">Zip Code</label>
                    <input type="text" class="form-control" id="ZIPCODE" name="ZIPCODE"  value="{{ $data->ZIPCODE}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                    <label for="STREET_1">Street Address 1</label>
                    <input type="text" class="form-control" id="STREET_1" name="STREET_1"  value="{{ $data->STREET_1 }}" >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                    <label for="STREET_2">Street Address 2</label>
                    <input type="text" class="form-control" id="STREET_2" name="STREET_2"  value="{{ $data->STREET_2 }}" >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                    <label for="STREET_3">Street Address 3</label>
                    <input type="text" class="form-control" id="STREET_3" name="STREET_3" value="{{ $data->STREET_3 }}" >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                    <label for="MOBILE_NUMBER_1">Mobile Phone 1</label>
                    <input type="text" class="form-control" id="MOBILE_NUMBER_1" name="MOBILE_NUMBER_1"  value="{{ $data->MOBILE_NUMBER_1 }}" >
                  </div>
                </div>
                 <div class="col-md-6">
                   <div class="form-group">
                    <label for="MOBILE_NUMBER_2">Mobile Phone 2</label>
                    <input type="text" class="form-control" id="MOBILE_NUMBER_2" name="MOBILE_NUMBER_2" value="{{ $data->MOBILE_NUMBER_2 }}" >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                    <label for="PHOTO">Photo</label>
                    <input type="file" name="image" class="form-control" id="image">
                  </div>
                </div>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="/js/vendor/cropme.min.js"></script>
<script type = "text/javascript" >
    //Cropme in Modal
    var CiM = {
        myCropme: null,

        opt: {
            //our extra properties. must be set!
            my_win_ratio: 0,
            my_final_size: {
                w: 0,
                h: 0
            },

            container: {
                width: 0,
                height: 0
            }, //to be set
            viewport: {
                width: 0,
                height: 0, //to be set
                type: 'square',
                border: {
                    width: 2,
                    enable: true,
                    color: '#fff'
                }
            },
            zoom: {
                enable: true,
                mouseWheel: true,
                slider: true
            },
            rotation: {
                slider: true,
                enable: true
            },
            transformOrigin: 'viewport',
        },

        crop_into_img: function(img, callback) {
            CiM.myCropme.crop({
                width: CiM.opt.my_final_size.w,
            }).then(function(res) {
                img[0].src = res;
                CiM.myCropme.destroy();
                CiM.myCropme = null;
                if (callback) callback();
            })
        },

        imgHolder: null,
        imgHolderCallback: null,
        read_file_from_input: function(input, callback) {
            if (input.files && input.files[0]) {
                imgHolderCallback = callback;
                var reader = new FileReader();
                if (!CiM.imgHolder) {
                    CiM.imgHolder = new Image();
                    CiM.imgHolder.onload = function() {
                        if (imgHolderCallback) {
                            imgHolderCallback();
                        }
                    }
                }
                reader.onload = function(e) {
                    console.log('image data loaded!');
                    CiM.imgHolder.src = e.target.result; //listen to img:load...
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                console.warn('failed to read file');
            }
        },

        getImagePlaceholder: function(width, height, text) {
            //based on https://cloudfour.com/thinks/simple-svg-placeholder/
            var svg = '\
        <svg xmlns="http://www.w3.org/2000/svg" width="{w}" \
        height="{h}" viewBox="0 0 {w} {h}">\
        <rect fill="#ddd" width="{w}" height="{h}"/>\
        <text fill="rgba(0,0,0,0.5)" font-family="sans-serif"\
        font-size="30" dy="10.5" font-weight="bold"\
        x="50%" y="50%" text-anchor="middle">{t}</text>\
        </svg>';
            var cleaned = svg
                .replace(/{w}/g, width)
                .replace(/{h}/g, height)
                .replace('{t}', text)
                .replace(/[\t\n\r]/gim, '') // Strip newlines and tabs
                .replace(/\s\s+/g, ' ') // Condense multiple spaces
                .replace(/'/gim, '\\i'); // Normalize quotes

            var encoded = encodeURIComponent(cleaned)
                .replace(/\(/g, '%28') // Encode brackets
                .replace(/\)/g, '%29');

            return 'data:image/svg+xml;charset=UTF-8,' + encoded;
        },

        get_image_placeholder: function(text) {
            return CiM.getImagePlaceholder(
                CiM.opt.my_final_size.w, CiM.opt.my_final_size.h, text);
        },

        uploadImage: function(img, callback) {
            var imgCanvas = document.createElement("canvas"),
                imgContext = imgCanvas.getContext("2d");

            // Make sure canvas is as big as the picture (needed??)
            imgCanvas.width = img.width;
            imgCanvas.height = img.height;

            // Draw image into canvas element
            imgContext.drawImage(img, 0, 0, img.width, img.height);

            var dataURL = imgCanvas.toDataURL();

            $.ajax({
                type: "POST",
                url: "save-img.php", // see code at the bottom
                data: {
                    imgBase64: dataURL
                }
            }).done(function(resp) {
                if (resp.startsWith('nok')) {
                    console.warn('got save error:', resp);
                } else {
                    if (callback) callback(resp);
                }
            });
        },

        update_options_for_width: function(w) {
            var o = CiM.opt, //shortcut
                vp_ratio = o.my_final_size.w / o.my_final_size.h,
                h, new_vp_w, new_vp_h;
            w = Math.floor(w * 0.9);
            h = Math.floor(w / o.my_win_ratio);
            o.container.width = w;
            o.container.height = h;
            new_vp_h = 0.6 * h;
            new_vp_w = new_vp_h * vp_ratio;
            // if we adapted to the height, but it's too wide:
            if (new_vp_w > 0.6 * w) {
                new_vp_w = 0.6 * w;
                new_vp_h = new_vp_w / vp_ratio;
            }
            new_vp_w = Math.floor(new_vp_w);
            new_vp_h = Math.floor(new_vp_h);
            o.viewport.height = new_vp_h;
            o.viewport.width = new_vp_w;
        },

        show_cropme_in_div: function(cropme_div) {
            if (CiM.myCropme)
                CiM.myCropme.destroy();
            CiM.myCropme = new Cropme(cropme_div, CiM.opt);
            CiM.myCropme.bind({
                url: CiM.imgHolder.src
            });
        }
    }

window.onload = function() {

    var croppedImg = $('#cropped-img'),
        savedImg = $('#saved-img');

    CiM.opt.my_final_size = {
        w: 240,
        h: 292
    };
    CiM.opt.my_win_ratio = 1.5;

    savedImg[0].src = CiM.get_image_placeholder('?');

    $('#imgModal-btnCrop').on('click', function() {
        CiM.crop_into_img(croppedImg, function() {
            $('#imgModal-btnSave').show();
            $('#imgModal-btnCrop').hide();
        });
    });
    $('#imgModal-btnSave').on('click', function() {
        alert('Oops - can\'t run PHP in CodePen. Please see bottom of HTML for my suggested PHP');
        return;
        CiM.uploadImage(croppedImg[0], function(path_to_saved) {
            savedImg[0].src = path_to_saved;
            $('#imgModal-dialog').modal('hide');
        });
    });
    $('#btnGetImage').on('click', function() {
        //force 'change' event even if repeating same file:
        $('#fileUpload').prop("value", "");
        $('#fileUpload').click();
    });
    $('#fileUpload').on('change', function() {
        CiM.read_file_from_input( /*input elem*/ this, function() {
            console.log('image src fully loaded');
            $('#imgModal-dialog').modal('show');
        });
    });
    $('#imgModal-dialog').on('shown.bs.modal', function() {
        var cropZone = $('#imgModal-cropme');

        CiM.update_options_for_width($('#imgModal-msg').width());

        $('#imgModal-btnSave').hide();
        $('#imgModal-btnCrop').show();
        croppedImg[0].src = '';
        CiM.show_cropme_in_div($('#imgModal-cropme')[0]);
    });
    //window.addEventListener('resize', function(){
    //we might want to reload cropme on resize
    //}, true);
};
</script>
<script>

   $( function() {
      $( "#BIRTH_DATE" ).datepicker({
        dateFormat: "mm/dd/yy",
        defaultDate : "{{ date('m/d/Y',strtotime($data->BIRTH_DATE)) }}"

      });
    });


    $('#form').on('submit', function(e) {
      e.preventDefault();
      var url_post=$("#form").attr('data-url-post');
      var loader=$("#form").attr('data-loader-file');

      var form = new FormData(this);
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
          //console.log(data);
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

            //console.log(err);
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
