@extends('layouts.default')

@section('title', 'Daily Cost Report')

@section('styles')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/template/css/largetable.css">
<link rel="stylesheet" href="/template/css/jquery.monthpicker.css">

<style type="text/css">
th,
thead tr.first th, thead tr.first td,
thead tr.second th, thead tr.second td {
  background: white;
  position: sticky;
  position: -webkit-sticky;
  top: -0.1px; /* Don't forget this, required for the stickiness */
  font-size: 12px !important;
  z-index: 7;
}

thead tr.second th, thead tr.second td {
  top: 30px;
  /*top: 0;*/
  box-shadow: inset 0px -2px 18px 0 #00000012;
  border: none !important;
}
thead tr.first th, thead tr.first td{
  top: 0;
  outline: 1px solid #e7e7e7;
}
thead th {
  position: relative;
  z-index: 2;
}
.largetable.largetable-maximized thead tr.second th, .largetable.largetable-maximized thead tr.second td {
  top: 30px;
}
.largetable.largetable-maximized thead tr.first th, .largetable.largetable-maximized thead tr.first td{
  top: 0;
}

tr.pivot th
{
  table-layout: fixed;
  top: 34px;
}
.fl-scrolls {
  bottom: 0;
  height: 35px;
  overflow: auto;
  position: fixed;
  background: #fff;
  width: 100%;
  z-index: 6;
}
.fl-scrolls div {
  height: 1px;
  overflow: hidden;
}
.fl-scrolls-hidden {
  bottom: 9999px;
}
.largetable .largetable-maximize-btn,
.largetable.largetable-maximized .largetable-maximize-btn {
  display: block;
}
.largetable.largetable-maximized .largetable-maximize-btn{
  right: 30px;
  bottom: 35px;
  top: auto;
  z-index: 7
}
.largetable .largetable-maximize-btn{
  /*right: -15px;*/
  right: 20px;
  bottom: auto;
  /*top: -58px;*/
  top: -48px;
}
.largetable-scroller {
  /*max-height: 500px;*/
}
.table,
.table td, .jsgrid .jsgrid-table td {
  font-size: 0.75rem;
}
table tbody tr td:not(:first-child) {
  text-align: right !important;
}

table tbody tr td.bg-black {
  text-align: center !important;
}

table tbody tr.fixed td:first-child {
  position: sticky;
  left: 0;
  background: white;
  z-index: 5;
  box-shadow: inset 0px -2px 18px 0 #00000012
}
table thead tr.fixed th:first-child {
  position: sticky;
  left: 0;
  background: white;
  z-index: 8;
  box-shadow: inset 0px -2px 18px 0 #00000012
}
.table-scroller{
  overflow: auto;
  position: relative;
  /*height: 700px*/
  height: auto;
}
.largetable.largetable-maximized .table-scroller {
  height: calc(100vh - 100px);
}
.child span.simple-tree-table-handler.simple-tree-table-icon{
  display: none !important;
}
.simple-tree-table-icon{
  margin-right: 5px !important;
  color: #000 !important;
  position: absolute;
  left: 5px;
  top: 5px;
  width: auto !important;
  padding: 0 10px;
}
.simple-tree-table-opened .simple-tree-table-icon:after {
  content: "- Hide" !important;
}
.simple-tree-table-closed .simple-tree-table-icon:after {
  content: "+ Show" !important;
}
.simple-tree-table-root td{
  position: relative;
}
.custom-badge{
  margin-bottom: 3px !important;
  font-size: 10px;
  display: inline-block;
}
/*.download-excel {
  font-size: 1.6em;
  float: right;
  bottom: 5px;
  position: relative;
}*/
.monthpicker {
  background: #fff;
}
.export-btn {
  display: inline-block !important;
}
.overlay {
  display: none;
  transition: all 2ms;
}
.overlay.in {
  display: flex;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  text-align: center;
  background: #ffffffd4;
  z-index: 9;
  justify-content: center;
}
.overlay-wrapper {
  display: block;
}
.ui-datepicker-calendar {
  display: none;
}
.select-company-pnl {
  text-align: center;
  outline: none !important;
  border: none !important;
  font-size: 1.6em;
  font-weight: 500;
  color: #a7afb7;
  padding: 0.630rem 1.375rem 0.3rem 1.375rem !important;
}
.select-company-pnl option{
  font-size: 15px;
}
button.ui-datepicker-current { display: none !important; }
button.ui-datepicker-close { display: inline-block !important; }

</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">SAP</a></li> 
    <li class="breadcrumb-item"><a href="/folio">Report</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>PNL Cost</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <div class="overlay-wrapper" style="position: relative;top: 0.5em">
        <img class="img" style="max-width: 30px" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
      </div>
    </div>
    <div class="card"> 
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
         <!-- REVENUE STATISTIC VILLA -->
         <div class="form-group">
           <div class="form-group mb-3">
              <div class="row">
                <div class="title-header col-12 text-center" style="position: relative;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                  {{--<h2> AYANA RIMBA BALI</h2>--}}
                  <div>
                    <form class="form" method="POST" action="{{ route('sap.report.pnl_cost') }}" id="pnl-form">
                      {{ csrf_field() }}
                      <div>
                        <!-- <h2> {{ isset($data['COMPANY_NAME']->PROPERTYNAME) ? strtoupper($data['COMPANY_NAME']->PROPERTYNAME) : 'UNKNOWN COMPANY' }}</h2> -->
                        <select class="form-control select-company-pnl" name="company">
                          @if(isset($data['COMPANY_LIST']) && count($data['COMPANY_LIST']))
                            @foreach($data['COMPANY_LIST'] as $key_company => $data_company)
                            <option value="{{ $key_company }}" @if(isset($data['COMPANY_NAME']) && $data['COMPANY_NAME'] == $key_company) selected @endif>{{ isset($data_company) ? strtoupper($data_company) : 'UNKNOWN COMPANY' }}</option>
                            @endforeach
                          @else 
                            <option value="" selected disabled="">NO COMPANY FOUND</option>
                          @endif
                        </select>
                      </div>
                      <div>
                        <h3 class="text-black"> Daily Cost Report </h3>
                      </div>
                      <div>
                        <h3><a data-toggle="tooltip" title="Click to change month and year" href="javascript:void(0)" id="monthpicker" class="text-primary">
                        {{ isset($data['MONTH_NAME']) ? $data['MONTH_NAME'] : date('M', strtotime("-1 months")) }} {{ isset($data['YEAR']) ? $data['YEAR'] : date('Y') }}</a></h3>
                        <input type="hidden" name="month" id="month-selected" value="{{ isset($data['MONTH']) ? $data['MONTH'] : date('n', strtotime('-1 months')) }}">
                        <input type="hidden" name="year" id="year-selected" value="{{ isset($data['YEAR']) ? $data['YEAR'] : date('Y') }}">
                      </div>
                      <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                      </div>
                    </form>
                  </div>

                  @if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl_cost', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0)
                  <!-- Sync Data SAP to DB Warehouse -->
                  <div style="position: absolute; top:0; right:0">
                    <div class="d-inline-block">
                      <div class="d-inline-block mx-1" style="max-width: 200px">
                          <div class="mb-1 text-left">
                            <small style="color:#000">Sync Data SAP</small>
                          </div>
                          <div class="mb-2">
                            <input type="text" class="date-picker-mtd form-control" placeholder="Select Period">
                            <input type="hidden" name="input-period" id="date-picker-mtd-value">
                            <input type="hidden" name="lock-status" id="pnl-lock-status">
                          </div>
                          <select class="form-control" id="select-company-sap">
                            <option value="" selected="" disabled="">Select Company</option>
                            @if(isset($data['COMPANY_SAP']) && count($data['COMPANY_SAP']))
                              @foreach($data['COMPANY_SAP'] as $company_sap => $data_comp)
                                <option value="{{ $data_comp }}">{{ isset($data_comp) ? strtoupper($data_comp) : 'UNKNOWN COMPANY' }}</option>
                              @endforeach
                            @endif
                          </select>
                      </div>
                      <div class="d-inline-block mx-1">
                        <button class="btn btn-secondary btn-sync" disabled><i class="fa fa-refresh"></i>&nbsp;&nbsp;Sync</button>
                      </div>
                    </div>
                    <div class="text-left ml-2 mt-2" id="sync-text" hidden>
                      <small class="text-primary"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Please wait while data is syncing</small>
                    </div>
                    <div class="text-left ml-2 mt-2" id="sync-error-text" hidden>
                      <small class="text-danger"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Something went wrong, please try again later</small>
                    </div>
                    <div class="text-left ml-2 mt-2" id="sync-custom-text" hidden>
                      <small class="text-danger info-custom"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;No Data Available</small>
                    </div>
                    <div class="text-left ml-2 mt-2" id="sync-success-text" hidden>
                      <small class="text-black"><i class="fa fa-check-circle text-success"></i>&nbsp;&nbsp;Please <a href="{{ url()->current() }}">refresh</a> the page to see changes</small>
                    </div>
                    <div class="text-left ml-2 mt-2" id="last-update-text">
                      <small class="text-muted"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Last Update : {{ isset($data['LAST_UPDATE']) && $data['LAST_UPDATE'] ? date('d M Y H:i:s', strtotime($data['LAST_UPDATE'])) : '-' }}</small>
                    </div>
                  </div>
                  <!--END Sync Data SAP to DB Warehouse -->
                  @endif

                </div>
              </div>
           </div>
           <hr class="mt-4">
           <div class="main-wrapper" id="tab-wrapper">
              <div class="row">
                <div class="col-12 mb-2 text-center">
                  <h5>{!! isset($data['VALUE_PNL']) ? '<i class="fa fa-check text-success"></i>&nbsp;'.(count($data['VALUE_PNL']))." Templates Available" : 'No PNL Template Available' !!}</h5>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <!-- START TABS -->
                  <ul class="nav nav-tabs" role="tablist" style="flex-wrap: nowrap;white-space: nowrap;overflow-x: scroll;overflow-y: hidden;">
                    @if(isset($data['VALUE_PNL']) && count($data['VALUE_PNL']))
                      @foreach($data['VALUE_PNL'] as $key_report => $data_pnl)
                        @php
                          if($loop->index == 0)
                            $active = 'active';
                          else
                            $active = '';
                        @endphp
                        <li class="nav-item">
                          <a class="nav-link {{ $active }}" id="{{ $key_report }}-tab" data-toggle="tab" href="#{{ $key_report }}" role="tab" aria-selected="true">
                            {{ isset($data_pnl['DESC']) ? $data_pnl['DESC'] : 'Unknown Desc' }}
                            <span class="export-btn">&nbsp; | &nbsp;<i class="mdi mdi-arrow-down-bold-circle-outline download-excel text-primary" data-title="{{ isset($data_pnl['DESC']) ? $data_pnl['DESC'] : 'Unknown Desc' }}" data-key="{{ $key_report }}"></i></span>
                          </a>
                        </li>
                      @endforeach
                    @else
                      <li class="nav-item">
                        <div class="ml-3 pt-3">
                          <h6 class="text-muted">No Data Available</h6>
                        </div>
                      </li>
                    @endif
                  </ul>
                  <div class="tab-content">
                    @if(isset($data['VALUE_PNL']) && count($data['VALUE_PNL']))
                      @foreach($data['VALUE_PNL'] as $key_report_tab => $data_pnl_tab)
                        @php
                          $pages = 'pages.report.finance.pnl_part_cost.'.$key_report_tab;
                          if($loop->index == 0)
                            $active_page = 'active show';
                          else
                            $active_page = '';
                        @endphp
                        <div class="tab-pane fade {{ $active_page }}" id="{{ $key_report_tab }}" role="tabpanel" aria-labelledby="{{ $key_report_tab }}-tab">
                          <div class="table-wrapper" style="overflow-x: hidden;">
                            <div class="text-center pt-3 pb-4" style="margin-right: 20px">
                              <h3 style="border-bottom: 1px solid #d2d2d2; line-height:0.1em;"><span style="background:#fff; padding:0 10px;">{{ isset($data_pnl_tab['DESC']) ? strtoupper($data_pnl_tab['DESC']) : '-' }}</span></h3>
                            </div>
                            @includeIf($pages, ['data' => isset($data_pnl_tab['DATA']) ? $data_pnl_tab['DATA'] : [] ])
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                  <!-- END TABS -->
                </div>
              </div>
            </div>


         </div>
        </div>
      </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="/template/js/report/jquery.floatingscroll.js"></script>
<script src="/template/js/jquery-simple-tree-table.js"></script>
<script src="/template/js/ResizeSensor.js"></script>
<script src="/template/js/ElementQueries.js"></script>
<script src="/template/js/largetable.js"></script>
<script src="/template/js/jquery.table2excel.min.js"></script>

<script src="/template/js/jquery.monthpicker.js"></script>

<script>
   $(window).on('load', function(){
      $(".table-scroller").floatingScroll();
      try{
        $('body').addClass('sidebar-icon-only');
      } catch(error){}

      var element = document.getElementsByClassName('table-scroller');
      new ResizeSensor(element, function(rm) {
        $(".table-scroller").floatingScroll("update");
      });

   })
   $(document).ready(function() {
        var currently_sync = false;
        const year = new Date().getFullYear(),
        array_year = Array.from({length: year - 2019}, (value, index) => 2020 + index);
        $('#monthpicker').monthpicker({
          years: array_year,
          year : {{ isset($data['YEAR']) ? $data['YEAR'] : date('Y') }},
          months : ("{{ isset($data['MONTH']) ? $data['MONTH'] : date('n', strtotime('-1 months')) }}") - 1,
          topOffset: 190,
          leftOffset : 85.5,
          onMonthSelect:function(m, y) {
              this.currently_sync = currently_sync;
              // if(!currently_sync){
              var month = m+1,
              year = y;
              $('#month-selected').val(month);
              $('#year-selected').val(year);
              // $('.overlay').addClass('in');
              // window.location.href="{{url()->current()}}?month="+month+"&year="+y;
              // } else {
                // Swal.fire('Attention!','Cannot change PNL period while data is syncing','warning');
              // }
            }
        });

        // $('#datepicker').datepicker({
        //   dateFormat: "yy-mm-dd",
        //   showWeek: true,
        //   changeYear: true,
        //   showButtonPanel: true,
        //   maxDate: new Date(),
        //   onSelect : function(text, obj){
        //     window.location.href="{{url()->current()}}?business_date="+text;
        //   }
        // });

        $('.date-picker-mtd').datepicker( {
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          dateFormat: 'MM yy',
          onClose: function(dateText, inst) { 
            // console.log(inst.selectedYear, inst.selectedMonth);
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            var value = `${inst.selectedYear}/${(inst.selectedMonth + 1)}`;
            $('#date-picker-mtd-value').val(value);
            $('.btn-sync').prop('disabled', false);

            try {
              $('#sync-text').prop('hidden', true);
              $('#sync-error-text').prop('hidden', true);
              $('#sync-custom-text').prop('hidden', true);
              $('.info-custom').html(`<i class="fa fa-info-circle"></i>&nbsp;&nbsp;No Data Available`);
              $('#sync-success-text').prop('hidden', true);
            } catch(error){}
          }
        });

        $('#datepicker').prop('disabled', false);
        $(".table").largetable({
          enableMaximize:true
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          $(".table-scroller").floatingScroll('update');
        });

        $('.table-tree').simpleTreeTable({
          opened:'all',
        });

        // $(".btn-download-fb-rev").click(function(e){
        //   $("#fnb-arj-rev-table").table2excel({
        //     // exclude CSS class
        //     exclude:".noExl",
        //     name:"Sheet1",
        //     filename:"FNB ARJ (Revenue)",//do not include extension
        //     fileext:".xls" // file extension
        //     // preserveColors:true
        //   });
        // });

        $(".download-excel").click(function(e){
          var title = $(this).data('title') || 'PNL Report';
          var key = $(this).data('key') || '#none';
          var mS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
          var year = "{{ isset($data['YEAR']) ? $data['YEAR'] : date('Y') }}";
          var months = "{{ isset($data['MONTH']) ? $data['MONTH'] : date('n', strtotime('-1 months')) }}";
          var heading2 = typeof mS[(months-1)] === 'undefined' ? 'Unknown Date & Year' : `${mS[(months-1)]}, ${year}`;

          Swal.fire({
            title: 'Export Confirmation',
            text: `This operation will export ${title} data to Excel workbook`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',
            confirmButtonText: 'Export'
          }).then((result) => {
            if (result.isConfirmed) {
              try {
                Swal.close();
              } catch(error){}
              setTimeout(function(){
                $(`#${key} .table-wrapper .table`).table2excel({
                  // exclude CSS class
                  exclude:".noExl",
                  filename:title,//do not include extension
                  fileext:".xls", // file extension
                  heading1: "Daily Cost Report",
                  heading2: heading2
                  // preserveColors:true
                });
              },400)
            }
          })
        });

        // Untuk keperluan sync data ke SAP
        $('.btn-sync').click(function(e){
          try {
            $('#sync-text').prop('hidden', true);
            $('#sync-error-text').prop('hidden', true);
            $('#sync-custom-text').prop('hidden', true);
            $('.info-custom').html(`<i class="fa fa-info-circle"></i>&nbsp;&nbsp;No Data Available`);
            $('#sync-success-text').prop('hidden', true);
            $('#last-update-text').prop('hidden', true);
          } catch(error){}

          Swal.fire({
            title: 'Are you sure ?',
            text: "This operation will regain all data from SAP and delete the old one based on chosen period!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Sync Data!'
          }).then((result) => {
            if (result.isConfirmed) {
              // $('.overlay').addClass('in');
              currently_sync = true;
              $('#sync-text').prop('hidden', false);
              $('.date-picker-mtd').prop('disabled', true);
              $('#select-company-sap').prop('disabled', true);
              $('.btn-sync').prop('disabled', true).html('<i class="fa fa-spin fa-refresh"></i>&nbsp;&nbsp;Sync');
              var period_value = $('#date-picker-mtd-value').val();
              var company_value = $('#select-company-sap').val() || '';
              $.ajax({
                'url'  : `{{ route("sap.api.budgetactual") }}?period=${period_value}&&bukrs=${company_value}`,
                'type' : 'GET',
                success : function(res){
                  if(res && res.hasOwnProperty('status') && res.status == 'success'){
                      $('#sync-text').prop('hidden', true);
                      $('#sync-error-text').prop('hidden', true);
                      $('#sync-custom-text').prop('hidden', true);
                      $('#sync-success-text').prop('hidden', false);
                  } else {
                      var custom_message = res.hasOwnProperty('message') ? res.message : 'No Data Available';
                      // console.log(res);
                      $('#sync-text').prop('hidden', true);
                      $('#sync-error-text').prop('hidden', true);
                      $('#sync-custom-text').prop('hidden', false);
                      $('.info-custom').html(`<i class="fa fa-info-circle"></i>&nbsp;&nbsp;${custom_message}`);
                      $('#sync-success-text').prop('hidden', true);
                  }
                },
                error : function(xhr){
                  console.log(xhr);
                  $('#sync-text').prop('hidden', true);
                  $('#sync-error-text').prop('hidden', false);
                  $('#sync-custom-text').prop('hidden', true);
                  $('#sync-success-text').prop('hidden', true);
                },
                complete : function(){
                  currently_sync = false;
                  $('.date-picker-mtd').prop('disabled', false);
                  $('#select-company-sap').prop('disabled', false);
                  $('.btn-sync').prop('disabled', false).html('<i class="fa fa-refresh"></i>&nbsp;&nbsp;Sync');
                }
              });
            }
          })
        });
        // END untuk keperluan sync data ke SAP
        
        $('#pnl-form').on('submit', function(e){
          if(currently_sync){
            e.preventDefault();
            Swal.fire('Attention!','Cannot change PNL period while data is syncing, please wait and try again','warning');
            return false;
          } else {
            $('.overlay').addClass('in');
            setTimeout(function(){
              return true;
            }, 100)
          }
        })

    });
  </script>
@endsection

