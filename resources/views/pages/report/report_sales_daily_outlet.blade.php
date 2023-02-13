@extends('layouts.default')

@section('title', 'Pos Culinary')
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
      <li class="breadcrumb-item"><a href="/report/sales_daily">POS</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>POS (Liu Li Palace)</span></li></ol>
  </nav>

 <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                  <div class="px-0 mb-3 col-md-6 float-left">
                        <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                        <h2> POS Liu Li Palace </h2>
                        <h3> {{ strtoupper($data['outlet']) }} POS TRANSACTION</h3>
                        <h5>
                        @if(isset($data) && $data['date_start']==$data['date_end'])
                         {{ date('d F Y',strtotime($data['date_start'])) }}
                        @else
                          {{ date('d F Y',strtotime($data['date_start'])) }} - {{ date('d F Y',strtotime($data['date_end'])) }}
                        @endif 
                        </h5>
                  </div>
                </div>
                <hr class="my-0">
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead>
                              <tr>
                                <th class="bg-secondary text-white"	style="width: 4%">NO</th>
                                <th class="bg-secondary text-white"	style="width: 14%">RECEIPT NUMBER</th>
                                <th class="bg-secondary text-white"	style="width: 8%">DATE</th>
                                <th class="bg-secondary text-white"	style="width: 10%">EVENT TYPE</th>
                                <th class="bg-secondary text-white"	style="width: 11%">GROSS SALES</th>
                                <th class="bg-secondary text-white"	style="width: 8%">DISCOUNT</th>
                                <th class="bg-secondary text-white"	style="width: 10%">REFUNDS</th>
                                <th class="bg-secondary text-white"	style="width: 10%">NET SALES</th>
                                <th class="bg-secondary text-white"	style="width: 7%">GRATUITY</th>
                                <th class="bg-secondary text-white"	style="width: 7%">TAX</th>
                                <th class="bg-secondary text-white"	style="width: 11%">GRAND TOTAL</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $TotalGross=0;$TotalDiscount=0;$TotalRefunds=0;
                                    $TotalNetSales=0;$TotalGratuity=0;$TotalTax=0;$TotalCollected=0;
                                @endphp
                              @if(isset($data_transaction) && count($data_transaction))
                                @php $i=0; @endphp
                                @foreach ($data_transaction as $key => $result)
                                  @if(strtolower($key) != 'total')
                                    @php 
                                        $i++;
                                        $TotalGross +=$result->GrossSales;
                                        $TotalDiscount +=$result->Discount;
                                        $TotalRefunds +=$result->Refunds;

                                        $TotalNetSales +=$result->NetSales;
                                        $TotalGratuity +=$result->Gratuity;
                                        $TotalTax +=$result->Tax;
                                        $TotalCollected +=$result->TotalCollected;
                                    @endphp
                                    <tr>
                                      <td style="text-align: center;">{{$i}}</td>
                                      <td><a href="{{ route('ReportAyana.report_sales_daily_detail') }}?{{ http_build_query(['ReceiptNumber'=>$result->ReceiptNumber,'TransactionDate'=>$result->TransactionDate,'EventType'=>$result->EventType]) }}">{{ $result->ReceiptNumber }}</a></td>
                                      <td style="text-align: left;">{{ date("d-M-y", strtotime($result->TransactionDate)) }}</td>
                                      <td style="text-align: left;">{{$result->EventType}}</td>
                                      <td style="text-align: right;">{{ number_format($result->GrossSales) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->Discount) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->Refunds) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->NetSales) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->Gratuity) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->Tax) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->TotalCollected) }}</td>
                                    </tr>
                                  @endif
                                @endforeach
                                <tr>
                                  <td colspan="4"  style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalGross) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalDiscount) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalRefunds) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalNetSales) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalGratuity) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalTax) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($TotalCollected) }}</td>
                                  
                                </tr>
                              @else
                                <tr>
                                  <td colspan="10" class="text-center">No Data</td>
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