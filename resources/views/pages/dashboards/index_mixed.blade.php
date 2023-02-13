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
    z-index: 8;
    width: calc(100% - 313px);
    padding-bottom: 1.5em !important;
  }
  .no-wrap-th th{
    padding-left: 3em;
    padding-right: 3em;
  }
  #ui-datepicker-div{
    z-index: 8 !important;
  }
  .normal-line-height{
    line-height: 1.5 !important;
  }
  .loading-text-dashboard {
    position: relative;
    line-height: 1.5 !important;
    top: -30px;
  }
  .vld-overlay.is-active {
    z-index: 6 !important;
  }
  .search-filter {
    display: block;
    position: absolute;
    z-index: 3;
    right: 0;
    top: -50px;
  }
  div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    margin: 10px;
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
                        </div>
                      </div>
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
         <div id="reactiveDashboard">
         @if(strtoupper($filter) != 'PNB1')
            <section id="resort">
              <div class="divider"></div>
              <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                  <h2>14 Days Room Forecast Summary</h2>
                  <div class="mt-1 mb-3">
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_forecast']) ? date('d F, Y', strtotime($data['date_forecast'])) : date('d F, Y') }}</h6>
                  </div>
                  <div class="table-wrapper">
                    <fourteen-days-forecast-dashboard resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_forecast']) ? $data['date_forecast'] : '' }}"></fourteen-days-forecast-dashboard>
                  </div>
              </div>
              <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                  <h2>Resort Summary</h2>
                  <div class="mt-1 mb-3">
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('Y-m-d') }}</h6>
                  </div>
                  <div class="table-wrapper">
                    <resort-summary resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_resort']) ? $data['date_resort'] : '' }}"></resort-summary>
                  </div>
              </div>
              <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                  <h2>7 Days Breakfast Buffet Forecast Summary</h2>
                  <div class="mt-1 mb-3">
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_forecast']) ? date('d F, Y', strtotime($data['date_forecast'])) : date('d F, Y') }}</h6>
                  </div>
                  <div class="table-wrapper">
                    <seven-days-forecast-breakfast resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_forecast']) ? $data['date_forecast'] : '' }}"></seven-days-forecast-breakfast>
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
                    <top-most-selling-item resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_fnb']) ? $data['date_fnb'] : '' }}"></top-most-selling-item>
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
                  <vip-guest-list resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_vip_details']) ? $data['date_vip_details'] : '' }}"></vip-guest-list>
                </div>
              </div>
              {{--<div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                <h2>Cancellation Resort Summary</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                  <cancellation-summary resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_resort']) ? $data['date_resort'] : '' }}"></cancellation-summary>
                </div>
              </div>--}}
              {{--<div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                <h2>MTD Cancellation Details</h2>
                <div class="mt-1 mb-3">
                  <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                </div>
                <div class="table-wrapper">
                  <cancellation-details-mtd resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_resort']) ? $data['date_resort'] : '' }}"></cancellation-details-mtd>
                </div>
              </div>--}}
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
                      <ayana-cruises-revenue resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_resort']) ? $data['date_resort'] : '' }}"></ayana-cruises-revenue>
                    </div>
                </div>
              </section>
            @endif
          @else
            <section id="pnb-revenue">
              <div class="divider"></div>
              <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; position: relative;">
                  <h2>PNB1 - Ayana Cruises</h2>
                  <div class="mt-1 mb-3">
                    <h6 class="text-muted">* Posting date : {{ isset($data['date_resort']) ? date('d F, Y', strtotime($data['date_resort'])) : date('d F, Y') }}</h6>
                  </div>
                  <div class="table-wrapper">
                    <ayana-cruises-revenue resort="{{ isset($data['resort']) ? $data['resort'] : '' }}" business-date="{{ isset($data['date_resort']) ? $data['date_resort'] : '' }}"></ayana-cruises-revenue>
                  </div>
              </div>
            </section>
          @endif
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

      // $('.datatable-able').DataTable({
      //   "dom":'<"abs-search"f>rtip',
      //   "scrollX":true,
      //   "autoWidth":false,
      //   "lengthChange":false,
      //   "columnDefs": [{
      //     "targets": [1],
      //     render: function(data, type, row, meta) {
      //       if(data){
      //         if (type == 'display' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
      //            return moment(data).format('DD-MMM-YYYY');
      //         }else if (type == 'filter' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
      //            return moment(data).format('DD-MMM-YYYY');
      //         }else{
      //           return "-";
      //         }
      //       } else { return "Not Available" }
      //     }
      //   }]
      // });
      // $('.datatable-vip').DataTable({
      //   "dom":'<"abs-search"f>rtip',
      //   "scrollX":true,
      //   "autoWidth":false,
      //   "lengthChange":false,
      //   "columnDefs": [{
      //     "targets": [0, 5, 6],
      //     render: function(data, type, row, meta) {
      //         if(data){
      //           if (type == 'display' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
      //              return moment(data).format('DD-MMM-YYYY');
      //           }else if (type == 'filter' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
      //              return moment(data).format('DD-MMM-YYYY');
      //           }else{
      //             return "-";
      //           }
      //         } else { return "Not Available" }
      //     }
      //   }]
      // });
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
