@extends('layouts.default')
@section('title', 'Daily Revenue Report')
@section('styles')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.dataTables.min.css">
{{-- <link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.dataTables.min.css"> --}}
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
.beda td{
background: #6bf0fe !important;
color:#000 !important;
}

.table.fixedHeader-floating.no-footer{
    top:60px !important;
}
</style>
@endsection
@section('content')
<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li>
    <li class="breadcrumb-item"><a href="/folio">Report</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>Daily Revenue Report</span></li>
</ol>
</nav>
<div class="row flex-grow" id="main-header">
<div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card">
        {{--
        <div class="card-body main-wrapper pb-0 bg-white" id="header">
        <div class="px-0">
            <div class="d-flex justify-content-between mb-3 border-bottom">
                <h4 class="card-title mx-auto text-center">
                    <img src="{{ url('/image/ayana_logo.png')}}" style="height:100px;width:auto;margin:10px auto;display:table;">
                    <span>Room Villa Revenue</span>
                </h4>
            </div>
        </div>
        </div>
        --}}
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
        <!-- REVENUE STATISTIC VILLA -->
        <div class="form-group">
            <div class="form-group mb-3">
                <div class="row">
                    <div class="title-header col-5">
                    <h2> {{$plant_name->SUB_RESORT_NAME}} </h2>
                    <h3> Daily Revenue Report </h3>
                    <h5> Revenue Date : {{ date("d-M-y", strtotime($date_to_lookup)) }} </h5>
                    </div>
                    <div class="pt-3 date-range-wrapper col-7">
                    <form method="GET" action="">
                        <div class="form-group col-md-3 float-left">
                            <div class="mb-1">
                                <small style="color:#000;text-align: right;">Plant</small>
                            </div>
                            <select name="plant" id="cmbPlant" class="select2 form-control">
                            @foreach ($list_plant as $plant)
                            <option value="{{$plant->RESORT}}" {{  ($filter_plant == $plant->RESORT)? 'selected' : ''}}>{{$plant->RESORT}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5 float-left">
                            <div class="mb-1">
                                <small style="color:#000;text-align: right;">Sub Resort</small>
                            </div>
                            <select name="subresort" id="cmbSubResort" class="select2 form-control">
                            @foreach ($list_subresort as $subresort)
                            <option value="{{$subresort->SUB_RESORT}}" {{  ($filter_subresort == $subresort->SUB_RESORT)? 'selected' : '' }}>{{$subresort->SUB_RESORT_NAME}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3 float-left">
                            <div class="mb-1">
                                <small style="color:#000;text-align: right;">Date</small>
                            </div>
                            <input disabled type="text" class="form-control datepicker" name="business_date" id="datepicker" value="{{ date('Y-m-d', strtotime($date_to_lookup)) }}">
                        </div>
                        <div class="form-group col-md-1 float-left">
                            <div class="mb-1">
                                <small style="color:#000;text-align: right;">Apply</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" id="btnSubmit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive form-group">
                <table class="table table-bordered" id="content-table">
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
                    @if(strtoupper($filter_subresort) != 'PNB1')
                    <tbody>
                        <tr>

                            <td class="text-left">Bookkeeping Rate</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_TODAY) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_TODAY)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_MTD) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_MTD)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right px-0 py-0">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right px-0 py-0">
                                {{-- <div style="position: relative;">
                                    <input type="text" name="budget_mtd" disabled class="form-control define-input" placeholder="Input Budget..">
                                    <div style="position: absolute; top:5px;right:2px">
                                    <a class="btn btn-success pl-2 pr-2 ml-1 mr-1 py-1 btn-save-budget"><i class="mdi mdi-check text-white"></i></a>
                                    </div>
                                </div> --}}
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Room Available</td>
                            <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->ROOM_AVAILABLE_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->ROOM_AVAILABLE_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($room_available) && count($room_available)) {{ number_format($room_available[0]->ROOM_AVAILABLE_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Resort Rooms Sold</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->RESORT_ROOMS_SOLD_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Villa Rooms Sold</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->VILLA_ROOMS_SOLD_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($resort_room_sold) && count($resort_room_sold)) {{ number_format($resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL ROOMS SOLD</td>
                            <td class="text-right">
                                @if(isset($resort_room_sold) && count($resort_room_sold))
                                @php
                                $total_rooms_sold_today=$resort_room_sold[0]->RESORT_ROOMS_SOLD_TODAY + $resort_room_sold[0]->VILLA_ROOMS_SOLD_TODAY;
                                @endphp
                                {{ number_format($total_rooms_sold_today, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($resort_room_sold) && count($resort_room_sold))
                                @php
                                $total_rooms_sold_mtd=$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD + $resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD;
                                @endphp
                                {{ number_format($total_rooms_sold_mtd, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($resort_room_sold) && count($resort_room_sold))
                                @php
                                    $total_rooms_sold_mtd_last_year=$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD_LAST_YEAR + $resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD_LAST_YEAR;
                                @endphp
                                {{ number_format($total_rooms_sold_mtd_last_year, 0, '.',',') }}
                                @else -
                                @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END TOTAL ROOMS -->
                        <tr>
                            <td class="text-left">House Use Rooms 2</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOMS_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOMS_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->HOUSE_USE_ROOMS_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Total Room Occupied</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_ROOM_OCC_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_ROOM_OCC_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->TOTAL_ROOM_OCC_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Group Rooms Sold</td>
                            <td class="text-right">@if(isset($groups_room_sold) && count($groups_room_sold)) {{ number_format($groups_room_sold[0]->GROUPS_ROOM_SOLD_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($groups_room_sold) && count($groups_room_sold)) {{ number_format($groups_room_sold[0]->GROUPS_ROOM_SOLD_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($groups_room_sold) && count($groups_room_sold)) {{ number_format($groups_room_sold[0]->GROUPS_ROOM_SOLD_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Number Of Guest</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUEST_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUEST_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->NUMBER_OF_GUEST_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Room Occupancy (%)</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available))
                                @if((int)$room_available[0]->ROOM_AVAILABLE_TODAY != 0)
                                {{ number_format(((int)$total_rooms_sold_today / (int)$room_available[0]->ROOM_AVAILABLE_TODAY) * 100, 2, '.',',').'%' }}
                                @else
                                {{ 0 }}
                                @endif
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available))
                                @if((int)$room_available[0]->ROOM_AVAILABLE_MTD != 0)
                                {{ number_format(((int)$total_rooms_sold_mtd/ (int)$room_available[0]->ROOM_AVAILABLE_MTD) * 100, 2, '.',',').'%' }}
                                @else
                                {{ 0 }}
                                @endif
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($room_available) && count($room_available))
                                @if((int)$room_available[0]->ROOM_AVAILABLE_MTD_LAST_YEAR != 0)
                                {{ number_format(((int)$occupancy[0]->TOTAL_ROOM_OCC_MTD_LAST_YEAR / (int)$room_available[0]->ROOM_AVAILABLE_MTD_LAST_YEAR) * 100, 2, '.',',').'%' }}
                                @else
                                {{ 0 }}
                                @endif
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Double Occupancy (%)</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_ROOM_OCC_TODAY != 0)
                                {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUEST_TODAY - (int)$occupancy[0]->TOTAL_ROOM_OCC_TODAY) / (int)$occupancy[0]->TOTAL_ROOM_OCC_TODAY) * 100, 2, '.',',')."%" }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_ROOM_OCC_MTD != 0)
                                {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUEST_MTD - (int)$occupancy[0]->TOTAL_ROOM_OCC_MTD) / (int)$occupancy[0]->TOTAL_ROOM_OCC_MTD) * 100, 2, '.',',')."%" }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && $occupancy[0]->TOTAL_ROOM_OCC_MTD_LAST_YEAR != 0)
                                {{ number_format((((int)$occupancy[0]->NUMBER_OF_GUEST_MTD_LAST_YEAR - (int)$occupancy[0]->TOTAL_ROOM_OCC_MTD_LAST_YEAR) / (int)$occupancy[0]->TOTAL_ROOM_OCC_MTD_LAST_YEAR) * 100, 2, '.',',')."%" }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- START ADR -->
                        @php
                        $total_adr_now = 0;
                        $total_adr_mtd = 0;
                        $total_adr_last_year = 0;
                        @endphp
                        <tr>
                            <td class="text-left">ADR RESORT</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->RESORT_ROOMS_SOLD_TODAY != 0)
                                @php
                                $total_adr_now += (int)$occupancy[0]->ROOM_REVENUE_TODAY / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_TODAY;
                                @endphp
                                {{ number_format((int)$occupancy[0]->ROOM_REVENUE_TODAY / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_TODAY, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD != 0)
                                @php
                                $total_adr_mtd += (int)$occupancy[0]->ROOM_REVENUE_MTD / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD;
                                @endphp
                                {{ number_format((int)$occupancy[0]->ROOM_REVENUE_MTD / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD_LAST_YEAR != 0)
                                @php
                                $total_adr_last_year += (int)$occupancy[0]->ROOM_REVENUE_MTD_LAST_YEAR / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD_LAST_YEAR;
                                @endphp
                                {{ number_format((int)$occupancy[0]->ROOM_REVENUE_MTD_LAST_YEAR / (int)$resort_room_sold[0]->RESORT_ROOMS_SOLD_MTD_LAST_YEAR, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">ADR VILLA</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->VILLA_ROOMS_SOLD_TODAY != 0)
                                @php
                                $total_adr_now += (int)$occupancy[0]->VILLA_REVENUE_TODAY / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_TODAY;
                                @endphp
                                {{ number_format((int)$occupancy[0]->VILLA_REVENUE_TODAY / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_TODAY, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD != 0)
                                @php
                                $total_adr_mtd += (int)$occupancy[0]->VILLA_REVENUE_MTD / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD;
                                @endphp
                                {{ number_format((int)$occupancy[0]->VILLA_REVENUE_MTD / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy) && isset($resort_room_sold) && count($resort_room_sold) && $resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD_LAST_YEAR != 0)
                                @php
                                $total_adr_last_year += (int)$occupancy[0]->VILLA_REVENUE_MTD_LAST_YEAR / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD_LAST_YEAR;
                                @endphp
                                {{ number_format((int)$occupancy[0]->VILLA_REVENUE_MTD_LAST_YEAR / (int)$resort_room_sold[0]->VILLA_ROOMS_SOLD_MTD_LAST_YEAR, 0, '.',',') }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            {{-- override total adr  --}}
                            @if(isset($occupancy) && count($occupancy))
                                @php
                                $total_room_revenue_today = $occupancy[0]->ROOM_REVENUE_TODAY + $occupancy[0]->VILLA_REVENUE_TODAY;
                                $total_room_revenue_mtd = $occupancy[0]->ROOM_REVENUE_MTD + $occupancy[0]->VILLA_REVENUE_MTD;
                                $total_room_revenue_mtd_last_year = $occupancy[0]->ROOM_REVENUE_MTD_LAST_YEAR + $occupancy[0]->VILLA_REVENUE_MTD_LAST_YEAR;
                                @endphp
                            @else
                                @php
                                    $total_room_revenue_today= $total_room_revenue_mtd = $total_room_revenue_mtd_last_year = 0;
                                @endphp
                            @endif
                            @php
                                $total_adr_now = $total_room_revenue_today/($total_rooms_sold_today ? : 1);
                                $total_adr_mtd = $total_room_revenue_mtd/($total_rooms_sold_mtd ? : 1);
                                $total_adr_last_year = $total_room_revenue_mtd_last_year/($total_rooms_sold_mtd_last_year ?: 1);
                            @endphp
                            {{-- end override total adr --}}
                            <td class="text-left">ADR (TOTAL)</td>
                            <td class="text-right">{{ number_format($total_adr_now, 0, '.',',') }}</td>
                            <td class="text-right">{{ number_format($total_adr_mtd, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_adr_last_year, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END ADR -->
                        <!-- REV PAR -->
                        <tr>
                            <td class="text-left">REVPAR(TOTAL)</td>
                            <td class="text-right">
                                @if(isset($revpar) && count($revpar)) {{ number_format($revpar[0]->REVPAR_TODAY, 0, '.',',') }} @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($revpar) && count($revpar)) {{ number_format($revpar[0]->REVPAR_MTD, 0, '.',',') }} @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($revpar) && count($revpar)) {{ number_format($revpar[0]->REVPAR_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END REVPAR -->
                        <tr>
                            <td colspan="9" class="text-left" style="font-weight: bold;"> STATISTICS</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">ROOMS</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>

                        <!-- START ROOM STATISTIC REVENUE -->
                        <tr>
                            <td class="text-left">Resort Room Revenue</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->ROOM_REVENUE_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Villa Revenue</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($occupancy) && count($occupancy)) {{ number_format($occupancy[0]->VILLA_REVENUE_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL ROOM REVENUE</td>
                            <td class="text-right">
                                @if(isset($occupancy) && count($occupancy))
                                @php
                                $total_room_revenue_today = $occupancy[0]->ROOM_REVENUE_TODAY + $occupancy[0]->VILLA_REVENUE_TODAY;
                                $total_room_revenue_mtd = $occupancy[0]->ROOM_REVENUE_MTD + $occupancy[0]->VILLA_REVENUE_MTD;
                                $total_room_revenue_mtd_last_year = $occupancy[0]->ROOM_REVENUE_MTD_LAST_YEAR + $occupancy[0]->VILLA_REVENUE_MTD_LAST_YEAR;
                                @endphp
                                {{ number_format($total_room_revenue_today, 0, '.',',') }}
                                @else -
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($total_room_revenue_mtd, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_room_revenue_mtd_last_year, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END ROOM STATISTIC REVENUE -->
                        <!-- START FOOD REVENUE -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">FOOD</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                        $total_food_now = 0;
                        $total_food_mtd = 0;
                        $total_food_mtd_last_year = 0;
                        @endphp
                        @if(isset($outlet_revenue_food) && $outlet_revenue_food)
                        @foreach($outlet_revenue_food as $outlet_revenue_food)
                        <tr>
                            <td class="text-left">{{ $outlet_revenue_food->REVENUECENTERNAME }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food->REVENUE_TODAY, 0) }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food->REVENUE_MTD, 0) }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food->REVENUE_MTD_LAST_YEAR, 0) }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        @php
                        //if(isset($outlet_revenue_food->REVENUE_TODAY) || isset($outlet_revenue_food->REVENUE_MTD)){
                        $total_food_now += $outlet_revenue_food->REVENUE_TODAY;
                        $total_food_mtd += $outlet_revenue_food->REVENUE_MTD;
                        $total_food_mtd_last_year += $outlet_revenue_food->REVENUE_MTD_LAST_YEAR;
                        //}
                        @endphp
                        @endforeach
                        @endif
                        <tr style="background: #ececec;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL FOOD</td>
                            <td class="text-right">{{ number_format($total_food_now, 0, '.',',') }}</td>
                            <td class="text-right">{{ number_format($total_food_mtd, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_food_mtd_last_year, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END FOOD REVENUE -->
                        <!-- START BEVERAGE REVENUE -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">BEVERAGE</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                        $total_bvg_now = 0;
                        $total_bvg_mtd = 0;
                        $total_bvg_mtd_last_year = 0;
                        @endphp
                        @if(isset($outlet_revenue_beverage) && $outlet_revenue_beverage)
                        @foreach($outlet_revenue_beverage as $outlet_revenue_beverage)
                        <tr>
                            <td class="text-left">{{ $outlet_revenue_beverage->REVENUECENTERNAME }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_beverage->REVENUE_TODAY, 0) }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_beverage->REVENUE_MTD, 0) }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($outlet_revenue_beverage->REVENUE_MTD_LAST_YEAR, 0) }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        @php
                       // if(isset($outlet_revenue_beverage->REVENUE_TODAY) || isset($outlet_revenue_beverage->REVENUE_MTD)){
                        $total_bvg_now += $outlet_revenue_beverage->REVENUE_TODAY;
                        $total_bvg_mtd += $outlet_revenue_beverage->REVENUE_MTD;
                        $total_bvg_mtd_last_year += $outlet_revenue_beverage->REVENUE_MTD_LAST_YEAR == null ? 0 : (int)$outlet_revenue_beverage->REVENUE_MTD_LAST_YEAR;
                          
                       // }
                        @endphp
                        @endforeach
                        @endif
                        <tr style="background: #ececec;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL BEVERAGE </td>
                            <td class="text-right">{{ number_format($total_bvg_now, 0, '.',',') }}</td>
                            <td class="text-right">{{ number_format($total_bvg_mtd, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_bvg_mtd_last_year, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END BEVERAGE REVENUE -->
                        <!-- START OTHER FB REVENUE -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">F&B OTHERS</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr>
                            <td class="text-left">F&B Other</td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #ececec;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL F&B OTHER</td>
                            <td  class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END OTHER FB REVENUE -->
                        <!-- START TOTAL FB -->
                        @php
                            $total_fb_now=$total_food_now+$total_bvg_now+$fnb_other[0]->REVENUE_TODAY;
                            $total_fb_mtd=$total_food_mtd+$total_bvg_mtd+$fnb_other[0]->REVENUE_MTD;
                            $total_fb_mtd_last_year=$total_food_mtd_last_year+$total_bvg_mtd_last_year+$fnb_other[0]->REVENUE_MTD_LAST_YEAR;
                        @endphp
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL F&B REVENUE</td>
                            <td  class="text-right">
                                @if(isset($total_fb_now)) {{ number_format($total_fb_now, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($total_fb_mtd)) {{ number_format($total_fb_mtd, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($total_fb_mtd_last_year)) {{ number_format($total_fb_mtd_last_year, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END TOTAL FB -->
                        <!-- START TELEPHONE & INTERNET -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">TELEPHONE & INTERNET</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Local</td>
                            <td  class="text-right">
                                @if(isset($tel_local) && count($tel_local)) {{ number_format($tel_local[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($tel_local) && count($tel_local)) {{ number_format($tel_local[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($tel_local) && count($tel_local)) {{ number_format($tel_local[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Long Distance</td>
                            <td  class="text-right">
                                @if(isset($tel_long_distance) && count($tel_long_distance)) {{ number_format($tel_long_distance[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($tel_long_distance) && count($tel_long_distance)) {{ number_format($tel_long_distance[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($tel_long_distance) && count($tel_long_distance)) {{ number_format($tel_long_distance[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">IDD </td>
                            <td  class="text-right">
                                @if(isset($tel_idd) && count($tel_idd)) {{ number_format($tel_idd[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($tel_idd) && count($tel_idd)) {{ number_format($tel_idd[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($tel_idd) && count($tel_idd)) {{ number_format($tel_idd[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Internet</td>
                            <td  class="text-right">
                                @if(isset($tel_internet) && count($tel_internet)) {{ number_format($tel_internet[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($tel_internet) && count($tel_internet)) {{ number_format($tel_internet[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($tel_internet) && count($tel_internet)) {{ number_format($tel_internet[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">CN Telephone</td>
                            <td  class="text-right">
                                @if(isset($cn_tel) && count($cn_tel)) {{ number_format($cn_tel[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($cn_tel) && count($cn_tel)) {{ number_format($cn_tel[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($cn_tel) && count($cn_tel)) {{ number_format($cn_tel[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">CN Internet</td>
                            <td  class="text-right">
                                @if(isset($cn_internet) && count($cn_internet)) {{ number_format($cn_internet[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($cn_internet) && count($cn_internet)) {{ number_format($cn_internet[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($cn_internet) && count($cn_internet)) {{ number_format($cn_internet[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        @php
                            $total_tel_internet=$tel_local[0]->REVENUE_TODAY+ $tel_long_distance[0]->REVENUE_TODAY+ $tel_idd[0]->REVENUE_TODAY +$tel_internet[0]->REVENUE_TODAY +$cn_tel[0]->REVENUE_TODAY +$cn_internet [0]->REVENUE_TODAY;
                            $total_tel_internet_mtd=$tel_local[0]->REVENUE_MTD+ $tel_long_distance[0]->REVENUE_MTD+ $tel_idd[0]->REVENUE_MTD +$tel_internet[0]->REVENUE_MTD +$cn_tel[0]->REVENUE_MTD +$cn_internet [0]->REVENUE_MTD;
                            $total_tel_internet_mtd_last_year=$tel_local[0]->REVENUE_MTD_LAST_YEAR+ $tel_long_distance[0]->REVENUE_MTD_LAST_YEAR+ $tel_idd[0]->REVENUE_MTD_LAST_YEAR +$tel_internet[0]->REVENUE_MTD_LAST_YEAR +$cn_tel[0]->REVENUE_MTD_LAST_YEAR +$cn_internet [0]->REVENUE_MTD_LAST_YEAR;
                        @endphp
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL TELEPHONE & INTERNET </td>
                            <td  class="text-right">
                                @if(isset($total_tel_internet)) {{ number_format($total_tel_internet, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($total_tel_internet_mtd)) {{ number_format($total_tel_internet_mtd, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($total_tel_internet_mtd_last_year)) {{ number_format($total_tel_internet_mtd_last_year, 0, '.',',') }}@else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END TELEPHONE & INTERNET -->
                        <!-- START RETAIL RECREATION SPA -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">RETAIL, RECREATION & SPA</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                        $total_now_rrs = 0;
                        $total_mtd_rrs = 0;
                        $total_last_year_rrs = 0;
                        @endphp
                        <tr>
                            <td class="text-left">Retail</td>
                            <td  class="text-right">
                                @if(isset($retail) && count($retail)) {{ number_format($retail[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_now_rrs+= $retail[0]->REVENUE_TODAY @endphp
                                @else - @endif
                            </td>
                            <td  class="text-right">
                                @if(isset($retail) && count($retail)) {{ number_format($retail[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_mtd_rrs+= $retail[0]->REVENUE_MTD @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td  class="text-right">
                                @if(isset($retail) && count($retail)) {{ number_format($retail[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_last_year_rrs+= $retail[0]->REVENUE_MTD_LAST_YEAR @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Recreation</td>
                            <td class="text-right">
                                @if(isset($recreation) && count($recreation)) {{ number_format($recreation[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_now_rrs+= $recreation[0]->REVENUE_TODAY @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($recreation) && count($recreation)) {{ number_format($recreation[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_mtd_rrs+= $recreation[0]->REVENUE_MTD @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($recreation) && count($recreation)) {{ number_format($recreation[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_last_year_rrs+= $recreation[0]->REVENUE_MTD_LAST_YEAR @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Spa</td>
                            <td class="text-right">
                                @if(isset($spa) && count($spa)) {{ number_format($spa[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_now_rrs+= $spa[0]->REVENUE_TODAY @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($spa) && count($spa)) {{ number_format($spa[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_mtd_rrs+= $spa[0]->REVENUE_MTD @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($spa) && count($spa)) {{ number_format($spa[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_last_year_rrs+= $spa[0]->REVENUE_MTD_LAST_YEAR @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL RETAIL, RECREATION & SPA</td>
                            <td  class="text-right"> @if(isset($total_now_rrs) && $total_now_rrs>0) {{ number_format($total_now_rrs, 0, '.',',') }}@else - @endif</td>
                            <td class="text-right"> @if(isset($total_mtd_rrs) && $total_mtd_rrs>0) {{ number_format($total_mtd_rrs, 0, '.',',') }}@else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right"> @if(isset($total_last_year_rrs) && $total_last_year_rrs>0) {{ number_format($total_last_year_rrs, 0, '.',',') }}@else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END RETAIL RECREATION SPA -->
                        <!-- START LAUNDRY -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">LAUNDRY</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Laundry</td>
                            <td class="text-right">
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL LAUNDRY</td>
                            <td class="text-right">
                                @php
                                    $total_laundry_now=$laundry[0]->REVENUE_TODAY;
                                    $total_laundry_mtd=$laundry[0]->REVENUE_MTD;
                                    $total_laundry_mtd_last_year=$laundry[0]->REVENUE_MTD_LAST_YEAR;
                                @endphp
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($laundry) && count($laundry)) {{ number_format($laundry[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END LAUNDRY -->
                        <!-- START RENT/OTHER -->
                        <tr>
                            <td colspan="9" class="text-left"  style="font-weight: bold;">RENT / OTHER</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                        $total_rent_other_now = 0;
                        $total_rent_other_mtd = 0;
                        $total_rent_other_last_year = 0;
                        @endphp
                        {{-- <tr>
                            <td class="text-left">Other Spa</td>
                            <td class="text-right">
                                @if(isset($other_spa) && count($other_spa)) {{ number_format($other_spa[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_rent_other_now +=$other_spa[0]->REVENUE_TODAY; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($other_spa) && count($other_spa)) {{ number_format($other_spa[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_rent_other_mtd +=$other_spa[0]->REVENUE_MTD; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($business_center) && count($business_center)) {{ number_format($business_center[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_rent_other_last_year +=$business_center[0]->REVENUE_MTD_LAST_YEAR; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr> --}}
                        <tr>
                            <td class="text-left">Business Center</td>
                            <td class="text-right">
                                @if(isset($business_center) && count($business_center)) {{ number_format($business_center[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_rent_other_now +=$business_center[0]->REVENUE_TODAY; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($business_center) && count($business_center)) {{ number_format($business_center[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_rent_other_mtd +=$business_center[0]->REVENUE_MTD; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($business_center) && count($business_center)) {{ number_format($business_center[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_rent_other_last_year +=$business_center[0]->REVENUE_MTD_LAST_YEAR; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Other Income</td>
                            <td class="text-right">
                                @if(isset($other_income) && count($other_income)) {{ number_format($other_income[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_rent_other_now +=$other_income[0]->REVENUE_TODAY; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($other_income) && count($other_income)) {{ number_format($other_income[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_rent_other_mtd +=$other_income[0]->REVENUE_MTD; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($other_income) && count($other_income)) {{ number_format($other_income[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_rent_other_last_year +=$other_income[0]->REVENUE_MTD_LAST_YEAR; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td class="text-left">Transportation</td>
                            <td class="text-right">
                                @if(isset($transportation) && count($transportation)) {{ number_format($transportation[0]->REVENUE_TODAY, 0, '.',',') }}
                                @php $total_rent_other_now +=$transportation[0]->REVENUE_TODAY; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($transportation) && count($transportation)) {{ number_format($transportation[0]->REVENUE_MTD, 0, '.',',') }}
                                @php $total_rent_other_mtd +=$transportation[0]->REVENUE_MTD; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($transportation) && count($transportation)) {{ number_format($transportation[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @php $total_rent_other_last_year +=$transportation[0]->REVENUE_MTD_LAST_YEAR; @endphp
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="background: #00ffdc;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL RENT / OTHER</td>
                            <td class="text-right">
                                @if(isset($total_rent_other_now)) {{ number_format($total_rent_other_now, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($total_rent_other_now)) {{ number_format($total_rent_other_mtd, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($total_rent_other_now)) {{ number_format($total_rent_other_last_year, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END RENT/OTHER -->
                        <!-- START TOTALAN AWAL -->
                        <tr rowspan=2>
                            <td colspan="9"> &nbsp;</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                            $total_revenue_today=$total_room_revenue_today+$total_fb_now+$total_now_rrs+$total_laundry_now+$total_rent_other_now+$total_tel_internet;
                            $total_revenue_mtd=$total_room_revenue_mtd+$total_fb_mtd+$total_mtd_rrs+$total_laundry_mtd+$total_rent_other_mtd+$total_tel_internet_mtd;
                            $total_revenue_mtd_last_year=$total_room_revenue_mtd_last_year+$total_fb_mtd_last_year+$total_last_year_rrs+$total_laundry_mtd_last_year+$total_rent_other_last_year+$total_tel_internet_mtd_last_year;

                            $grand_total_revenue_today=$total_revenue_today;
                            $grand_total_revenue_mtd=$total_revenue_mtd;
                            $grand_total_revenue_mtd_last_year=$total_revenue_mtd_last_year;
                        @endphp
                        <tr style="background: #00ffdc; color:#000; font-weight:bold;">
                            <td class="text-left">GRAND TOTAL REVENUE</td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_today)) {{ number_format($grand_total_revenue_today, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_mtd)) {{ number_format($grand_total_revenue_mtd, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_mtd_last_year)) {{ number_format($grand_total_revenue_mtd_last_year, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END TOTALAN AWAL -->
                        <!-- START TOTAL F&B KEDUA -->
                        <tr rowspan=2>
                            <td colspan="9"> &nbsp;</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr style="background: #ececec; color:#000; font-weight:bold;">
                            <td colspan="9" class="text-left"  style="font-weight: bold;">TOTAL FOOD & BEVERAGE</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        @php
                        $total_now_fnb = 0;
                        $total_mtd_fnb = 0;
                        $total_last_year_fnb = 0;
                        @endphp
                        @if(isset($outlet_revenue_food_beverage) && $outlet_revenue_food_beverage)
                        @foreach($outlet_revenue_food_beverage as $outlet_revenue_food_beverage)
                        <tr>
                            <td class="text-left">{{ $outlet_revenue_food_beverage->REVENUECENTERNAME }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food_beverage->REVENUE_TODAY, 0) }}</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food_beverage->REVENUE_MTD, 0) }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($outlet_revenue_food_beverage->REVENUE_MTD_LAST_YEAR, 0) }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        @php
                        if(isset($outlet_revenue_food_beverage->REVENUE_TODAY) || isset($outlet_revenue_food_beverage->REVENUE_MTD)){
                        $total_now_fnb += $outlet_revenue_food_beverage->REVENUE_TODAY;
                        $total_mtd_fnb += $outlet_revenue_food_beverage->REVENUE_MTD;
                        $total_last_year_fnb += $outlet_revenue_food_beverage->REVENUE_MTD_LAST_YEAR;
                        }
                        @endphp
                        @endforeach
                        @endif
                        <tr>
                            <td class="text-left">F&B Other</td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($fnb_other) && count($fnb_other)) {{ number_format($fnb_other[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        @php
                            $total_now_fnb += $fnb_other[0]->REVENUE_TODAY;
                            $total_mtd_fnb += $fnb_other[0]->REVENUE_MTD;
                            $total_last_year_fnb += $fnb_other[0]->REVENUE_MTD_LAST_YEAR;
                        @endphp
                        <tr style="background: #ececec;color:#000;font-weight:bold;">
                            <td class="text-left">TOTAL FOOD & BEVERAGE REVENUE</td>
                            <td class="text-right">{{ number_format($total_now_fnb, 0, '.',',') }}</td>
                            <td class="text-right">{{ number_format($total_mtd_fnb, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_last_year_fnb, 0, '.',',') }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        <!-- END TOTAL F&B KEDUA -->
                        <!-- START TOTAL AKHIR -->
                        <!-- END TOTAL AKHIR -->
                        <tr rowspan=2>
                            <td colspan="9"> &nbsp;</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
                        </tr>
                        <tr style="background: #00ffdc; color:#000; font-weight:bold;">
                            <td class="text-left">TOTAL DRR</td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_today)) {{ number_format($grand_total_revenue_today, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_mtd)) {{ number_format($grand_total_revenue_mtd, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($grand_total_revenue_mtd_last_year)) {{ number_format($grand_total_revenue_mtd_last_year, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="display:none;background: #00ffdc; color:#000; font-weight:bold;">
                            <td class="text-left">TOTAL MGR</td>
                            <td class="text-right">
                                @if(isset($total_mgr) && count($total_mgr)) {{ number_format($total_mgr[0]->REVENUE_TODAY, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($total_mgr) && count($total_mgr)) {{ number_format($total_mgr[0]->REVENUE_MTD, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($total_mgr) && count($total_mgr)) {{ number_format($total_mgr[0]->REVENUE_MTD_LAST_YEAR, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr style="display:none;background: #00ffdc; color:#000; font-weight:bold;">
                            @php
                                $different_today=$grand_total_revenue_today-($total_mgr[0]->REVENUE_TODAY);
                                $different_mtd=$grand_total_revenue_mtd-($total_mgr[0]->REVENUE_MTD);
                                $different_mtd_last_year=$grand_total_revenue_mtd_last_year-($total_mgr[0]->REVENUE_MTD_LAST_YEAR);
                            @endphp
                            <td class="text-left">DIFFERENT</td>
                            <td class="text-right">
                                @if(isset($different_today)) {{ number_format($different_today, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">
                                @if(isset($different_mtd)) {{ number_format($different_mtd, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if(isset($different_mtd_last_year)) {{ number_format($different_mtd_last_year, 0, '.',',') }}
                                @else - @endif
                            </td>
                            <td class="text-right">-</td>
                        </tr>
                    </tbody>
                    @else
                    <tbody>
                        <tr>
                            <td class="text-left">Bookkeeping Rate</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_TODAY) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_TODAY)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_TODAY, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_MTD) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_MTD)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_MTD, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                {{-- <div style="position: relative;">
                                    <input type="text" name="budget_mtd" disabled class="form-control define-input" placeholder="Input Budget..">
                                    <div style="position: absolute; top:5px;right:2px">
                                    <a class="btn btn-success pl-2 pr-2 ml-1 mr-1 py-1 btn-save-budget"><i class="mdi mdi-check text-white"></i></a>
                                    </div>
                                </div> --}}
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">@if(isset($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR) && !empty($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR)) {{ number_format($booking_rate[0]->BOOK_KEEPING_RATE_MTD_LAST_YEAR, 0, '.',',') }} @else - @endif</td>
                            <td class="text-right">-</td>
                        </tr>
                        @if(isset($pnb_revenue) && count($pnb_revenue))
                            @foreach($pnb_revenue as $key => $data_rev)
                                @php
                                    $title = strtolower($key) == 'guest' ? 'GUEST STATISTIC' : 'CRUISES REVENUE';
                                    $total_rev = 0;
                                    $total_rev_mtd = 0;
                                    $total_lymtd = 0;
                                @endphp
                                <tr style="font-weight: bold;">
                                    <td colspan="9" class="text-left">{{ strtoupper($title) }}</td>
                                </tr>
                                @foreach($data_rev as $key_data => $dm)
                                    @php
                                        if(strtolower($key) == 'rev'){
                                            $total_rev += $dm->NumberofGuest;
                                            $total_rev_mtd += $dm->NumberofGuest_mtd;
                                            $total_lymtd += $dm->NumberOfGuest_LYMTD;
                                        }
                                    @endphp
                                    @if($loop->iteration == 1 && $key == 'guest')
                                    <tr>
                                        <td class="text-left">{{ isset($dm->Outlet) ? $dm->Outlet : '-' }}</td>
                                        <td class="text-right">{{ isset($dm->NumberofGuest) ? number_format($dm->NumberofGuest, 2).'%' : '-' }}</td>
                                        <td class="text-right">{{ isset($dm->NumberofGuest_mtd) ? number_format($dm->NumberofGuest_mtd, 2).'%' : '-' }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">{{ isset($dm->NumberOfGuest_LYMTD) ? number_format($dm->NumberOfGuest_LYMTD, 2).'%' : '-' }}</td>
                                        <td class="text-right">-</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="text-left">{{ isset($dm->Outlet) ? $dm->Outlet : '-' }}</td>
                                        <td class="text-right">{{ isset($dm->NumberofGuest) ? number_format($dm->NumberofGuest) : '-' }}</td>
                                        <td class="text-right">{{ isset($dm->NumberofGuest_mtd) ? number_format($dm->NumberofGuest_mtd) : '-' }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">{{ isset($dm->NumberOfGuest_LYMTD) ? number_format($dm->NumberOfGuest_LYMTD) : '-' }}</td>
                                        <td class="text-right">-</td>
                                    </tr>
                                    @endif
                                @endforeach
                                @if(strtolower($key) == 'rev')
                                    <tr style="background: #00ffdc; color:#000; font-weight:bold;">
                                        <td class="text-left">TOTAL CRUISE REVENUE</td>
                                        <td class="text-right">{{ number_format($total_rev) }}</td>
                                        <td class="text-right">{{ number_format($total_rev_mtd) }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">{{ number_format($total_lymtd) }}</td>
                                        <td class="text-right">-</td>
                                    </tr>
                                @endif
                            @endforeach
                        <tr style="background: #00ffdc; color:#000; font-weight:bold;">
                            <td class="text-left">TOTAL DRR</td>
                            <td class="text-right">{{ number_format($total_rev) }}</td>
                            <td class="text-right">{{ number_format($total_rev_mtd) }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($total_lymtd) }}</td>
                            <td class="text-right">-</td>
                        </tr>
                        @endif
                    </tbody>
                    @endif
                </table>
            </div>
            <!-- End Table -->
        </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.0.2/cleave.min.js" integrity="sha512-SvgzybymTn9KvnNGu0HxXiGoNeOi0TTK7viiG0EGn2Qbeu/NFi3JdWrJs2JHiGA1Lph+dxiDv5F9gDlcgBzjfA==" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
{{-- <script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script> --}}

<script>
$(document).ready(function() {
    $(".select2").select2({});
    $('#datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        showWeek: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: new Date()
    });
    $('#datepicker, .define-input').prop('disabled', false);

    // var number_only = new Cleave('.define-input', {
    //     numeral: true,
    //     stripLeadingZeroes: true
    // });

    $("#cmbPlant").change(function(){
        var plant = this.value;
        $.ajax({
        url: "{{ url('report/ajax/getSubResort')}}",
        type: "post",
        data: {plant : plant} ,
        beforeSend : function (act){
            $("#cmbSubResort").prop("disabled", true);
            $("#btnSubmit").prop("disabled", true);
            $("#datepicker").prop("disabled", true);
        },
        success: function (response) {
            $('#cmbSubResort').empty().trigger("change");
            var select = document.getElementById('cmbSubResort');
            let plant = response.data;
            plant.forEach(element => {
                var opt = document.createElement('option');
                opt.value = element.SUB_RESORT;
                opt.innerHTML = element.SUB_RESORT_NAME;
                select.appendChild(opt);
            });

            $("#cmbSubResort").prop("disabled", false);
            $("#btnSubmit").prop("disabled", false);
            $("#datepicker").prop("disabled", false);

        },
        error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
        }
    });
    })

    // var tables =  $('#content-table').DataTable({
    //     "aaSorting": [],
    //     "asStripeClasses": [],
    //     "fixedHeader":true,
    //     "searching":false,
    //     "paging":   false,
    //     "autoWidth":false,
    // });

    // new $.fn.dataTable.FixedHeader( tables);
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
