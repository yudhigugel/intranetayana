@extends('layouts.default')

@section('title', 'POS Simphony Report')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

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
  .pd-new{
    margin-bottom: .9em
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li> 
      <li class="breadcrumb-item"><a href="/report/fnb_ayana">POS</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>POS (Simphony)</span></li></ol>
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
                        <h2> POS Simphony </h2>
                        <h3> {{ strtoupper($data_outlet) }} - TRANSACTION</h3>
                        <h5>
                        @if(isset($data) && count($data))
                          @if(isset($is_ytd) && $is_ytd)
                            Transaction Year To Date : {{ date('Y',strtotime($data['date_start'])) }}
                          @elseif($data['date_start']==$data['date_end'])
                            Transaction Date : {{ date('d F Y',strtotime($data['date_start'])) }}
                          @else
                            Transaction Date : {{ date('d F Y',strtotime($data['date_start'])) }} - {{ date('d F Y',strtotime($data['date_end'])) }}
                          @endif
                        @else 
                          Transaction Date : Unknown
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
                                <th class="bg-secondary text-white"	style="width: 5%">NO</th>
                                <th class="bg-secondary text-white"	style="width: 5%">CHECKNUMID</th>
                                <th class="bg-secondary text-white"	>DATE</th>
                                <!-- <th class="bg-secondary text-white"	>TIME</th> -->
                                <th class="bg-secondary text-white"	style="width: 13%">SUBTOTAL</th>
                                <th class="bg-secondary text-white"	style="width: 13%">SERVICE</th>
                                <th class="bg-secondary text-white"	style="width: 13%">TAX</th>
                                <th class="bg-secondary text-white"	style="width: 13%">DISCOUNT</th>
                                <th class="bg-secondary text-white"	style="width: 13%">GRAND TOTAL</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(isset($data_transaction) && count($data_transaction))
                                @php $i=0; @endphp
                                @foreach ($data_transaction as $key => $result)
                                  @if(strtolower($key) != 'total')
                                    @php $i++ @endphp
                                    <tr>
                                      <td style="text-align: center;">{{$i}}</td>
                                      <td style="text-align: left;"><a href="{{ route('ayana.pos_revenue.outlet_detail') }}?COSTCENTERID={{ $data_costcenter }}&CHECKNUM={{$key}}&TRANSDATE={{$result->DATE_TRANS}}">{{$key}}</a></td>
                                      <td  style="text-align: left;">{{$result->DATE_TRANS}}</td>
                                      <!-- <td style="text-align: left;">{{$result->TIME_TRANS}}</td> -->
                                      <td style="text-align: right;">{{ number_format($result->SUBTOTAL) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->SERVICECHARGE) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->TAXTOTAL) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->DISCOUNT) }}</td>
                                      <td style="text-align: right;">{{ number_format($result->CHECKTOTAL) }}</td>
                                    </tr>
                                  @endif
                                @endforeach
                              @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td colspan="3" style="text-align: center;background: #ececec;color:#000;font-weight:bold;">TOTAL</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format(isset($data_transaction['TOTAL']->SUBTOTAL) ? $data_transaction['TOTAL']->SUBTOTAL : 0) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format(isset($data_transaction['TOTAL']->SERVICECHARGE) ? $data_transaction['TOTAL']->SERVICECHARGE : 0) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format(isset($data_transaction['TOTAL']->TAXTOTAL) ? $data_transaction['TOTAL']->TAXTOTAL : 0) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format(isset($data_transaction['TOTAL']->DISCOUNT) ? $data_transaction['TOTAL']->DISCOUNT : 0) }}</td>
                                  <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format(isset($data_transaction['TOTAL']->CHECKTOTAL) ? $data_transaction['TOTAL']->CHECKTOTAL : 0) }}</td>
                                </tr>
                            </tfoot>
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
  <script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script type="text/javascript" src="/js/app/report/jquery.floatingscroll.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

  <script>
     $(document).ready(function() {
          $('#daterange').daterangepicker(null, function(start, end, label) {
              const date_start = moment(start).format('YYYY-MM-DD');
              const date_end = moment(end).format('YYYY-MM-DD');
              window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
              // console.log(start.toISOString(), end.toISOString(), label);
          });

          $('#content-table').DataTable({
            "dom":'<<"button-wrapper">B"abs-search"f>rtip',
            buttons: {
              dom: {
                button: {
                  tag: 'button',
                  className: 'pd-new'
                }
              },
              buttons: 
              [{
                extend: 'excelHtml5',
                className : 'btn btn-primary',
                text: 'Export Excel',
                messageTop: function () {
                  try {
                    var date = $("#datepicker").datepicker("getDate");
                    date = $.datepicker.formatDate("dd-mm-yy", date);
                    return `Report Date : ${date}`;
                  } catch(error){}
                },
                exportOptions: {
                  columns: [ 1, 2, 3, 4, 5, 6, 7 ]
                },
                action: function(e, dt, button, config) {
                  var data_revenue = [];
                  try {
                    var table = $('#content-table').DataTable()
                    data_revenue = table.rows().data();
                  } catch(error){}

                  if(data_revenue.length > 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this,e, dt, button, config);
                    return false;
                  } else {
                    Swal.fire({
                      icon: 'info',
                      title: 'Oops...',
                      text: 'Data is empty, nothing to export',
                    })
                    return false;
                  }
                }
              }]
            },
            "scrollX":true,
            "autoWidth":false,
            "paging":false,
            "lengthChange":false,
          });
      });
  
  </script>
@endsection