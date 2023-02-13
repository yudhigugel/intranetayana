@extends('layouts.default')

@section('title', 'Invoice List')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.yadcf.css">
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

  .dtsb-delete:first-of-type {
    display: none !important;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Statement List</span></li></ol>
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
                        @php

                        @endphp
                          <h2> SAP STATEMENT </h2>
                          <h3> Active Statement List</h3>
                          <h5> @php echo ($data['date_start']!==$data['date_end'])? date('d F Y',strtotime($data['date_start'])).' - '.date('d F Y',strtotime($data['date_end'])) : date('d F Y',strtotime($data['date_start'])) @endphp</h5>
                    </div>
                    <div class="pt-3 col-5">
                      <form method="GET" action="">
                        <div class="form-group col-md-6 float-right">
                          <div class="mb-1">
                            <small style="color:#000;text-align: right;">Pick a date</small>
                          </div>
                          <input type="text" class="form-control" name="date" id="daterange" value="{{date('m/d/Y',strtotime($data['date_start'])) }} - {{ date('m/d/Y',strtotime($data['date_end'])) }}">

                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <hr class="my-0">
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="filter-wrapper mb-3">
                      <div class="row filter-align">
                      </div>
                    </div>
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <div id="reactiveListener">
                          <table class="table table-border" id="content-table">
                            <thead>
                              <tr>
                                <th class="bg-secondary text-white text-center" style="width: 5%">NO</th>
                                <th class="bg-secondary text-white text-center" style="width: 7%">STATEMENT NO.</th>
                                <th class="bg-secondary text-white text-center" style="width: 7%">COMPANY CODE</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 8%">POSTING DATE</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 10%">TYPE</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 8%">SAP <br> CUSTOMER ID</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 20%">CUSTOMER NAME</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 9%">SUBTOTAL</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 9%">VAT</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 9%">TOTAL</a></th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(isset($invoice) && $invoice)
                                @php
                                  $total=0;
                                  $subtotal_all=0;
                                  $tax_all=0;
                                @endphp
                                @foreach($invoice as $inv)
                                  @php 
                                    // VAT ASUMSI 10%, JADI DIBAGI TOTAL PEMBAYARAN UNTUK MENDAPATKAN TAXNYA BERAPA
                                    $subtotal = (int)$inv->WRBTR / 1.1;
                                    $tax = (int)$subtotal * 0.1;

                                    $subtotal_all += $subtotal;
                                    $tax_all += $tax;
                                  @endphp
                                  <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><a class="detail-link" data-origin="{{ '/'.request()->path() }}" href="{{route('sap.invoice.detail', $inv->CAANO)}}">{{$inv->CAANO}}</a></td>
                                    <td class="text-center">{{$inv->BUKRS}}</td>
                                    <td class="text-center">{{ isset($inv->BUDAT) && date('d M Y', strtotime($inv->BUDAT)) ? date('d M Y', strtotime($inv->BUDAT)) : '' }}</td>
                                    <td class="text-center">{{$inv->INVTY}}</td>
                                    <td class="text-center">{{$inv->KUNNR}}</td>
                                    <td class="text-left">{{ $inv->NAME_FIRST }}</td>
                                    <td class="text-right">{{ number_format($subtotal)}}</td>
                                    <td class="text-right">{{ number_format($tax)}}</td>
                                    <td class="text-right">{{ number_format($inv->WRBTR)}}</td>
                                    {{--<td class="text-left">{{ App::call('App\Http\Controllers\SAP\RealEstate@check_invoice_status_blade', ['no_invoice'=>$inv->CAANO, 'plant_code'=>strtolower($inv->BUKRS)]) }}</td>--}}
                                  </tr>
                                  @php
                                    $total += $inv->WRBTR;
                                  @endphp
                                @endforeach
                              {{--@else
                              <tr>
                                <td colspan="10">No Data</td>
                              </tr>--}}
                              @endif
                            </tbody>
                            <tfoot class="all-page">
                              <tr>
                                <td colspan="7" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                                <td class="subtotal-all-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="tax-all-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="total-all-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                              </tr>
                            </tfoot>
                            <tfoot class="this-page">
                              <tr>
                                <td colspan="7" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL THIS PAGE</td>
                                <td class="subtotal-this-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="tax-this-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="total-this-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                              </tr>
                            </tfoot>
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
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/dataTables.searchBuilder.min.js"></script>
<script src="/template/js/jquery.yadcf.js"></script>

<script>
 $(document).ready(function() {
    $('#daterange').daterangepicker(null, function(start, end, label) {
        const date_start = moment(start).format('YYYY-MM-DD');
        const date_end = moment(end).format('YYYY-MM-DD');
        window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
        // console.log(start.toISOString(), end.toISOString(), label);
    });

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

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

    $('#content-table').DataTable({
        // dom: 'Qlfrtip'
        // "processing": true,
        // cache: false,
        // "language": {
        //    "processing": ""
        // },
        // "columns": [
        //    { "data": "NUM_ORDER"},
        //    { "data": "CAANO", className: 'text-left'},
        //    { "data": "BUKRS", className: 'text-left'},
        //    { "data": "GJAHR", className: 'text-right'},
        //    { "data": "INVTY", className: 'text-right'},
        //    { "data": "KUNNR"},
        //    { "data": "NAME_FIRST"},
        //    { "data": "WRBTR"},
        //    { "data": "WRBTR"},
        //    { "data": "WRBTR"},
        // ],
        // "serverSide": true,
        // "ajax": {
        //   "type" : "GET",
        //   "url" : "/sap/invoice_list_getData",
        //   dataSrc: function(data){
        //     if(data.length == 0){
        //       return [];
        //     }
        //     else {
        //       return data.data;
        //     }
        //   },
        //   error: function (jqXHR, textStatus, errorThrown) {
        //     var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
        //     Swal.fire({
        //       title: "Oops..",
        //       text: error,
        //       icon: "error",
        //       showConfirmButton: true
        //     });
        //   }
        // },
        dom: 'rtip',
        "pageLength": 50,
        // searchBuilder: {
        //     columns: [1,3,4,5,6,7],
        //     preDefined: {
        //       criteria:[{}]
        //     }
        // },
        stateSave: false,
        initComplete: function() {
            var text_order = 0;
            this.api().columns([1,2,3,4,5,6]).every( function (i) {
                var column = this;
                var text = ['Statement No.', 'Company Code', 'Posting Date', 'Type', 'Cust. ID', 'Cust. Name'];
                var col_length = 'col-2';
                var select = $(`<div class="content-filter ${col_length}"><label><small>Filter By ${text[text_order]}</small></label><div><select class="form-control select2 filter-select-${i} mr-3"><option value="">Pilih Data Disini</option></select></div></div>`)
                    .appendTo( $('.filter-align') )
                    .on('select2:select', function (e) {
                        var value = e.params.data.id;
                        // var val = $.fn.dataTable.util.escapeRegex(
                        //     $(this).val()
                        // );
                        var val = $.fn.dataTable.util.escapeRegex(
                            value
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    }).on("select2:unselecting", function(e) {
                        var val = $.fn.dataTable.util.escapeRegex("");
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                        $(this).data('state', 'unselected');
                    }).on("select2:open", function(e) {
                        try {
                          if ($(this).data('state') === 'unselected') {
                              $(this).removeData('state'); 

                              var self = $(this).find('.select2')[0];
                              setTimeout(function() {
                                  $(self).select2('close');
                              }, 0);
                          }
                        } catch(error){}   
                    });
                column.data().unique().sort().each( function ( d, j ) {
                    try {
                      d = d.replace(/<\/?[^>]+(>|$)/g, "");
                    } catch(error){}
                    $(`.filter-select-${i}`).append( '<option value="'+d+'">'+d+'</option>' )
                });
                text_order++;
            });
            $('.select2').select2({
              placeholder: 'Choose data',
              allowClear: true
            });
        },
        stateSaveCallback: function(settings,data) {
            var preserve_data = sessionStorage.getItem( 'dt_normal_state_' + location.pathname ) ?? '{}';
            if(!Object.keys(sessionStorage).includes('dt_filter_state_' + location.pathname) && preserve_data.length == 2)
              sessionStorage.setItem( 'dt_normal_state_'+ location.pathname, JSON.stringify(data))
        },
        stateLoadCallback: function(settings) {
          var filtered = sessionStorage.getItem( 'dt_filter_state_' + location.pathname ) ?? '{}';
          var path_name = sessionStorage.getItem( 'dt_path_from' ) ?? '{}';

          sessionStorage.removeItem( 'dt_filter_state_' + location.pathname );
          sessionStorage.removeItem( 'dt_path_from' );

          if(filtered.length > 2 && path_name.length > 2)
            return JSON.parse( filtered );
          else
            return JSON.parse( sessionStorage.getItem('dt_normal_state_'+ location.pathname) );
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            /* QTY */
            // Total over all pages
            subtotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            subtotalpage = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END QTY */

            /* AMOUNT */
            // Total over all pages
            taxtotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            taxtpageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END AMOUNT */

            /* AMOUNT */
            // Total over all pages
            total = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END AMOUNT */
 
            // Update footer
            // $( api.column( 8 ).footer() ).html(
            //     '$'+pageTotal +' ( $'+ total +' total)'
            // );
            $('.subtotal-this-page').html(numberWithCommas(subtotalpage));
            $('.subtotal-all-page').html(numberWithCommas(subtotal));

            $('.tax-this-page').html(numberWithCommas(taxtpageTotal));
            $('.tax-all-page').html(numberWithCommas(taxtotal));

            $('.total-this-page').html(numberWithCommas(pageTotal));
            $('.total-all-page').html(numberWithCommas(total));

        }
    }).on('stateSaveParams.dt', function (e, settings, data) {
        data.filtered = true;
        sessionStorage.setItem( 'dt_filter_state_'+ location.pathname, JSON.stringify(data) );
    });
    

    $(document).on('click', '.dtsb-clearAll', function(e){
        var table = $(e.target).parents('.table-wrapper').find('.table')[0];
        $(table).DataTable().destroy();
        $(table).DataTable({
          dom: 'Qlfrtip',
          searchBuilder: {
              columns: [1,3,4,5,6,7],
              preDefined: {
                criteria:[{}]
              }
          },
          stateSave: true,
          initComplete : function(settings, json){
            // try {
            //   console.log('ready');
            //   $.each(settings.oInstance.api().data(), function(index, item){
            //       const baseURI = "{{ route('sap.invoice.payment.status') }}";
            //       const invoice = $('#status-payment-'+index).data('invoice');
            //       const plant_code = $('#status-payment-'+index).data('plant');

            //       var params = {
            //        'no_invoice' : invoice,
            //        'plant_code' : plant_code,
            //       }
            //       $.ajax({
            //         url : baseURI,
            //         method : 'GET',
            //         data : params,
            //         success : function(res){
            //           settings.oInstance.api().cell(index, 2).data(res);
            //         },
            //         error : function(xhr){}
            //       })

            //   });
            // } catch(error){ console.log(error) }
          },
          stateSaveCallback: function(settings,data) {
              var preserve_data = sessionStorage.getItem( 'dt_normal_state_' + location.pathname ) ?? '{}';
              if(!Object.keys(sessionStorage).includes('dt_filter_state_' + location.pathname) && preserve_data.length == 2)
                sessionStorage.setItem( 'dt_normal_state_'+ location.pathname, JSON.stringify(data))
          },
          stateLoadCallback: function(settings) {
            var filtered = sessionStorage.getItem( 'dt_filter_state_' + location.pathname ) ?? '{}';
            var path_name = sessionStorage.getItem( 'dt_path_from' ) ?? '{}';

            sessionStorage.removeItem( 'dt_filter_state_' + location.pathname );
            sessionStorage.removeItem( 'dt_path_from' );

            if(filtered.length > 2 && path_name.length > 2)
              return JSON.parse( filtered );
            else
              return JSON.parse( sessionStorage.getItem('dt_normal_state_'+ location.pathname) );
          },
          "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;
   
              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              /* QTY */
              // Total over all pages
              subtotal = api
                  .column( 7 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
   
              // Total over this page
              subtotalpage = api
                  .column( 7, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              /* END QTY */

              /* AMOUNT */
              // Total over all pages
              taxtotal = api
                  .column( 8 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
   
              // Total over this page
              taxtpageTotal = api
                  .column( 8, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              /* END AMOUNT */

              /* AMOUNT */
              // Total over all pages
              total = api
                  .column( 9 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
   
              // Total over this page
              pageTotal = api
                  .column( 9, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              /* END AMOUNT */
   
              // Update footer
              // $( api.column( 8 ).footer() ).html(
              //     '$'+pageTotal +' ( $'+ total +' total)'
              // );
              $('.subtotal-this-page').html(numberWithCommas(subtotalpage));
              $('.subtotal-all-page').html(numberWithCommas(subtotal));

              $('.tax-this-page').html(numberWithCommas(taxtpageTotal));
              $('.tax-all-page').html(numberWithCommas(taxtotal));

              $('.total-this-page').html(numberWithCommas(pageTotal));
              $('.total-all-page').html(numberWithCommas(total));

          }
      }).on('stateSaveParams.dt', function (e, settings, data) {
          data.filtered = true;
          sessionStorage.setItem( 'dt_filter_state_'+ location.pathname, JSON.stringify(data) );
      });
    })
  });

  $(document).on('click', '.detail-link', function(e){
    var data_path = $(this).data('origin') ?? '';
    var data_allowed = $(this).attr('href') ?? '';
    if(data_path && data_allowed)
      sessionStorage.setItem( 'dt_path_from', JSON.stringify({'path':data_path, 'allowed':data_allowed}));
  });
</script>
@endsection
