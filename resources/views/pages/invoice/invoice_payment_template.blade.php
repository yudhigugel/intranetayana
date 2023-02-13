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

<body>
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
                            <h3 style="">IDR 58,277,110</h3>
                            <p style="color:#ffc000;font-weight: 500;">Due Balance</p>
                        </div>
                        <div class="col-md-3 float-left">
                            <div style="display: table;float:right;">
                                <a href="javascript:void(0)" class="btn btn-light border border-dark"><i class="fa fa-print"></i></a>
                                <a href="{{route('invoice_payment.download')}}" class="btn btn-light border border-dark"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 offset-md-2">
                        <div class="col-md-2 float-left" style="border-right: #999 1px solid;">
                        <p style="margin:0;color:#999"> Invoice #</p>
                        <p style="margin:0"> 2020011476</p>
                        </div>
                        <div class="col-md-2 float-left">
                        <p  style="margin:0;color:#999"> Due Date</p>
                        <p style="margin:0"> 21 Jan 2021</p>
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
                <td colspan="8">
                    <table>
                        <tr>
                            <td class="title" style="width: 60%;">
                                <img src="/bali/ayana_template/img/logo-ayana-resort.jpeg" style="width:150px;">
                            </td>

                           <td style="font-size:12px;padding-top:20px;">
                                <span style="font-size:16px;font-weight: bold;">AYANA Resort Bali</span><br>
                                Jl. Karang Mas Sejahtara, Jimbaran  <br>
                                Kec. Kuta Selatan, Kabupaten Badung <br>
                                Bali 80364
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table>
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Document Information</td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table>
                        <tr class="heading">
                            <td>Invoice No</td>
                            <td>Invoice Period</td>
                            <td>Invoice Date</td>
                            <td>Due Date</td>
                        </tr>
                        <tr class="item-center">
                            <td>2020011476</td>
                            <td>1 Jan 2021 – 3 Jan 2021</td>
                            <td>7 Jan 2021</td>
                            <td>21 Jan 2021</td>
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
                            <td>58,277,110</td>
                        </tr>
                         <tr class="heading">
                            <td>Company Code</td>
                            <td></td>
                            <td>BCA Virtual Account No</td>
                            <td>CIMB Niaga Virtual Account No</td>
                        </tr>
                        <tr class="item-center">
                            <td>KMS1</td>
                            <td></td>
                            <td>78792020011476</td>
                            <td>4321002020011476</td>
                        </tr>


                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table style="margin-top:10px;">
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Customer Info</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="8">
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
                <td colspan="8">
                    <table>
                        <tr>
                            <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Item List</td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr class="heading">
                <td style="width:10%;">Date</td>
                <td style="width:10%;">Invoice No</td>
                <td style="width:10%;">Voucher No</td>
                <td style="width:10%;">Guest Name</td>
                <td style="width:30%;">Transaction Detail</td>
                <td style="width:10%;">Arrival Date</td>
                <td style="width:10%;">Departure Date</td>
                <td style="width:10%;">Amount</td>
            </tr>

            <tr class="item">
                <td>1-Jan-21</td>
                <td>950650</td>
                <td></td>
                <td>Erwin Handoko</td>
                <td>Deluxe Room Double, 1,898,000++ x 4</td>
                <td>28-Dec-20</td>
                <td>1-Jan-21</td>
                <td>9,186,320</td>
            </tr>
             <tr class="item">
                <td>1-Jan-21</td>
                <td>950651</td>
                <td></td>
                <td>Erwin Handoko</td>
                <td>Deluxe Room Double, 1,898,000++ x 4</td>
                <td>28-Dec-20</td>
                <td>1-Jan-21</td>
                <td>9,186,320</td>
            </tr>
            <tr class="item">
                <td>1-Jan-21</td>
                <td>950657</td>
                <td></td>
                <td>Sebastian Liminggus</td>
                <td>Deluxe Room Double, 1,898,000++ x 2</td>
                <td>28-Dec-20</td>
                <td>1-Jan-21</td>
                <td>4,593,160</td>
            </tr>
            <tr class="item">
                <td>1-Jan-21</td>
                <td>950658</td>
                <td></td>
                <td>Sebastian Liminggus</td>
                <td>Deluxe Room Double, 1,898,000++ x 2</td>
                <td>28-Dec-20</td>
                <td>1-Jan-21</td>
                <td>4,593,160</td>
            </tr>
            <tr class="item">
                <td>1-Jan-21</td>
                <td>021129</td>
                <td></td>
                <td>Adi Kusma</td>
                <td>Deluxe Room Double, 1,898,000++ x 4</td>
                <td>1-Jan-21</td>
                <td>1-Jan-21</td>
                <td>1,766,600</td>
            </tr>
            <tr class="item">
                <td>1-Jan-21</td>
                <td>867728</td>
                <td></td>
                <td>Adi Kusma</td>
                <td>Rock Bar Dinner, 3,267,000 x 1</td>
                <td>1-Jan-21</td>
                <td>1-Jan-21</td>
                <td>3,267,000</td>
            </tr>
            <tr class="item">
                <td>1-Jan-21</td>
                <td>038258</td>
                <td></td>
                <td>Adi Kusma</td>
                <td>Sami Dinner, 3,025,000 x 1</td>
                <td>1-Jan-21</td>
                <td>1-Jan-21</td>
                <td>3,025,000</td>
            </tr>
            <tr class="item">
                <td>2-Jan-21</td>
                <td>950849</td>
                <td></td>
                <td>Novrada Budiarta</td>
                <td>Deluxe Room King, 2,107,000 x 5</td>
                <td>28-Dec-20</td>
                <td>1-Jan-21</td>
                <td>10,535,000 </td>
            </tr>
            <tr class="item">
                <td>2-Jan-21</td>
                <td>950854</td>
                <td></td>
                <td>Bagus Wicaksono </td>
                <td>Deluxe Room King, 2,107,000 x 4, Incidental charges, 1,415,700 x 1</td>
                <td>29-Dec-20</td>
                <td>1-Jan-21</td>
                <td>9,843,700</td>
            </tr>
            <tr class="item last">
                <td>3-Jan-21</td>
                <td>978805</td>
                <td></td>
                <td>Adi Kusma</td>
                <td>Rock Bar Dinner, 2,280,850 x 1</td>
                <td>3-Jan-21</td>
                <td>3-Jan-21</td>
                <td>2,280,850</td>
            </tr>
            <tr class="total">
                <td colspan="5"></td>
                <td colspan="2" style="font-size:12px;border-top: 2px solid #eee;font-weight: bold;"><b>Grand Total</b></td>
                <td style="font-size:12px;border-top: 2px solid #eee;font-weight: bold;"><b>58,277,110</b></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" style="margin-top:40px;">
            <tr>
                <td style="width: 50%;">
                    <table>
                        <tr><td style="border-bottom: black 1px solid;text-align: left;font-size:16px;"><span>Terms & Conditions</span></td></tr>
                        <tr><td style="font-size:12px;text-align: left">
                            Please transfer the full amount to:<br/>
                            <b>Beneficiary name: PT. Karang Mas Sejahtera</b><br/>
                            Bank Name & Branch: BCA Kuta Bali<br/>
                            Bank Address: Jl Raya Kuta No 121, Kab Badung, Bali 80361, Indonesia<br/>
                            Bank Account No: IDR 772.00.678.08<br/>USD No: 146.11.881.12<br/>
                            Swift code: CENAIDJA</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table>
                        <tr><td style="border-bottom: black 1px solid;text-align:left;font-size:16px;"><span>Notes</span></td></tr>
                        <tr><td style="font-size:12px;text-align: left;">
                            Please kindly contact us immediately if there are any discrepancies to my email: rudy.prianadjati@ayanaresort.com<br/><br/>
                            Please provide us details of your payment within 48 hours or it will be considered no payment received.<br/><br/>
                            Email receivable@ayanaresort.com or fax +62-361-702592.
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
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



</body>
</html>
