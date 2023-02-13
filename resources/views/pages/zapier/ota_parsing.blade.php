<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox"  data-client-key="{{config('intranet.midtrans_client_key.sandbox.kms')}}"></script>

<span id="parse"></span>

<body>
    <script>
        // card data from customer input, for example
        var cardData = {
        "card_number": 4811111111111114,
        "card_exp_month": 02,
        "card_exp_year": 25,
        "card_cvv": 123,
        };

        var amount= {{ empty($data['amount']) ? 0 : $data['amount'] }};
        var first_name = "{{ $data['first_name'] }}";
        var last_name = "{{ $data['last_name'] }}";
        var email = "{{ $data['email'] }}";
        var phone ="{{ $data['phone'] }}";


        // callback functions
        var options = {
            onSuccess: function(response){
                // Success to get card token_id, implement as you wish here
                // console.log('Success to get card token_id, response:', response);
                //console.log('This is the card token_id:', token_id);
                var token_id = response.token_id;
                if (token_id){
                    // Implement sending the token_id to backend to proceed to next step
                    var http = new XMLHttpRequest();
                    var url = "{{ url('webservice/zapier/vcc_process')}}";
                    var params = 'token_id='+token_id+'&amount='+amount+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&phone='+phone;
                    http.open('POST', url, true);
                    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    http.onreadystatechange = function() {
                        if(http.readyState == 4 && http.status == 200) {
                            var hasil= document.getElementById("parse");
                            console.log(hasil);
                            hasil.innerHTML = http.responseText;

                        }
                    }
                    http.send(params);
                }

            },
            onFailure: function(response){
                // Fail to get card token_id, implement as you wish here
                console.log('Fail to get card token_id, response:', response);

                // you may want to implement displaying failure message to customer.
                // Also record the error message to your log, so you can review
                // what causing failure on this transaction.
            }
        };

        // trigger `getCardToken` function
        MidtransNew3ds.getCardToken(cardData, options);
    </script>
</body>
</html>
