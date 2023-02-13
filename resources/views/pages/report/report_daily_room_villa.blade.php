@extends('layouts.default')

@section('title', 'Villa Daily Report')

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
.fl-scrolls {
bottom: 0;
height: 35px;
overflow: auto;
position: fixed;
background: #fff;
}
.fl-scrolls div {
height: 1px;
overflow: hidden;
}
.fl-scrolls-hidden {
bottom: 9999px;
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
                  <h3> Villa Daily Report </h3>
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
           <div class="table-responsive">
             <table class="table table-bordered" cellspacing="0">
               <thead>
                <tr>
                 <th class="bg-secondary text-white">VILLA STATISTIC</th>
                 <th class="bg-secondary text-white">TODAY</th>
                 <th class="bg-secondary text-white">ACTUAL MTD</th>
                </tr>
               </thead>
               <tbody>
                 <tr>
                   <td class="text-left">Villa Available</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_AVAILABLE_ROOM, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_AVAILABLE_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Occupied</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_OCCUPIED_NOW, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_OCCUPIED_ACTUAL_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Upgrade</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_UPGRADE_NOW, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_UPGRADE_ACTUAL_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Upsold</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_UPSOLD_NOW, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_UPSOLD_ACTUAL_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Complimentary</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_COMPLIMENTARY_NOW, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_COMPLIMENTARY_ACTUAL_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Non Upgrade-Original Villa</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_ORIGINAL_NOW, 0, '.',',') }} @else - @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic)) {{ number_format($data_villa_statistic[0]->VILLA_ORIGINAL_ACTUAL_MTD, 0, '.',',') }} @else - @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Occupancy (%)</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_OCCUPIED_NOW / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_MTD) {{ number_format(((int)$data_villa_statistic[0]->VILLA_OCCUPIED_ACTUAL_MTD / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_MTD) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Upgrade (%)</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_UPGRADE_NOW / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_UPGRADE_ACTUAL_MTD / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_MTD) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Upsold (%)</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_UPSOLD_NOW / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_UPSOLD_ACTUAL_MTD / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_MTD) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                 </tr>
                 <tr>
                   <td class="text-left">Villa Non Upgrade-Original Villa (%)</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_ORIGINAL_NOW / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                   <td>@if(isset($data_villa_statistic) && count($data_villa_statistic) && $data_villa_statistic[0]->VILLA_AVAILABLE_ROOM) {{ number_format(((int)$data_villa_statistic[0]->VILLA_ORIGINAL_ACTUAL_MTD / (int)$data_villa_statistic[0]->VILLA_AVAILABLE_MTD) * 100, 2, '.',',').'%' }} @else 0.00% @endif</td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
         <!-- REVENUE PER CATEGORY VILLA -->
         <div class="form-group">
           <div class="form-group mb-3 text-center">
            <h3>Villa Per Room Category Revenue</h3>
           </div>
           <div class="table-responsive">
               <table id="table-daily-report" class="table table-bordered" cellspacing="0" width="100%">
                 <thead>
                    <tr>
                     <th rowspan="2" class="all align-middle bg-secondary text-white" width="7px">NO</th>
                     <!-- <th rowspan="2" class="all align-middle bg-secondary text-white" width="12px">ROOM LABEL</th> -->
                     <th rowspan="2" class="all align-middle bg-secondary text-white" width="210px">ROOM CATEGORY</th>
                     <th rowspan="2" class="all align-middle bg-secondary text-white" width="10px">OCCP</th>
                     <th rowspan="2" class="all align-middle bg-secondary text-white" width="10px">MTD OCCP</th>
                     <th colspan="11" class="all align-middle bg-secondary text-white">REVENUE</th>
                    </tr>
                    <tr class="pivot">
                     <th class="all align-middle bg-secondary text-white">ROOM</th>
                     <th class="all align-middle bg-secondary text-white">F&B</th>
                     <th class="all align-middle bg-secondary text-white">OTHERS</th>
                     <th class="all align-middle bg-secondary text-white">MTD ROOM</th>
                     <th class="all align-middle bg-secondary text-white">MTD F&B</th>
                     <th class="all align-middle bg-secondary text-white">MTD OTHERS</th>
                     <th class="all align-middle bg-secondary text-white">MTD TOTAL</th>
                     <th class="all align-middle bg-secondary text-white">YTD ROOM</th>
                     <th class="all align-middle bg-secondary text-white">YTD F&B</th>
                     <th class="all align-middle bg-secondary text-white">YTD OTHERS</th>
                     <th class="all align-middle bg-secondary text-white">YTD TOTAL</th>
                    </tr>
                 </thead>
                 <tbody>
                    @php 
                      $i=1;
                      $total_mtd_revenue = 0;
                      $total_summary_mtd = [];
                      $total_summary_ytd = [];
                      $total_ytd_revenue = 0; 
                    @endphp
                    @if(isset($data_room_category) && count($data_room_category))
                    @foreach($data_room_category as $room_class => $room_category)
                      @if(strtolower($room_class) != 'total')
                      @php
                        $total_mtd_revenue += isset($room_category->MTD_ROOM_REVENUE) ? (int)$room_category->MTD_ROOM_REVENUE : 0;
                        $total_mtd_revenue += isset($room_category->MTD_FNB_REVENUE) ? (int)$room_category->MTD_FNB_REVENUE : 0;
                        $total_mtd_revenue += isset($room_category->MTD_OTHER_REVENUE) ? (int)$room_category->MTD_OTHER_REVENUE : 0;

                        $total_ytd_revenue += isset($room_category->YTD_ROOM_REVENUE) ? (int)$room_category->YTD_ROOM_REVENUE : 0;
                        $total_ytd_revenue += isset($room_category->YTD_FNB_REVENUE) ? (int)$room_category->YTD_FNB_REVENUE : 0;
                        $total_ytd_revenue += isset($room_category->YTD_OTHER_REVENUE) ? (int)$room_category->YTD_OTHER_REVENUE : 0;

                        $total_summary_mtd[$i] = $total_mtd_revenue;
                        $total_summary_ytd[$i] = $total_ytd_revenue;
                      @endphp

                      <tr>
                        <td>{{ $i }}</td>
                        <!-- <td>{{ $room_class }}</td> -->
                        <td class="text-left">{{ $room_category->ROOM }}</td>
                        <td class="text-right">{{ number_format($room_category->OCC, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->MTD_OCC, 0, '.', ',') }}</td>

                        <td class="text-right">{{ number_format($room_category->NOW_ROOM_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->NOW_FNB_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->NOW_OTHER_REVENUE, 0, '.', ',') }}</td>

                        <td class="text-right">{{ number_format($room_category->MTD_ROOM_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->MTD_FNB_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->MTD_OTHER_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($total_mtd_revenue, 0, '.', ',') }}</td>

                        <td class="text-right">{{ number_format($room_category->YTD_ROOM_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->YTD_FNB_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($room_category->YTD_OTHER_REVENUE, 0, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($total_ytd_revenue, 0, '.', ',') }}</td>
                      </tr>
                      @php 
                        $i++;
                        $total_mtd_revenue = 0;
                        $total_ytd_revenue = 0; 
                      @endphp
                      @endif
                    @endforeach
                      <tr style="background: #ececec;color:#000;font-weight:bold;">
                        <td colspan="2">TOTAL ALL VILLA</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->OCC,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->MTD_OCC,0,'.',',') }}@else - @endif</td>

                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->NOW_ROOM_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->NOW_FNB_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->NOW_OTHER_REVENUE,0,'.',',') }}@else - @endif</td>

                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->MTD_ROOM_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->MTD_FNB_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->MTD_OTHER_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">{{ number_format(array_sum($total_summary_mtd), 0, '.', ',') }}</td>

                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->YTD_ROOM_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->YTD_FNB_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">@if(isset($data_room_category['TOTAL'])){{ number_format($data_room_category['TOTAL']->YTD_OTHER_REVENUE,0,'.',',') }}@else - @endif</td>
                        <td class="text-right">{{ number_format(array_sum($total_summary_ytd), 0, '.', ',') }}</td>
                      </tr>
                    @else
                    <tr>
                      <td colspan="15">No Data</td>
                    </tr>
                    @endif
                 </tbody>
               </table>
            </div>
          </div>
          <hr>
         <!-- REVENUE PER ROOM VILLA -->
          <div class="form-group">
            <div class="form-group mb-3 text-center">
              <h3>Villa Per Room Number Revenue</h3>
            </div>
            <div class="table-wrapper">
              <div class="table-responsive table-room-villa">
                 <table id="table-daily-report" class="table table-bordered" style="width: 1500px; white-space: nowrap">
                   <thead>
                      <tr>
                       <th rowspan="2" class="all align-middle bg-secondary text-white" style="width: 5%">NO</th>
                       <th rowspan="2" class="all align-middle bg-secondary text-white" style="width: 25%">ROOM DESCRIPTION</th>
                       <th rowspan="2" class="all align-middle bg-secondary text-white" style="width: 7%">ROOM NO</th>
                       <th colspan="9" class="all align-middle bg-secondary text-white">REVENUE</th>
                      </tr>
                      <tr class="pivot">
                       <th class="all align-middle bg-secondary text-white" style="width:10%">ROOM</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">F&B</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">OTHERS</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">MTD ROOM</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">MTD F&B</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">MTD OTHERS</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">YTD ROOM</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">YTD F&B</th>
                       <th class="all align-middle bg-secondary text-white" style="width:10%">YTD OTHERS</th>
                      </tr>
                   </thead>
                   <tbody>
                    @if(isset($data_room) && !empty($data_room))
                      @php 
                        $total_room_revenue = 0;
                        $total_fnb_revenue = 0;
                        $total_others_revenue = 0;
                        $total_mtd_room_revenue = 0;
                        $total_mtd_fnb_revenue = 0;
                        $total_mtd_others_revenue = 0;
                        $total_ytd_room_revenue = 0;
                        $total_ytd_fnb_revenue = 0;
                        $total_ytd_others_revenue = 0;
                      @endphp 

                      @foreach($data_room as $data)
                        @php
                          $total_room_revenue += isset($data->NOW_ROOM_REVENUE) ? (int)$data->NOW_ROOM_REVENUE : 0;
                          $total_fnb_revenue += isset($data->NOW_FNB_REVENUE) ? (int)$data->NOW_FNB_REVENUE : 0;
                          $total_others_revenue += isset($data->NOW_OTHER_REVENUE) ? (int)$data->NOW_OTHER_REVENUE : 0;
                          $total_mtd_room_revenue += isset($data->MTD_ROOM_REVENUE) ? (int)$data->MTD_ROOM_REVENUE : 0;
                          $total_mtd_fnb_revenue += isset($data->MTD_FNB_REVENUE) ? (int)$data->MTD_FNB_REVENUE : 0;
                          $total_mtd_others_revenue += isset($data->MTD_OTHER_REVENUE) ? (int)$data->MTD_OTHER_REVENUE : 0;
                          $total_ytd_room_revenue += isset($data->YTD_ROOM_REVENUE) ? (int)$data->YTD_ROOM_REVENUE : 0;
                          $total_ytd_fnb_revenue += isset($data->YTD_FNB_REVENUE) ? (int)$data->YTD_FNB_REVENUE : 0;
                          $total_ytd_others_revenue += isset($data->YTD_OTHER_REVENUE) ? (int)$data->YTD_OTHER_REVENUE : 0;
                        @endphp

                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <!-- <td>{{ $data->ROOM_CLASS }}</td> -->
                          <td class="text-left">{{ $data->ROOM_DESC }}</td>
                          <td>{{ $data->ROOM_NO }}</td>
                          <!-- <td>-</td> -->
                          <!-- Current Date Revenue -->
                          <td class="text-right">@if(isset($data->NOW_ROOM_REVENUE)) {{ number_format($data->NOW_ROOM_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->NOW_FNB_REVENUE)) {{ number_format($data->NOW_FNB_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->NOW_OTHER_REVENUE)) {{ number_format($data->NOW_OTHER_REVENUE, 0, '.',',') }} @else 0 @endif </td>

                          <!-- Month to Date Revenue -->
                          <td class="text-right">@if(isset($data->MTD_ROOM_REVENUE)) {{ number_format($data->MTD_ROOM_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->MTD_FNB_REVENUE)) {{ number_format($data->MTD_FNB_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->MTD_OTHER_REVENUE)) {{ number_format($data->MTD_OTHER_REVENUE, 0, '.',',') }} @else 0 @endif </td>

                          <!-- Year to date Revenue -->
                          <td class="text-right">@if(isset($data->YTD_ROOM_REVENUE)) {{ number_format($data->YTD_ROOM_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->YTD_FNB_REVENUE)) {{ number_format($data->YTD_FNB_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                          <td class="text-right">@if(isset($data->YTD_OTHER_REVENUE)) {{ number_format($data->YTD_OTHER_REVENUE, 0, '.',',') }} @else 0 @endif </td>
                        </tr>
                      @endforeach
                        <tr style="background: #ececec;color:#000;font-weight:bold;">
                          <td colspan="3">TOTAL ALL ROOM</td>
                          <td class="text-right">{{ number_format($total_room_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_fnb_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_others_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_mtd_room_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_mtd_fnb_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_mtd_others_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_ytd_room_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_ytd_fnb_revenue, 0, '.',',') }}</td>
                          <td class="text-right">{{ number_format($total_ytd_others_revenue, 0, '.',',') }}</td>
                        </tr>

                    @else
                     <tr>
                       <td colspan="14">No Data</td>
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
</div>
@endsection

@section('scripts')
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="/template/js/report/jquery.floatingscroll.js"></script>
<script src="/template/js/ResizeSensor.js"></script>
<script src="/template/js/ElementQueries.js"></script>
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
        $('#datepicker').prop('disabled', false);
        $(".table-room-villa").floatingScroll();
        var element = document.getElementsByClassName('simple-tree-table-root');
        new ResizeSensor(element, function() {
          $(".table-room-villa").floatingScroll("update");
        });
    });
  </script>
@endsection

