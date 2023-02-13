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
    <li class="breadcrumb-item"><a href="/folio/folio_categorized">POS</a></li> 
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
                                  <td class="title" style="width: 70%;">
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
                                  <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Folio Information</td>
                              </tr>
                          </table>
                          
                      </td>
                  </tr>
                  <tr>
                      <td colspan="8">
                          <table>
                              <tr class="heading">
                                  <td>COMPANY CODE</td>
                                  <td>BUSINESS UNIT</td>
                                  <td>RESORT</td>
                              </tr>
                              <tr class="item-center">
                                  <td>{{ $data_guest->company_code }}</td>
                                  <td>{{ $data_guest->business_unit }}</td>
                                  <td>{{ $data_guest->resort }}</td>
                              </tr>
                              
                              <tr class="heading">
                                  <td>CONF NO</td>
                                  <td>FOLIO NO</td>
                                  <td>TOTAL AMOUNT</td>
                              </tr>
                              <tr class="item-center">
                                  <td>{{ $conf_no }}</td>
                                  <td>{{ $folio_no }}</td>
                                  <td>{{ number_format($data_guest->folio_amount, 0, '.', ',') }}</td>
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
                                  <td style="width: 70%;">
                                     @if($data_guest->title) {{ $data_guest->title."." }} @endif {{ $data_guest->first_name }} {{ $data_guest->last_name }}<br>
                                     {{ $data_guest->address1 ? $data_guest->address1 : "-" }}  <br><br>
                                     <div class="mb-1">
                                      <b>Agent</b> <span class="ml-4">: &nbsp; @if($data_guest->agent) {{ $data_guest->agent }} @else - @endif</span> <br>
                                     </div>
                                     <div class="mb-1">
                                      <b>Company</b> <span class="ml-1">: &nbsp; @if($data_guest->agent) {{ $data_guest->company }} @else - @endif</span> <br>
                                     </div>
                                     <div class="mb-1">
                                      <b>Group</b><span class="ml-3">&nbsp;&nbsp; : &nbsp; @if($data_guest->agent) {{ $data_guest->groups }} @else - @endif</span> <br>
                                     </div>
                                     <div class="mb-1">
                                      <b>Charge to</b><span>&nbsp;  : &nbsp; @if($data_guest->charge_to) {{ $data_guest->charge_to }} @else - @endif</span>
                                     </div>
                                  </td>

                                  <td>
                                      <!-- <b>Conf No.</b> : {{ $conf_no }} <br> -->
                                      <div class="mb-1">
                                        <b>Room</b> : {{ $data_guest->room }} <br>
                                      </div>
                                      <div class="mb-1">
                                        <b>Persons</b> :  {{ $data_guest->persons }} <br>
                                      </div>
                                      <div class="mb-1"> 
                                        <b>Arrival</b> : {{ date('d M Y', strtotime($data_guest->arrival)) }} <br>
                                      </div>
                                      <div class="mb-1">
                                        <b>Departure</b> : {{ date('d M Y', strtotime($data_guest->departure)) }} <br>
                                      </div>
                                      <div class="mb-1">
                                        <b>Room Rate</b> : {{ $data_guest->room_rate }} <br>
                                      </div>
                                      <div class="mb-1">
                                        <b>Cashier</b> : {{ $data_guest->cashier }} <br>
                                      </div>
                                  </td>
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
                      <td style="width:10%;">Description</td>
                      <td style="width:10%;">Reference</td>
                      <td style="width:10%;">Time</td>
                      <td style="width:10%;">Debit</td>
                      <td style="width:10%;">Credit</td>         
                  </tr>
                  @if(isset($data_folio) && $data_folio)
                  @foreach($data_folio as $folio)
                  <?php 
                      $total_debit[] = (int)$folio->DEBIT;
                      $total_credit[] = (int)$folio->CREDIT;
                  ?>
                  <tr class="item">
                      <td class="text-center">{{ date('d M Y', strtotime($folio->TRX_DATE)) }}</td>
                      <td class="text-left">@if($folio->DESCRIPTION) {{ $folio->DESCRIPTION }} @else - @endif</td>
                      <td class="text-left">@if($folio->REFERENCE) {{ $folio->REFERENCE }} @else - @endif</td>
                      <td class="text-center">{{ $folio->POSTING_DATE }}</td>
                      <td class="text-center">{{ number_format((int)$folio->DEBIT, 0, '.', ',') }}</td>
                      <td class="text-center">{{ number_format((int)$folio->CREDIT, 0, '.', ',') }}</td>
                  </tr>
                  @endforeach
                  <tr class="total">
                      <td colspan="3"></td>
                      <td class="text-center" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>Total in IDR : </b></td>
                      <td class=" text-center" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b><?= number_format(array_sum($total_debit), 0, '.', ','); ?></b></td>
                      <td class="text-center" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b><?= number_format(array_sum($total_credit), 0, '.', ','); ?></b></td>
                  </tr>
                  @else 
                  <tr class="item">
                    <td colspan="7" class="text-center">No Data Available</td>
                  </tr>
                  @endif
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

