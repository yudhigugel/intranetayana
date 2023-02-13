@extends('layouts.default')

@section('title', 'Credit Card Validator')

@section('custom_source_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/css/vendor/cropme.min.css">
<style>
    .field-cc{
        width: 80%;
        font-size: 14px;
        font-weight: bold;
        border-radius: 3px;
    }

    .field-cc::-webkit-input-placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }
    .field-cc:-ms-input-placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }
    .field-cc::placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }

</style>
@endsection

@section('extra_inline_styles')
@endsection


@section('content')
<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><span>Credit Card Validator</span></li>
</ol>
</nav>
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <h2 class="text-center">Credit Card Validator</h2>
                <form id="form">
                    <div class="col-md-8 offset-md-3" style="margin-top:20px;">
                        <div class="form-group clearfix">
                            <input type="text" name="" id="cc_number" class="form-control col-md-8 float-left field-cc" style="width:80%"  placeholder="insert credit card number..." required  onkeypress="return resetType();">
                            <div class="col-md-4 float-right">
                                <button type="submit" class="btn btn-primary float-left" style="margin-right:5px;margin-top:2px;">Check</button>
                                <button type="button" class="btn btn-dark float-left text-white" onclick="return copyToClipboard('#cc_number')"> <i class="fa fa-copy"></i></button>
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-md-6 offset-md-3">
                        <div class="alert alert-warning" role="alert" id="alert-warning" style="display: none;"></div>
                        <div class="alert alert-success" role="alert" id="alert-success" style="display: none;"></div>
                        <div class="alert alert-danger" role="alert" id="alert-error"  style="display: none;"></div>
                    </div>
                </form>



            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    function AmexCardnumber(inputtxt) {
    var cardno = /^(?:3[47][0-9]{13})$/;
    return cardno.test(inputtxt);
    }

    function VisaCardnumber(inputtxt) {
    var cardno = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
    return cardno.test(inputtxt);
    }

    function MasterCardnumber(inputtxt) {
    var cardno = /^(?:5[1-5][0-9]{14})$/;
    return cardno.test(inputtxt);
    }

    function DiscoverCardnumber(inputtxt) {
    var cardno = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
    return cardno.test(inputtxt);
    }

    function DinerClubCardnumber(inputtxt) {
    var cardno = /^(?:3(?:0[0-5]|[68][0-9])[0-9]{11})$/;
    return cardno.test(inputtxt);
    }

    function JCBCardnumber(inputtxt) {
    var cardno = /^(?:(?:2131|1800|35\d{3})\d{11})$/;
    return cardno.test(inputtxt);
    }

    function IsValidCreditCardNumber(cardNumber) {
        var cardType = null;
        if (VisaCardnumber(cardNumber)) {
            cardType = "Visa";
        } else if (MasterCardnumber(cardNumber)) {
            cardType = "MasterCard";
        } else if (AmexCardnumber(cardNumber)) {
            cardType = "American Express";
        } else if (DiscoverCardnumber(cardNumber)) {
            cardType = "Discover";
        } else if (DinerClubCardnumber(cardNumber)) {
            cardType = "Diners Club";
        } else if (JCBCardnumber(cardNumber)) {
            cardType = "JCB";
        }
        return cardType;
    }

    function resetType(){
        $("#alert-error").hide();
        $("#alert-success").hide();
    }

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();

        $("#alert-warning").html(`<i class="fa fa-copy"></i> Credit Card number copied to clipboard.`);
        $("#alert-warning").slideDown();
        $('#alert-warning').delay(3000).fadeOut();

    }

    $('#form').submit(function(e){
        e.preventDefault();

        var cc_number=$("#cc_number").val();
        if(cc_number.length>13){
            var cek = IsValidCreditCardNumber(cc_number);
            if(cek){
                $("#alert-error").hide();
                $("#alert-success").html(`<i class="fa fa-check"></i> Credit Card is valid. Credit Card Type is <b>`+cek+`</b>`);
                $("#alert-success").slideDown();
            }else{
                $("#alert-success").hide();
                $("#alert-error").html(`<i class="fa fa-times"></i> Credit Card is not valid.`);
                $("#alert-error").slideDown();
            }
        }else{
            $("#alert-success").hide();
            $("#alert-error").html(`Please input minimum 14 length of number`);
            $("#alert-error").slideDown();
        }
    });
</script>
@endsection
