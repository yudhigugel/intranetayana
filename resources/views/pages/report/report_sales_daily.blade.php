@extends('layouts.default')

@section('title', 'Pos Culinary')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">

@endsection
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
    <li aria-current="page" class="breadcrumb-item active"><span>POS (Liu Li Palace)</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
      <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
      </div>
      <div class="card">
          <div class="card-body pb-0 bg-white" id="header">
            <div class="row">
              <div class="col-7">
                    <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                    <h2> POS Liu Li Palace </h2>
                    <h3> Daily Revenue Detail</h3>
                    <h5> Revenue Date : {{ date("d-M-y", strtotime($date_to_lookup)) }} </h5>
              </div>
              <div class="pt-3 col-5">
                <form method="GET" action="">
                  <div class="form-group col-md-6 float-right">
                    <div class="mb-1">
                      <small style="color:#000;text-align: right;">Pick a date</small>
                    </div>
                    {{-- <input type="text" class="form-control" name="date" id="daterange" value="{{date('m/d/Y',strtotime($data['date_start'])) }} - {{ date('m/d/Y',strtotime($data['date_end'])) }}"> --}}
                    <input disabled type="text" class="form-control datepicker" name="date" id="datepicker" value="{{ date('Y-m-d', strtotime($date_to_lookup)) }}">

                  </div>
                </form>
              </div>
            </div>
          </div>
          <hr class="my-0">
          <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
            <div class="table-wrapper">
              <div class="table-container-h table-responsive">
                  <table class="table table-border" id="content-table">
                    <thead>
                      <tr>
                        <th class="bg-secondary text-white text-center">F&B Outlet</th>
                        <th class="bg-secondary text-white text-center">Today Guest</a></th>
                        <th class="bg-secondary text-white text-center">Today Revenue(IDR)</a></th>
                        <th class="bg-secondary text-white text-center">MTD Guest</a></th>
                        <th class="bg-secondary text-white text-center">MTD Revenue(IDR)</a></th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($outlet) && $outlet)
                        @foreach($outlet as $ot)
                          <tr>
                            <td>{{ $ot->Tenant }}</td>
                            <td class="text-right">{{ number_format($ot->TodayGuest ? $ot->TodayGuest : 0, 0, ".",",") }}</td>
                            <td class="text-right"><a href="{{ route('ReportAyana.sales_daily_outlet') }}?{{ http_build_query(['date_start'=>app('request')->query('transaction_date'), 'date_end'=>app('request')->query('transaction_date'), 'outlet'=>$ot->Tenant]) }}">{{ number_format($ot->TodayRevenue ? $ot->TodayRevenue : 0, 0, ".",",") }}</a></td>
                            <td class="text-right">{{ number_format($ot->MtdGuest ? $ot->MtdGuest : 0, 0, ".",",") }}</td>
                            <td class="text-right"><a href="{{ route('ReportAyana.sales_daily_outlet_mtd') }}?{{ http_build_query(['date_start'=>app('request')->query('transaction_date'), 'date_end'=>app('request')->query('transaction_date'), 'outlet'=>$ot->Tenant]) }}">{{ number_format($ot->MtdRevenue ? $ot->MtdRevenue : 0, 0, ".",",") }}</a></td>
                          </tr>
                        @endforeach
                      @else
                      <tr>
                        <td colspan="5" class="text-center">No Data</td>
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
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
<script type="text/javascript" src="/js/app/report/jquery.floatingscroll.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
  $('#datepicker').datepicker({
    dateFormat: "yy-mm-dd",
    showWeek: true,
    changeYear: true,
    showButtonPanel: true,
    maxDate: new Date(),
    onSelect : function(text, obj){
      window.location.href="{{url()->current()}}?transaction_date="+text;
    }
    });
  $('#datepicker').prop('disabled', false);
</script>
@endsection
