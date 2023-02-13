<!doctype html>
<html>
   <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui"/>
      <title>AYANA PAYMENT</title>
      <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/custom.css">
      <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="/bali/ayana_template/vendor/fontawesome/css/all.css">
      <script type="text/javascript" src="/bali/ayana_template/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="/bali/ayana_template/vendor/fontawesome/js/all.js"></script>
   </head>
   <style>
       #overlay{
            width:100%;
            height: 100%;
            background:url('/bali/ayana_template/img/pattern.png');
            z-index: 9999;
            position: absolute;
            transition: opacity 1s;
       }
       .btn-ayana{
           background: #aa9157;
           color:#fff;
           text-align: center;
           display: block;
           margin:0 auto;
       }

       #content{
            transition: opacity 1s;
       }
       .thecontent{
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        -moz-transform: translateX(-50%) translateY(-50%);
        -webkit-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
       }

       .come-title{
            text-align: center;font-size:46px;text-transform:uppercase;color:#505152;
       }

       @media only screen and (max-width: 600px) {
            .come-title{
                font-size:25px;
            }
       }

       @media only screen and (min-width: 768px) and (max-width:1023px) {
         #section1{
             display: none;
         }
         #section2{
             display: none;
         }
       }

   </style>
   <body>
        <div id="overlay">
            <div class="col-md-3 col-xs-12 d-none d-sm-none d-md-block d-lg-block float-left" style="background:url('/bali/ayana_template/img/bg1.jpg');background-size:cover;background-position:center center;height:100%;" id="section1">

            </div>
            <div class="col-md-6 col-xs-12 float-left" style="position: relative;height:100%;">
                <div class="thecontent">
                    <img src="/bali/ayana_template/img/logo_ayana_residences.png" style="width:100px;margin:0 auto;display:block;padding:10px 0px 20px 0px;">
                    <p style="text-align: center;font-size:20px;color:#505152;margin:0px;">This invoice is addressed to :</p>
                    <p class="come-title" >Mr Nyoman Doe</p>
                    <button onclick="hide()" class="btn btn-ayana">View Invoice</button>
                </div>
            </div>
            <div class="col-md-3 col-xs-12 d-none d-md-block d-lg-block float-left" style="background:url('/bali/ayana_template/img/bg2.jpg');background-size:cover;background-position:center center;height:100%;" id="section2">

            </div>

        </div>
       <div id="content" style="display: none;">
        <div class="contain-fix" style="max-height: 240px;position: fixed;width: 100%;">
            <div class="container-fluid" style="background: #FFF;">
                <div class="container">
                <div class="row pt-2 pb-2" >
                    <div class="col-md-8 offset-md-2">
                        <div class="col-md-8 col-xs-6 float-left">
                            <h1 style="font-size:30px;">AYANA Web Payment Invoice</h1>
                        </div>
                        <div class="col-md-4 col-xs-6 float-left">
                            <div style="display: table;float:right;">
                            <img src="/bali/ayana_template/img/ayana-bali-logo-color.png" style="width:50px; margin-right: 10px;">
                            <img src="/bali/ayana_template/img/rimba-bali-logo-color.png" style="width:50px; margin-right: 10px;">
                            <img src="/bali/ayana_template/img/thevillas-bali-logo-color.png" style="width:50px; margin-right: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="container-fluid" style="background: #F4F2EF">
                <div class="container pt-3 pb-2">
                <div class="row" >
                    <div class="col-md-8 offset-md-2">
                        <div class="col-md-9 float-left">
                            <h3 style="">IDR 1,529,672</h3>
                            <p style="color:#ffc000;font-weight: 500;">Due Balance</p>
                        </div>
                        <div class="col-md-3 float-left">
                            <div style="display: table;float:right;">
                            <a href="" class="btn btn-light border border-dark"><i class="fa fa-print"></i></a>
                            <a href="{{url('folio/invoice_payment_template/print')}}" class="btn btn-light border border-dark"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 offset-md-2">
                        <div class="col-md-2 float-left" style="border-right: #999 1px solid;">
                            <p style="margin:0;color:#999"> Invoice #</p>
                            <p style="margin:0"> 20210520123</p>
                        </div>
                        <div class="col-md-2 float-left">
                            <p  style="margin:0;color:#999"> Due Date</p>
                            <p style="margin:0"> 23 May 2021</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="contain-kosong" style="height:240px;">
        </div>
        <div class="invoice-box mb-3">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                <td colspan="9">
                    <table>
                        <tr>
                            <td style="width:33.333%">
                            <span style="font-size:2rem;font-weight: bold;">INVOICE</span>
                            </td>
                            <td style="text-align:center;width:33.333%">
                            <p style="font-size:2rem;font-weight: bold;">20210520123</p>
                            </td>
                            <td class="title" style="width:33.333%">
                            <img src="/bali/ayana_template/img/logo_ayana_residences.png" style="width:75px;float:right;margin:0px 5% 0px 0px">
                            </td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td colspan="9">
                    <table>
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Document Information</td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td colspan="9">
                    <table>
                        <tr class="heading">
                            <td>Invoice No</td>
                            <td>Invoice Date</td>
                            <td></td>
                            <td>Due Date</td>
                        </tr>
                        <tr class="item-center">
                            <td>20210520123</td>
                            <td>20 May 2021 – 23 May 2021</td>
                            <td></td>
                            <td>23 May 2021</td>
                        </tr>
                        <tr class="heading">
                            <td>SAP Customer No</td>
                            <td>Account Executive</td>
                            <td>Currency</td>
                            <td>Total Amount</td>
                        </tr>
                        <tr class="item-center">
                            <td>OW0011</td>
                            <td></td>
                            <td>IDR</td>
                            <td>1,529,672</td>
                        </tr>
                        <tr class="heading">
                            <td>Company Code</td>
                            <td>Contract Number</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="item-center">
                            <td>NJP1</td>
                            <td>XXXXXXXXX</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td colspan="9">
                    <table style="margin-top:10px;">
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Customer Info</td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr class="information">
                <td colspan="9">
                    <table id="customer-info">
                        <tr>
                            <td style="width: 60%;">
                            PT Bali Bangkit Travel<br>
                            Jl. Imam Bonjol No 999  <br>
                            Denpasar, Bali 80361 <br>
                            Indonesia
                            </td>
                            <td>
                            ATTN : Mr. Nyoman Doe<br>
                            TEL : +62 361 123 456<br>
                            MOBILE : +62 812 345 678<br/>
                            EMAIL : nyomandoe@balibangkittravel.com
                            </td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td colspan="9">
                    <table>
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Item List</td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                    <td class="invoice-box alter" colspan="9">
                        <table>
                            <tr class="heading">
                                <td style="width:10%;">Transaction Date</td>
                                <td style="width:10%;">Transaction Ref</td>
                                <td style="width:10%;">SAP Material ID</td>
                                <td style="width:30%;">Transaction Detail</td>
                                <td style="width:10%;">Quantity</td>
                                <td style="width:10%;">Unit Price</td>
                                <td style="width:10%;">VAT 10%</td>
                                <td style="width:10%;">Withholding Tax</td>
                                <td style="width:10%;">Ext Price</td>
                            </tr>
                            <tr class="item">
                                <td>4-Dec-20</td>
                                <td>MC20206129</td>
                                <td>N10201AB-WATER</td>
                                <td>AYANA RESIDENCES BALI – LILY – UNIT 1AB - WATER (1 NOV 2020 – 30 NOV 2020)</td>
                                <td style="text-align: right;">692.00</td>
                                <td style="text-align: right;">264,836</td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;">264,836</td>
                            </tr>
                            <tr class="item">
                                <td>4-Dec-20</td>
                                <td>MC20206129</td>
                                <td>N10201AB-ELEC</td>
                                <td>AYANA RESIDENCES BALI – LILY – UNIT 1AB - ELECTRICITY (1 NOV 2020 – 30 NOV 2020)</td>
                                <td style="text-align: right;">7.00</td>
                                <td style="text-align: right;">264,836</td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;">1,264,836</td>
                            </tr>
                            <tr class="total">
                                <td colspan="5"></td>
                                <td colspan="3" style="font-size:12px;border-top: 2px solid #eee;font-weight: bold;background:#eee"><b>GRAND TOTAL</b></td>
                                <td style="font-size:12px;border-top: 2px solid #eee;font-weight: bold;background:#eee; text-align:right"><b>1,529,672</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                <td colspan="9">
                    <table>
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;padding-top:5%">Electricity & Water Consumption Report</td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                    <td colspan="9" class="invoice-box alter">
                    <table>
                        <tr class="heading">
                            <td style="width:20%;" rowspan="2">Description</td>
                            <td style="width:20%;" colspan="2">Meter</td>
                            <td style="width:20%" rowspan="2" colspan="2"> Consumption</td>
                            <td style="" colspan="3" >Consumption Charge</td>
                        </tr>
                        <tr class="heading">
                            <td >Start</td>
                            <td >End</td>
                            <td >Charge</td>
                            <td >VAT</td>
                            <td >Total</td>
                        </tr>
                        <tr class="item">
                            <td>ELECTRICITY</td>
                            <td style="text-align: right;">5,899.00</td>
                            <td style="text-align: right;">6,591.00</td>
                            <td style="text-align: right;">692.00</td>
                            <td style="text-align: right;">KWH</td>
                            <td style="text-align: right;">1,149,682</td>
                            <td style="text-align: right;">114,968</td>
                            <td style="text-align: right;">1,264,650</td>
                        </tr>
                        <tr class="item">
                            <td>WATER</td>
                            <td style="text-align: right;">3,671.00</td>
                            <td style="text-align: right;">3,678.00</td>
                            <td style="text-align: right;">7.00</td>
                            <td style="text-align: right;">M3</td>
                            <td style="text-align: right;">146,360</td>
                            <td style="text-align: right;">14,636</td>
                            <td style="text-align: right;">160,996</td>
                        </tr>
                        <tr class="item">
                            <td>SEWAGE</td>
                            <td style="text-align: right;"></td>
                            <td style="text-align: right;"></td>
                            <td style="text-align: right;">118.00</td>
                            <td style="text-align: right;">M2</td>
                            <td style="text-align: right;">94,400</td>
                            <td style="text-align: right;">9,440</td>
                            <td style="text-align: right;">103,840</td>
                        </tr>
                    </table>
                    </td>
                </tr>

            </table>
            <table cellpadding="0" cellspacing="0" style="margin:80px 0px 20px 0px;">
                <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <p style="margin:0;font-size:20px;font-weight:bold;">PT Nusa Jaya Prima</p>
                                <p style="margin:0;font-size:12px;">Karang Mas Estate. Jl Karang Mas Sejahtera. Kab Badung, Bali 80364 – Indonesia.</p>
                                <p style="margin:0;font-size:12px;">P +62-361-702120 | E finance@ayanaresidences.com | www.ayanaresidences.com</p>
                            </td>
                        </tr>
                    </table>
                </td>
                </tr>
            </table>
            <div style="background:#c2a459;width:100%;height:26px;">
            </div>
        </div>
        <div class="container-fluid" style="background: #F4F2EF">
            <div class="container pt-3">
                <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="col-md-6 col-xs-6 float-left">
                        <form action="{{url('folio/invoice_payment')}}" method="GET">
                            <input type="submit" value="Pay Now With Credit Card" class="btn btn-primary">
                        </form>
                    </div>
                    <div class="col-md-6 col-xs-6 float-left">
                        <img src="/bali/ayana_template/img/payment.png" style="width: 200px;float: right;" >
                    </div>
                </div>
                </div>
            </div>
        </div>
       </div>
       <script>
           function hide(){
                document.getElementById("overlay").style.display="none";
                document.getElementById("content").style.display="block";
           }
       </script>
   </body>
</html>
