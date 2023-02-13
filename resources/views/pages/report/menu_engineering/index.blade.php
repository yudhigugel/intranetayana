@extends('layouts.default')

@section('title', 'Menu Engineering')
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
    left: 32em !important;
    top: 20px !important;
  }
  #table-detail_processing{
    top: -1em !important;
  }
  .dataTables_info {
    float: left;
  }
  .abs-search {
    float: left;
  }
  .button-export-wrapper{
    padding-left: 0 !important;
    padding-right: 30px !important;
  }
  .dataTables_scroll{
    margin-bottom: 15px;
  }

  .dataTables_scrollBody:nth-child(2){
    max-height: 300px !important;
  }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li> 
    <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>Menu Engineering</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
      <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
      </div>
      <div class="card">
          <div class="card-body pb-0 bg-white" id="header">
            <div class="row align-items-center">
              <div class="col-5">
                    <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                    <h3> Menu Engineering Report</h3>
                    <h5> Transaction Date : {{ isset($filtered['date_start']) ? $filtered['date_start'].' -' : '-'}} {{isset($filtered['date_end']) ? $filtered['date_end'] : '' }} </h5>
              </div>
              <div class="pt-3 col-7">
                <form method="GET" action="">
                  <div class="row">
                    <div class="form-group col-3 col-md-3">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Plant</small>
                      </div>
                      <select name="plant" id="select-plant" class="form-control select2" required disabled style="width: 100%">
                        <option value="" selected disabled></option>
                        @if(isset($data_plant))
                          @foreach($data_plant as $pl)
                            <option value="{{ $pl }}" @if(isset($filtered['plant']) && $filtered['plant'] == $pl) selected @endif>{{ $pl }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group col-4 col-md-4">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Cost Center</small>
                        <div style="position: absolute;top: -5px;left: 6em;">
                          <small class="text-muted loading-cost-center" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}"></small>
                        </div>
                      </div>
                      <select name="cost_center" id="cost_center" class="form-control select2" required disabled style="width: 100%">
                        <option value="" selected></option>
                      </select>
                    </div>

                    <div class="form-group col-5 col-md-5">
                      <div class="mb-1">
                        <small style="color:#000;text-align: right;">Choose report date</small>
                      </div>
                      <div class="input-group mb-1">
                        <input disabled required type="text" class="form-control datepicker" name="daterange" id="daterangepicker" value="{{ isset($filtered['date_start']) ? $filtered['date_start'].' - ' : ''}}{{isset($filtered['date_end']) ? $filtered['date_end'] : '' }}" placeholder="Transaction Date..">
                        <div class="input-group-prepend">
                          <button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <hr class="my-0">
          <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
            @if(isset($filtered) && $filtered)
            <div class="table-wrapper">
              <div class="title-wrapper mb-3">
                <div class="text-center">
                  <h4>{{ isset($plant_name) ? $plant_name : 'Unknown Plant Name' }}</h4>
                </div>
                <div class="text-center">
                  <h5>{{ isset($cost_center_name) ? $cost_center_name : 'Unknown Cost Center Name' }}</h5>
                </div>
              </div>
              <div class="table-container-h table-responsive mb-3">
                <table class="table table-bordered table-header" id="table-header" style="width: 100% !important">
                  <thead>
                    <tr>
                      <th class="bg-secondary text-white exportable" style="width: 11.5%">Major Group</th>
                      <th class="bg-secondary text-white exportable">Items</th>
                      <th class="bg-secondary text-white exportable">Total <br>Item Sold</th>
                      <th class="bg-secondary text-white exportable">Total <br>Revenue</th>
                      <th class="bg-secondary text-white exportable">Total <br>Cost</th>
                      <th class="bg-secondary text-white exportable">Total <br>Profit</th>
                      <th class="bg-secondary text-white exportable">Food Cost (%)</th>
                      <th class="bg-secondary text-white exportable">Avg. Item Profit</th>
                      <th class="bg-secondary text-white exportable">Menu <br>Popularity Factor</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="table-container-h table-responsive mb-3">
                <table class="table table-bordered table-header" id="table-detail" style="width: 100% !important">
                  <thead>
                    <tr>
                      <th class="bg-secondary text-white" style="width: 11%">Major Group</th>
                      <th class="bg-secondary text-white">SAP <br>Material Code</th>
                      <th class="bg-secondary text-white">Menu <br>Item Name</th>
                      <th class="bg-secondary text-white">Sell Price</th>
                      <th class="bg-secondary text-white">Number <br>Sold</th>
                      <th class="bg-secondary text-white">Popularity (%)</th>
                      <th class="bg-secondary text-white">Item <br>Cost</th>
                      <th class="bg-secondary text-white">Cost (%)</th>
                      <th class="bg-secondary text-white">Item <br>Profit</th>
                      <th class="bg-secondary text-white">Total <br>Cost</th>
                      <th class="bg-secondary text-white">Total <br>Revenue</th>
                      <th class="bg-secondary text-white">Total <br>Profit</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            @else
              <div class="p-3 text-center">
                <h3 class="mb-1">No Data Available</h3>
                <div>
                  <small>* Please choose plant, costcenter and report date first</small>
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
  $(window).on('load', function(){
      try{
          var plant = $('#select-plant').val();
          if(plant){
            setTimeout(function(){
              $('#select-plant').trigger('change');
            }, 500)
          }
      } catch(error){}

      try{
        $('body').addClass('sidebar-icon-only');
      } catch(error){}
  });

  function number_format (number, decimals, dec_point, thousands_sep) {
      // Strip all characters but numerical ones.
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
      var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function (n, prec) {
              var k = Math.pow(10, prec);
              return '' + Math.round(n * k) / k;
          };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
  }

  $(function(){
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
    $('#cost_center').prop('disabled', false);
    
    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    var data_subresort = [];
    var data_consignment = [];
    var data_filter_menu = {};
    var data_table_detail = {};

    $('#table-header').DataTable({
      "dom":'<"abs-search"B>frt',
      "paging": false,
      "pageLength" : 50,
      "lengthChange": false,
      "serverSide":true,
      "scrollX": false,
      "searching":true,
      "autoWidth":true,
      "responsive": true,
      "processing":true,
      "ajax":{
          "url": "/sap/menu-engineering/report",
          "type": "GET",
          "dataSrc": function ( json ) {
              if(json.hasOwnProperty('data') && json.data)
               return json.data;
              else
               return [];
          },
          data : {
            "daterange" : params.hasOwnProperty('daterange') ? params.daterange : document.getElementById('daterangepicker').value,
            "plant" : params.hasOwnProperty('plant') ? params.plant : document.getElementById('select-plant').value,
            "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : document.getElementById('cost_center').value,
            "table_header" : true
          }
      },
      "language": {
        "zeroRecords": "Sorry, there is no data available",
        "processing": ''
      },
      "columns": [
          { data: "MajorGrpName", 
            "render": function (id, type, full, meta)
            {
                return id;
            },
            className: 'text-left' 
          },
          { data: "CountMjr",
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right'
          },
          { data: "TotSold",
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right'
          },
          { data: "TotRev", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right' 
          },
          { data: "TotCost", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right' 
          },
          { data: "TotProfit", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right' 
          },
          { data: "FoodCost", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 2, '.', ',') + '%';
            },
            className: 'text-right' 
          },
          { data: "AvgProfit", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 0, '.', ',');
            },
            className: 'text-right' 
          },
          { data: "MenuPopFactor", 
            "render": function (id, type, full, meta)
            {
                return number_format(id, 2, '.', ',') + '%';
            },
            className: 'text-right' 
          } 
      ],
      "buttons": {
            dom: {
              button: {
                tag: 'button',
                className: 'mb-3'
              }
            },
            buttons: 
            [{
                extend: 'excelHtml5',
                title: '',
                className : 'btn btn-primary buttons-excel',
                text: '<i class="mdi mdi-export"></i>&nbsp;Export Excel',
                exportOptions: {
                    orthogonal: 'export-excel',
                    columns: 'th.exportable'
                },
                action: function(e, dt, button, config) {
                    var data_export = [];
                    $btn_scope = this;
                    // getMethods = (obj) => Object.getOwnPropertyNames(obj).filter(item => typeof obj[item] === 'function')

                    try {
                      var table = $('#table-header').DataTable();
                      data_export = table.rows().data();
                    } catch(error){}

                    if(data_export.length > 0) {
                      try{ 
                        $('.buttons-excel').prop('disabled', true);
                        $.toast({
                          text : "<i class='fa fa-spin fa-spinner'></i> &nbsp;Exporting data...",
                          hideAfter : false,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        })

                        setTimeout(function(){
                          try {
                            $.ajax({
                              "url": "/sap/menu-engineering/report",
                              "type": "GET",
                              "data" : {
                                "daterange" : params.hasOwnProperty('daterange') ? params.daterange : document.getElementById('daterangepicker').value,
                                "plant" : params.hasOwnProperty('plant') ? params.plant : document.getElementById('select-plant').value,
                                "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : document.getElementById('cost_center').value,
                                "table_detail" : true
                              },
                              success : function(resp){
                                data_table_detail = resp.data;
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call($btn_scope, e, dt, button, config);
                              },
                              error : function(xhr){
                                console.log(xhr);
                              }
                            });
                          } catch(error){}
                        }, 500);
                        setTimeout(function(){
                          $('.buttons-excel').prop('disabled', false);
                          $.toast().reset('all');
                        }, 600);
                      } catch(error){
                        setTimeout(function(){
                          $('.buttons-excel').prop('disabled', false);
                          $.toast().reset('all');
                            $.toast({
                            text : "Something when wrong when trying to export data, please try again in a moment ...",
                            hideAfter : 3000,
                            textAlign : 'left',
                            showHideTransition : 'slide',
                            position : 'bottom-right'  
                          })
                        }, 700);
                      }
                      return false;
                    } else {
                      Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Data is empty, nothing to export',
                      })
                      return false;
                    }

                    return false;
                },
                customize: function( xlsx ) {
                  var sSh = xlsx.xl['styles.xml'];
                  $(xlsx.xl["styles.xml"]).find('numFmt[numFmtId="167"]').attr('formatCode', '0.00%');
                  var lastXfIndex = $('cellXfs xf', sSh).length - 1;
                  var lastFontIndex = $('fonts font', sSh).length - 1;
                  var new_font_1 = //bold and underlined font
                  '<font>'+
                    '<sz val="14" />'+
                    '<name val="Calibri" />'+
                    '<color rgb="FFFFFFFF" />'+
                  '</font>'


                  var fillBlackWhiteText = '<fill>'+
                  '<patternFill patternType="solid">'+
                      '<fgColor rgb="FF000000" />'+
                      '<bgColor indexed="64" />'+
                    '</patternFill>'+
                  '</fill>';
                  var s1 = '<xf numFmtId="0" fontId="'+(lastFontIndex + 1)+'" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment vertical="top" horizontal="center" wrapText="1" /></xf>';
                  var s2 = '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment vertical="top" horizontal="center" wrapText="1" /></xf>';
                  var s3 = '<xf numFmtId="0" fontId="'+(lastFontIndex + 1)+'" fillId="6" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment vertical="top" horizontal="center" wrapText="1" /></xf>';
                  var s4 = '<xf numFmtId="167" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment vertical="top" horizontal="right" wrapText="1" /></xf>';

                  sSh.childNodes[0].childNodes[1].innerHTML += new_font_1;
                  sSh.childNodes[0].childNodes[2].innerHTML += fillBlackWhiteText;
                  sSh.childNodes[0].childNodes[5].innerHTML += s1 + s2 + s3 + s4;      
                  var normal = lastXfIndex + 1;
                  var th = lastXfIndex + 2;
                  var header_black_bg = lastXfIndex + 3;
                  var percentage = lastXfIndex + 4;


                  var sheet = xlsx.xl.worksheets['sheet1.xml'];
                  var colRange = sheet.getElementsByTagName('col').length - 1;

                  // Add new 1 row as header
                  var downrows = 1;
                  var clRow = $('row', sheet);

                  //update Row
                  clRow.each(function () {
                    var attr = $(this).attr('r');
                    var ind = parseInt(attr);
                    ind = ind + downrows;
                    $(this).attr("r", ind);
                  });

                  // Update  row > c
                  $('row c', sheet).each(function () {
                    var attr = $(this).attr('r');
                    var pre = attr.substring(0, 1);
                    var ind = parseInt(attr.substring(1, attr.length));
                    ind = ind + downrows;
                    $(this).attr("r", pre + ind);
                  });

                  function Addrow(index, data, colRange, sheet, isMergeCells=false, isAngka=false, isPercentage=false) {
                      msg='<row r="'+index+'">'
                      for(i=0;i<data.length;i++){
                          var key=data[i].key;
                          var value=data[i].value;
                          var style=data[i].hasOwnProperty('style') ? data[i].style : 0;
                          if(isMergeCells){
                            mergeCells( index, colRange, sheet );
                          }
                          if(isAngka){
                            msg += '<c t="n" s="'+ style +'" r="' + key + index + '">';
                            msg +=  '<v>'+value+'</v>';
                            msg +='</c>';
                          } else if(isPercentage){
                            msg += '<c s="'+ style +'" r="' + key + index + '">';
                            msg +=  '<v>'+value+'</v>';
                            msg +='</c>';
                          } else {
                            msg += '<c t="inlineStr" s="'+ style +'" r="' + key + index + '">';
                            msg += '<is>';
                            msg +=  '<t>'+value+'</t>';
                            msg+=  '</is>';
                            msg+='</c>';
                          }
                          
                      }
                      msg += '</row>';
                      return msg;
                  }

                  var mergeCells = function ( row, colspan, sheet ) {
                      var mergeCells = $('mergeCells', sheet);
                      mergeCells[0].appendChild( _createNode( sheet, 'mergeCell', {
                          attr: {
                              ref: 'A'+row+':'+createCellPos(colspan)+row
                          }
                      } ) );
                      // mergeCells.attr( 'count', mergeCells.attr( 'count' )+1 );
                      // $('row:eq('+(row-1)+') c', sheet).attr( 's', '51' ); // centre
                  };

                  function createCellPos( n ){
                      var ordA = 'A'.charCodeAt(0);
                      var ordZ = 'Z'.charCodeAt(0);
                      var len = ordZ - ordA + 1;
                      var s = "";
                    
                      while( n >= 0 ) {
                          s = String.fromCharCode(n % len + ordA) + s;
                          n = Math.floor(n / len) - 1;
                      }
                    
                      return s;
                  }
                  function _createNode( doc, nodeName, opts ) {
                      var tempNode = doc.createElement( nodeName );

                      if ( opts ) {
                          if ( opts.attr ) {
                              $(tempNode).attr( opts.attr );
                          }
                          if ( opts.children ) {
                              $.each( opts.children, function ( key, value ) {
                                  tempNode.appendChild( value );
                              } );
                          }
                          if ( opts.text !== null && opts.text !== undefined ) {
                              tempNode.appendChild( doc.createTextNode( opts.text ) );
                          }
                      }
                      return tempNode;
                  }

                  // BorderStyle
                  // $( 'row c', sheet ).attr( 's', '25');              
                  $( 'row c[r^="G"]', sheet ).attr('s', percentage);
                  $( 'row c[r^="I"]', sheet ).attr('s', percentage);
                  $( 'row:first c', sheet).attr('s', th);
                  var first_row = Addrow(1, [{key: 'A', value: 'MENU ENGINEERING REPORT SUMMARY', style: header_black_bg}], colRange, sheet, true);
                  var first_row_detail = '';
                  var inner_html = '';

                  try {
                    if(data_table_detail.length > 0){
                      var colRangeDetail = (Object.keys(data_table_detail[0]).length - 2);
                      first_row_detail = Addrow(11, [{key: 'A', value: 'MENU ENGINEERING REPORT DETAIL', style: header_black_bg}], colRangeDetail, sheet, true);
                      var colName = [
                        'Major Group',
                        'SAP Material Code',
                        'Menu Item Name',
                        'Sell Price',
                        'Number Sold',
                        'Popularity (%)',
                        'Item Cost',
                        'Cost (%)',
                        'Item Profit',
                        'Total Cost',
                        'Total Revenue',
                        'Total Profit'
                      ];

                      // Header for detail
                      for(var iter=0;iter<(Object.keys(data_table_detail[0]).length - 1);iter++){
                        var row_to_add = Addrow(12, [{key: String.fromCharCode(65 + iter), value: colName[iter], style: th}], colRange, sheet);
                        inner_html += row_to_add;
                      }

                      var detail_header = Object.keys(data_table_detail[0]);
                      var headerIndex = detail_header.indexOf("TotalSell");
                      detail_header.splice(headerIndex, 1);

                      // Data for detail
                      for(var data_iter=0;data_iter<data_table_detail.length;data_iter++){
                        for(var iter=0;iter<detail_header.length;iter++){
                          var columnName = String.fromCharCode(65 + iter);
                          if(columnName == 'D' || columnName == 'E' || columnName == 'G' || columnName == 'I' || columnName == 'J' || columnName == 'K' || columnName == 'L'){
                            var row_to_add = Addrow((13 + data_iter), [{key: columnName, value: data_table_detail[data_iter][detail_header[iter]], style: '63'}], colRange, sheet, false, true);
                          }
                          else if(columnName == 'B'){
                            var row_to_add = Addrow((13 + data_iter), [{key: columnName, value: data_table_detail[data_iter][detail_header[iter]], style: '51'}], colRange, sheet, false, true);
                          }
                          else if(columnName == 'F' || columnName == 'H'){
                            var row_to_add = Addrow((13 + data_iter), [{key: columnName, value: (data_table_detail[data_iter][detail_header[iter]] / 100), style: percentage}], colRange, sheet, false, false, true);
                          }
                          else {
                            var row_to_add = Addrow((13 + data_iter), [{key: columnName, value: data_table_detail[data_iter][detail_header[iter]], style: ''}], colRange, sheet);
                          }
                          inner_html += row_to_add;
                        }

                        // var row_to_add = Addrow(13 + data_iter, [{key: String.fromCharCode(65 + iter), value: colName[iter], style: th}], colRange, sheet);
                        // inner_html += row_to_add;
                      }
                    }
                  } catch(error){
                    console.log(error);
                  }
                  $('<col min="10" max="10" width="20" customWidth=1/><col min="11" max="11" width="20" customWidth=1/><col min="12" max="12" width="20" customWidth=1/>').appendTo(sheet.childNodes[0].childNodes[0]);
                  sheet.childNodes[0].childNodes[1].innerHTML = first_row + sheet.childNodes[0].childNodes[1].innerHTML + first_row_detail + inner_html;

                  var col = $('col', sheet);
                  $(col[1]).attr('width', 18);
                  $(col[2]).attr('width', 40);
                  return false;
                }
            }]
        },
    });

    $('#table-detail').DataTable({
      "paging": true,
      "pageLength" : 10,
      "lengthChange": true,
      "serverSide":true,
      "scrollX": false,
      "searching":true,
      "autoWidth":true,
      "responsive": true,
      "processing":true,
      "ajax":{
          "delay": 600,
          "url": "/sap/menu-engineering/report",
          "type": "GET",
          "dataSrc": function ( json ) {
              if(json.hasOwnProperty('data') && json.data)
               return json.data;
              else
               return [];
          },
          data : {
            "daterange" : params.hasOwnProperty('daterange') ? params.daterange : document.getElementById('daterangepicker').value,
            "plant" : params.hasOwnProperty('plant') ? params.plant : document.getElementById('select-plant').value,
            "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : document.getElementById('cost_center').value,
            "table_detail" : true
          }
      },
      "language": {
        "zeroRecords": "Sorry, there is no data available",
        "processing": ''
      },
      "columns": [
          { data: "MajorGrpName", className: 'text-left' },
          { data: "SapMatCode",
            "render": function (id, type, full, meta)
            {
                return id;
            },
            className: 'text-center'
          },
          { data: "MenuItemName",
            "render": function (id, type, full, meta)
            {
                return id;
            },
            className: 'text-left'
          },
          { data: "SellPrice",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "QtySell",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "PctgPop",
            "render": function (id, type, full, meta)
            {
                return number_format(id, 2, '.', ',') + '%';
            },
            className: 'text-right'
          },
          { data: "ItemCost",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "PctgCost",
            "render": function (id, type, full, meta)
            {
                return number_format(id, 2, '.', ',') + '%';
            },
            className: 'text-right'
          },
          { data: "ItemProfit",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "TotCost",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "TotRev",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          },
          { data: "TotPft",
            "render": function (id, type, full, meta)
            {
                return number_format(id);
            },
            className: 'text-right'
          }
      ],

    });

    $('.select2').select2({
      placeholder: 'Choose data',
      allowClear: true
    });

    $(document).on('change','#select-plant', function(e){
       var plant_value = this.value;
       var cost_center = params.hasOwnProperty('cost_center') ? params.cost_center : '';

       try {
            $('#cost_center').select2('destroy').html("<option value='' selected></option>");
            $("#cost_center").select2({
                placeholder: "Choose Data",
                allowClear: true,
            });
            $('#cost_center').prop('required', true);
        } catch(error){}

       $.ajax({
            "url": "/sap/menu-engineering/report",
            "method": "GET",
            "data" : {"cost_center_lookup": plant_value},
            beforeSend : function(){
                try{
                    $('.loading-cost-center').prop('hidden', false);
                    $('#cost_center').prop('disabled', true);
                    // $('.btn').prop('disabled', true);
                } catch(error){}
            },
            success : function(response){
                try {
                    var newOptionCostCenter = [];
                    if(response.hasOwnProperty('data') && response.data && response.data.length){
                        $.each(response.data, function(index, data){
                            if(cost_center == data.SAP_COST_CENTER_ID){
                                newOptionCostCenter[index] = new Option(`${data.SAP_COST_CENTER_ID} - ${data.SAP_COST_CENTER_DESCRIPTION}`, `${data.SAP_COST_CENTER_ID}`, true, true);
                            } else {
                                newOptionCostCenter[index] = new Option(`${data.SAP_COST_CENTER_ID} - ${data.SAP_COST_CENTER_DESCRIPTION}`, `${data.SAP_COST_CENTER_ID}`, false, false);
                            }
                        });
                    } else {
                        $('#cost_center').prop('disabled', true);
                    }

                    setTimeout(function(){
                        $('#cost_center').append(newOptionCostCenter).trigger('change');
                        // $('#material_group_request_select').select2('open');
                    },100)
                } catch(error){
                    // Swal.fire('Oops..', 'Something went wrong while generating data, please check your connection', 'error');
                    $.toast({
                      text : "Oops.. Something went wrong while generating data, please check your connection",
                      hideAfter : 4000,
                      textAlign : 'left',
                      showHideTransition : 'slide',
                      position : 'bottom-right'  
                    });
                }
            },
            error : function(xhr){
                // Swal.fire('Oops..', 'Something went wrong when trying to load cost center data, please try again later', 'error');
                $.toast({
                  text : "Oops.. Something went wrong when trying to load cost center data, please try again later",
                  hideAfter : 4000,
                  textAlign : 'left',
                  showHideTransition : 'slide',
                  position : 'bottom-right'  
                });
            },
            complete : function(){
                $('.loading-cost-center').prop('hidden', true);
                $('#cost_center').prop('disabled', false);
                $('#cost_center').prop('required', true);
                // $('.btn').prop('disabled', false);
            }
       })
    });


  })
</script>
@endsection
