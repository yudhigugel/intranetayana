@extends('layouts.default')

@section('title', 'Pos Culinary')

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
      <li class="breadcrumb-item"><a href="#">POS</a></li>
      <li class="breadcrumb-item"><a href="#">POS (Liu Li Palace)</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Detail Transaction</span></li>
    </ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body mb-3 pb-0 bg-white" id="header">
                  <div class="col-md-8 float-left">
                        <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                        <h2> POS Liu Li Palace</h2>
                        <h3> {{ strtoupper($Tenant) }} POS TRANSACTION DETAIL</h3>
                        <h5> Transaction Date : {{ date("d-M-y", strtotime($TransactionDate)) }} </h5>
                  </div>
                  <div class="col-md-4 float-left">
                        <table class="detail-table">
                             <tr><td>TRANSACTION #</td><td>:</td><td>{{$ReceiptNumber}}</td></tr>
                            <tr><td>DATE</td><td>: </td><td>{{date('Y-m-d',strtotime($TransactionDate))}}</td></tr>
                            <tr><td>OUTLET</td><td>: </td><td>{{strtoupper($Tenant)}}</td></tr>
                            <tr><td>ORDER TYPE</td><td>: </td><td>{{strtolower($SalesType) == 'unknown' ? '-': strtoupper($SalesType)}}</td></tr>
                            
                           
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
                                <th class="bg-secondary text-white">ITEM PRICE</th>
                                <th class="bg-secondary text-white">SUBTOTAL</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $Discounts =0; $Gratuity=0;$Tax=0;$Total=0;$TotalCollected=0; @endphp
                               @if(isset($data_transaction) && count($data_transaction))
                                @php $i=1 @endphp
                                @foreach ($data_transaction as $key => $result)
                                  @if(strtolower($key) != 'tender')
                                  @php 
                                      $Discounts = $result->Discount; 
                                      $Gratuity  = $result->Gratuity; 
                                      $Tax  = $result->Tax; 
                                      $Total =$result->TotalNetSales;
                                      $TotalCollected=$result->TotalCollected;
                                  @endphp 
                                  <tr>
                                    <td class="text-center">{{$i}}</td>
                                    <td  style="text-align: left;">{{$result->Items}}</td>
                                    <td  style="text-align: left;">{{$result->Category}}</td>
                                    <td  style="text-align: right;">{{number_format($result->Qty)}}</td>
                                    <td style="text-align: right;">{{number_format($result->NetSales)}}</td>
                                    <td style="text-align: right;">{{number_format($result->Qty * $result->NetSales)}}</td>
                                   </tr>
                                  @endif
                                @php $i+=1 @endphp
                                @endforeach
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format($Total)}}</td>
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">GRATUITY</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format($Gratuity)}}</td>
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TAX</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format($Tax)}}</td>
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">DISCOUNT</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format($Discounts)}}</td>
                                </tr>
                                <tr style="background: #FFF;color:#000;">
                                  <td colspan="5"style="text-align: right;background: #ececec;color:#000;font-weight:bold;">GRAND TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{number_format($TotalCollected)}}</td>
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