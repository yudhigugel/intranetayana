
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
  
  
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(e){
        e.preventDefault()

        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        if (check){
                var form = $('.login100-form');
                
                jQuery.ajax({
                    type:"POST",
                    url: form.attr("data-url-post"),
                    data: form.serialize(),
                    beforeSend: function(){
                        swal({
                            title: "Loading...",
                            text: "Please wait!",
                            imageUrl: form.attr("data-loader-file"),
                            imageSize: '150x150',
                            showConfirmButton: false
                        });
                    },
                    success: function(data) {
                        if(data.success)
                            window.location.href = form.attr("data-next-page"); 
                        else
                            swal("Error", data.msg, "error");

                    }
                })
                    
        }
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    /*==================================================================
    [ Show pass ]*/
    // var showPass = 0;
    // $('.btn-show-pass').on('click', function(){
    //     if(showPass == 0) {
    //         $(this).next('input').attr('type','text');
    //         $(this).addClass('active');
    //         showPass = 1;
    //     }
    //     else {
    //         $(this).next('input').attr('type','password');
    //         $(this).removeClass('active');
    //         showPass = 0;
    //     }
        
    // });


})(jQuery);