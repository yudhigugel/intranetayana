@extends('layouts.default')

@section('title', 'Outlet Menu Total Sales')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}">

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
  table.dataTable td.dataTables_empty {
    text-align: center !important;    
  }
  .select2-container--default .select2-selection--single {
    padding: 0.78rem 1.375rem;
  }
  .dataTables_wrapper{
    position: relative;
  }
  .dataTables_wrapper .dataTables_processing {
    position: absolute !important;
    left: 25em !important;
    top: 40px !important;
  }
  .dataTables_info {
    float: left;
  }
  .abs-search {
    float: none !important;
  }
  .button-export-wrapper{
    padding-left: 0 !important;
    padding-right: 30px !important;
  }
  .dataTables_scroll{
    margin-bottom: 15px;
  }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">POS</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>Menu Daily Total</span></li></ol>
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
                    <h2> POS Outlet Menu </h2>
                    <h3> Menu Sales Report</h3>
                    <h5> Transaction Date : {{ isset($filtered['date_start']) ? $filtered['date_start'].' -' : '-'}} {{isset($filtered['date_end']) ? $filtered['date_end'] : '' }} </h5>
              </div>
              <div class="pt-3 col-5">
                <form method="GET" action="">
                  <div class="row">
                    <div class="form-group col-md-5">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Plant</small>
                      </div>
                      <select name="plant" id="select-plant" class="form-control select2" required disabled>
                        <option value="" selected disabled></option>
                        @if(isset($data_plant))
                          @foreach($data_plant as $pl)
                            <option value="{{ $pl }}" @if(isset($filtered['plant']) && $filtered['plant'] == $pl) selected @endif>{{ $pl }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group col-md-7">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Choose date range</small>
                      </div>
                      <div class="input-group mb-1">
                        <input disabled required type="text" class="form-control datepicker" name="daterange" id="daterangepicker" value="{{ isset($filtered['date_start']) ? $filtered['date_start'].' - ' : ''}}{{isset($filtered['date_end']) ? $filtered['date_end'] : '' }}" placeholder="Transaction Date..">
                        <div class="input-group-prepend">
                          <button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="form-group col-md-3">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Apply</small>
                      </div>
                      <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div> -->
                  </div>
                </form>
              </div>
            </div>
          </div>
          <hr class="my-0">
          <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
            @if(isset($filtered) && $filtered)
            <div class="table-wrapper">
              <div class="table-container-h table-responsive">
                  <table class="table table-border" id="content-table">
                    <thead>
                      <tr>
                        <th class="bg-secondary text-white text-center">NO</th>
                        <th class="bg-secondary text-white text-center">RESORT</th>
                        <th class="bg-secondary text-white text-center">SUBRESORT</th>
                        <th class="bg-secondary text-white text-center">REVENUE CENTER</a></th>
                        <th class="bg-secondary text-white text-center">SAP MATERIAL CODE</a></th>
                        <th class="bg-secondary text-white text-center">ITEM NAME</a></th>
                        <th class="bg-secondary text-white text-center">SALES COUNT</a></th>
                        <th class="bg-secondary text-white text-center">CONSIGNMENT FLAG</a></th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($data_menu) && $data_menu)
                      @foreach($data_menu as $ot)
                        <tr>
                          <td class="text-center">{{ $loop->iteration }}</td>
                          <td>{{ isset($ot->REVCTRRESORT) ? $ot->REVCTRRESORT : '-' }}</td>
                          <td>{{ isset($ot->REVCTRSUBRESORT) ? $ot->REVCTRSUBRESORT : '-' }}</td>
                          <td>{{ isset($ot->REVENUECENTERNAME) ? $ot->REVENUECENTERNAME : '-' }}</td>
                          <td class="text-center">{{ isset($ot->SAPMATERIALCODE) ? $ot->SAPMATERIALCODE : '-' }}</td>
                          <td>{{ isset($ot->MENUITEMNAME) ? $ot->MENUITEMNAME : '-' }}</td>
                          <td class="text-right">{{ isset($ot->SALESCOUNT) ? $ot->SALESCOUNT : '-' }}</td>
                          <td class="text-right">{{ isset($ot->CONSIGNMENT_FLAG) ? $ot->CONSIGNMENT_FLAG : '-' }}</td>

                        </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
              </div>
            </div>
            @else
              <div class="p-3 text-center">
                <h3 class="mb-1">No Data Available</h3>
                <div>
                  <small>* Please choose plant and date first</small>
                </div>
              </div>
            @endif
          </div>
    </div>
</div>
</div>
@endsection


@section('scripts')
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>
<script type="text/javascript">
  $(function(){
    // $('#datepicker').datepicker({
    //   dateFormat: "yy-mm-dd",
    //   showWeek: true,
    //   changeYear: true,
    //   showButtonPanel: true,
    //   maxDate: new Date(),
    //   onSelect : function(text, obj){
    //     window.location.href="{{url()->current()}}?transaction_date="+text;
    //   }
    // });
    $('input[name="daterange"]').daterangepicker({
      opens: 'left',
    }, function(start, end, label) {
        console.log(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
    });

    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('#daterangepicker').prop('disabled', false);
    $('#select-plant').prop('disabled', false);
    
    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    var data_subresort = [];
    var data_consignment = [];
    var data_filter_menu = {};

    $('#content-table').DataTable({
      "dom":'<"abs-search row mb-2" <"button-export-wrapper col-6"B>>rtip',
      "paging": true,
      "pageLength" : 50,
      "lengthChange": false,
      "serverSide":true,
      "scrollX": true,
      "searching":false,
      "autoWidth":false,
      "responsive": true,
      "processing":true,
      "buttons": {
        dom: {
          button: {
            tag: 'button',
            className: 'mb-2 mt-4'
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
            columns: [ 1, 2, 3, 4, 5, 6, 7 ],
            modifier: {
              page: 'all',
            }
          },
          action: function(e, dt, button, config) {
            var data_revenue = [];
            $btn_scope = this;

            try {
              var table = $('#content-table').DataTable()
              data_revenue = table.rows().data();
            } catch(error){}

            if(data_revenue.length > 0) {
              // window.location.href = './ServerSide.php?ExportToCSV=Yes';
              try{ 
                $('.buttons-excel').prop('disabled', true);
                $.toast({
                  text : "<i class='fa fa-spin fa-spinner'></i> &nbsp;Exporting data...",
                  hideAfter : false,
                  textAlign : 'left',
                  showHideTransition : 'slide',
                  position : 'bottom-right'  
                })
              } catch(error){}
              var $data_sent = {
                 "daterange" : params.hasOwnProperty('daterange') ? params.daterange : document.getElementById('daterangepicker').value,
                  "plant" : params.hasOwnProperty('plant') ? params.plant : document.getElementById('select-plant').value,
                  ...data_filter_menu
              }

              $.ajax({
                "url": "/report/menu_sales_revenue",
                "type": "GET",
                "data" : $data_sent
              }).then(function (ajaxReturnedData) {
                  setTimeout(function(){
                    dt.clear();
                    dt.rows.add(ajaxReturnedData.data);
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call($btn_scope, e, dt, button, config);
                  }, 400)
              }).catch(function(error){
                  console.log(error);
                  setTimeout(function(){
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong while exporting, please try again in a moment',
                    })
                  },400);
              }).done(function(){
                  $('.buttons-excel').prop('disabled', false);
                  $.toast().reset('all');
              });
              return false;
            } else {
              Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Data is empty, nothing to export',
              })
              return false;
            }
          },
        }]
      },
      "ajax":{
          "url": "/report/menu_sales_revenue",
          "type": "GET",
          "dataSrc": function ( json ) {
              data_subresort = [...new Set(json.data_all.map(item => item.REVCTRSUBRESORT))];
              data_consignment = [...new Set(json.data_all.map(item => item.CONSIGNMENT_FLAG))];
              //Make your callback here.
              if(json.hasOwnProperty('data') && json.data)
               return json.data;
              else
               return [];
          },
          data : {
            "daterange" : params.hasOwnProperty('daterange') ? params.daterange : document.getElementById('daterangepicker').value,
            "plant" : params.hasOwnProperty('plant') ? params.plant : document.getElementById('select-plant').value
          }
      },
      "language": {
        "zeroRecords": "Sorry, there is no data to approve at the moment",
        "processing": ''
      },
      "columns": [
          { data: "ROW_NUM", className: 'text-center' },
          { data: "REVCTRRESORT", className: 'text-center' },
          { data: "REVCTRSUBRESORT", className: 'text-center' },
          { data: "REVENUECENTERNAME", className: 'text-left' },
          { data: "SAPMATERIALCODE", className: 'text-center' },
          { data: "MENUITEMNAME", className: 'text-left' },
          { data: "SALESCOUNT", className: 'text-right' },
          { data: "CONSIGNMENT_FLAG", className: 'text-center' } 
      ],
      initComplete: function() {
            var text_order = 0;
            var table_obj = this.api();
            this.api().columns([2, 7]).every( function (i) {
                var column = this;
                var text = ['Sub Resort', 'Consignment Flag'];
                var paramName = ['REVCTRSUBRESORT', 'CONSIGNMENT_FLAG']
                var col_length = 'col-3';
                var select = $(`<div class="content-filter ${col_length}"><label>Filter By ${text[text_order]}</label><div><select class="form-control select2 filter-select-${i} mr-3" data-filter="${text_order}"><option value="">Pilih Data Disini</option></select></div></div>`)
                    .appendTo( $('.abs-search') )
                    .on('select2:select', function (e) {
                        var value = e.params.data.id;
                        var target = $(e.target).data('filter');
                        var val = $.fn.dataTable.util.escapeRegex(
                            value
                        );
                        if(val){
                          data_filter_menu[paramName[target]] = val
                        }

                        const qs = Object.keys(data_filter_menu)
                        .map(key => `${key}=${data_filter_menu[key]}`)
                        .join('&');
                        if(qs)
                          table_obj.ajax.url(`?${qs}`).load();

                    }).on("select2:unselecting", function(e) {
                        var target = $(e.target).data('filter');
                        try {
                          delete data_filter_menu[paramName[target]]
                          const qs = Object.keys(data_filter_menu)
                          .map(key => `${key}=${data_filter_menu[key]}`)
                          .join('&');
                          if(qs)
                            table_obj.ajax.url(`?${qs}`).load();
                          else
                            table_obj.ajax.url('').load();

                        } catch(error){
                          console.log(error);
                        }
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
                if(i == 2){
                  $(data_subresort).each( function ( d, j ) {
                      $(`.filter-select-${i}`).append( '<option value="'+j+'">'+j+'</option>' )
                  });
                }
                else if(i == 7){
                  $(data_consignment).each( function ( d, j ) {
                      $(`.filter-select-${i}`).append( '<option value="'+j+'">'+j+'</option>' )
                  });
                }
                // else {
                //   $(`.filter-select-${i}`).prop('disabled', true);
                // }
                text_order++;
            });
            $('.select2').select2({
              placeholder: 'Choose data',
              allowClear: true
            });
        },
    });

    $('.select2').select2({
      placeholder: 'Choose data',
      allowClear: true
    });
  })
</script>
@endsection
