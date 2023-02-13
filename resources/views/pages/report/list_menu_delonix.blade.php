@extends('layouts.default')

@section('title', 'Menu List Delonix')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">

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
      <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Menu List Delonix</span></li></ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                  
                  <div class="px-0 col-md-6 float-left">
                    <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                    <h2> DELONIX HOTEL KARAWANG </h2>
                    <h3> POS Menu List</h3>
                    <h5>
                    </h5>
                  </div>
                  <div class="px-0 col-md-6 float-left">
                  </div>
                </div>
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead>
                              <tr>
                                <th class="bg-secondary text-white"	>ID</th>
                                <th class="bg-secondary text-white"	>Object Number</th>
                                <th class="bg-secondary text-white"	>Menu Def ID</th>
                                <th class="bg-secondary text-white"	>Major Group</th>
                                <th class="bg-secondary text-white"	>Family Group</th>
                                <th class="bg-secondary text-white"	>Item Name</th>
                                <th class="bg-secondary text-white"	>Price</th>
                                <!-- <th class="bg-secondary text-white"	>Last Update</th> -->
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($data['result'] as $result)
                              <tr>
                                <td style="text-align: left;">{{(int)$result->MENUITEMMASTERID}}</td>
                                <td  style="text-align: left;">{{(int)$result->OBJECTNUMBER}}</td>
                                <td style="text-align: left;">{{(int)$result->MENUITEMDEFID}}</td>
                                <td style="text-align: left;">{{$result->MAJORGROUP}}</td>
                                <td style="text-align: left;">{{$result->FAMILYGROUP}}</td>
                                <td style="text-align: left;">{{$result->ITEMNAME}}</td>

                                <td style="text-align: right;">{{ number_format($result->PRICE) }}</td>
                                
                                <!-- <td style="text-align: left;">{{$result->LASTPRICEUPDATE}}</td> -->
                              </tr>
                              @endforeach
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
@endsection