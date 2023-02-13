@extends('layouts.default')

@section('title', 'Invoice List')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
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
    text-align: center;
    border: 1px solid #ddd;
    font-size:11px !important;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Purchase Order</span></li></ol>
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
                          <h2> SAP PURCHASE ORDER </h2>
                          <h3> Purchase Order Detail</h3>
                          <h5> @php echo ($data['date_start']!==$data['date_end'])? date('d F Y',strtotime($data['date_start'])).' - '.date('d F Y',strtotime($data['date_end'])) : date('d F Y',strtotime($data['date_start'])) @endphp</h5>
                    </div>
                    <div class="pt-3 col-5">
                      {{--<form method="GET" action="">
                        <div class="form-group col-md-6 float-right">
                          <div class="mb-1">
                            <small style="color:#000;text-align: right;">Pick a date</small>
                          </div>
                          <input type="text" class="form-control" name="date" id="daterange" value="{{date('m/d/Y',strtotime($data['date_start'])) }} - {{ date('m/d/Y',strtotime($data['date_end'])) }}">

                        </div>
                      </form>--}}
                    </div>
                  </div>
                </div>
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead>
                                <tr>
                                  <th class="bg-secondary text-white text-left" style="width: 4%">NO.</th>
                                  <th class="bg-secondary text-white text-left" style="width: 14%">SAP <br>MATERIAL GROUP</th>
                                  <th class="bg-secondary text-white text-left" style="width: 13%">SAP <br>MATERIAL NO.</th>
                                  <th class="bg-secondary text-white text-left" style="width: 25%">SAP <br>MATERIAL DESC</th>
                                  <th class="bg-secondary text-white text-left">PO <br> UNIT</th>
                                  <th class="bg-secondary text-white text-left">PO <br> CURRENCY</th>
                                  <th class="bg-secondary text-white text-left">PO <br> QTY</th>
                                  <th class="bg-secondary text-white text-left" style="width: 10%">PO <br> PRICE</th>
                                  <th class="bg-secondary text-white text-left" style="width: 10%">TOTAL AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['po']))
                                  @php
                                    $qty_all=0;
                                    $price_all=0;
                                    $total_all=0;
                                  @endphp
                                  @foreach($data['po'] as $dummy)
                                  @php
                                    $qty_all += $dummy->PO_QTY_ITEM;
                                    $price_all += $dummy->PO_PRICE;
                                    $total_all += $dummy->TOTAL_AMOUNT;
                                  @endphp
                                  <tr>
                                      <td class="text-center">{{ $loop->iteration }}</td>
                                      <td class="text-center">{{ $dummy->MATERIAL_GROUP }}</a></td>
                                      <td class="text-center">{{ $dummy->MATERIAL_NO }}</td>
                                      <td class="text-left">{{ $dummy->MATERIAL_DESC }}</td>
                                      <td class="text-center">{{ $dummy->PO_UNIT }}</td>
                                      <td class="text-center">{{ $dummy->PO_CURRENCY }}</td>
                                      <td class="text-right">{{ number_format($dummy->PO_QTY_ITEM) }}</td>
                                      <td class="text-right">{{ number_format($dummy->PO_PRICE) }}</td>
                                      <td class="text-right">{{ number_format($dummy->TOTAL_AMOUNT) }}</td>
                                  </tr>
                                  @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="6" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($qty_all) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($price_all) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_all) }}</td>
                              </tr>
                            </tfoot>
                        </table>
                        <div class="mt-1">
                          <small class="text-muted">* Data source from SAP</small>
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
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<script>
 $(document).ready(function() {
    $('#daterange').daterangepicker(null, function(start, end, label) {
        const date_start = moment(start).format('YYYY-MM-DD');
        const date_end = moment(end).format('YYYY-MM-DD');
        window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
        // console.log(start.toISOString(), end.toISOString(), label);
    });

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

    $('#content-table').DataTable();
  });
</script>
@endsection
