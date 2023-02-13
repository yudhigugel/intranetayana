@extends('layouts.default')
@section('title', 'List Purchase Order')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}">
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

#modalFile{
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
}
.dataTables_info {
    float: left;

}
.dataTables_wrapper .dataTables_processing {
    top: 50px !important
}

.abs-search {
    float: none !important;
}

.datepicker2[disabled] {
    /*background: white;*/
    background: #e9ecef !important;
}
</style>

@endsection

@section('content')
<div class="row" id="app">
    <div class="col-lg-12 col-xl-12 grid-margin stretch-card flex-column d-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Finance</a></li>
                <li class="breadcrumb-item"><a href="#">Purchase Order</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>List</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">List Purchase Order (Final Release)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 form-group" style="position: relative;overflow:hidden">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-12 mb-2">
                                            <label class="">Created Date (Monthly)</label>
                                            <div class="input-group">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="created_date_from" id="created_date_from" value="{{ isset($data['created_date_from']) && !empty($data['created_date_from']) ? date('m/d/Y',strtotime($data['created_date_from'])) : date('m/d/Y') }}" placeholder="Date From">

                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" id="created_date_to_display" value="{{ isset($data['created_date_to']) && !empty($data['created_date_to']) ? date('m/d/Y',strtotime($data['created_date_to'])) : date('m/d/Y', strtotime('+30 day', strtotime(date('m/d/Y')) )) }}" placeholder="Date To" disabled>

                                                <input type="hidden" name="created_date_to" id="created_date_to" value="{{ isset($data['created_date_to']) && !empty($data['created_date_to']) ? date('m/d/Y',strtotime($data['created_date_to'])) : date('m/d/Y', strtotime('+30 day', strtotime(date('m/d/Y')) )) }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">* Date Range will always calculated 31 days</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="">Plant</label>
                                            <div class="input-group mb-1">
                                                <select name="plant_filter" id="plant_filter" required class="form-control select2" disabled>
                                                    <option value="">-- Select Plant --</option>
                                                    @foreach ($data['plant_list'] as $pl)
                                                        <option @if(isset($filtered_plant) && isset($pl->SAP_PLANT_ID) && $filtered_plant == $pl->SAP_PLANT_ID) selected @endif value="{{$pl->SAP_PLANT_ID}}">{{ isset($pl->SAP_PLANT_ID) ? $pl->SAP_PLANT_ID." - " : 'Unknown' }} {{ isset($pl->SAP_PLANT_NAME) ? $pl->SAP_PLANT_NAME : '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <small class="text-muted">* Choose plant first to get cost center</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="">Cost Center</label>
                                            <div style="position: absolute;top: -5px;left: 6em;">
                                                <small class="text-muted loading-cost-center" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}"></small>
                                            </div>
                                            <div class="input-group">
                                                <select name="cost_center" id="cost_center" class="form-control select2" disabled required>
                                                    {{--<option value="">-- Select Cost Center --</option>
                                                    @foreach ($list_cost_center as $cost_center)
                                                        <option @if(isset($filtered_cost_center) && $filtered_cost_center == $cost_center->SAP_COST_CENTER_ID) selected @endif value="{{$cost_center->SAP_COST_CENTER_ID}}">{{$cost_center->SAP_COST_CENTER_DESCRIPTION}}</option>
                                                    @endforeach
                                                    --}}
                                                </select>
                                            </div>
                                            <small class="text-muted">* Choose cost center to reduce load time</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-3">
                                    <div class="mb-3 row">
                                        <div class="col-md-12">
                                            {{--<label class="">Apply</label>--}}
                                            <div class="input-group justify-content-end">
                                            <a href="{{url('finance/purchase-order/list')}}" class="btn btn-danger mr-2" id="resetList">Reset</a>
                                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                        <br />
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped compact" id="approvalList" style="color: #000; width: 100%;">
                                <thead>
                                    <tr>
                                        {{--<th style="width:20px;"><input type="checkbox" name="select_all" value="1" id="approvalList-select-all"></th>--}}
                                        <th class="exportable" style="width:10%;">PO. Number</th>
                                        <th class="exportable" style="width:10%;">From PR</th>
                                        {{-- <th style="min-width:70px;">Created By</th> --}}
                                        <th class="exportable" style="width:10%;">Created Date</th>

                                        <!-- Export Field -->
                                        <th class="exportable" style="display: none;">PO Purpose</th>
                                        <th class="exportable" style="display: none;">Vendor Name</th>
                                        <th class="exportable" style="display: none;">Requestor Cost Center</th>
                                        <th class="exportable" style="display: none;">Tracking No.</th>
                                        <th class="exportable" style="display: none;">Material</th>
                                        <th class="exportable" style="display: none;">Material Desc.</th>

                                        <th class="exportable" style="display: none;">Requisitioner</th>
                                        <th class="exportable" style="display: none;">Item Reason</th>
                                        <th class="exportable" style="display: none;">Item Qty</th>
                                        <th class="exportable" style="display: none;">Order Unit</th>
                                        <th class="exportable" style="display: none;">Store Loc.</th>
                                        <th class="exportable" style="display: none;">Plant</th>
                                        <th class="exportable" style="display: none;">Currency</th>
                                        <th class="exportable" style="display: none;">Net Price</th>
                                        <th class="exportable" style="display: none;">VAT</th>
                                        <th class="exportable" style="display: none;">Subtotal</th>
                                        <!-- End Export Field -->

                                        {{--<th style="max-width:90px;">Company Code</th>--}}
                                        <th style="width:15%;">Cost Center ID</th>
                                        <th style="width:20%;">Cost Center Desc</th>
                                        {{--<th style="min-width:90px;">Purch. Group</th>--}}
                                        <th style="width:10%;">Currency</th>
                                        <th style="width:10%;">Doc. Type</th>
                                        <th style="width:15%;">Grand Total</th>

                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="bg-secondary text-white text-center" colspan="23">Total Purchase Order (IDR)</th>
                                        <th class="bg-secondary text-white text-center">{{ 0 }}</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-secondary text-white text-center" colspan="23">Total Purchase Order (USD)</th>
                                        <th class="bg-secondary text-white text-center">{{ 0 }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                            {{--<div class="form-group">
                                <button type="button" class="btn btn-danger" id="rejectForm" onClick="actionForm('Cancel', 'REJECTED')"><i class="fa fa-close"></i>&nbsp; Cancel Approve</button>
                                <!-- <button type="button" class="btn btn-primary" id="approveForm" onClick="actionForm('Approve','APPROVED')"><i class="fa fa-check"></i>&nbsp; Batch Approve</button> -->
                            </div>--}}
                        </div>
                    </div>
                </div>
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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>
<script>
    $(window).on('load', function(){
        try{
            var plant = $('#plant_filter').val();
            if(plant)
                $('#plant_filter').trigger('change');

        } catch(error){}
    });

    $(function(){
        $(".datepicker2").datepicker({
            onSelect: function(dateText) {
                var now_date = moment(new Date(dateText)).add(30, 'd').format('MM/DD/YYYY');
                try {
                    $('#created_date_to').val(now_date);
                    $('#created_date_to_display').val(now_date);
                } catch(error) {}
            }
        });
        $('#plant_filter').prop('disabled', false);
        // $('#cost_center').prop('disabled', false);

        $(".select2").select2({
            allowClear: true,
            placeholder: 'Choose Data',
        }).on('select2:unselecting', function(){
            $(this).data('state', 'unselected');
        }).on("select2:open", function(e) {
            try {
                // console.log($(this).data('state'), $(this));
              if ($(this).data('state') === 'unselected') {
                  $(this).removeData('state'); 
                  var self = $(this)[0];
                  setTimeout(function() {
                      $(self).select2('close');
                  }, 0);
              }
            } catch(error){}   
        });    
        
    });

    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    var data_filter_menu = {};
    $(document).on('change','#plant_filter', function(e){
       var plant_value = this.value;
       var cost_center = params.hasOwnProperty('cost_center') ? params.cost_center : '';

       try {
            $('#cost_center').select2('destroy').html("<option value='' selected default></option>");
            $("#cost_center").select2({
                placeholder: "Choose Data",
                allowClear: true,
            });
        } catch(error){}

       $.ajax({
            "url": "/finance/purchase-order/list",
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
                        newOptionCostCenter[0] = new Option(`Choose Cost Center`, '', false, true);
                        $.each(response.data, function(index, data){
                            if(cost_center == data.SAP_COST_CENTER_ID){
                                newOptionCostCenter[index+1] = new Option(`${data.SAP_COST_CENTER_ID} - ${data.SAP_COST_CENTER_DESCRIPTION}`, `${data.SAP_COST_CENTER_ID}`, true, true);
                            } else {
                                newOptionCostCenter[index+1] = new Option(`${data.SAP_COST_CENTER_ID} - ${data.SAP_COST_CENTER_DESCRIPTION}`, `${data.SAP_COST_CENTER_ID}`, false, false);
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
    })

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

    WrapAndBreak = function(index) {
        // if (index > 0){ //skip the header
        $(this).attr('s', '55'); //wrap
        var cellText = $('t', $(this));
        if (cellText.text() != null && cellText.text().indexOf('~') > 0){
            cellText.text(cellText.text().replace(/~/g,"\n")); //insert stripped line break
        }
        // }
    }

    var objFile = {};var objJsonDetail = {}; var objRow = {};
    var tables =  $('#approvalList').DataTable({
        "dom":'<"abs-search row mb-2" <"button-export-wrapper col-9 align-items-center"B>>rtip',
        "processing":true,
        "serverSide":true,
        "searching":false,
        "pageLength": 100,
        // "lengthChange": false,
        "autoWidth":false,
        // "scrollX": true,
        "ajax":{
            "url": "/finance/purchase-order/list",
            "type": "GET",
            "dataSrc": function ( json ) {
                //Make your callback here.
               if(json.hasOwnProperty('data') && json.data)
                return json.data;
               else
                return [];
            },
            data : {
                "created_date_from" : params.hasOwnProperty('created_date_from') ? params.created_date_from : '',
                "created_date_to" : params.hasOwnProperty('created_date_to') ? params.created_date_to : '',
                "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : '',
                "plant_filter": params.hasOwnProperty('plant_filter') ? params.plant_filter : ''
            }
        },
        'columnDefs': [{
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
                return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
            }
        }],
        "columns": [
            { "data": "PO_NUMBER",
                "render": function (id, type, full, meta)
                {
                    // console.log(id, type, full);
                    return '<a href="{{url("finance/purchase-order/detail_approved/")}}/'+id+'" ><strong>'+id+'</strong></a>';
                }
            },
            { "data": "REQ_NO" },
            // { "data": "PR_REQ_NAME" },
            { "data": "CREATED_ON" ,
                "render": function (id, type, full, meta)
                {
                    return moment(id).format("DD MMM YYYY");
                }
            },
            { 
                "data": "PO_HEADER_TEXTS",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "VEND_NAME",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "COST_CENTER_DESC",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        if(id)
                            return id;
                        else
                            return 'PO MRP';
                    }
                    return '';
                }
            },
            { 
                "data": "TRACKING_NO",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "MATERIAL",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "MATERIAL_DESC",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "REQUISITIONER",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "ITEM_REASON",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "PO_QTY",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "UOM",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "SLOC",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "PLANT",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "CURRENCY",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "NET_VALUE",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "TAX",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },
            { 
                "data": "SUBTOTAL",
                "visible": false,
                render :function(id, type, full, meta){
                    if(type === 'export-excel'){
                        return id;
                    }
                    return '';
                }
            },

            // { "data": "CO_CODE" },
            // { "data": "PUR_GROUP"},
            { "data": "COST_CENTER" },
            { "data": "COST_CENTER_DESC", className: "text-left"},
            { "data": "CURRENCY" },
            { "data": "DOC_TYPE" },
            { "data": "TARGET_VAL",
                "render": function (id, type, full, meta)
                    {
                        return number_format(id);
                    }
            }
        ],
        "language": {
            "zeroRecords": "Sorry, there is no data at the moment",
            "processing": ''
        },
        "order": [[ 2, "desc" ]],
        "fixedHeader": {
            header: true,
            footer:true
        },
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
                title: '',
                className : 'btn btn-primary',
                text: '<i class="mdi mdi-export"></i>&nbsp;Export Excel',
                exportOptions: {
                    orthogonal: 'export-excel',
                    columns: 'th.exportable'
                },
                action: function(e, dt, button, config) {
                    var data_po = [];
                    var data_po_no = [];
                    $btn_scope = this;

                    try {
                      var table = $('#approvalList').DataTable();
                      data_po = table.rows().data();
                    } catch(error){}

                    if(data_po.length > 0) {
                        for(i=0;i<data_po.length;i++){
                            if(data_po[i].hasOwnProperty('PO_NUMBER') && data_po[i].PO_NUMBER){
                                data_po_no.push(data_po[i].PO_NUMBER);
                            }
                        }
                        try{ 
                            $('.buttons-excel').prop('disabled', true);
                            $.toast({
                              text : "<i class='fa fa-spin fa-spinner'></i> &nbsp;Exporting data...",
                              hideAfter : false,
                              textAlign : 'left',
                              showHideTransition : 'slide',
                              position : 'bottom-right'  
                            });
                      } catch(error){}

                      var $data_sent = {
                        'po_no' : []
                      }

                      $.ajax({
                        "url": "/finance/purchase-order/export",
                        "type": "POST",
                        "data" : $data_sent
                      }).then(function (ajaxReturnedData) {
                        setTimeout(function(){
                          dt.clear();
                          dt.rows.add(ajaxReturnedData.data);
                          $.fn.dataTable.ext.buttons.excelHtml5.action.call($btn_scope, e, dt, button, config);
                        }, 400)
                      }).catch(function(error){
                          setTimeout(function(){
                            Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Something went wrong while exporting, please try again in a moment',
                            })
                          },400);
                      }).done(function(){
                          $('.buttons-excel').prop('disabled', false);
                          try {
                            $.toast().reset('all');
                          } catch(error){}
                      });
                      return false;

                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Oops...',
                            text: 'Data is empty, nothing to export',
                        })
                    }
                },
                customize: function(xlsx) {
                    // var ocellXfs = $('cellXfs', xlsx.xl['styles.xml']);
                    // ocellXfs.append('<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'+'<alignment horizontal="center" vertical="top" wrapText="0" />'+'</xf>');
                    // //this doesn't seem needed but maybe useful
                    // ocellXfs.attr('count', ocellXfs.attr('count') +1 );
                    // var oxf = $('xf', xlsx.xl['styles.xml']);
                    // var styleIndex = oxf.length;
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    // Set Properties
                    var column_width_within_index = [
                        {
                            width: 14,
                            wrap_text:'',
                            alignment: '51',
                            column : 'PO. Number',
                            is_wrap : false,
                            col_name : 'A'
                        },
                        {
                            width: 14,
                            wrap_text:'',
                            alignment: '51',
                            column : 'From PR',
                            is_wrap : false,
                            col_name : 'B'
                        },
                        {
                            width: 14,
                            wrap_text:'',
                            alignment: '51',
                            column : 'Created Date',
                            is_wrap : false,
                            col_name : 'C'
                        },
                        {
                            width: 35,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'PO Purpose',
                            is_wrap : true,
                            col_name : 'D'
                        },
                        {
                            width: 35,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Vendor Name',
                            is_wrap : false,
                            col_name : 'E'
                        },
                        {
                            width: 35,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Requestor Cost Center',
                            is_wrap : false,
                            col_name : 'F'
                        },
                        {
                            width: 15,
                            wrap_text:'55',
                            alignment: '51',
                            column : 'Tracking No',
                            is_wrap : false,
                            col_name : 'G'
                        },
                        {
                            width: 15,
                            wrap_text:'55',
                            alignment: '51',
                            column : 'Material',
                            is_wrap : false,
                            col_name : 'H'
                        },
                        {
                            width: 40,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Material Desc.',
                            is_wrap : false,
                            col_name : 'I'
                        },
                        {
                            width: 37,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Requisitioner',
                            is_wrap : false,
                            col_name : 'J'
                        },
                        {
                            width: 35,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Item Reason',
                            is_wrap : true,
                            col_name : 'K'
                        },
                        {
                            width: 14,
                            wrap_text:'55',
                            alignment: '52',
                            column : 'Item Qty',
                            is_wrap : false,
                            col_name : 'L'
                        },
                        {
                            width: 14,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Order Unit',
                            is_wrap : false,
                            col_name : 'M'
                        },
                        {
                            width: 14,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Store Loc.',
                            is_wrap : false,
                            col_name : 'N'
                        },
                        {
                            width: 12,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Plant',
                            is_wrap : false,
                            col_name : 'O'
                        },
                        {
                            width: 13,
                            wrap_text:'55',
                            alignment: '50',
                            column : 'Currency',
                            is_wrap : false,
                            col_name : 'P'
                        },
                        {
                            width: 19,
                            wrap_text:'55',
                            alignment: '52',
                            column : 'Net Price',
                            is_wrap : false,
                            col_name : 'Q'
                        },
                        {
                            width: 19,
                            wrap_text:'55',
                            alignment: '52',
                            column : 'VAT',
                            is_wrap : false,
                            col_name : 'R'
                        },
                        {
                            width: 19,
                            wrap_text:'55',
                            alignment: '52',
                            column : 'Subtotal',
                            is_wrap : false,
                            col_name : 'S'
                        }
                    ];
                    var col = $('col', sheet);
                    $('row:first c', sheet).attr( 's', '2' );
                    col.each(function (index, val) {
                        $(this).attr('width', column_width_within_index[index].width);
                        if(column_width_within_index[index].col_name != 'Q' && column_width_within_index[index].col_name != 'R' && column_width_within_index[index].col_name != 'S' && column_width_within_index[index].col_name != 'L'){
                            if(column_width_within_index[index].is_wrap){
                                $(`row:not(:first) c[r^="${column_width_within_index[index].col_name.toUpperCase()}"]`, sheet).each(WrapAndBreak);
                            } else {
                                $(`row:not(:first) c[r^="${column_width_within_index[index].col_name.toUpperCase()}"]`, sheet).attr('s', column_width_within_index[index].alignment);
                            }
                        }
                        
                    });
                    // $('row:not(:first) c[r^="A"]', sheet).attr('s', '51');
                    // $('row:not(:first) c[r^="B"]', sheet).attr('s', '51');
                    // $('row:not(:first) c[r^="C"]', sheet).attr('s', '51');
                    // $('row:not(:first) c[r^="D"]', sheet).each(WrapAndBreak);
                    // $('row:not(:first) c[r^="E"]', sheet).attr('s', '50');
                    // $('row:not(:first) c[r^="F"]', sheet).attr('s', '50');

                    // Top align Vertical
                    // $('row:not(:first) c', sheet).attr('s', styleIndex - 2);
                }
            }]
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages IDR
            var total_idr = 0;
            api.column( 23 )
            .data()
            .each( function (value, index) {
                if(api.column( 21 ).data()[index] == 'IDR')
                    total_idr += intVal(value);
            });
            // Total over all pages USD
            var total_usd = 0;
            api.column( 23 )
            .data()
            .each( function (value, index) {
                if(api.column( 21 ).data()[index] == 'USD')
                    total_usd += intVal(value);
            });

            // Update footer
            total_idr = number_format(total_idr, 0, 0 , ',');
            total_usd = number_format(total_usd, 0, 0 , ',');
            // $( api.column( 7 ).footer() ).html(total);

            $('tr:eq(0) th:eq(1)', api.table().footer()).html(total_idr);
            $('tr:eq(1) th:eq(1)', api.table().footer()).html(total_usd);
        },
        initComplete : function(settings, json){
            var table_obj = this.api();
            var requestor = [];
            if(typeof json.data.length != 'undefined' && json.data.length > 0){
                requestor = [...new Set(json.data.map(element => ({'ALIAS':element.PREQ_NAME_SAP,'NAME':element.PR_REQ_NAME})).filter(x => x !== null))];
                requestor = [...new Map(requestor.map((item) => [item["NAME"], item]).filter(x => x[0] !== null && x[0] !== '-')).values()];
            }
            var select = $(`<div class="content-filter col-3 mb-2"><label>Filter By Requestor Name</label><div><select class="form-control select2 filter-select-1 mr-3" data-filter="0"><option value="">Pilih Data Disini</option></select></div></div>`)
            .appendTo( $('.abs-search') )
            .on('select2:select', function (e) {
                var value = e.params.data.id;
                var val = $.fn.dataTable.util.escapeRegex(
                    value
                );
                if(val){
                  data_filter_menu['PR_REQ_NAME'] = val
                }
                const qs = Object.keys(data_filter_menu)
                .map(key => `${key}=${data_filter_menu[key]}`)
                .join('&');
                if(qs)
                  table_obj.ajax.url(`?${qs}`).load();

            }).on("select2:unselecting", function(e) {
                try {
                  delete data_filter_menu['PR_REQ_NAME'];
                  const qs = Object.keys(data_filter_menu)
                  .map(key => `${key}=${data_filter_menu[key]}`)
                  .join('&');
                  if(qs)
                    table_obj.ajax.url(`?${qs}`).load();
                  else
                    table_obj.ajax.url('').load();

                } catch(error){}
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

            $(requestor).each( function ( d, j ) {
                $(`.filter-select-1`).append( '<option value="'+j.ALIAS+'">'+j.NAME+'</option>' )
            });
            $('.select2').select2({
              placeholder: 'Choose Data',
              allowClear: true
            });
        }
    });

    function actionForm(type, type2){
        event.preventDefault();
        var table =  $('#approvalList').DataTable();
        var idArray = [];
        // Iterate over all checkboxes in the table
        table.$('input[type="checkbox"]').each(function(){
            // If checkbox doesn't exist in DOM
            if(this.checked){
                // Create a hidden element
                idArray.push(this.value);
            }
        });
        var idJoin = idArray.join(";");
        // console.log(idJoin);

        if(idArray.length > 0){
            swal({
                title: ""+type+"!",
                text: "Input a reason:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Input a reason"
            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "" && type == "Reject") {
                    swal.showInputError("Input your reason!!");
                    return false
                }
                else{
                    try {
                        $('.sa-confirm-button-container > .confirm').html('<i class="fa fa-spin fa-spinner"></i>');
                        $('.sa-confirm-button-container > .confirm').prop('disabled', true);
                    } catch(error){}

                    setTimeout(function(){
                        jQuery.ajax({
                            type:"POST",
                            url: "/finance/purchase-order/approval/submitApprovalPO",
                            data:
                            {
                                "approval_id": idJoin, //Pisahkan dengan ;
                                "status_approval": type2, //APPROVE or REJECT
                                "reason":inputValue,
                            },
                            success: function(data) {
                                swal.close();
                                setTimeout(function(){
                                    swal({
                                        title: type+": ",
                                        text: "Total Data: " + data.Total_Data +" Total Failed: " + data.Total_Failed +" Total Success: " + data.Total_Success,
                                        type: "success",
                                    }, function() {
                                        location.reload();
                                    });
                                },500)
                            },
                            error : function(xhr){
                                console.log(xhr);
                            }
                        });
                    }, 300)
                }
            });
        }
        else{
            swal("Warning", "Please select at least one data.", "warning");
        }
    }


    $(document).on("click", ".actionFormAcct", function(){
        event.preventDefault();
        var aksi = $(this).attr('data-action');
        var divisi = $(this).attr('data-division');
        var id=$(this).attr('data-id');
        var updateBy =  $('#updateBy').val();
        var url_asset="{{url('upload/business_partner')}}";
        var approval_level_previous = objRow[id].APPROVAL_LEVEL;

        if(aksi=="approve"){
            var status_approval="APPROVED";
            $('#modalApprove #bodyModalApprove').html('Loading data .. <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
            $.get("{{url('finance/purchase-requisition/modal-approve-detail')}}", {id : id}, function( data ) {
                $('#modalApprove #bodyModalApprove').html(data);

                    var JSONObject = JSON.parse(objJsonDetail[id]);
                    var tanggal = moment(objRow[id].INSERT_DATE).format("DD/MM/YYYY - HH:mm");
                    var no_form =objRow[id].UID;
                    var approval_level_previous = objRow[id].APPROVAL_LEVEL;
                    var type_form = $('#type_form').val();
                    $("#modalApprove #bodyModalApprove #employee_id").val(updateBy);
                    $("#modalApprove #bodyModalApprove #status_approval").val(status_approval);
                    $("#modalApprove #bodyModalApprove #modal_type_form").val(type_form);
                    $("#modalApprove #bodyModalApprove #approval_level_previous").val(approval_level_previous);

            });
            $('#modalApprove').modal('show');
        }else{
            var status_approval="REJECTED";
            swal({
                title: ""+aksi+"!",
                text: "Input a reason:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Input a reason"
            },
            function(inputValue){
                if (inputValue === false) return false;

                if (inputValue === "" && aksi == "reject") {
                    swal.showInputError("Input your reason!!");
                    return false
                }
                else{
                    jQuery.ajax({
                        type:"POST",
                        url: "/finance/purchase-requisition/approval/save-with-form-data",
                        data:
                        {
                            "form_number": id, //Pisahkan dengan ;
                            "employee_id": updateBy, //emp id yg melakukan approve
                            "status_approval": status_approval, //APPROVE or REJECT
                            "approval_level_previous" : approval_level_previous,
                            "type_form": $('#type_form').val(), //type_form
                            "reason":inputValue,
                            "aksi" : aksi
                        },
                        success: function(data) {
                            swal.close();
                            if (data.code==200) {
                                if (data.message) {
                                    Swal.fire({
                                    icon: 'success',
                                    title: 'Request Purchase Requisition Rejected',
                                    text: 'Request '+id+' has been rejected',
                                    }).then((result) =>{

                                    location.reload();
                                    });

                                }
                            } else {
                                Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message,
                                });

                            }

                        },
                        error: function(err) {
                            swal.close();
                            //console.log(err);
                            Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text : err && err.responseJSON && err.responseJSON.message
                            });

                        }
                    });

                }
            });
        }
    });

    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }
    // Handle click on "Select all" control
    $('#approvalList-select-all').on('click', function(){
        // Get all rows with search applied
        var table =  $('#approvalList').DataTable();
        var rows = table.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Handle click on checkbox to set state of "Select all" control
    $('#approvalList tbody').on('change', 'input[type="checkbox"]', function(){
        // If checkbox is not checked
        if(!this.checked){
        var el = $('#approvalList-select-all').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
        }
        }
    });

</script>
@endsection
