@extends('layouts.default')
@section('title', 'List Reservation')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<style>

::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #a7afb7 !important;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color:  #a7afb7;;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #a7afb7;;
}

.red{
    color:red !Important;
}

.thead-apri{
    text-align:center;
    vertical-align:middle;
}

.td-apri{
    text-transform:uppercase;
    text-align:center;
}

.td-apri[readonly], .td-apri[disabled]{
    background:#f5f5f5 !important;
}

select[readonly].select2-hidden-accessible + .select2-container {
    pointer-events: none;
    touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
    background: #e9ecef;
    box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
    display: none;
}

/*#modalFile{
    padding-right:0px !important;
}

#modalFile .modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  max-width:100%;
}

#modalFile .modal-content {
  height: auto;
  min-height: 100%;
  border-radius: 0;
}

#modalFile .modal-lg{
    max-width:100%;
    width:100%;
}*/

.modal-dialog {
    max-width: unset !important;
}

.dataTables_wrapper .dataTables_processing {
    top: 2em !important;
    left: 40% !important;
}
.select-plain {
    padding: 0 !important;
    border: none !important;
    outline: none !important;
    font-size: 0.75rem !important;
    color: #6c7293 !important;
    max-width: 100px !important;
    text-align: left !important;
    margin: 0 -5px .5rem !important;
}
.dataTables_wrapper{
    position: relative;
}
.table-container-h {
    overflow: auto;
}
.reject-info {
    max-height: 200px
}
.reject-info h6 {
    line-height: 1.5;
    max-height: 60px;
    /*overflow: hidden;*/
    text-overflow:ellipsis;
    overflow:hidden;
    display: -webkit-box !important;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    white-space: normal;
}
.custom-material-wrapper {
    position: absolute;
    right: 0;
    top: -4em;
}
@media (min-width: 576px){
  .modal-dialog-custom-width {
    max-width: 1200px;
  }
}
.badge-additional {
    position: absolute;
    top: -1.2em;
    right: -11px;
    border: 2px solid #fff;
}
</style>

@endsection

@section('content')
<div class="row" id="app">
    <div class="col-lg-12 col-xl-12 grid-margin stretch-card flex-column d-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Forms</a></li>
                <li class="breadcrumb-item"><a href="#">Reservation</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>List</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">List Reservation</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 form-group" style="position: relative;overflow:hidden">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <!-- <label class="">Request Date</label> -->
                                            <select class="form-control select-plain" name="filter_type" id="filter_type">
                                              <option value="INSERT_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'INSERT_DATE') selected default @endif>Request Date</option>
                                              <option value="DELIVERY_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'DELIVERY_DATE') selected default @endif>Delivery Date</option>
                                            </select>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker2" name="date_from" id="date_from" value="{{ isset($data['created_date_from']) && !empty($data['created_date_from']) ? date('m/d/Y',strtotime($data['created_date_from'])) : date('m/01/Y') }}" placeholder="Date From">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="date_to" id="date_to" value="{{ isset($data['created_date_to']) && !empty($data['created_date_to']) ? date('m/d/Y',strtotime($data['created_date_to'])) : date('m/d/Y') }}" placeholder="Date To">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <label class="">Cost Center Requestor</label>
                                            <div class="input-group">
                                                <select name="cost_center" id="cost_center" class="form-control select2">
                                                    <option value="" selected></option>
                                                    @foreach ($list_cost_center as $cost_center)
                                                        <option @if(isset($filtered_cost_center) && $filtered_cost_center == $cost_center->SAP_COST_CENTER_ID) selected @endif value="{{$cost_center->SAP_COST_CENTER_ID}}">{{$cost_center->SAP_COST_CENTER_DESCRIPTION}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label class="">Status</label>
                                        </div>
                                        <div class="input-group">
                                            <div class="col-4">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">All</option>
                                                    <option value="Waiting" {{ (isset($data['status']) && $data['status']=="Waiting")? 'selected' : '' }}>Waiting for Approval</option>
                                                    <option value="Finished" {{ (isset($data['status']) && $data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                                    <option value="Rejected" {{ (isset($data['status']) && $data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                            <div class="col-8">
                                                <a href="{{url('finance/purchase-requisition-marketlist/list')}}" class="btn btn-danger" id="resetList">Reset</a>
                                                <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                        <table class="table table-bordered table-striped" id="requestList" style="white-space: nowrap; width: 100%">
                            <thead>
                                <tr>
                                    <th style="min-width:90px;">Form No</th>
                                    <th style="min-width:90px;">SAP Reservation No.</th>
                                    <th style="min-width:90px;">Status</th>
                                    <th style="min-width:90px;">Request For Date</th>
                                    <th style="min-width:90px;">Approval Name</th>
                                    <th style="min-width:90px;">Approval Date</th>
                                    <th style="min-width:90px;">Grand Total</th>
                                    <th style="min-width:90px;">Movement Type</th>
                                    <th style="min-width:90px;">Receiving SLOC / Cost Center</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalHistory"  role="dialog" aria-labelledby="modalHistoryLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistoryLabel">History List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalHistory">
                <table class="table table-bordered datatable requestHistoryList" id="requestHistoryList">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Level Desc</th>
                            <th style="min-width: 150px !important">Approver List</th>
                            <th>Approved By</th>
                            <th>Status</th>
                            <th>Approval Date</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody id="historyListDT">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFile"  role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Request Detail </h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalFile">
            </div>
        </div>
    </div>
</div>

{{--<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">
<input type="hidden" name="deptid" id="deptid" class="form-control" value="{{$data['department_id']}}">
<input type="hidden" name="midjobid" id="midjobid" class="form-control" value="{{$data['midjob_id']}}">
<input type="hidden" name="costcenter" id="costcenter" class="form-control" value="{{$data['costcenter']}}">
<input type="hidden" name="releasecode" id="releasecode" class="form-control" value="{{$data['releasecode']}}">
<input type="hidden" name="companycode" id="companycode" class="form-control" value="{{$data['company_code']}}">
<input type="hidden" name="type_form" id="type_form" class="form-control" value="{{$data['form_code']}}">
<input type="hidden" id="current_user_release_code" value="{{$data['current_user_release_code']}}">--}}

@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>
<script>
    $(function(){
        $(".datepicker2").datepicker();
    })

    $(document).ready(function(){
        $(".select2").select2({
            allowClear: true, 
            placeholder: 'Select Data Here'
        });
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
    var objFile = {};var objJsonDetail = {}; var objRow = {};
    var data_movement_type = [];
    var data_rsv_number = [];
    var data_filter_menu = {};
    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());

    var tables =  $('#requestList').DataTable({
        "dom": '<"abs-search row mb-2 align-items-end" <"button-export-wrapper col-6 align-items-center"Bfl>> <"table-wrapper table-container-h"rt> ip',
        "processing":true,
        "serverSide":true,
        "scrollX": false,
        "searching":false,
        "pageLength": 50,
        'paging':true,
        // "lengthChange": false,
        "autoWidth":false,
        "responsive": true,
        "ajax":{
            "url": "/finance/add-reservation/list",
            "type": "GET",
            "dataSrc": function ( json ) {
                try {
                    data_movement_type = [...new Set(json.data.map(item => `${item.MOVEMENT_TYPE}`))];
                    data_rsv_number = [...new Set(json.data.map(item => `${item.SAP_RSV_NO}`))];
                } catch(error){}
                //Make your callback here.
                if(json.hasOwnProperty('data') && json.data)
                 return json.data;
                else
                 return [];
            },
            data : {
                "date_from" : params.hasOwnProperty('date_from') ? params.date_from : document.getElementById('date_from').value,
                "date_to" : params.hasOwnProperty('date_to') ? params.date_to : document.getElementById('date_to').value,
                "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : document.getElementById('cost_center').value,
                "status" : params.hasOwnProperty('status') ? params.status : '',
                "filter_type" : params.hasOwnProperty('filter_type') ? params.filter_type : ''
            }
        },
        "columns": [
            {
                "data": "UID",
                "render": function (id, type, full, meta)
                {
                    return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                },   className: 'text-left'
            },
            { "data": "SAP_RSV_NO", className: 'text-center'},
            { 
                "data": "STATUS_APPROVAL", className: 'text-left',
                "render": function (id, type, full, meta)
                {

                    if(id == null || id == "null"){ id = "REQUESTED"; }
                    if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}
                    // return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';

                    if(id=="APPROVED" || id=="REQUESTED" || id=="" || id=="WAITING FOR APPROVAL"){
                        return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;">'+id+'</a>';
                    }else if(id=="FINISHED"){
                        return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;color:green;">'+id+'</a>';
                    }else{
                        return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;color:red;">'+id+'</a>';
                    }
                }
            },
            { 
                "data": "CREATED_FOR_DATE",
                "render": function (id, type, full, meta)
                {
                    return type === 'export-excel' ? moment(id).format("YYYY-MM-DD") : moment(id).format("MM/DD/YYYY");
                }
            },
            { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
            { 
                "data": "LAST_APPROVAL_DATE", className: 'text-center',
                "render": function (id, type, full, meta)
                {
                    if(id != null){
                        return type === 'export-excel' ? moment(id).format("YYYY-MM-DD HH:mm") : moment(id).format("MM/DD/YYYY - HH:mm");
                    }
                    else{
                        return "";
                    }

                }
            },
            { 
                "data": "GRAND_TOTAL",
                className: 'text-right',
                "render": function (id, type, full, meta)
                {
                    return id;
                }
            },
            { "data": "MOVEMENT_TYPE", className: 'text-left'},
            { "data": "RCV_SLOC_CC", className: 'text-left'},
        ],
        "language": {
            "zeroRecords": "Sorry, there is no data available at the moment",
            "processing": ''
        },
        "order": [[ 1, "desc" ]],
        initComplete : function(settings, json){
            var table_obj = this.api();
            var requestor = [];
            if(typeof json.data.length != 'undefined' && json.data.length > 0){
                requestor = [...new Set(json.data.map(element => ({'ALIAS':element.ALIAS,'NAME':element.NAME})).filter(x => x !== null))];
                requestor = [...new Map(requestor.map((item) => [item["NAME"], item]).filter(x => x[0] !== null && x[0] !== '-')).values()];
            }
            var select = $(`<div class="content-filter col-2 mb-2"><label>Filter By Requestor Name</label><div><select style="width: 100%" class="form-control select2-requestor filter-select-1 mr-3" data-filter="0"><option value="">Pilih Data Disini</option></select></div></div>`)
            .appendTo( $('.abs-search') )
            .on('select2:select', function (e) {
                var value = e.params.data.id;
                var val = $.fn.dataTable.util.escapeRegex(
                    value
                );
                if(val){
                  data_filter_menu['REQUESTOR_NAME'] = val
                }
                const qs = Object.keys(data_filter_menu)
                .map(key => `${key}=${data_filter_menu[key]}`)
                .join('&');
                if(qs)
                  table_obj.ajax.url(`?${qs}`).load();
            }).on("select2:unselecting", function(e) {
                try {
                  delete data_filter_menu['REQUESTOR_NAME'];
                  const qs = Object.keys(data_filter_menu)
                  .map(key => `${key}=${data_filter_menu[key]}`)
                  .join('&');
                  if(qs)
                    table_obj.ajax.url(`list?${qs}`).load();
                  else
                    table_obj.ajax.url(`list`).load();

                } catch(error){}
                $(this).data('state', 'unselected');
            }).on("select2:open", function(e) {
                try {
                  if ($(this).data('state') === 'unselected') {
                      $(this).removeData('state'); 

                      var self = $(this).find('.select2-requestor')[0];
                      setTimeout(function() {
                          $(self).select2('close');
                      }, 0);
                  }
                } catch(error){}   
            });

            $(requestor).each( function ( d, j ) {
                $(`.filter-select-1`).append( '<option value="'+j.ALIAS+'">'+j.NAME+'</option>' )
            });

            $('.select2-requestor').select2({
              placeholder: 'Choose Data',
              allowClear: true
            });

            // Filter costcenter dan sloc
            var text_order = 0;
            this.api().columns([1, 7]).every( function (i) {
                var column = this;
                var text = [{'name':'Reservation No.', 'filter_name':'reservation-no'}, {'name':'Movement Type', 'filter_name':'mv-type'}];
                var paramName = ['RESERVATION_NO_SAP','MOVEMENT_TYPE'];
                var col_length = 'col-2';
                var select = $(`<div class="content-filter mb-2 ${col_length}"><label>Filter By ${text[text_order].name}</label><div><select style="width: 100%" class="form-control select2-filter-reservation filter-select-${text[text_order].filter_name} mr-3" data-filter="${text_order}"><option value="">Choose Data</option></select></div></div>`)
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
                      table_obj.ajax.url(`list?${qs}`).load();

                }).on("select2:unselecting", function(e) {
                    var target = $(e.target).data('filter');
                    try {
                      delete data_filter_menu[paramName[target]]
                      const qs = Object.keys(data_filter_menu)
                      .map(key => `${key}=${data_filter_menu[key]}`)
                      .join('&');
                      if(qs)
                        table_obj.ajax.url(`list?${qs}`).load();
                      else
                        table_obj.ajax.url(`list`).load();

                    } catch(error){
                      console.log(error);
                    }
                    $(this).data('state', 'unselected');
                }).on("select2:open", function(e) {
                    try {
                      if ($(this).data('state') === 'unselected') {
                          $(this).removeData('state'); 

                          var self = $(this).find('.select2-filter-reservation')[0];
                          setTimeout(function() {
                              $(self).select2('close');
                          }, 0);
                      }
                    } catch(error){}   
                });
                
                if(i == 1){
                    $(data_rsv_number).each( function ( d, j ) {
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+j+'">'+j+'</option>');
                    });
                } else {
                    $(data_movement_type).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                    });
                }

                text_order++;
            });

            $('.select2-filter-reservation').select2({
              placeholder: 'Choose data',
              allowClear: true
            });
        },
    });

    function getFormDetail(id){
        $('.loader-modal').show();
        $('#modalFile #bodyModalFile').html('');
        $.get("{{url('finance/add-reservation/modal-detail')}}", { id : id, reservation_type: 'report'}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $('.loader-modal').hide();
        });
    }

    function getDetailPO(id){
        var segment='request';
        $('#modalPO #bodymodalPO').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition/modal-detail-po')}}", { id : id, segment : segment}, function( data ) {
            $('#modalPO #bodymodalPO').html(data);
            $(".loader-modal").hide();
        });

    }

    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }

    function getHistoryDetail(id){
        $('#requestHistoryList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/add-reservation/getHistoryApproval',
            dataSrc: "data",
            data: {
                "form_number":id
            },
            success: function(responseSIA){
                if(responseSIA.code == "200"){
                    if(responseSIA.data.length > 0)
                    {
                        listSIA = responseSIA.data;
                        for (i = 0; i < listSIA.length; i++) {
                            try {
                                var approver = listSIA[i].APPROVER.split(",");
                                approver = [...new Set(approver)];
                                
                                if(approver.length > 1){
                                    var truncated = 'truncated';
                                } else {
                                    var truncated = '';
                                }
                                approver = approver.map(x => { 
                                    if(x) 
                                        return `<p>${x.trim()}</p>`; 
                                    else 
                                        return ""; 
                                }).join('');
                            } catch(error) { var approver = '-'; }

                            if(changeNull(listSIA[i].APPROVAL_DATE) == ""){
                                var APPROVAL_DATE = "";
                            }
                            else{
                                var APPROVAL_DATE = moment(listSIA[i].APPROVAL_DATE).format("DD/MM/YYYY - HH:mm");
                            }
                            tr += '<tr>';
                            tr += '<td>'+changeNull(listSIA[i].LEVEL_APPROVAL)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].TYPE_DESC)+'</td>';
                            tr += `<td class="text-left ${truncated} truncated-wrapper">`+changeNull(approver)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].EMPLOYEE_NAME)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].STATUS_APPROVAL)+'</td>';
                            tr += '<td>'+APPROVAL_DATE+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].REASON)+'</td>';
                            tr += '</tr>';
                        }
                    }
                }else{
                    swal("Error API!", responseSIA.message, type);
                }
                $('#historyListDT').html(tr);

                document.querySelectorAll(".truncated").forEach(function(current) {
                    current.addEventListener("click", function(e) {
                        if(current.classList.contains('truncated'))
                            current.classList.remove("truncated");
                        else
                            current.classList.add("truncated");
                    }, false);
                });
            }
        });
    }

</script>
@endsection
