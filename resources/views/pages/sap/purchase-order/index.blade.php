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
  <!-- Modal -->
  <div class="modal fade" id="modal-detail-po" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalLabel">Purchase Order Detail | <span class="po-number"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="overlay in align-items-center">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  

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
                          <h3> Purchase Order List</h3>
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
                          <table class="table table-border" id="content-table">
                            <thead>
                                <tr>
                                    <th class="bg-secondary text-white text-left" style="width: 5%">NO.</th>
                                    <th class="bg-secondary text-white text-left" style="width: 12%">PO <br> DOC NUMBER</th>
                                    <th class="bg-secondary text-white text-left" style="width: 10%">PO <br> DOC TYPE</th>
                                    <th class="bg-secondary text-white text-left" style="width: 10%">PO <br> DOC DATE</th>
                                    <th class="bg-secondary text-white text-left" style="width: 25%">PO <br> SUPPLIER / SUPPLYING PLANT</th>
                                    <th class="bg-secondary text-white text-left">PO <br> COMPANY CODE</th>
                                    <th class="bg-secondary text-white text-left">PO <br> PLANT</th>
                                    <th class="bg-secondary text-white text-left">PO <br> S_LOC</th>
                                    {{-- <th class="bg-secondary text-white text-left" style="width: 7%">TOTAL QTY</th> --}}
                                    <th class="bg-secondary text-white text-left" style="width: 7%">TOTAL AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                              @if(isset($data['po']))
                                @php
                                  $qty_all=0;
                                  $total_all=0;
                                @endphp
                                @foreach($data['po'] as $dummy)
                                  @php 
                                      $qty_all += $dummy->TOTAL_PO_QTY;
                                      $total_all += $dummy->TOTAL_PO_AMOUNT;
                                  @endphp
                                  <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><a class="detail-link" href="javascript:void(0)" data-po-number="{{ $dummy->DOC_NUMBER }}" data-toggle="modal" data-target="#modal-detail-po">{{ $dummy->DOC_NUMBER }}</a></td>
                                    <td class="text-left">{{ $dummy->DOC_TYPE_DESC }}</td>
                                    <td class="text-center">{{ date('d M Y', strtotime($dummy->DOC_DATE)) ? date('d M Y', strtotime($dummy->DOC_DATE)) : '-' }}</td>
                                    <td class="text-left">{{ $dummy->SUPPLIER }}</td>
                                    <td class="text-center">{{ $dummy->COMPANY_CODE }}</td>
                                    <td class="text-center">{{ $dummy->PO_PLANT }}</td>
                                    <td class="text-left">{{ $dummy->S_LOC_DESC }}</td>
                                    {{-- <td class="text-right">{{ number_format($dummy->TOTAL_PO_QTY) }}</td> --}}
                                    <td class="text-right">{{ number_format($dummy->TOTAL_PO_AMOUNT) }}</td>
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                            <!-- <tfoot>
                              <tr>
                                <td colspan="8" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($qty_all) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_all) }}</td>
                              </tr>
                            </tfoot> -->
                            <tfoot class="all-page">
                              <tr>
                                <td colspan="8" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                                {{-- <td class="qty-all-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td> --}}
                                <td class="amount-all-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                              </tr>
                            </tfoot>
                            <tfoot class="this-page">
                              <tr>
                                <td colspan="8" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL THIS PAGE</td>
                                {{-- <td class="qty-this-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td> --}}
                                <td class="amount-this-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
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
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/dataTables.searchBuilder.min.js"></script>

<script>
  function htmlDecode(input){
    var e = document.createElement('textarea');
    e.innerHTML = input;
    // handle case of empty input
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

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

    $('#content-table').DataTable({
        // dom: 'Qlfrtip',
        dom: 'rtip',
        pageLength: 50,
        columnDefs: [{type: "po_doc_type", targets: 2}],
        searchBuilder: {
            columns: [1,2,3,4,5,6,7],
            conditions:{
              string:{
                  '=': {
                    inputValue: function(el, that) {
                      var $_unescape = htmlDecode($(el)[0].val()) || '';
                      return [$_unescape];
                    }
                  }
              }
            },
            preDefined: {
              criteria:[{}]
            }
        },
        "search": {
          "regex": true
        },
        initComplete: function() {
            var text_order = 0;
            this.api().columns([1,2,3,4,6,7]).every( function (i) {
                var column = this;
                var text = ['PO. Doc Number', 'PO. Doc Type', 'PO. Doc Date', 'PO Supplier', 'Plant', 'Storage Location'];
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
        stateSave: true,
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

        footerCallback: function ( row, data, start, end, display ) {
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
            qtytotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            qtypageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END QTY */

            /* AMOUNT */
            // Total over all pages
            amounttotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            amountpageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END AMOUNT */
 
            // Update footer
            // $( api.column( 8 ).footer() ).html(
            //     '$'+pageTotal +' ( $'+ total +' total)'
            // );
            $('.qty-this-page').html(numberWithCommas(qtypageTotal));
            $('.qty-all-page').html(numberWithCommas(qtytotal));

            $('.amount-this-page').html(numberWithCommas(amountpageTotal));
            $('.amount-all-page').html(numberWithCommas(amounttotal));

        }
    }).on('stateSaveParams.dt', function (e, settings, data) {
        data.filtered = true;
        sessionStorage.setItem( 'dt_filter_state_'+ location.pathname, JSON.stringify(data) );
    });

    $(document).on('click', '.dtsb-clearAll', function(e){
      var table = $(e.target).parents('.table-wrapper').find('.table')[0];
      // stored = table.DataTable().searchBuilder.getDetails();         
      // console.log('click remove');
      $(table).DataTable().destroy();
      $(table).DataTable({
          dom: 'Qlfrtip',
          columnDefs: [{type: "po_doc_type", targets: 2}],
          searchBuilder: {
              columns: [1,2,3,4,5,6,7],
              conditions:{
                string:{
                    '=': {
                      inputValue: function(el, that) {
                        var $_unescape = htmlDecode($(el)[0].val()) || '';
                        return [$_unescape];
                      }
                    }
                }
              },
              preDefined: {
                criteria:[{}]
              }
          },
          "search": {
            "regex": true
          },
          stateSave: true,
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

          footerCallback: function ( row, data, start, end, display ) {
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
              qtytotal = api
                  .column( 7 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
   
              // Total over this page
              qtypageTotal = api
                  .column( 7, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              /* END QTY */

              /* AMOUNT */
              // Total over all pages
              amounttotal = api
                  .column( 8 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
   
              // Total over this page
              amountpageTotal = api
                  .column( 8, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              /* END AMOUNT */
   
              // Update footer
              // $( api.column( 8 ).footer() ).html(
              //     '$'+pageTotal +' ( $'+ total +' total)'
              // );
              $('.qty-this-page').html(numberWithCommas(qtypageTotal));
              $('.qty-all-page').html(numberWithCommas(qtytotal));

              $('.amount-this-page').html(numberWithCommas(amountpageTotal));
              $('.amount-all-page').html(numberWithCommas(amounttotal));

          }
      }).on('stateSaveParams.dt', function (e, settings, data) {
          data.filtered = true;
          sessionStorage.setItem( 'dt_filter_state_'+ location.pathname, JSON.stringify(data) );
      });
    })
  // END READY FUNCTION
  });

  $(document).on('click', '.detail-link', function(e){
    // var data_path = $(this).data('origin') ?? '';
    // var data_allowed = $(this).attr('href') ?? '';
    // if(data_path && data_allowed)
    //   sessionStorage.setItem( 'dt_path_from', JSON.stringify({'path':data_path, 'allowed':data_allowed}));
    $_data = $(this).data('po-number');
    $('.po-number').text($_data);
    $.ajax({
      url : `/sap/purchase_order_list/detail/${$_data}`,
      type : 'GET',
      success : function(resp){
        setTimeout(function(){
          $('#modal-detail-po .modal-body').html(resp);
          setTimeout(function(){
            $('#detail-po-table').DataTable();
          },200)
        });
      },
      error : function(xhr){
        $('#modal-detail-po .modal-body').html('<div class="text-center"><h5>Failed to get data, please try again</h5></div>');
        console.log(xhr);
      }
    });

    $('#modal-detail-po').on('hidden.bs.modal', function(){
      $(this).find('.modal-body').html('<div class="overlay in align-items-center"><img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader"></div>');
    });

  });
</script>
@endsection
