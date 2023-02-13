@extends('layouts.default')
@section('title', 'Dashboard')
@section('custom_source_css')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
@endsection
@section('styles')
<style>
    #food-tbody,
    #beverage-tbody {
      transition: all .5s ease-in-out;
    }
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
   table{
   border: 1px solid #ddd !Important;
   }
   #content-table thead tr th,
   .datatable-able thead tr th {
   text-align: center;
   outline: 1px solid #ddd;
   font-size: 12px !important;
   }
   #content-table thead tr th b {
   font-size: 13px !important;
   }
   #content-table tr td{
   text-align: left;
   outline: 1px solid #ddd;
   font-size:11px !important;
   }
   .text-left{
   text-align: left;
   }
   .text-right{
   text-align: right;
   }
   .divider{
   border-bottom:#6c7293 2px dotted;display:block;clear:both;width:100%;padding:10px 0px;
   }
   .selectionTab{
   position: -webkit-sticky; /* Safari */
   position: sticky;
   }
   /* FLOATING SIDEBAR */
   #ulStatic {
   clear: both;
   position: fixed;
   display: block;
   list-style-type: none;
   right: -3.2em;
   top: 25%;
   -webkit-animation: slideright 1s forwards;
   -webkit-animation-delay: 1.5s;
   animation: slideright 1s forwards;
   animation-delay: 1.5s;
   z-index: 999;
   }
   @keyframes slideright {
   100% {
   right: 0;
   }
   }
   #ulStatic li {
   margin-bottom: 40px;
   margin-right: -20px;
   padding: 15px;
   -webkit-transform: rotate(270deg);
   -moz-transform: rotate(270deg);
   -o-transform: rotate(270deg);
   background-color: rgba(30, 45, 127, 0.75);
   border-top-left-radius: 10px;
   border-top-right-radius: 10px;
   -webkit-transition: width .5s, background-color .1s;
   transition: width .5s, background-color .1s;

   }
   #ulStatic li a {
   font-size: 19px;
   text-transform: none;
   text-align: center;
   text-decoration: none;
   color: #ebebeb;

   }
   #ulStatic li:hover {
   background-color: rgba(30, 45, 127, 1);
   color: #fff;
   }
   .totalColumn{
       text-align: right;background: #ececec;color:#000;font-weight:bold;
   }
   .fl-scrolls {
    bottom: 0;
    height: 15px;
    overflow: auto;
    position: fixed;
    background: #fff;
    z-index: 3;
   }
   .fl-scrolls div {
    height: 1px;
    overflow: hidden;
   }
   .fl-scrolls-hidden {
    bottom: 9999px;
   }
   .abs-search{
    display: block;
    position: absolute;
    z-index: 3;
    right: 30px;
    top: 40px;
   }
   div.dataTables_wrapper div.dataTables_info {
    float: left;
   }
   /*.tableFixHead { overflow: auto; max-height: 400px; }*/
   .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
   thead tr.first th, thead tr.first td,
   thead tr.second th, thead tr.second td {
    background: white;
    position: sticky;
    position: -webkit-sticky;
    top: -0.1px; /* Don't forget this, required for the stickiness */
    font-size: 12px !important;
    z-index: 4;
   }
   table tbody tr.fixed td.header,
   table tbody td.period {
    position: sticky;
    left: 1px;
    background: white;
    z-index: 2;
    /*box-shadow: inset 0px -2px 18px 0 #00000012*/
    box-shadow: inset 0px -2px 18px 0 #0000000d;
  }
  table thead tr.fixed th.header,
  table tbody th.period {
    position: sticky;
    left: 1px;
    background: white;
    z-index: 2;
    /*box-shadow: inset 0px -2px 18px 0 #00000012*/
    box-shadow: inset 0px -2px 18px 0 #0000000d;
  }
  table tbody td.period.header,
  table thead th.period.header {
    left: 150px !important;
  }
  table tbody td.total {
    background: #eaeaea !important;
  }
  #content-table tr td.dataTables_empty {
    text-align: center;
  }
  .sticky {
    position: fixed;
    left: auto;
    box-shadow: 0px 4px 8px 0px #0000001a;
    z-index: 6;
    width: calc(100% - 313px);
    padding-bottom: 1.5em !important;
  }
  .no-wrap-th th{
    padding-left: 3em;
    padding-right: 3em;
  }
  #ui-datepicker-div{
    z-index: 6 !important;
  }
</style>
@endsection
@section('content')
<ul id="ulStatic" style="display: none;">
    <li><a href="javascript:void(0);"> Resort</a></li>
    <li><a href="javascript:void(0);"> Reservation </a></li>
    <li><a href="javascript:void(0);">Cancellation </a></li>
    <li><a href="javascript:void(0);"> Operation</a></li>
</ul>
<nav aria-label="breadcrumb">
   <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Dashboard</span></li>
   </ol>
</nav>
<div class="row flex-grow" id="main-header">
   <div class="col-sm-12 stretch-card" style="position: relative;">
      <div class="overlay">
         <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
      </div>
      <div class="card">
         <div class="card-body pb-0 bg-white" id="header">
            <div class="row">
              <div class="px-0 col-md-6 text-left">
                 <!-- <h2> Ayana Hotel Management Dashboard</h2> -->
                 <h2> Group Hotel Dashboard</h2>
                 <h3>
                  @php
                  if(!empty($data['resort'])){
                      $filter=$data['resort'];
                  }else{
                      // $filter=$data['company_code'];
                      $filter='';

                  }
                      switch($filter){
                          case 'KMS':
                          case 'KMS1':
                              echo "KMS1 - Ayana Rimba Bali";
                              $resort="KMS1";
                          break;
                          case 'PPC':
                          case 'PPC1':
                              echo "PPC1 - Ayana Komodo Waecicu Beach";
                              $resort="PPC1";
                          break;
                          case 'PNB':
                          case 'PNB1':
                              echo "PNB1 - Ayana Cruises";
                              $resort="PNB1";
                          break;
                          case 'PAD':
                          case 'PAD1':
                              echo "PAD1 - Ayana Midplaza Jakarta";
                              $resort="PAD1";
                          break;
                          case 'WKK':
                          case 'WKK1':
                              echo "WKK1 - Delonix Hotel";
                              $resort="WKK1";
                          break;
                          default:
                              echo "All Resort";
                              $resort="All";
                      }
                  @endphp
                  </h3>
                 <h5> Selected Date : {{ date('Y-m-d', strtotime(isset($data['date_fnb']) ? $data['date_fnb'] : date('Y-m-d H:i:s')) )}}</h5>
              </div>
              <div class="px-0 col-md-6 text-right">
                  <div class="d-block">
                      <div class="dropdown d-inline-block" style="margin:0 2.5px;">
                        <div class="mb-1">
                              <small style="color:#000;text-align: left;">Plant</small>
                            </div>
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownResortButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          @php
                            echo $resort;
                          @endphp
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownResortButton">
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="">ALL</a>
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="KMS1">KMS1</a>
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="PPC1">PPC1</a>
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="PNB1">PNB1</a>
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="PAD1">PAD1</a>
                          <a class="dropdown-item text-center filter-resort" href="javascript:void(0)" data-href="WKK1">WKK1</a>

                          {{-- @if(isset($data['plant_hotel']) && $data['plant_hotel'])
                            @if(Session::get('permission_menu')->has('view_'.route('dashboard.summary',[], false)))
                            <a class="dropdown-item text-center" href="/dashboard">ALL</a>
                            @foreach($data['plant_hotel'] as $plant_ht)
                              <a class="dropdown-item text-center" href="?resort={{ isset($plant_ht->SAP_PLANT_ID) ? $plant_ht->SAP_PLANT_ID : '' }}">{{ isset($plant_ht->SAP_PLANT_ID) ? $plant_ht->SAP_PLANT_ID : 'No Plant Name' }}</a>
                            @endforeach
                            @endif

                            @foreach($data['plant_hotel'] as $plant_ht)
                              <a class="dropdown-item text-center" href="javascript:void(0)">{{ isset($plant_ht->SAP_PLANT_ID) ? $plant_ht->SAP_PLANT_ID : 'No Plant Name' }}</a>
                            @endforeach
                          @else
                            <a class="dropdown-item text-center" href="javascript:void(0)">No Plant Found</a>
                          @endif --}}
                        </div>
                      </div>
                      <!-- <div class="dropdown d-inline-block" style="margin:0 2.5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownReportButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          @php
                          echo (!empty($data['report']))? $data['report'] : "All Summary";
                          @endphp
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownReportButton">
                          <a class="dropdown-item text-center" href="#" onclick="changeView('all');" >All Summary</a>
                          <a class="dropdown-item text-center" href="#" onclick="changeView('resort');" >Resort Summary</a>
                          <a class="dropdown-item text-center" href="#" onclick="changeView('reservation');">Reservation Summary</a>
                          <a class="dropdown-item text-center" href="#" onclick="changeView('cancel');">Cancellation Summary</a>
                          <a class="dropdown-item text-center" href="#" onclick="changeView('other');">Other Summary</a>
                          {{-- <a class="dropdown-item text-center" href="#" onclick="changeView('property');">Property Summary</a> --}}
                          {{-- <a class="dropdown-item text-center" href="#" onclick="changeView('rental-object');">Rental Object Summary</a> --}}
                        </div>
                      </div> -->
                      <div class="d-inline-block" style="margin:0 2.5px;">
                        <form method="GET" action="">
                          <div class="d-inline-block">
                            <div class="mb-1">
                              <small style="color:#000;text-align: left;">Choose Revenue date</small>
                            </div>
                            <input disabled type="text" class="form-control datepicker" name="date" id="datepicker" value="{{ date('Y-m-d', strtotime(isset($data['date_fnb']) ? $data['date_fnb'] : date('Y-m-d') )) }}" style="min-width: 220px">
                          </div>
                        </form>
                      </div>
                  </div>
              </div>
            </div>
         </div>

         <!-- Exclude semua summary dashboard jika filternya PNB1 -->
         @if(strtoupper($filter) != 'PNB1')
         <section id="resort">
            <div class="divider"></div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                <h2>14 Days Room Forecast Summary</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_forecast']) ? date('d F, Y', strtotime($data['date_forecast'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                  <div class="table-container-h table-responsive table-floating-overflow" style="max-height: 500px">
                      <table class="table table-border" id="content-table">
                          <thead style="white-space: nowrap;" class="no-wrap-th">
                              <tr class="fixed first">
                                <th class="bg-secondary text-white header" style="z-index: 5"><b>Resort / Subresort</b></th>
                                <th class="bg-secondary text-white period header" style="z-index: 5"><b>Description</b></th>
                                @php
                                  $tanggal_pertama = date('d M Y',strtotime($data['date_forecast']));
                                  $hari_pertama_echo = date('l',strtotime($tanggal_pertama));
                                @endphp
                                <!-- Hari pertama -->
                                <th class="bg-secondary text-white">
                                  <b>
                                    {{$tanggal_pertama}} <br> ({{$hari_pertama_echo}})
                                  </b>
                                </th>
                                <!-- Hari selanjutnya -->
                                @for ($i=1;$i<=13;$i++)
                                  @php
                                      $tanggal_echo = date('d M Y',strtotime("+ $i day", strtotime ($tanggal_pertama) ));
                                      $hari_echo = date('l',strtotime($tanggal_echo));
                                  @endphp
                                  <th class="bg-secondary text-white" ><b>{{$tanggal_echo}} <br> ({{$hari_echo}})</b></th>
                                @endfor
                              </tr>
                          </thead>
                          {{--<tbody>
                              @if(isset($data['forecast_7_days_rooms']) && $data['forecast_7_days_rooms'])
                                @foreach($data['forecast_7_days_rooms'] as $fc_rooms)
                                  <tr class="fixed">
                                    <td class="text-left header"><b>{{ $fc_rooms->RoomCategory }}</b></td>
                                    @if(strtolower($fc_rooms->RoomCategory) == 'occupancy %')
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0, 2)."%" ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0, 2)."%" ?? '-' }}</td>

                                    @else
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0 ) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0) ?? '-' }}</td>
                                    <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0) ?? '-' }}</td>

                                    @endif
                                  </tr>
                                @endforeach
                              @else
                                <tr>
                                  <td class="text-center" colspan="15">No Data</td>
                                </tr>
                              @endif
                          </tbody>--}}

                          <tbody>
                          @if(isset($data['forecast_7_days_rooms']) && $data['forecast_7_days_rooms'])
                              <!-- Loop Company Code -->
                              @foreach($data['forecast_7_days_rooms'] as $company_code => $resort)
                                <tr>
                                  <td colspan="16" class="bg-black text-white text-center"><b>{{ $company_code }}</b></td>
                                </tr>
                                @if(isset($resort['DATA_SUBRESORT']) && $resort['DATA_SUBRESORT'])
                                  @foreach($resort['DATA_SUBRESORT'] as $frcst_resort => $frc_rooms)
                                    @php
                                      $loop_now_forecast = 0;
                                    @endphp

                                      @foreach($frc_rooms as $fc_rooms)
                                        <tr class="fixed">
                                          @if($loop_now_forecast == 0)
                                            <td class="text-center header" style="vertical-align: middle;" rowspan="{{ count($frc_rooms) }}">
                                            <b>{{ $frcst_resort }}</b>
                                            </td>
                                          @endif

                                          <td class="text-left header period"><b>{{ $fc_rooms->RoomCategory }}</b></td>
                                          @if(strtolower($fc_rooms->RoomCategory) == 'occupancy %')
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0, 2)."%" ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0, 2)."%" ?? '-' }}</td>

                                          @else
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0 ) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0) ?? '-' }}</td>
                                          <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0) ?? '-' }}</td>

                                          @endif
                                        </tr>

                                        @php
                                          $loop_now_forecast++;
                                        @endphp
                                      @endforeach
                                  @endforeach

                                  @if(isset($resort['DATA_TOTAL']) && $resort['DATA_TOTAL'])
                                    @php
                                      $loop_now_forecast = 0;
                                    @endphp

                                    @foreach($resort['DATA_TOTAL'] as $fc_total)
                                      <tr class="fixed total" style="background: #eaeaea !important">
                                        @if($loop_now_forecast == 0)
                                          <td class="text-center header total" style="vertical-align: middle;" rowspan="{{ count($resort['DATA_TOTAL']) }}">
                                          <b>TOTAL ALL</b>
                                          </td>
                                        @endif

                                        <td class="text-left header period total"><b>{{ $fc_total->RoomCategory }}</b></td>
                                        @if(strtolower($fc_total->RoomCategory) == 'occupancy %')
                                        <td class="text-right">{{ number_format(isset($fc_total->Day1) ? $fc_total->Day1 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day2) ? $fc_total->Day2 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day3) ? $fc_total->Day3 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day4) ? $fc_total->Day4 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day5) ? $fc_total->Day5 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day6) ? $fc_total->Day6 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day7) ? $fc_total->Day7 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day8) ? $fc_total->Day8 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day9) ? $fc_total->Day9 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day10) ? $fc_total->Day10 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day11) ? $fc_total->Day11 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day12) ? $fc_total->Day12 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day13) ? $fc_total->Day13 : 0, 2)."%" ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day14) ? $fc_total->Day14 : 0, 2)."%" ?? '-' }}</td>

                                        @else
                                        <td class="text-right">{{ number_format(isset($fc_total->Day1) ? $fc_total->Day1 : 0 ) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day2) ? $fc_total->Day2 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day3) ? $fc_total->Day3 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day4) ? $fc_total->Day4 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day5) ? $fc_total->Day5 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day6) ? $fc_total->Day6 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day7) ? $fc_total->Day7 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day8) ? $fc_total->Day8 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day9) ? $fc_total->Day9 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day10) ? $fc_total->Day10 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day11) ? $fc_total->Day11 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day12) ? $fc_total->Day12 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day13) ? $fc_total->Day13 : 0) ?? '-' }}</td>
                                        <td class="text-right">{{ number_format(isset($fc_total->Day14) ? $fc_total->Day14 : 0) ?? '-' }}</td>

                                        @endif
                                      </tr>

                                      @php
                                        $loop_now_forecast++;
                                      @endphp
                                    @endforeach

                                  @endif
                                @else
                                  <tr>
                                    <td colspan="16" class="bg-black text-white text-center">No Data Available</td>
                                  </tr>
                                @endif

                              @endforeach
                          @endif
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                <h2>Resort Summary</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('Y-m-d') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive table-floating-overflow" style="max-height: 500px">
                    <table class="table table-border" id="content-table" style="width: 1500px;">
                        <thead>
                            <tr class="first fixed">
                              <th style="width: 10%;z-index: 5" class="bg-secondary text-white text-left header"><b>Resort / Subresort</b></th>
                              <th class="bg-secondary text-white text-left header period" style="z-index: 5"><b>Period</b></th>
                              <th class="bg-secondary text-white text-left"><b>Total</b> <br>Rooms In Hotel</th>
                              <th class="bg-secondary text-white text-left"><b>Total</b> <br>Room To Sell </th>
                              <th class="bg-secondary text-white text-left"><b>Room</b> <br>Total Occupancy</th>
                              <th class="bg-secondary text-white text-left"><b>Room</b> <br>Occupancy (%)</th>
                              <th class="bg-secondary text-white text-left"><b>Room</b> <br>Total Revenue</th>
                              <th class="bg-secondary text-white text-left"><b>Room</b> <br>ADR</th>
                              <th class="bg-secondary text-white text-left"><b>Room</b> <br>RevPar</th>
                              <th class="bg-secondary text-white text-left"><b>F&amp;B</b> <br>Total Guest</th>
                              <th class="bg-secondary text-white text-left"><b>F&amp;B</b> <br>Total Revenue</th>
                              <th class="bg-secondary text-white text-left"><b>Spa</b> <br>Total Guest</th>
                              <th class="bg-secondary text-white text-left"><b>Spa</b> <br>Total Revenue</th>
                              <th class="bg-secondary text-white text-left"><b>Others</b> <br>Total Revenue</th>
                              <th class="bg-secondary text-white text-left"><b>Resort</b> <br>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if(isset($data['resort_summary']) && $data['resort_summary'])
                              <!-- Loop Company Code -->
                              @foreach($data['resort_summary'] as $company_code => $resort)
                                  <tr>
                                    <td colspan="15" class="bg-black text-white text-center"><b>{{ $company_code }}</b></td>
                                  </tr>
                                  <!-- Loop Resort / Property -->
                                  @if(isset($resort['PROPERTY']))
                                    @foreach($resort['PROPERTY'] as $key_resort => $per_resort)
                                      <!-- Loop TOday MTD YTD -->
                                      @php
                                        $loop_now = 0;
                                      @endphp
                                      @foreach($per_resort as $key_period => $resort_sm)
                                      <tr class="fixed">
                                        @if($loop_now == 0)
                                        <td class="text-center header" style="vertical-align: middle;" rowspan="{{ count($per_resort) }}">
                                        <b>{{ $key_resort }}</b>
                                        </td>
                                        @endif
                                        <td class="period header">
                                          @if(strtolower($key_period) == 'today')
                                          {{ isset($data['date_resort']) ? date('d M Y',strtotime($data['date_resort'])) : date('d M Y') }}
                                          @else
                                          {{ $key_period }}
                                          @endif
                                        </td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->TotalPhysicalRoom ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->TotalAvailability ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->TotalOccupancy ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->OccupancyPctg ?? 0, 2)."%" }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->TodayRevenue ?? 0)}}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->ADR ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->RevPar ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->FnBTotalGuest ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->FnBTotalRevenue ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->SpaTotalGuest ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->SpaTotalRevenue ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->OtherTotalRevenue ?? 0) }}</td>
                                        <td class="text-right">{{ number_format($resort_sm[0]->ResortTotalRevenue ?? 0) }}</td>
                                      </tr>
                                      @php
                                        $loop_now ++;
                                      @endphp
                                      @endforeach
                                      <!-- END Loop Today MTD YTD -->
                                    @endforeach
                                    <!-- END Loop Resort / Property -->
                                  <!-- CURRENT -->
                                  <tr class="fixed" style="background: #eaeaea !important">
                                    <td class="text-center header total" rowspan="3" style="vertical-align: middle;"><b>TOTAL ALL</b></td>
                                    <td class="period header total"><b>{{ isset($data['date_resort']) ? date('d M Y', strtotime($data['date_resort'])) : date('d F Y') }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_PHYSICAL']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_AVAILABLE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_OCCUPANCY']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_OCC_PCTG'], 2)."%" : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_ROOM_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_ADR']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_ADR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_REVPAR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_FNB_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_SPA_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_SPA_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_TOTAL_REVENUE']) : '-' }}</b></td>
                                  </tr>
                                  <!-- MTD -->
                                  <tr class="total" style="background-color: #eaeaea">
                                    <td class="period header total"><b>MTD</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['MTD_PHYSICAL']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_AVAILABLE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OCCUPANCY']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OCC_PCTG'],2)."%" : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_ROOM_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_ADR']) ? number_format($resort['TOTAL_SUMMARY']['MTD_ADR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['MTD_REVPAR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['MTD_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_FNB_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['MTD_SPA_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_SPA_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_TOTAL_REVENUE']) : '-' }}</b></td>
                                  </tr>
                                  <!-- YTD -->
                                  <tr class="total" style="background-color: #eaeaea">
                                    <td class="period header total"><b>YTD</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['YTD_PHYSICAL']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_AVAILABLE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OCCUPANCY']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OCC_PCTG'],2)."%" : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_ROOM_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_ADR']) ? number_format($resort['TOTAL_SUMMARY']['YTD_ADR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['YTD_REVPAR']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['YTD_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_FNB_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['YTD_SPA_GUEST']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_SPA_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                                    <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_TOTAL_REVENUE']) : '-' }}</b></td>
                                  </tr>
                                  @else
                                    <tr>
                                      <td colspan="15" class="text-center">No data</td>
                                    </tr> 
                                  @endif
                              @endforeach
                            @else
                              <tr>
                                <td class="text-center" colspan="14">No Data</td>
                              </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                <h2>7 Days Breakfast Buffet Forecast Summary</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_forecast']) ? date('d F, Y', strtotime($data['date_forecast'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive">
                    <table class="table table-border" id="content-table">
                        <thead>
                            <tr>
                                <th class="bg-black text-white" rowspan="2">Outlet</th>
                                @php
                                  $tanggal_pertama = date('d M Y', strtotime("+ 1 day",strtotime($data['date_forecast'])));
                                  $hari_pertama_echo = date('l',strtotime($tanggal_pertama));
                                @endphp
                                <!-- Hari pertama -->
                                <th class="bg-black text-white" colspan="2">
                                  {{$tanggal_pertama}} <br> ({{$hari_pertama_echo}})
                                </th>
                                <!-- Hari selanjutnya -->
                                @for ($i=1;$i<=6;$i++)
                                  @php
                                      $tanggal_echo = date('d M Y',strtotime("+ $i day", strtotime ($tanggal_pertama) ));
                                      $hari_echo = date('l',strtotime($tanggal_echo));
                                  @endphp
                                  <th class="bg-black text-white" colspan="2">{{$tanggal_echo}} <br> ({{$hari_echo}})</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($i=1;$i<=7;$i++)
                                <th class="bg-black text-white">Adult</th>
                                <th class="bg-black text-white">Children</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data['7days_breakfast']) > 0)
                                @foreach ($data['7days_breakfast'] as $forecast_bf)
                                    <tr>
                                        <td>{{ $forecast_bf->BREAKFAST_OUTLET}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY1_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY1_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY2_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY2_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY3_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY3_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY4_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY4_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY5_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY6_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY6_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY6_CHILDREN}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY7_ADULT}}</td>
                                        <td class="text-right">{{ (int) $forecast_bf->DAY7_CHILDREN}}</td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center"> No data available </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                <h2>Top 5 Most Selling F&B Items</h2>
                <div class="mt-1 mb-3">
                  @if(isset($data['fnb_last_update_daily']) && $data['fnb_last_update_daily'])
                    <h6 class="text-muted">* Last updated : {{ isset($data['fnb_last_update_daily']) ? date('d F, Y H:i:s', strtotime($data['fnb_last_update_daily'])) : date('d F, Y') }}</h6>
                  @else
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_fnb']) ? date('d F, Y', strtotime($data['date_fnb'])) : date('d F, Y') }}</h6>
                  @endif
                </div>
                <div class="table-wrapper">
                  <div class="table-container-h table-responsive">
                      <table class="table table-border" id="content-table"s>
                          <thead>
                              <tr>
                              <th class="bg-secondary text-white" style="width:5%">No</th>
                              <th class="bg-secondary text-white" style="width:5%">SAP Material ID</th>
                              <th class="bg-secondary text-white" style="width:40%">Food Name</th>
                              <th class="bg-secondary text-white" style="width:5%">Qty</th>
                              <th class="bg-secondary text-white" style="width:5%">Qty <br>MTD</th>
                              {{--<th class="bg-secondary text-white" style="width:10%">Qty Warehouse <br> (Non Recipe)</th>--}}
                              <th class="bg-secondary text-white" style="width:15%">Revenue</th>
                              <th class="bg-secondary text-white" style="width:15%">Revenue <br>MTD</th>

                              </tr>
                          </thead>
                          <tbody id="food-tbody">
                              @if(count($data['top5_food'])>0)
                                @php
                                  $i=0;
                                @endphp
                                @foreach ($data['top5_food'] as $tsf)
                                @php
                                  $i++;
                                @endphp
                                  <tr>
                                    <td class="text-center">{{$i}}</td>
                                    <td style="text-align: left;">{{ isset($tsf->SAPMATERIALCODE) ? $tsf->SAPMATERIALCODE : '-' }}</td>
                                    <td style="text-align: left;">{{ isset($tsf->MENUITEMNAME) ? $tsf->MENUITEMNAME : '-'}}</td>
                                    <td style="text-align: right;">{{ isset($tsf->TODAY_QTY) ? number_format($tsf->TODAY_QTY) : 0 }}</td>
                                    <td style="text-align: right;">{{ isset($tsf->MTD_QTY) ? number_format($tsf->MTD_QTY) : 0 }}</td>
                                    {{--<td style="text-align: right;">-</td>--}}
                                    <td style="text-align: right;">{{ isset($tsf->TODAY_REV) ? number_format($tsf->TODAY_REV) : 0}}</td>
                                    <td style="text-align: right;">{{ isset($tsf->MTD_REV) ? number_format($tsf->MTD_REV) : 0}}</td>
                                  </tr>
                                @endforeach
                              @else
                                @php
                                  $padding = number_format((float)((0.5*count($data['top5_food']))), 1);
                                  $line = number_format((float)((1.5*count($data['top5_food']))+0.13), 2);
                                  if($padding <= 0)
                                    $padding = 0.5;
                                  if($line < 1.5)
                                    $line = 1.5;
                                @endphp
                                <tr>
                                    <td style="padding-top: {{ $padding }}rem;padding-bottom: {{ $padding }}rem; line-height: {{ $line }}!important" colspan="8" class="text-center"> No data available </td>
                                </tr>
                              @endif
                          </tbody>
                      </table>
                  </div>
                  <div class="table-container-h table-responsive">
                      <table class="table table-border" id="content-table">
                          <thead>
                              <tr>
                                <th class="bg-green text-white" style="width:5%">No</th>
                                <th class="bg-green text-white" style="width:5%">SAP Material ID</th>
                                <th class="bg-green text-white" style="width:40%">Beverage Name</th>
                                <th class="bg-green text-white" style="width:5%">Qty</th>
                                <th class="bg-green text-white" style="width:5%">Qty <br>MTD</th>
                                {{--<th class="bg-green text-white" style="width:10%">Qty Warehouse <br> (Non Recipe)</th>--}}
                                <th class="bg-green text-white" style="width:15%">Revenue</th>
                                <th class="bg-green text-white" style="width:15%">Revenue <br>MTD</th>
                              </tr>
                          </thead>
                          <tbody id="beverage-tbody">
                              @if(count($data['top5_beverage'])>0)
                                @foreach ($data['top5_beverage'] as $tsb)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td style="text-align: left;">{{ isset($tsb->SAPMATERIALCODE) ? $tsb->SAPMATERIALCODE : '-' }}</td>
                                    <td style="text-align: left;">{{ isset($tsb->MENUITEMNAME) ? $tsb->MENUITEMNAME : '-'}}</td>
                                    <td style="text-align: right;">{{ isset($tsb->TODAY_QTY) ? number_format($tsb->TODAY_QTY) : 0 }}</td>
                                    <td style="text-align: right;">{{ isset($tsb->MTD_QTY) ? number_format($tsb->MTD_QTY) : 0 }}</td>
                                    {{--<td style="text-align: right;">-</td>--}}
                                    <td style="text-align: right;">{{ isset($tsb->TODAY_REV) ? number_format($tsb->TODAY_REV) : 0}}</td>
                                    <td style="text-align: right;">{{ isset($tsb->MTD_REV) ? number_format($tsb->MTD_REV) : 0}}</td>
                                </tr>
                                @endforeach
                              @else
                                @php
                                  $padding = number_format((float)((0.5*count($data['top5_food']))), 1);
                                  $line = number_format((float)((1.5*count($data['top5_food']))+0.13), 2);
                                  if($padding <= 0)
                                    $padding = 0.5;
                                  if($line < 1.5)
                                    $line = 1.5;
                                @endphp
                                <tr>
                                    <td style="padding-top: {{ $padding }}rem;padding-bottom: {{ $padding }}rem; line-height: {{ $line }}!important" colspan="8" class="text-center">No Data Available</td>
                                </tr>
                              @endif
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>
         </section>
         <section id="cancel">
            <div class="divider"></div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                <h2>VIP Guest List</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_vip_details']) ? date('d F, Y', strtotime($data['date_vip_details'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive tableFixHead">
                    <table class="table table-border datatable-vip" id="content-table">
                        <thead>
                            <tr>
                            <th class="bg-secondary text-white" style="width: 7%">Date</th>
                            <th class="bg-secondary text-white" style="width: 6%">VIP Code</th>
                            <th class="bg-secondary text-white" style="width: 15%">Full Name</th>
                            <th class="bg-secondary text-white" >Company</th>
                            <th class="bg-secondary text-white" style="width: 7%">Room No</th>
                            <th class="bg-secondary text-white" style="width: 10%">Check In</th>
                            <th class="bg-secondary text-white" style="width: 10%">Check Out</th>
                            <th class="bg-secondary text-white" >Notes</th>
                            <th class="bg-secondary text-white" >Preference</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if(isset($data['guestVIP_details']) && $data['guestVIP_details'])
                              @foreach($data['guestVIP_details'] as $vip_detail)
                                <tr>
                                  <td class="text-center">{{ date('d M Y', strtotime($vip_detail->ReportDate)) ?? '-' }}</td>
                                  <td class="text-center">{{ $vip_detail->VIPCode ?? '-' }}</td>
                                  <td class="text-left">{{ $vip_detail->FullName ?? '-' }}</td>
                                  <td class="text-left">{{ $vip_detail->Company ?? '-' }}</td>
                                  <td class="text-center">{{ $vip_detail->RoomNo ?? '-' }}</td>
                                  <td class="text-center">{{ $vip_detail->CheckIn ? date('d M Y', strtotime($vip_detail->CheckIn)) : '-' }}</td>
                                  <td class="text-center">{{ $vip_detail->CheckOut ? date('d M Y', strtotime($vip_detail->CheckOut)) : '-' }}</td>
                                  <td class="text-left">{{ $vip_detail->Notes ?? '-' }}</td>
                                  <td class="text-left">{{ $vip_detail->Preference ?? '-' }}</td>
                                </tr>
                              @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                <h2>Cancellation Resort Summary</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive">
                    <table class="table table-border" id="content-table">
                        <thead>
                            <tr>
                            <th class="bg-red text-white text-left"></th>
                            <th class="bg-red text-white text-left"><b>Room</b> <br>Total Cancellation</th>
                            <th class="bg-red text-white text-left"><b>Room</b> <br>Total Loss Revenue</th>
                            <th class="bg-red text-white text-left"><b>Resort</b> <br>Actual Room Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data['cancellation_summary']) && $data['cancellation_summary'])
                              @foreach($data['cancellation_summary'] as $cancellation_sm)
                                <tr>
                                  <td class="text-left">
                                    <strong>
                                    @php
                                      if(isset($cancellation_sm->Description) && strtolower($cancellation_sm->Description) == 'today')
                                        $desc = isset($data['date_resort']) ? date('d M, Y', strtotime($data['date_resort'])) : date('d M, Y');
                                      else 
                                        $desc = $cancellation_sm->Description;
                                    @endphp
                                    {{ $desc }}
                                    </strong>
                                  </td>
                                  <td class="text-right">{{ number_format($cancellation_sm->RoomTotalCancellation ?? 0) }}</td>
                                  <td class="text-right">{{ number_format($cancellation_sm->RoomTotalLossRevenue ?? 0) }}</td>
                                  <td class="text-right">{{ number_format($cancellation_sm->TotalRevenue ?? 0)}}</td>
                                </tr>
                              @endforeach
                            @else
                              <tr>
                                <td class="text-center" colspan="15">No Data</td>
                              </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                <h2>MTD Cancellation Details</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive table-floating-overflow tableFixHead" >
                    <table class="table table-border datatable-able" id="content-table" style="width: 2500px" >
                        <thead>
                            <tr>
                            <th class="bg-red text-white" style="width: 2%">No</th>
                            <th class="bg-red text-white" style="width: 4%">Date</th>
                            <th class="bg-red text-white" style="width: 6%">Full Name</th>
                            {{--<th class="bg-red text-white" style="width: 6.6%">Guest Country</th>--}}
                            <th class="bg-red text-white" style="width: 10%">Segment</th>
                            <th class="bg-red text-white" style="width: 3%">Room Type</th>
                            <th class="bg-red text-white" style="width: 3%">Conf No.</th>
                            <th class="bg-red text-white" style="width: 7%">C A G S <br> (Company, Agent, Group Source)</th>
                            <th class="bg-red text-white" style="width: 4%">Room Class</th>
                            <th class="bg-red text-white" style="width: 3%">Arrival Date</th>
                            <th class="bg-red text-white" style="width: 3%">Departure Date</th>
                            <th class="bg-red text-white" style="width: 2%">Room Night</th>
                            <th class="bg-red text-white" style="width: 2%">Rate Code</th>
                            <th class="bg-red text-white" style="width: 4%">Room Rate</th>
                            <th class="bg-red text-white" style="width: 3%">Cncl Code</th>
                            <th class="bg-red text-white" style="width: 17%">Cncl Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data['cancellation_mtd_details']) && $data['cancellation_mtd_details'])
                              @foreach($data['cancellation_mtd_details'] as $cl_detail)
                                <tr>
                                  <td class="text-center">{{ $loop->iteration }}</td>
                                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->ReportDate)) ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->FullName ?? '-' }}</td>
                                  {{--<td class="text-left">-</td>--}}
                                  <td class="text-left">{{ $cl_detail->Segment ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->RoomType ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->ConfirmationNo ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->CAGS ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->RoomClass ?? '-' }}</td>
                                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->ArrivalDate)) ?? '-' }}</td>
                                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->DepartureDate)) ?? '-' }}</td>
                                  <td class="text-right">{{ number_format($cl_detail->RoomNight) ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->RateCode ?? '-' }}</td>
                                  <td class="text-right">{{ number_format($cl_detail->RoomRate) ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->CxlCode ?? '-' }}</td>
                                  <td class="text-left">{{ $cl_detail->CxlReason ?? '-' }}</td>
                                </tr>
                              @endforeach
                            @else
                              <tr>
                                <td class="text-center" colspan="15">No Data</td>
                              </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
         </section>
           @if(strtolower($filter) == '' || empty($filter))
           <section id="pnb-revenue">
              <div class="divider"></div>
              <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                  <h2>PNB1 - Ayana Cruises</h2>
                  <div class="mt-1 mb-3">
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                  </div>
                  <div class="table-wrapper">
                  <div class="table-container-h table-responsive tableFixHead">
                      <table class="table table-border" id="content-table">
                          <thead>
                              <tr>
                                <th class="bg-secondary text-white">No.</th>
                                <th class="bg-secondary text-white">Cruise & Boat</th>
                                <th class="bg-secondary text-white">Revenue</th>
                                <th class="bg-secondary text-white">Revenue MTD</th>
                                <th class="bg-secondary text-white">Revenue YTD</th>
                              </tr>
                          </thead>
                          <tbody>
                            @if(isset($data['pnb1_revenue']) && $data['pnb1_revenue'])
                                @php
                                  $revenue=$revenue_MTD=$revenue_YTD=0;
                                @endphp
                                @foreach($data['pnb1_revenue'] as $pnb)
                                  <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ isset($pnb->Outlet) ? $pnb->Outlet : '-' }}</td>
                                    <td class="text-right">{{ isset($pnb->Revenue) ? number_format($pnb->Revenue) : 0 }}</td>
                                    <td class="text-right">{{ isset($pnb->Revenue_MTD) ? number_format($pnb->Revenue_MTD) : 0 }}</td>
                                    <td class="text-right">{{ isset($pnb->Revenue_YTD) ? number_format($pnb->Revenue_YTD) : 0 }}</td>
                                  </tr>

                                  @php
                                    $revenue += isset($pnb->Revenue) ? $pnb->Revenue : 0;
                                    $revenue_MTD += isset($pnb->Revenue_MTD) ? $pnb->Revenue_MTD : 0;
                                    $revenue_YTD += isset($pnb->Revenue_YTD) ? $pnb->Revenue_YTD : 0;
                                  @endphp
                                @endforeach
                            @else
                              <tr>
                                <td class="text-center" colspan="10">No Data</td>
                              </tr>
                            @endif
                          </tbody>
                          @if(isset($revenue))
                          <tfoot>
                             <tr style="background: #eaeaea !important">
                                <td class="text-center header total" colspan="2" style="vertical-align: middle;"><b>TOTAL ALL</b></td>
                                <td class="text-right"><b>{{ number_format($revenue) }}</b></td>
                                <td class="text-right"><b>{{ number_format($revenue_MTD) }}</b></td>
                                <td class="text-right"><b>{{ number_format($revenue_YTD) }}</b></td>
                              </tr>
                          </tfoot>
                          @endif
                      </table>
                  </div>
                  </div>
              </div>
            </section>
            @endif
          <!-- TARIK HANYA PNB1 JIKA FILTER PLANTNYA PNB1 -->
          @else
          <section id="pnb-revenue">
            <div class="divider"></div>
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                <h2>PNB1 - Ayana Cruises</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                <div class="table-container-h table-responsive tableFixHead">
                    <table class="table table-border" id="content-table">
                        <thead>
                            <tr>
                              <th class="bg-secondary text-white">No.</th>
                              <th class="bg-secondary text-white">Cruise & Boat</th>
                              <th class="bg-secondary text-white">Revenue</th>
                              <th class="bg-secondary text-white">Revenue MTD</th>
                              <th class="bg-secondary text-white">Revenue YTD</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if(isset($data['pnb1_revenue']) && $data['pnb1_revenue'])
                              @php
                                $revenue=$revenue_MTD=$revenue_YTD=0;
                              @endphp
                              @foreach($data['pnb1_revenue'] as $pnb)
                                <tr>
                                  <td class="text-center">{{ $loop->iteration }}</td>
                                  <td class="text-left">{{ isset($pnb->Outlet) ? $pnb->Outlet : '-' }}</td>
                                  <td class="text-right">{{ isset($pnb->Revenue) ? number_format($pnb->Revenue) : 0 }}</td>
                                  <td class="text-right">{{ isset($pnb->Revenue_MTD) ? number_format($pnb->Revenue_MTD) : 0 }}</td>
                                  <td class="text-right">{{ isset($pnb->Revenue_YTD) ? number_format($pnb->Revenue_YTD) : 0 }}</td>
                                </tr>

                                @php
                                  $revenue += isset($pnb->Revenue) ? $pnb->Revenue : 0;
                                  $revenue_MTD += isset($pnb->Revenue_MTD) ? $pnb->Revenue_MTD : 0;
                                  $revenue_YTD += isset($pnb->Revenue_YTD) ? $pnb->Revenue_YTD : 0;
                                @endphp
                              @endforeach
                          @else
                            <tr>
                              <td class="text-center" colspan="10">No Data</td>
                            </tr>
                          @endif
                        </tbody>
                        @if(isset($revenue))
                        <tfoot>
                           <tr style="background: #eaeaea !important">
                              <td class="text-center header total" colspan="2" style="vertical-align: middle;"><b>TOTAL ALL</b></td>
                              <td class="text-right"><b>{{ number_format($revenue) }}</b></td>
                              <td class="text-right"><b>{{ number_format($revenue_MTD) }}</b></td>
                              <td class="text-right"><b>{{ number_format($revenue_YTD) }}</b></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                </div>
            </div>
          </section>
          @endif
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="/template/js/report/jquery.floatingscroll.js"></script>
<script src="/template/js/ResizeSensor.js"></script>
<script src="/template/js/ElementQueries.js"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script>
   $('.filter-resort').click(function(e){
      try {
        e.preventDefault();
        var company = e.target.getAttribute('data-href');
        if(company){
            const params = new URLSearchParams(window.location.search);
            var obj = {};
            // iterate over all keys
            for (const key of params.keys()) {
                if (params.getAll(key).length > 1) {
                    obj[key] = params.getAll(key);
                } else {
                    obj[key] = params.get(key);
                }
            }
            var _business_date = obj.business_date ? obj.business_date : '';
            if(obj && 'business_date' in obj){
                window.location.href="{{url()->current()}}?business_date="+_business_date+"&resort="+company;
            } else {
                window.location.href="{{url()->current()}}?resort="+company;
            }
        }
        else {
          const params = new URLSearchParams(window.location.search);
          var obj = {};
          // iterate over all keys
          for (const key of params.keys()) {
              if (params.getAll(key).length > 1) {
                  obj[key] = params.getAll(key);
              } else {
                  obj[key] = params.get(key);
              }
          }
          var _business_date = obj.business_date ? obj.business_date : '';
          if(obj && 'business_date' in obj){
              window.location.href="{{url()->current()}}?business_date="+_business_date;
          } else {
              window.location.href="{{url()->current()}}";
          }
        }

      } catch(error){}
   });

   $(document).ready(function() {
      $('#cmbResort').on('change', function() {
          this.form.submit();
      });

      // cekFnbTbody();      

      $('#datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        showWeek: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: new Date(),
        onSelect : function(text, obj){
          var nowDate = formatDate(new Date()),
          selected_date = formatDate(new Date(text));

          const params = new URLSearchParams(window.location.search);
          var obj = {};
          // iterate over all keys
          for (const key of params.keys()) {
              if (params.getAll(key).length > 1) {
                  obj[key] = params.getAll(key);
              } else {
                  obj[key] = params.get(key);
              }
          }
          var _companycode = obj.resort ? obj.resort : '';
          if(obj && 'resort' in obj){
            if(nowDate == selected_date){
              window.location.href="{{url()->current()}}?resort="+_companycode;
            }
            else {
              window.location.href="{{url()->current()}}?business_date="+text+"&resort="+_companycode;
            }
          } else {
            if(nowDate == selected_date){
              window.location.href="{{url()->current()}}";
            }
            else{
              window.location.href="{{url()->current()}}?business_date="+text;
            }
          }
        }
      });

      $('#datepicker').prop('disabled', false);
      $(".table-floating-overflow").floatingScroll();
      var element = document.getElementsByClassName('content-wrapper');
      new ResizeSensor(element, function() {
        $(".table-floating-overflow").floatingScroll("update");
      });

      $('.datatable-able').DataTable({
        "dom":'<"abs-search"f>rtip',
        "scrollX":true,
        "autoWidth":false,
        "lengthChange":false,
        "columnDefs": [{
          "targets": [1],
          render: function(data, type, row, meta) {
            if(data){
              if (type == 'display' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
                 return moment(data).format('DD-MMM-YYYY');
              }else if (type == 'filter' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
                 return moment(data).format('DD-MMM-YYYY');
              }else{
                return "-";
              }
            } else { return "Not Available" }
          }
        }]
      });
      $('.datatable-vip').DataTable({
        "dom":'<"abs-search"f>rtip',
        "scrollX":true,
        "autoWidth":false,
        "lengthChange":false,
        "columnDefs": [{
          "targets": [0, 5, 6],
          render: function(data, type, row, meta) {
              if(data){
                if (type == 'display' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
                   return moment(data).format('DD-MMM-YYYY');
                }else if (type == 'filter' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
                   return moment(data).format('DD-MMM-YYYY');
                }else{
                  return "-";
                }
              } else { return "Not Available" }
          }
        }]
      });
    });

    function formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

      if (month.length < 2)
          month = '0' + month;
      if (day.length < 2)
          day = '0' + day;

      return [year, month, day].join('-');
    }

    // function cekFnbTbody(){
    //   try{
    //       var foodheight = $('#food-tbody').outerHeight(),
    //       beverageHeight = $('#beverage-tbody').outerHeight();
    //       var arr = [foodheight, beverageHeight];
    //       var max = arr.reduce(function(a, b) { return Math. max(a, b); }, 0);
    //       if(max){
    //         $('#food-tbody').css('height', max+"px");
    //         $('#beverage-tbody').css('height', max+"px");
    //       }
    //   } catch(error){}
    // }

    function changeView(section){
        switch (section) {
            case 'all':
                $("#resort").fadeIn('slow');
                $("#reservation").fadeIn('slow');
                $("#cancel").fadeIn('slow');
                $("#other").fadeIn('slow');
                $("#property").fadeIn('slow');
                $("#rental-object").fadeIn('slow');
                $("#dropdownReportButton").html('All Report');
            break;
            case 'resort':
                $("#resort").fadeIn('slow');
                $("#reservation").fadeOut('slow');
                $("#cancel").fadeOut('slow');
                $("#other").fadeOut('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeOut('slow');
                $("#dropdownReportButton").html('Resort Summary');
            break;
            case 'reservation':
                $("#resort").fadeOut('slow');
                $("#reservation").fadeIn('slow');
                $("#cancel").fadeOut('slow');
                $("#other").fadeOut('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeOut('slow');
                $("#dropdownReportButton").html('Reservation Summary');
            break;
            case 'cancel':
                $("#resort").fadeOut('slow');
                $("#reservation").fadeOut('slow');
                $("#cancel").fadeIn('slow');
                $("#other").fadeOut('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeOut('slow');
                $("#dropdownReportButton").html('Cancellation Summary');
            break;
            case 'other':
                $("#resort").fadeOut('slow');
                $("#reservation").fadeOut('slow');
                $("#cancel").fadeOut('slow');
                $("#other").fadeIn('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeOut('slow');
                $("#dropdownReportButton").html('Other Summary');
            break;
            case 'property':
                $("#resort").fadeOut('slow');
                $("#reservation").fadeOut('slow');
                $("#cancel").fadeOut('slow');
                $("#other").fadeOut('slow');
                $("#property").fadeIn('slow');
                $("#rental-object").fadeOut('slow');
                $("#dropdownReportButton").html('Property Summary');
            break;
            case 'rental-object':
                $("#resort").fadeOut('slow');
                $("#reservation").fadeOut('slow');
                $("#cancel").fadeOut('slow');
                $("#other").fadeOut('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeIn('slow');
                $("#dropdownReportButton").html('Rental Object Summary');
            break;

            default:
                $("#resort").fadeIn('slow');
                $("#reservation").fadeIn('slow');
                $("#cancel").fadeIn('slow');
                $("#other").fadeIn('slow');
                $("#property").fadeOut('slow');
                $("#rental-object").fadeIn('slow');
                $("#dropdownReportButton").html('All Report');
                break;
        }
    }

    // $(window).on('resize', function(){
    //   setTimeout(function(){
    //     cekFnbTbody();
    //   },1000)
    // })
    
    // When the user scrolls the page, execute myFunction
    window.onscroll = function() {myFunction()};

    // Get the header
    var header = document.getElementById("main-header");

    // Get the offset position of the navbar
    var sticky = (header.offsetTop - 56);
    // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
    function myFunction() {
      // console.log(window.pageYOffset);
      const checkOffset = $.datepicker._checkOffset;
      $.extend($.datepicker, {
        _checkOffset: function(inst, offset, isFixed) {
            if(!isFixed) {
                return checkOffset.apply(this, arguments);
            }

            let isRTL = this._get(inst, "isRTL");
            let obj = inst.input[0];

            while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.filters.hidden(obj))) {
                obj = obj[isRTL ? "previousSibling" : "nextSibling"];
            }

            let rect = obj.getBoundingClientRect();
            let container_height = obj.clientHeight || 0;

            return {
                top: rect.top + container_height,
                left: rect.left,
            };
        }
      });

      try {
        if (sticky > 0 && window.pageYOffset > sticky) {
          $( "#datepicker" ).datepicker( "hide" );
          $( "#datepicker" ).blur();
          document.getElementById('header').classList.add("sticky");
          var top = ($('.navbar-menu-wrapper').outerHeight() || 0) + 'px';
          $('#resort > .divider').css('padding', '5em 0px');
          document.getElementById('header').style.top = top;
        } else {
          $( "#datepicker" ).datepicker( "hide" );
          $( "#datepicker" ).blur();
          document.getElementById('header').classList.remove("sticky");
          document.getElementById('header').style.top = null;
          $('#resort > .divider').css('padding', '10px 0px');

        }
      } catch(error){}
    } 
</script>
@endsection
