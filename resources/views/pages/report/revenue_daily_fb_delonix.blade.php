@extends('layouts.default')

@section('title', 'Revenue Report')
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
      <li class="breadcrumb-item"><a href="#">Daily Report POS</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Delonix</span></li></ol>
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
                        <h3> POS Daily Report</h3>
                        <h5>
                        @if($data['date_start']==$data['date_end'])
                         {{ date('d F Y',strtotime($data['date_start'])) }}
                        @else
                          {{ date('d F Y',strtotime($data['date_start'])) }} - {{ date('d F Y',strtotime($data['date_end'])) }}
                        @endif
                        </h5>
                  </div>
                  <div class="px-0 col-md-6 float-left">

                    <form method="GET" action="">
                      <div class="form-group col-md-6 float-right">
                          <small style="color:#000;text-align: right;">Pick a date range</small>
                          <input type="text" class="form-control" name="date" id="daterange" value="{{date('m/d/Y',strtotime($data['date_start'])) }} - {{ date('m/d/Y',strtotime($data['date_end'])) }}">

                      </div>
                    </form>
                        
                  </div>
                </div>
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead>
                              <tr>
                                <th class="bg-secondary text-white"	>No</th>
                                <th class="bg-secondary text-white"	>ID</th>
                                <th class="bg-secondary text-white"	>Outlet</th>
                                <th class="bg-secondary text-white"	>Date</th>
                                <th class="bg-secondary text-white"	>Time</th>
                                <th class="bg-secondary text-white"	>Subtotal</th>
                                <th class="bg-secondary text-white"	>Service</th>
                                <th class="bg-secondary text-white"	>Tax</th>
                                <th class="bg-secondary text-white"	>Discount</th>
                                <th class="bg-secondary text-white"	>Grand Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=0; @endphp
                              @foreach ($data['result']['data'] as $result)
                              @php $i++ @endphp
                              <tr>
                                <td style="text-align: left;">{{$i}}</td>
                                <td style="text-align: left;"><a href="/report/fnb_delonix/{{$result['GUESTCHECKID']}}">{{$result['GUESTCHECKID']}}</a></td>
                                <td style="text-align: left;">{{$result['OUTLET']}}</td>
                                <td  style="text-align: left;">{{$result['DATE']}}</td>
                                <td style="text-align: left;">{{$result['TIME']}}</td>
                                <td style="text-align: right;">{{ number_format($result['TOTAL_REVENUE']) }}</td>
                                <td style="text-align: right;">{{ number_format($result['TOTAL_SERVICE']) }}</td>
                                <td style="text-align: right;">{{ number_format($result['TOTAL_TAX']) }}</td>
                                <td style="text-align: right;">{{ number_format($result['TOTAL_DISCOUNT']) }}</td>
                                <td style="text-align: right;">{{ number_format($result['GRAND_TOTAL']) }}</td>
                              </tr>
                              @endforeach
                              <tr>
                                <td colspan="5"  style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($data['result']['total_revenue_daily']) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($data['result']['total_service_daily']) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($data['result']['total_tax_daily']) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($data['result']['total_discount_daily']) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($data['result']['grand_total_daily']) }}</td>
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
  <script type="text/javascript" src="/js/vendor/moment.min.js"></script>
  <script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
  
  <script type="text/javascript" src="/js/app/report/jquery.floatingscroll.js"></script>
  <script>
     $(document).ready(function() {
          $('#daterange').daterangepicker(null, function(start, end, label) {
              const date_start = moment(start).format('YYYY-MM-DD');
              const date_end = moment(end).format('YYYY-MM-DD');
              window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
              // console.log(start.toISOString(), end.toISOString(), label);
          });
      });
  
  </script>
@endsection