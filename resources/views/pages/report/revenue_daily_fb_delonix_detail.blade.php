@extends('layouts.default')

@section('title', 'Revenue Report')

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
      <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li>
      <li class="breadcrumb-item"><a href="#">Daily Report POS</a></li> 
      <li class="breadcrumb-item"><a href="/report/fnb_delonix">Delonix</a></li>      
      <li aria-current="page" class="breadcrumb-item active"><span>{{$id}}</span></li>
    </ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                  <div class="col-md-8 float-left">
                        <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                        <h2> DELONIX HOTEL KARAWANG </h2>
                        <h3> POS Transaction Detail</h3>
                  </div>
                  <div class="col-md-4 float-left">
                        <table class="detail-table">
                            <tr><td>Transaction #</td><td>:</td><td>{{$id}}</td></tr>
                            <tr><td>Date</td><td>: </td><td>{{date('Y-m-d H:i:s',strtotime($data['summary']['datetime']))}}</td></tr>
                            <tr><td>Outlet</td><td>: </td><td>{{$data['summary']['outlet']}}</td></tr>
                            <tr><td>Order Type</td><td>: </td><td>{{$data['summary']['ordertype']}}</td></tr>
                            <tr><td>Room</td><td>: </td><td>{{$data['summary']['room']}}</td></tr>
                            <tr><td>Guest Name</td><td>: </td><td>{{$data['summary']['guest']}}</td></tr>
                        </table>
                  </div>
                </div>
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead >
                              <tr >
                                <th class="bg-secondary text-white">No</th>
                                <th class="bg-secondary text-white">Item ID </th>
                                <th class="bg-secondary text-white">Item Group </th>
                                <th class="bg-secondary text-white">Item Name</th>
                                <th class="bg-secondary text-white">Qty</th>
                                <th class="bg-secondary text-white">Price</th>
                                <th class="bg-secondary text-white">Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=$subtotal=$total_tax=$total_service=$totaltax=0; @endphp
                              @foreach ($data['result'] as $result)
                              @php 
                                $i++;
                                //$price = ($result->DISCOUNT<0)? $result->DISCOUNT : $result->ITEMREVENUE;

                                $qty=$result->TOTAL_QTY;
                                $totalprice=$result->TOTAL_REVENUE;
                                $price = $totalprice/$qty;

                                $subtotal += $totalprice;

                                //$tax=$result->TAX1TOTAL;
                                //$service=$result->SERVICE_CHG;
                                //$discount=$result->DISCOUNT;
                                
                                //$totaltax+=$tax;



                              @endphp
                              <tr>
                                <td class="text-center">{{$i}}</td>
                                <td  style="text-align: left;">{{(int)$result->ITEMREFNUMBER}}</td>
                                <td  style="text-align: left;">{{$result->FAMILYGROUPNAME}}</td>
                                <td  style="text-align: left;">{{$result->ITEMNAME}}</td>
                                <td  class="text-center">{{number_format($qty)}}</td>
                                <td style="text-align: right;">{{number_format($price)}}</td>
                                <td style="text-align: right;">IDR {{number_format($totalprice)}}</td>
                                  </tr>
                              @endforeach
                              @php
                              $totalwithtax=$subtotal+$totaltax;
                              @endphp
                              <tr style="background: #FFF;color:#000;">
                                <td colspan="6"style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">TOTAL</td>
                                <!-- <td style="text-align: right;">{{number_format($subtotal)}}</td>
                                <td style="text-align: right;">{{number_format($totaltax)}}</td>
                                <td style="text-align: right;">{{number_format($data['summary']['total_service'])}}</td> -->
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;	"> {{number_format($subtotal)}}</td>
                              </tr>
                              <tr style="background: #FFF;color:#000;">
                                <td colspan="6"style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">SERVICE</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">{{number_format($data['summary']['total_service'])}}</td> 
                              </tr>
                              <tr style="background: #FFF;color:#000;">
                                <td colspan="6"style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">TAX</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">{{number_format($data['summary']['total_tax'])}}</td> 
                              </tr>
                              <tr style="background: #FFF;color:#000;">
                                <td colspan="6"style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">DISCOUNT</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">{{number_format($data['summary']['total_discount'])}}</td>
                              </tr>
                              <tr style="background: #FFF;color:#000;">
                                <td colspan="6"style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">GRAND TOTAL</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;	">{{number_format($data['summary']['grand_total'])}}</td>
                              </tr>

                             
                             
                             
                              
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