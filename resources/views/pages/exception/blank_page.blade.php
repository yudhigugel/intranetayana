@extends('layouts.default')

@section('title', 'Invoice List')
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
      <li aria-current="page" class="breadcrumb-item active"><span>Not Available</span></li></ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; min-height: 50vh">
                    <div class="d-flex align-items-center" style="min-height: 40vh">
                      <div class="flex-fill text-center">
                        <div class="mb-2">
                          <i class="fa-5x fa fa-frown-o"></i>
                        </div>
                        <div>
                          @if(Session::get('error_detail_invoice'))
                          <h6 class="text-muted">{{ Session::get('error_detail_invoice') }}</h6>
                          @else
                          <h6 class="text-muted">Opps, No Data Found</h6>
                          @endif
                          <a href="javascript:void(0)" onclick="window.history.go(-1); return false;">Go Back</a>
                        </div>
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
@endsection
