@extends('layouts.default')

@section('title', 'Guest Folio')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/custom.css">
<!-- <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/bootstrap.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="/bali/ayana_template/vendor/fontawesome/css/all.css"> -->
<script type="text/javascript" src="/bali/ayana_template/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bali/ayana_template/vendor/fontawesome/js/all.js"></script>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li> 
    <li class="breadcrumb-item"><a href="/folio">POS</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Guest Folio</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card">
        <div class="card-body main-wrapper pb-3 bg-white" id="header">
            <div class="invoice-box col-9 mb-3">
              <table cellpadding="0" cellspacing="0">
                  <tr class="top">
                      <td colspan="8">
                          <table>
                              <tr>
                                  <td class="title" style="width: 60%;">
                                      <img src="/bali/ayana_template/img/logo-ayana-resort.jpeg" style="width:150px;">
                                  </td>
                                 <td style="font-size:12px;padding:20px 5px 0 0;float: right;">
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
                                  <td class="title text-center" style="width:70%,border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">INVOICE STATEMENT</td>
                              </tr>
                          </table>
                          <hr style="margin: 0 0 .2em 0">
                          <tr>
                            <td class="text-left" style="font-size: 12px"><div style="padding: 0 5px"><span class="badge badge-sm bg-secondary text-white">CONF NO {{ $conf_no }}</span></div></td>
                            <td class="text-right" style="font-size: 12px"><div style="padding: 0 5px"><span class="badge badge-sm bg-secondary text-white">WINDOW {{ $window }}</span></div></td>
                          </tr>
                      </td>
                  </tr>
                  @foreach($data_guest as $data_header => $data_guest)
                  <tr class="information">
                      <td colspan="8" style="padding-bottom: 1em">
                          <table id="customer-info">
                              <tr>
                                <td colspan="8">
                                    <table class="table-bordered">
                                        <tr class="heading">
                                            <td class="text-center" width="10%">FOLIO NO</td>
                                            <td class="text-center" width="20%">GUEST NAME</td>
                                            <td class="text-center" width="12%">ARRIVAL</td>
                                            <td class="text-center" width="12%">DEPARTURE</td>
                                            <td class="text-center" width="12%">ROOM RATE</td>
                                            <td class="text-center" width="13%">ROOM CLASS</td>
                                            <td class="text-center" width="8%">ROOM</td>
                                            <td class="text-center">AMOUNT (IDR)</td>
                                        </tr>
                                        <tr class="item-center">
                                            <td class="text-center">{{ $data_guest->folio_no }}</td>
                                            <td>@if($data_guest->title) {{ $data_guest->title."." }} @endif {{ $data_guest->first_name }} {{ $data_guest->last_name }}</td>
                                            <td class="text-center">{{ date('d M Y', strtotime($data_guest->arrival)) }}</td>
                                            <td class="text-center">{{ date('d M Y', strtotime($data_guest->departure)) }}</td>
                                            <td class="text-center">@if(explode(" ",$data_guest->room_rate, 2)) {{ explode(" ",$data_guest->room_rate, 2)[0] }} @else <span>Unknown</span> @endif</td>
                                            <td class="text-center">@if($data_guest->room_class) {{ $data_guest->room_class }} @else <span>-</span> @endif</td>
                                            <td class="text-center">{{ $data_guest->room }}</td>
                                            <td class="text-right">{{ number_format($data_guest->folio_amount, 0, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                              <td colspan="8"><b>TRANSACTION DETAILS</b></td>
                            </tr>
                            <tr>
                              <td colspan="8">
                                <table class="table-bordered" style="margin-top: -5px">
                                  <tr class="heading">
                                    <td class="text-center" style="width:3%;">DATE</td>
                                    <td class="text-center" style="width:7%;">INVOICE NO</td>
                                    <td class="text-center" style="width:8%;">DESCRIPTION</td>
                                    <td class="text-center" style="width:19%;">REFERENCE</td>
                                    <td class="text-center" style="width:5%;">POSTING TIME</td>
                                    <td class="text-center" style="width:8%;">DEBIT (IDR)</td>
                                    <td class="text-center" style="width:8%;">CREDIT (IDR)</td>         
                                  </tr>
                                  
                                  @if(isset($data_guest->details) && $data_guest->details)
                                  <?php 
                                    $total_debit[$data_header] = $total_credit[$data_header] = 0;
                                    $inv_dummy = 633176;
                                  ?>
                                  @foreach($data_guest->details as $key => $folio)
                                  <?php 
                                      if((int)$folio->DEBIT >= 0)
                                        $total_debit[$data_header] += (int)$folio->DEBIT;
                                      else 
                                        $total_debit[$data_header] -= (int)($folio->DEBIT*-1); 

                                      if((int)$folio->CREDIT >= 0)
                                        $total_credit[$data_header] += (int)$folio->CREDIT;
                                      else
                                        $total_credit[$data_header] -= (int)($folio->CREDIT*-1); 
                                  ?>
                                  <tr class="item">
                                      <td class="text-center">{{ date('d M Y', strtotime($folio->TRX_DATE)) }}</td>
                                      <td class="text-center">{{ $inv_dummy }}</td>
                                      <td class="text-left">@if($folio->DESCRIPTION) {{ $folio->DESCRIPTION }} @else - @endif</td>
                                      <td class="text-left">@if($folio->REFERENCE) {{ $folio->REFERENCE }} @else - @endif</td>
                                      <td class="text-center">{{ $folio->POSTING_DATE }}</td>
                                      <td class="text-right">{{ number_format((int)$folio->DEBIT, 0, '.', ',') }}</td>
                                      <td class="text-right">{{ number_format((int)$folio->CREDIT, 0, '.', ',') }}</td>
                                  </tr>
                                  <?php $inv_dummy++ ?>
                                  @endforeach
                                  <tr class="total">
                                    <td colspan="5" class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>Total in IDR : </b></td>
                                    <td class=" text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b><?= number_format($total_debit[$data_header], 0, '.', ','); ?></b></td>
                                    <td class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b><?= number_format($total_credit[$data_header], 0, '.', ','); ?></b></td>
                                  </tr>
                                  @endif
                                  
                                </table>
                              </td>
                            </tr>
                          </table>
                      </td>
                  </tr>
                  @endforeach
                  
                  
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
          {{-- <div class="container-fluid" style="background: #F4F2EF">
              <div class="container p-3">
                  <div class="row">
                      <div class="col-9 m-auto">
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
          </div> --}}       
        </div>
    </div>
  </div>
</div>
          
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(document).ready( function () {
      $('#table-folio-reference').DataTable();
   });
</script>
@endsection

