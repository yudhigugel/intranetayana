@extends('layouts.default')

@section('title', 'POS Simphony Report')

@section('styles')
<style type="text/css">
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
    top: 45px;
    z-index: 99;
    box-shadow: 0px 3px 7px -5px #878787;
  }
  .sticky + .main-wrapper {
    padding-top: auto;
  }
  #header{
    transform: all .7s ease-in-out;
  }

  .detail-table{
    display: table;
    text-align: left;
  }
  .detail-table tr td:last-child{
    text-align: left;
  }

  #content-table{

  }

  #content-table tr th{
    text-align: center;
    border: 1px solid #ddd;
    font-size:12px !important;
  }

  #content-table tr td{
    text-align: left;
    border: 1px solid #ddd;
    font-size:11px !important;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li> 
      <li class="breadcrumb-item"><a href="/report/fnb_ayana">POS</a></li>
      <li class="breadcrumb-item"><a href="javascript:history.back(-1)">POS (Simphony)</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Detail Transaction</span></li>
    </ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 mb-3 bg-white" id="header">
                  <div class="col-md-8 float-left">
                        <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                        <h2> POS Simphony </h2>
                        <h3> {{ strtoupper($outletname) }} - DETAIL TRANSACTION</h3>
                        <h5> Transaction Date : {{ date("d F Y", strtotime($trans_date)) }} </h5>
                  </div>
                  <div class="col-md-4 float-left">
                        <table class="detail-table">
                            <tr><td>CHECK#</td><td>:</td><td>{{$checknum}}</td></tr>
                            {{-- <tr><td>DATE</td><td>: </td><td>{{date('Y-m-d',strtotime($trans_date))}}</td></tr> --}}
                            <tr><td>OUTLET</td><td>: </td><td>{{strtoupper($outletname)}}</td></tr>
                            <tr><td>ORDER TYPE</td><td>: </td><td>{{strtoupper($ordertype)}}</td></tr>
                            <tr><td>DAY PART</td><td>: </td><td>{{strtoupper($daypartname)}}</td></tr>

                            <!-- <tr><td>ROOM</td><td>: </td><td></td></tr>
                            <tr><td>GUEST NAME</td><td>: </td><td></td></tr> -->
                        </table>
                  </div>
                </div>
                <hr class="my-0">
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead >
                              <tr >
                                <th class="bg-secondary text-white" style="width: 7%">NO</th>
                                <th class="bg-secondary text-white">ITEM NAME</th>
                                <th class="bg-secondary text-white">ITEM GROUP</th>
                                <th class="bg-secondary text-white">QTY</th>
                                <th class="bg-secondary text-white">ITEM PRICE (IDR)</th>
                                <th class="bg-secondary text-white">SUBTOTAL (IDR)</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(isset($data_transaction) && count($data_transaction))
                                @php $i=1 @endphp
                                @foreach ($data_transaction as $key => $result)
                                  @if(strtolower($key) != 'tender')
                                  @php 
                                    $data_price = $result->QUANTITY == 0 ? (int)$result->ITEMREVENUE : (int)$result->ITEMREVENUE / (int)$result->QUANTITY;

                                    if(isset($result->TRANSACTIONTYPE) && trim(strtolower($result->TRANSACTIONTYPE)) == 'discount')
                                      $data_price = $result->DISCOUNT;
                                  @endphp 
                                  <tr>
                                    <td class="text-center">{{$i}}</td>
                                    <td  style="text-align: left;">{{ isset($result->TRANSACTIONTYPE) && trim(strtolower($result->TRANSACTIONTYPE)) == 'discount' ? 'DISCOUNT' : $result->MENUITEMNAME}}</td>
                                    <td  style="text-align: left;">{{ isset($result->TRANSACTIONTYPE) && trim(strtolower($result->TRANSACTIONTYPE)) == 'discount' ? $result->DISCOUNTNAME : $result->FAMILYGROUPNAME}}</td>
                                    <td  style="text-align: left;">{{$result->QUANTITY}}</td>
                                    <td style="text-align: right;" @if(isset($result->TRANSACTIONTYPE) && trim(strtolower($result->TRANSACTIONTYPE)) == 'discount') colspan='2' @endif>{{number_format($data_price)}}</td>

                                    @if(isset($result->TRANSACTIONTYPE) && trim(strtolower($result->TRANSACTIONTYPE)) != 'discount')
                                    <td style="text-align: right;">{{number_format($result->ITEMREVENUE)}}</td>
                                    @endif
                                  </tr>
                                  @endif
                                @php $i+=1 @endphp
                                @endforeach
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format(isset($data_transaction['TENDER']->SUBTOTAL) ? $data_transaction['TENDER']->SUBTOTAL : 0)}}</td>
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">SERVICE CHARGE</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format(isset($data_transaction['TENDER']->SERVICECHARGETOTAL) ? $data_transaction['TENDER']->SERVICECHARGETOTAL : 0)}}</td> 
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TAX</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format(isset($data_transaction['TENDER']->TAXTOTAL) ? $data_transaction['TENDER']->TAXTOTAL : 0)}}</td> 
                                </tr>
                                {{-- <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">DISCOUNT</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format(isset($data_transaction['TENDER']->DISCOUNT) ? $data_transaction['TENDER']->DISCOUNT : 0)}}</td>
                                </tr> --}}
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">GRAND TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format(isset($data_transaction['TENDER']->CHECKTOTAL) ? $data_transaction['TENDER']->CHECKTOTAL : 0)}}</td>
                                </tr>
                              @else
                                <tr>
                                  <td colspan="7" class="text-center">No Data</td>
                                </tr>
                              @endif
                            </tbody>
                          </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  
@endsection