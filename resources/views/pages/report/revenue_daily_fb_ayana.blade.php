@extends('layouts.default')

@section('title', 'POS Simphony Report')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/searchBuilder.dataTables.min.css">
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
    text-align: left;
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
      <li class="breadcrumb-item"><a href="#">POS</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>POS (Simphony)</span></li></ol>
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
                          <h2> POS Simphony </h2>
                          <h3> Daily Transaction Detail</h3>
                          <h5> Transaction Date : {{ date("d-M-y", strtotime($date_to_lookup)) }} </h5>
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
                    <div class="filter-wrapper mb-3">
                      <div class="d-flex" style="justify-content: space-around;">
                        <div class="">
                          <h6 class="text-primary"><i class="mdi mdi-account-check"></i>&nbsp;TOTAL GUEST</h6>
                          <h3 class="text-black guest-today-all">0</h3>
                        </div>
                        <div class="">
                          <h6 class="text-primary"><i class="mdi mdi-chart-line"></i>&nbsp;TOTAL REVENUE</h6>
                          <h3 class="text-black revenue-today-all">0</h3>
                        </div>
                        <div class="">
                          <h6 class="text-primary"><i class="mdi mdi-account-check"></i>&nbsp;TOTAL MTD GUEST</h6>
                          <h3 class="text-black guest-mtd-all">0</h3>
                        </div>
                        <div class="">
                          <h6 class="text-primary"><i class="mdi mdi-chart-line"></i>&nbsp;TOTAL MTD REVENUE</h6>
                          <h3 class="text-black revenue-mtd-all">0</h3>
                        </div>
                      </div>
                      <hr class="my-3">
                      <div class="row filter-align">
                      </div>
                      
                    </div>
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-border" id="content-table">
                            <thead>
                              <tr>
                                <th class="bg-secondary text-white text-center" style="width: 5%">NO</th>
                                <th class="bg-secondary text-white text-center" style="width: 20%">F&B OUTLET</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 10%">RESORT</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 10%">SUB RESORT</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 10%">TODAY GUEST</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 20%">TODAY REVENUE (IDR)</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 10%">MTD GUEST</a></th>
                                <th class="bg-secondary text-white text-center" style="width: 20%">MTD REVENUE (IDR)</a></th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(isset($outlet) && $outlet)
                                @php 
                                  $total_today_rev=$total_mtd_rev=$total_today_guest=$total_mtd_guest=0;
                                @endphp
                                @foreach($outlet as $ot)
                                  <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $ot->OUTLET }}</td>
                                    <td>{{ $ot->RESORT }}</td>
                                    <td data-resort="{{ $ot->RESORT }}">{{ $ot->SUBRESORT }}</td>
                                    <td class="text-right">{{ number_format($ot->GUEST_TODAY ? $ot->GUEST_TODAY : 0) }}</td>
                                    <td class="text-right">
                                      <a href="{{ route('ayana.pos_revenue.outlet') }}?{{ http_build_query(['BUSINESSDATESTART'=>app('request')->query('business_date', date('Y-m-d')),'BUSINESSDATEEND'=>app('request')->query('business_date', date('Y-m-d')), 'COSTCENTERID'=>$ot->SAPCOSTCENTER]) }}">{{ number_format($ot->TODAY_REVENUE ? $ot->TODAY_REVENUE : 0) }}</a>
                                    </td>
                                    <td class="text-right">{{ number_format($ot->GUEST_MTD ? $ot->GUEST_MTD : 0) }}</td>
                                    <td class="text-right">
                                      <a href="{{ route('ayana.pos_revenue.outlet') }}?{{ http_build_query(['BUSINESSDATESTART'=>date('Y-m-01',strtotime(app('request')->query('business_date', date('Y-m-01')))),'BUSINESSDATEEND'=>app('request')->query('business_date', date('Y-m-d')), 'COSTCENTERID'=>$ot->SAPCOSTCENTER]) }}">{{ number_format($ot->MTD_REVENUE ? $ot->MTD_REVENUE : 0) }}</a>
                                    </td>
                                  </tr>
                                  @php 
                                    $total_today_rev += (int)$ot->TODAY_REVENUE;
                                    $total_mtd_rev += (int)$ot->MTD_REVENUE;
                                    $total_today_guest += (int)$ot->GUEST_TODAY;
                                    $total_mtd_guest += (int)$ot->GUEST_MTD;
                                  @endphp
                                @endforeach
                              @else
                              {{-- <tr>
                                <td colspan="5">No Data</td>
                              </tr> --}}
                              @endif
                            </tbody>
                            <!-- <tfoot>
                              <tr>
                                <td colspan="2" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_today_guest) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_today_rev) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_mtd_guest) }}</td>
                                <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_mtd_rev) }}</td>
                              </tr>
                            </tfoot> -->
                            {{--<tfoot class="all-page">
                              <tr>
                                <td colspan="4" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL OUTLET</td>
                                <td class="guest-today-all" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="revenue-today-all" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="guest-mtd-all" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="revenue-mtd-all" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                              </tr>
                            </tfoot>
                            <tfoot class="this-page">
                              <tr>
                                <td colspan="4" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL OUTLET CURRENT PAGE</td>
                                <td class="guest-today-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="revenue-today-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="guest-mtd-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                                <td class="revenue-mtd-page" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">-</td>
                              </tr>
                            </tfoot>--}}
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
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/dataTables.searchBuilder.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>

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
        // "pageLength": 50,
        paging : false,
        columnDefs:[{
          searchBuilderTitle: 'F&B OUTLET',
          targets: 1
        }],
        searchBuilder: {
          columns: [1,2,3,4,5,6],
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
        initComplete: function() {
            var text_order = 0;
            var table_obj = this.api();
            this.api().columns([2,3,1]).every( function (i) {
                var column = this;
                var text = ['Resort', 'Sub Resort', 'Outlet'];
                var col_length = 'col-4';
                // if(i==1)
                //   col_length = 'col-3'
                var select = $(`<div class="content-filter ${col_length}"><label>Filter By ${text[text_order]}</label><div><select class="form-control select2 filter-select-${i} mr-3"><option value="">Pilih Data Disini</option></select></div></div>`)
                    .appendTo( $('.filter-align') )
                    .on('select2:select', function (e) {
                        var value = e.params.data.id;
                        // var val = $.fn.dataTable.util.escapeRegex(
                        //     $(this).val()
                        // );
                        var val = $.fn.dataTable.util.escapeRegex(
                            value
                        );
                        // var column_search = [];
                        // try {
                        //   table_obj.columns(3).nodes().eq( 0 ).each(function (cell, i) {
                        //     if(cell.getAttribute('data-resort') == val){
                        //       var subresort = table_obj.cell(i, 3).data()
                        //       column_search.push(subresort);
                        //     }
                        //   });
                        // } catch(error){}
                        // column_search = column_search.filter((v, i, a) => a.indexOf(v) === i);
                        // console.log(column_search);

                        column
                            .search( '' )
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();

                        var target_array = [... e.target.classList];
                        if(target_array.includes('filter-select-2')){
                          var unique_search_subresort = [];
                          table_obj.column(3, { search:'applied' } ).data().each(function(value, index) {
                              if(unique_search_subresort.indexOf(value) === -1) {
                                unique_search_subresort.push(value);
                              }
                          });
                          $('.filter-select-3').select2('destroy').html('<option value="" selected disabled></option>')
                          $('.filter-select-3').select2({
                            placeholder: 'Choose data',
                            allowClear: true
                          });

                          var newOption_subresort = [];
                          $.each(unique_search_subresort, function(index, data){
                            newOption_subresort[index] = new Option(`${data}`, data, false, false);
                          });
                          $('.filter-select-3').append(newOption_subresort).trigger('change');
                          $('.filter-select-3').prop('disabled', false);
                        }
                        else if(target_array.includes('filter-select-3')){
                          var unique_search_outlet = [];
                          table_obj.column(1, { search:'applied' } ).data().each(function(value, index) {
                              if(unique_search_outlet.indexOf(value) === -1) {
                                unique_search_outlet.push(value);
                              }
                          });
                          $('.filter-select-1').select2('destroy').html('<option value="" selected disabled></option>')
                          $('.filter-select-1').select2({
                            placeholder: 'Choose data',
                            allowClear: true
                          });

                          var newOption_outlet = [];
                          $.each(unique_search_outlet, function(index, data){
                            newOption_outlet[index] = new Option(`${data}`, data, false, false);
                          });
                          $('.filter-select-1').append(newOption_outlet).trigger('change');
                          $('.filter-select-1').prop('disabled', false);
                        }

                    }).on("select2:unselecting", function(e) {
                        var target_array = [... e.target.classList];
                        if(target_array.includes('filter-select-2')){
                          var val = $.fn.dataTable.util.escapeRegex("");
                              table_obj
                              .columns()
                              .search( '' )
                              // .search( val ? '^'+val+'$' : '', true, false )
                              .draw();
                          $(this).data('state', 'unselected');

                          var unique_search_subresort = [];
                          $('.filter-select-3').select2('destroy').html('<option value="" selected disabled></option>')
                          $('.filter-select-3').select2({
                            placeholder: 'Choose data',
                            allowClear: true
                          });

                          var newOption_subresort = [];
                          $.each(unique_search_subresort, function(index, data){
                            newOption_subresort[index] = new Option(`${data}`, data, false, false);
                          });
                          $('.filter-select-3').append(newOption_subresort).trigger('change');
                          $('.filter-select-3').prop('disabled', true);
                        } 
                        else if(target_array.includes('filter-select-3')){
                          var val = $.fn.dataTable.util.escapeRegex("");
                              table_obj
                              .columns([1,3])
                              .search( '' )
                              // .search( val ? '^'+val+'$' : '', true, false )
                              .draw();
                          $(this).data('state', 'unselected');

                          var unique_search_outlet = [];
                          $('.filter-select-1').select2('destroy').html('<option value="" selected disabled></option>')
                          $('.filter-select-1').select2({
                            placeholder: 'Choose data',
                            allowClear: true
                          });

                          var newOption_outlet = [];
                          $.each(unique_search_outlet, function(index, data){
                            newOption_outlet[index] = new Option(`${data}`, data, false, false);
                          });
                          $('.filter-select-1').append(newOption_outlet).trigger('change');
                          $('.filter-select-1').prop('disabled', true);
                        }
                        else {
                          console.log('eksekusi ini')
                          var val = $.fn.dataTable.util.escapeRegex("");
                          column
                              .search( '' )
                              .search( val ? '^'+val+'$' : '', true, false )
                              .draw();
                          $(this).data('state', 'unselected');
                        }
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
                if(i == 2){
                  column.data().unique().sort().each( function ( d, j ) {
                      $(`.filter-select-${i}`).append( '<option value="'+d+'">'+d+'</option>' )
                  });
                }
                else {
                  $(`.filter-select-${i}`).prop('disabled', true);
                }
                text_order++;
            });
            $('.select2').select2({
              placeholder: 'Choose data',
              allowClear: true
            });
        },
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                // return typeof i === 'string' ?
                //     i.replace(/[\$,]/g, '')*1 :
                //     typeof i === 'number' ?
                //         i : 0;
                var data_column = typeof i === 'string' ?
                i.replace(/(<([^>]+)>)/ig, '').replaceAll(',','')*1 :
                typeof i === 'number' ? i : 0;
                return data_column;
            };

            /* Guest Today */
            // Total over all pages
            guestTodayAll = api
                .column( 4, { search: 'applied' } )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            guestTodayPage = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END Guest Today */

            /* Guest MTD */
            // Total over all pages
            guestMtdAll = api
                .column( 6, { search: 'applied' } )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            guestMtdPage = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END Guest MTD */

            /* REVENUE TODAY */
            // Total over all pages
            revenueTodayAll = api
                .column( 5, { search: 'applied' } )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // console.log("REVENUE TODAY", revenueTodayAll);
 
            // Total over this page
            revenueTodayPage = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END REVENUE TODAY */

            /* REVENUE TODAY */
            // Total over all pages
            revenueMtdAll = api
                .column( 7, { search: 'applied' } )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            revenueMtdPage = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END REVENUE TODAY */
 
            $('.guest-today-page').html(numberWithCommas(guestTodayPage));
            $('.guest-mtd-page').html(numberWithCommas(guestMtdPage));
            $('.guest-today-all').html(numberWithCommas(guestTodayAll));
            $('.guest-mtd-all').html(numberWithCommas(guestMtdAll));

            $('.revenue-today-page').html(numberWithCommas(revenueTodayPage));
            $('.revenue-mtd-page').html(numberWithCommas(revenueMtdPage));
            $('.revenue-today-all').html(numberWithCommas(revenueTodayAll));
            $('.revenue-mtd-all').html(numberWithCommas(revenueMtdAll));

        }
      });

      $(document).on('click', '.dtsb-clearAll', function(e){
      var table = $(e.target).parents('.table-wrapper').find('.table')[0];
      // stored = table.DataTable().searchBuilder.getDetails();         
      // console.log('click remove');
      $(table).DataTable().destroy();
      $(table).DataTable({
        dom: 'Qlfrtip',
        columnDefs:[{
          searchBuilderTitle: 'F&B OUTLET',
          targets: 1
        }],
        searchBuilder: {
          columns: [1,2,3,4,5,6],
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
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                // return typeof i === 'string' ?
                //     i.replace(/[\$,]/g, '')*1 :
                //     typeof i === 'number' ?
                //         i : 0;
                var data_column = typeof i === 'string' ?
                i.replace(/(<([^>]+)>)/ig, '').replaceAll(',','')*1 :
                typeof i === 'number' ? i : 0;
                return data_column;
            };

            /* Guest Today */
            // Total over all pages
            guestTodayAll = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            guestTodayPage = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END Guest Today */

            /* Guest MTD */
            // Total over all pages
            guestMtdAll = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            guestMtdPage = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END Guest MTD */

            /* REVENUE TODAY */
            // Total over all pages
            revenueTodayAll = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            console.log("REVENUE TODAY", revenueTodayAll);
 
            // Total over this page
            revenueTodayPage = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END REVENUE TODAY */

            /* REVENUE TODAY */
            // Total over all pages
            revenueMtdAll = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            revenueMtdPage = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            /* END REVENUE TODAY */
 
            $('.guest-today-page').html(numberWithCommas(guestTodayPage));
            $('.guest-mtd-page').html(numberWithCommas(guestMtdPage));
            $('.guest-today-all').html(numberWithCommas(guestTodayAll));
            $('.guest-mtd-all').html(numberWithCommas(guestMtdAll));

            $('.revenue-today-page').html(numberWithCommas(revenueTodayPage));
            $('.revenue-mtd-page').html(numberWithCommas(revenueMtdPage));
            $('.revenue-today-all').html(numberWithCommas(revenueTodayAll));
            $('.revenue-mtd-all').html(numberWithCommas(revenueMtdAll));

        }
      });
      
    })

  });
</script>
@endsection