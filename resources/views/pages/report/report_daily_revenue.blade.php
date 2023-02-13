@extends('layouts.default')

@section('title', 'Daily Revenue Report')

@section('styles')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<style type="text/css">
th {
  background: white;
  position: sticky;
  position: -webkit-sticky;
  top: -0.1px; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgb(0 0 0 / 7%);
  font-size: 12px !important;

}
tr.pivot th
{
    table-layout: fixed;
    top: 34px;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li> 
      <li class="breadcrumb-item"><a href="/folio">Report</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Report Daily Room Villa</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card"> 
      {{-- <div class="card-body main-wrapper pb-0 bg-white" id="header">
            <div class="px-0">
                <div class="d-flex justify-content-between mb-3 border-bottom">
                    <h4 class="card-title mx-auto text-center">
                        <img src="{{ url('/image/ayana_logo.png')}}" style="height:100px;width:auto;margin:10px auto;display:table;">
                        <span>Room Villa Revenue</span>
                    </h4>
                </div>
            </div>
      </div> --}}
      <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
       <!-- REVENUE STATISTIC VILLA -->
        <div class="form-group">
          <div class="form-group mb-3">
            <div class="row">
              <div class="title-header col-9">
                <h2> AYANA RESORT & SPA BALI </h2>
                <h3> Daily Revenue Report </h3>
                <h5> Revenue Date : {{ date("d-M-y", strtotime($date_to_lookup)) }} </h5>
              </div>
              <div class="pt-3 date-range-wrapper col-3">
                <form method="GET" action="">
                  <div class="wrapper-form">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Pick a date</small>
                      </div>
                      <input disabled type="text" class="form-control datepicker" name="date" id="datepicker" value="{{ date('Y-m-d', strtotime($date_to_lookup)) }}">
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="table-responsive form-group">
             <table class="table table-bordered" cellspacing="0">
               <thead>
                 <th class="bg-secondary text-white" style="width: 20%">CATEGORY</th>
                 <th class="bg-secondary text-white">TODAY</th>
                 <th class="bg-secondary text-white">MTD ACTUAL</th>
                 <th class="bg-secondary text-white" style="width: 15%">FORECAST MTD</th>
                 <th class="bg-secondary text-white">%</th>
                 <th class="bg-secondary text-white" style="width: 15%">BUDGET MTD</th>
                 <th class="bg-secondary text-white">%</th>
                 <th class="bg-secondary text-white">LAST YEAR MTD</th>
                 <th class="bg-secondary text-white">%</th>
               </thead>
               <tbody>
                 <tr>
                    <td class="text-left">Bookkeeping Rate</td>
                    <td class="text-right">@if(isset($booking_rate) && count($booking_rate)) {{ number_format($booking_rate[0]->EXCHANGE_RATE_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($booking_rate) && count($booking_rate)) {{ number_format($booking_rate[0]->MTD_EXCHANGE_RATE, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right px-0 py-0">-</td>
                    <td>-</td>
                    <td class="text-right px-0 py-0">
                      <div style="position: relative;">
                        <input type="text" name="budget_mtd" disabled class="form-control define-input" placeholder="Input Budget..">
                        <div style="position: absolute; top:5px;right:2px">
                          <a class="btn btn-success pl-2 pr-2 ml-1 mr-1 py-1 btn-save-budget"><i class="mdi mdi-check text-white"></i></a>
                        </div>
                      </div>  
                    </td>
                    <td>-</td>
                    <td class="text-right">@if(isset($booking_rate) && count($booking_rate)) {{ number_format($booking_rate[0]->EXCHANGE_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Room Available</td>
                    <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->ROOM_AVAILABLE, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->MTD_ROOM_AVAILABLE, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->MTD_ROOM_AVAILABLE, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Resort Rooms Sold</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->RESORT_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_RESORT_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_LAST_YEAR_RESORT_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Villa Rooms Sold</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_LAST_YEAR_VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right">TOTAL ROOMS SOLD</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->RESORT_ROOM_SOLD + $resort_room_sold[0]->VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_RESORT_ROOM_SOLD + $resort_room_sold[0]->MTD_VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->MTD_LAST_YEAR_RESORT_ROOM_SOLD + $resort_room_sold[0]->MTD_LAST_YEAR_VILLA_ROOM_SOLD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <!-- END TOTAL ROOMS -->
                 <tr>
                    <td class="text-left">House Use Rooms 2</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOM, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOM_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOM_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Total Room Occupancy</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_OCCUPANCY_ROOM, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_OCCUPANCY_ROOM_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_OCCUPANCY_ROOM_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Group Rooms Sold</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">0</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Number Of Guest</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUESTS, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUESTS_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUESTS_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Room Occupancy (%)</td>
                    <td class="text-right">
                        @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available))
                          @if((int)$room_available[0]->ROOM_AVAILABLE != 0) 
                          {{ number_format(((int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM / (int)$room_available[0]->ROOM_AVAILABLE) * 100, 2, '.',',').'%' }}
                          @else 
                          {{ 0 }} 
                          @endif
                        @else 
                          -  
                        @endif
                    </td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available)) 
                          @if((int)$room_available[0]->MTD_ROOM_AVAILABLE != 0) 
                          {{ number_format(((int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_MTD / (int)$room_available[0]->MTD_ROOM_AVAILABLE) * 100, 2, '.',',').'%' }}
                          @else 
                          {{ 0 }} 
                          @endif
                        @else 
                          -  
                        @endif
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available)) 
                          @if((int)$room_available[0]->MTD_ROOM_AVAILABLE != 0) 
                          {{ number_format(((int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_LAST_YEAR_MTD / (int)$room_available[0]->MTD_ROOM_AVAILABLE) * 100, 2, '.',',').'%' }}
                          @else 
                          {{ 0 }} 
                          @endif
                        @else 
                          -  
                        @endif
                    </td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Double Occupancy (%)</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_OCCUPANCY_ROOM != 0) 
                        {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUESTS - (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM) / (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM) * 100, 2, '.',',')."%" }} 
                      @else 
                        {{ 0 }} 
                      @endif
                    </td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_OCCUPANCY_ROOM_MTD != 0) 
                        {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUESTS_MTD - (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_MTD) / (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_MTD) * 100, 2, '.',',')."%" }} 
                      @else 
                        {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_OCCUPANCY_ROOM_LAST_YEAR_MTD != 0) 
                        {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUESTS_LAST_YEAR_MTD - (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_LAST_YEAR_MTD) / (int)$occupancy[0]->TOTAL_OCCUPANCY_ROOM_LAST_YEAR_MTD) * 100, 2, '.',',')."%" }} 
                      @else 
                        {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                 </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
             <table class="table table-bordered" cellspacing="0">
               <thead>
                 <th class="bg-secondary text-white" style="width: 20%">AVERAGE DAILY RATE</th>
                 <th class="bg-secondary text-white">TODAY</th>
                 <th class="bg-secondary text-white">MTD ACTUAL</th>
                 <th class="bg-secondary text-white" style="width: 15%">FORECAST MTD</th>
                 <th class="bg-secondary text-white">%</th>
                 <th class="bg-secondary text-white" style="width: 15%">BUDGET MTD</th>
                 <th class="bg-secondary text-white">%</th>
                 <th class="bg-secondary text-white">LAST YEAR MTD</th>
                 <th class="bg-secondary text-white">%</th>
               </thead>
               <tbody>
                 @php
                  $total_adr_now = 0;
                  $total_adr_mtd = 0;
                  $total_adr_last_year = 0;
                 @endphp
                 <tr>
                    <td class="text-left">ADR RESORT</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->RESORT_ROOM_SOLD != 0)
                        @php
                        $total_adr_now += (int)$occupancy[0]->ROOM_REVENUE / (int)$resort_room_sold[0]->RESORT_ROOM_SOLD; 
                        @endphp 
                        {{ number_format((int)$occupancy[0]->ROOM_REVENUE / (int)$resort_room_sold[0]->RESORT_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->MTD_RESORT_ROOM_SOLD != 0)
                        @php
                        $total_adr_mtd += (int)$occupancy[0]->ROOM_REVENUE_MTD / (int)$resort_room_sold[0]->MTD_RESORT_ROOM_SOLD;
                        @endphp
                        {{ number_format((int)$occupancy[0]->ROOM_REVENUE_MTD / (int)$resort_room_sold[0]->MTD_RESORT_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">
                       @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->MTD_LAST_YEAR_RESORT_ROOM_SOLD != 0)
                        @php
                        $total_adr_last_year += (int)$occupancy[0]->ROOM_REVENUE_LAST_YEAR_MTD / (int)$resort_room_sold[0]->MTD_LAST_YEAR_RESORT_ROOM_SOLD;
                        @endphp
                        {{ number_format((int)$occupancy[0]->ROOM_REVENUE_LAST_YEAR_MTD / (int)$resort_room_sold[0]->MTD_LAST_YEAR_RESORT_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">ADR VILLA</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->VILLA_ROOM_SOLD != 0)
                        @php
                        $total_adr_now += (int)$occupancy[0]->VILLA_REVENUE / (int)$resort_room_sold[0]->VILLA_ROOM_SOLD;
                        @endphp 
                        {{ number_format((int)$occupancy[0]->VILLA_REVENUE / (int)$resort_room_sold[0]->VILLA_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->MTD_VILLA_ROOM_SOLD != 0)
                        @php
                        $total_adr_mtd += (int)$occupancy[0]->VILLA_REVENUE_MTD / (int)$resort_room_sold[0]->MTD_VILLA_ROOM_SOLD;
                        @endphp
                        {{ number_format((int)$occupancy[0]->VILLA_REVENUE_MTD / (int)$resort_room_sold[0]->MTD_VILLA_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">
                      @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->MTD_LAST_YEAR_VILLA_ROOM_SOLD != 0)
                        @php
                        $total_adr_last_year += (int)$occupancy[0]->VILLA_REVENUE_LAST_YEAR_MTD / (int)$resort_room_sold[0]->MTD_LAST_YEAR_VILLA_ROOM_SOLD;
                        @endphp
                        {{ number_format((int)$occupancy[0]->VILLA_REVENUE_LAST_YEAR_MTD / (int)$resort_room_sold[0]->MTD_LAST_YEAR_VILLA_ROOM_SOLD, 0, '.',',') }} 
                      @else 
                      {{ 0 }} 
                      @endif
                    </td>
                    <td>-</td>
                 </tr>
                 <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right">TOTAL ADR</td>
                    <td class="text-right">{{ number_format($total_adr_now, 0, '.',',') }}</td>
                    <td class="text-right">{{ number_format($total_adr_mtd, 0, '.',',') }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">{{ number_format($total_adr_last_year, 0, '.',',') }}</td>
                    <td>-</td>
                 </tr>


              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                 <!-- END ADR -->
                 <tr>
                   <th class="bg-secondary text-white" style="width: 20%">ROOM STATISTICS</th>
                   <th class="bg-secondary text-white">TODAY</th>
                   <th class="bg-secondary text-white">MTD ACTUAL</th>
                   <th class="bg-secondary text-white">FORECAST MTD</th>
                   <th class="bg-secondary text-white">%</th>
                   <th class="bg-secondary text-white">BUDGET MTD</th>
                   <th class="bg-secondary text-white">%</th>
                   <th class="bg-secondary text-white">LAST YEAR MTD</th>
                   <th class="bg-secondary text-white">%</th>
                  </tr>
              </thead>
              <tbody>
                 <tr>
                    <td class="text-left">Resort Room Revenue</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">Villa Revenue</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right">TOTAL ROOM REVENUE</td>
                    <td class="text-right">-</td>
                    <td class="text-right">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td class="text-left">No Show / Other</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                 </tr>
                 <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right">TOTAL ROOMS</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                 </tr>
              </tbody>
            </table>
          </div>
          <!-- END ROOM STATISTIC -->

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" colspan="2" style="width: 20%">F&B STATISTICS</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                @php 
                  $total_now = 0;
                  $total_mtd = 0;
                  $total_last_year = 0;
                @endphp
                 <tr>
                    <td class="text-left" rowspan="2">Sami Sami</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                  @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->SAMI_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->SAMI_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->SAMI_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Sami Sami Retail</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_RETAIL_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_RETAIL_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SAMI_RETAIL_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_RETAIL_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_RETAIL_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SAMI_RETAIL_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->SAMI_RETAIL_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->SAMI_RETAIL_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->SAMI_RETAIL_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">The Padi</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->PADI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->PADI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->PADI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->PADI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->PADI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->PADI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->PADI_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->PADI_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->PADI_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Damar Terrace</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAMAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAMAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAMAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAMAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAMAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAMAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->DAMAR_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->DAMAR_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->DAMAR_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Tsujiri</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->TSUJIRI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->TSUJIRI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->TSUJIRI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->TSUJIRI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->TSUJIRI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->TSUJIRI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->TSUJIRI_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->TSUJIRI_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->TSUJIRI_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Wedding Chapel</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->WEDDING_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->WEDDING_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->WEDDING_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->WEDDING_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->WEDDING_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->WEDDING_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->WEDDING_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->WEDDING_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->WEDDING_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Dava</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAVA_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAVA_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->DAVA_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAVA_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAVA_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->DAVA_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->DAVA_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->DAVA_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->DAVA_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Martini Bar</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->MARTINI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->MARTINI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->MARTINI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->MARTINI_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->MARTINI_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->MARTINI_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->MARTINI_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->MARTINI_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->MARTINI_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">H2O</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->H2O_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->H2O_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->H2O_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->H2O_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->H2O_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->H2O_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->H2O_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->H2O_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->H2O_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Rock Bar</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->ROCK_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->ROCK_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->ROCK_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->ROCK_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->ROCK_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->ROCK_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->ROCK_BAR_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->ROCK_BAR_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->ROCK_BAR_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">C-Bar</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->C_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->C_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->C_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->C_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->C_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->C_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->C_BAR_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->C_BAR_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->C_BAR_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Spa Bar</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SPA_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SPA_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->SPA_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SPA_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SPA_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->SPA_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->SPA_BAR_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->SPA_BAR_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->SPA_BAR_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Kubu</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KUBU_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KUBU_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KUBU_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KUBU_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KUBU_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KUBU_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->KUBU_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->KUBU_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->KUBU_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Kisik</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KISIK_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KISIK_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->KISIK_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KISIK_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KISIK_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->KISIK_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->KISIK_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->KISIK_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->KISIK_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Pool Deck</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->POOL_DECK_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->POOL_DECK_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->POOL_DECK_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->POOL_DECK_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->POOL_DECK_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->POOL_DECK_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->KISIK_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->KISIK_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->KISIK_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Lalita</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->LALITA_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->LALITA_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->LALITA_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->LALITA_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->LALITA_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->LALITA_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->LALITA_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->LALITA_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->LALITA_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">In Room Dining</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->IN_ROOM_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->IN_ROOM_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->IN_ROOM_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->IN_ROOM_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->IN_ROOM_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->IN_ROOM_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->IN_ROOM_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->IN_ROOM_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->IN_ROOM_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Honor Bar</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->HONOR_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->HONOR_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->HONOR_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->HONOR_BAR_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->HONOR_BAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->HONOR_BAR_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->HONOR_BAR_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->HONOR_BAR_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->HONOR_BAR_LAST_YEAR_MTD;
                    }
                  @endphp

                 <tr>
                    <td class="text-left" rowspan="2">Banquet</td>
                    <td>Food</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->BANQUET_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->BANQUET_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['FOOD'])) {{ number_format($revenue_fnb['FOOD']->BANQUET_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 <tr>
                    <td>Beverage</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->BANQUET_NOW, 0, '.',',') }} @else - @endif</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->BANQUET_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['BEVERAGE'])) {{ number_format($revenue_fnb['BEVERAGE']->BANQUET_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                    <td>-</td>
                 </tr>
                 @php 
                    if(isset($revenue_fnb['TOTAL']) && $revenue_fnb['TOTAL']){
                      $total_now +=  $revenue_fnb['TOTAL']->BANQUET_NOW;
                      $total_mtd += $revenue_fnb['TOTAL']->BANQUET_MTD;
                      $total_last_year += $revenue_fnb['TOTAL']->BANQUET_LAST_YEAR_MTD;
                    }
                  @endphp
                 <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right" colspan="2">TOTAL F&B</td>
                    <td class="text-right">{{ number_format($total_now, 0, '.',',') }}</td>
                    <td class="text-right">{{ number_format($total_mtd, 0, '.',',') }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="text-right">{{ number_format($total_last_year, 0, '.',',') }}</td>
                    <td>-</td>
                 </tr>
                 <!-- START FNB OTHERS -->
                 <tr>
                  <td class="text-left" colspan="2">Other / Public Room</td>
                  <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['F&B OTHERS'])) {{ number_format($revenue_fnb['F&B OTHERS']->FNB_OTHERS_NOW, 0, '.',',') }} @else - @endif</td>
                  <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['F&B OTHERS'])) {{ number_format($revenue_fnb['F&B OTHERS']->FNB_OTHERS_MTD, 0, '.',',') }} @else - @endif</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td class="text-right">@if(isset($revenue_fnb) && count($revenue_fnb) && isset($revenue_fnb['F&B OTHERS'])) {{ number_format($revenue_fnb['F&B OTHERS']->FNB_OTHERS_LAST_YEAR_MTD, 0, '.',',') }} @else - @endif</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                    <td class="text-right" colspan="2">TOTAL F&B OTHERS</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                 </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">TELEPHONE</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">Local</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Long Distance</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL TELEPHONE</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">RETAIL</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">Rock Bar Retail</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Retail</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Villa Retail</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Spa Retail</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL RETAIL</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">RECREATION & SPA</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">Recreation</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Spa</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL RECREATION & SPA</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">GARAGE</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">Garage</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Transportation</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL GARAGE</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">INTERNET</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">Internet</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL INTERNET</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-responsive form-group">
            <table class="table table-bordered" cellspacing="0">
              <thead>
                <tr>
                  <th class="bg-secondary text-white" style="width: 20%">RENT / OTHER</th>
                  <th class="bg-secondary text-white">TODAY</th>
                  <th class="bg-secondary text-white">MTD ACTUAL</th>
                  <th class="bg-secondary text-white">FORECAST MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">BUDGET MTD</th>
                  <th class="bg-secondary text-white">%</th>
                  <th class="bg-secondary text-white">LAST YEAR MTD</th>
                  <th class="bg-secondary text-white">%</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">ROI-BcAyana kidsFLower</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">ROI-No Tax / Service</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Laundry / Valet</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td class="text-left">Other Cost</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec;color:#000;font-weight:bold;">
                  <td class="text-right">TOTAL RENT / OTHER</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
              </tbody>

              <tbody>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">TOTAL REVENUE</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">OTHER INCOME</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">GRAND TOTAL REVENUE</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">TOTAL DDR</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">TOTAL MGR</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                </tr>
                <tr style="background: #ececec; color:#000; font-weight:bold;">
                  <td class="text-right">DIFFERENT</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
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
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.0.2/cleave.min.js" integrity="sha512-SvgzybymTn9KvnNGu0HxXiGoNeOi0TTK7viiG0EGn2Qbeu/NFi3JdWrJs2JHiGA1Lph+dxiDv5F9gDlcgBzjfA==" crossorigin="anonymous"></script>
<script>
   $(document).ready(function() {
        $('#datepicker').datepicker({
          dateFormat: "yy-mm-dd",
          showWeek: true,
          changeYear: true,
          showButtonPanel: true,
          maxDate: new Date(),
          onSelect : function(text, obj){
            window.location.href="{{url()->current()}}?business_date="+text;
          }
        });
        $('#datepicker, .define-input').prop('disabled', false);

        var number_only = new Cleave('.define-input', {
          numeral: true,
          stripLeadingZeroes: true
        });
    });

   function allowNumberOnly(value){
    var regex = /^[0-9]*$/;
    return regex.test(value);
   }

   $(document).on('click', '.btn-save-budget', function(){
     var button = this;
     $(this).html('<i class="mdi mdi-rotate-right mdi-spin text-white"></i>');
     setTimeout(function(){
      $(button).html('<i class="mdi mdi-check text-white"></i>');
     },2500)
   })

  </script>
@endsection

