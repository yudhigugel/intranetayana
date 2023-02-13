@extends('layouts.default')

@section('title', 'Approval Purchase Requisition Market List')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/template/css/card-form-step/main.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.dataTables.min.css">
<link rel="stylesheet" href="/css/sweetalert.min.css">
@endsection
@section('styles')
<style>
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #a7afb7 !important;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color:  #a7afb7;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #a7afb7;
}

.red{
    color:red !Important;
}

.dataTables_wrapper{
    position: relative;
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

.table{
    color:#000 !important;
}
td.purpose {
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
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

.modal-obstruct:nth-of-type(odd) {
    z-index: 1054 !important;
}
.modal-backdrop.show:nth-of-type(even) {
    z-index: 1052 !important;
}
#table-marketlist_info{
    text-align: left;
}
.relative-td {
    position: relative;
}

.dataTables_wrapper .dataTables_processing {
  top: 10% !important;
  left: 42% !important;
}
.select-plain {
    padding: 0 !important;
    border: none !important;
    outline: none !important;
    font-size: 0.75rem !important;
    color: #6c7293 !important;
    max-width: 100px !important;
    text-align: left !important;
    margin: 0 -5px !important;
}
.table-container-h {
    overflow: auto;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition Market List</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Approval</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal mb-3" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <!-- <label class="col-sm-2 col-form-label">Request Date</label> -->
                                <div class="col-sm-2">
                                    <select class="form-control select-plain" name="filter_type" id="filter_type">
                                      <option value="REQUEST_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'REQUEST_DATE') selected default @endif>Request Date</option>
                                      <option value="DELIVERY_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'DELIVERY_DATE') selected default @endif>Delivery Date</option>
                                    </select>
                                    <div class="mt-1">
                                        <small class="text-muted">* Click to change filter</small>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="date_from" id="date_from" value="{{ $data['request_date_from'] }}" placeholder="Date From...">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="date_to" id="date_to" value="{{ $data['request_date_to'] }}" placeholder="Date To...">
                                    </div>
                                </div>
                                <div class="col-sm-2 mt-1 text-right">
                                    <a href="{{url('finance/purchase-requisition-marketlist/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                    <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered table-striped datatable requestList" id="requestList" style="white-space: nowrap;width: 100%">
                    <thead>
                      <tr>
                        <th style="width: 5% !important;"><input type="checkbox" name="select_all" id="requestList-select-all"></th>
                        <th style="width: 9% !important">Form Number</th>
                        <th style="width: 10% !important">Status PR</th>
                        <th style="width: 10% !important">Req. Date</th>
                        <th style="width: 8% !important">Deliv. Date</th>
                        <th style="width: 20% !important">Purpose</th>
                        <th style="width: 17% !important">Cost Center</th>
                        <th style="width: 12% !important">SLOC</th>
                        <th style="width: 10% !important">Grand Total</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th style="background-color: #ececec" class="text-center" style="width: 91.5% !important" colspan="8">Grand Total Active Requests</th>
                        <th style="background-color: #ececec" class="text-center">{{ 0 }}</th>
                      </tr>
                    </tfoot>
                </table>
                <div class="form-group" id="btnRejectBatch">
                    <button type="button" class="btn btn-danger" id="rejectForm" onClick="actionForm('Reject', 'REJECTED')">Reject</button>
                    <button type="button" class="btn btn-primary" id="approveForm" onClick="actionForm('Approve','APPROVED')">Approve</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="modal-detail-content">
			<div class="modal-header">
          <div class="d-flex">
    				<h5 class="modal-title mr-3" id="modalFileLabel">Purchase Requisition Market List Detail </h5>
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
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">
<input type="hidden" name="deptid" id="deptid" class="form-control" value="{{$data['department_id']}}">
<input type="hidden" name="midjobid" id="midjobid" class="form-control" value="{{$data['midjob_id']}}">
<input type="hidden" name="costcenter" id="costcenter" class="form-control" value="{{$data['costcenter']}}">
<input type="hidden" name="type_form" id="type_form" class="form-control" value="{{$data['form_code']}}">
@endsection
@section('scripts')

<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/ellipsis.js"></script>
<script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/js/card-form-step/main.js"></script>
<script src="/template/js/card-form-step/plugins.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript">

    // Change scrollbar to existing modal
    $(document).on('hidden.bs.modal', '.modal',
    () => $('.modal:visible').length && $(document.body).addClass('modal-open'));

    var is_error_template = false;
    var data_item = [];
    var data_filter_menu = {};
    var data_cc = [];
    var data_sloc = [];
    $('#marketlist-template').on('show.bs.modal', function(){
        try {
            $('#template-content-load').html('');
            $('#spinner-template').prop('hidden', false);
            $('#spinner-template-failed').prop('hidden', true);
        } catch(error){}
        var emp_id = $('input[name="Requestor_Employee_ID"]').val() || 0;
        $.ajax({
            url: '/finance/purchase-requisition-marketlist/request',
            method: 'GET',
            data: {'type':'recipe-template', 'employee_id': emp_id},
            dataType: 'json',
            success : function(response){
                if(response.hasOwnProperty('data') && response.data.length > 0){
                    is_error_template = false;
                    var element_to_add = '';
                    var col = 'col-4 col-md-4';
                    for(var loop=0;loop<response.data.length;loop++){
                        element_to_add += `<div class="switch-field ${col}">
                            <input type="radio" id="radio-${loop+1}" name="template-selection" value="${response.data[loop]}"/>
                            <label for="radio-${loop+1}">${response.data[loop]}</label>
                        </div>`;
                    }
                    $(element_to_add).appendTo('#template-content-load');
                }
            },
            error: function(xhr){
                $('#spinner-template-failed').prop('hidden', false);
                is_error_template = true;
            },
            complete: function(){
                $('#spinner-template').prop('hidden', true);
                setTimeout(function(){
                    if(!is_error_template)
                        $('.btn-next').prop('disabled', false);
                }, 500)
            }
        })
    });

    $(document).on('click', '.btn-next', function(){
        data_item = [];
        var check_element = document.getElementsByClassName('toRight').length;
        if(check_element > 0){
            $('.btn-finish').prop('disabled', true);
            try {
                var template_select = document.querySelector('input[name="template-selection"]:checked').value;
            } catch(error){
                var template_select = '';
            }
            $.ajax({
                url: '/finance/purchase-requisition-marketlist/request',
                method: 'GET',
                data: {'type':'recipe-template-item', 'template': template_select},
                dataType: 'json',
                success : function(response){
                    $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    setTimeout(function(){
                        $('#table-wrapper').prop('hidden', false);
                        try{
                          var table = $('#table-marketlist').DataTable({
                            "paging": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "aoColumnDefs": [
                                { "bSortable": false, "aTargets": [ 0 ] }, 
                                { "bSearchable": false, "aTargets": [ 0 ] }
                            ],
                            "buttons": [],
                            "data": response.data,
                            "order": [[ 0, "" ]],
                            "columns": [
                               { "data": "SAPMATERIALCODE",
                                 render: function(id, type, full, meta){
                                    return '<input type="checkbox" name="idItemTemplate[]" value="'+id+'">';
                                 },
                                 orderable: false,
                                 sortable: false,
                                 // width : "5%",
                                 visible : false
                               },
                               { "data": "MATERIALNAME",
                                 className: 'text-left',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="materialItemTemplate[]" value="'+id+'">';
                                 },
                                 width : "30%"
                               },
                               { "data": "UOM",
                                 className: 'text-center',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="unitItemTemplate[]" value="'+id+'">';
                                 },
                                 width : "7%"
                               },
                               { "data": null, 
                                 className : 'relative-td',
                                 "defaultContent": "<input type='text' value='0' oninput='qtyInput(this)' class='form-control' name='qtyItemTemplate[]' id='qtyItemTemplate' placeholder='Input Qty...'/><div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>",
                                 width : "15%"
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' readonly class='form-control' name='costItemTemplate[]' id='costItemTemplate' placeholder='Autofill...' value='0.00' />",
                                 width : "15%"
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' class='form-control' oninput='noteInput(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>",
                                 width : "28%"
                               },
                            ]
                          });

                        } catch(error){
                            Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                            console.log(error.message)
                        }
                    }, 500);
                },
                error: function(xhr){
                    $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    setTimeout(function(){
                        $('#table-wrapper').prop('hidden', false);
                        try{
                          var table = $('#table-marketlist').DataTable({
                            "paging": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "buttons": [],
                            "data": response.data,
                          });

                        } catch(error){
                            Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                            console.log(error.message)
                        }
                    }, 500);
                },
                complete: function(){
                    $('#spinner-template-item').prop('hidden', true);
                    $('.btn-finish').prop('disabled', false);
                }
            });
        }

    });

    function clear_form_elements(class_name) {
        jQuery("."+class_name).find(':input').each(function() {
            switch(this.type) {
                case 'password':
                case 'text':
                case 'textarea':
                case 'file':
                case 'select-one':
                case 'select-multiple':
                case 'date':
                case 'number':
                case 'tel':
                case 'email':
                    jQuery(this).val('');
                    break;
                case 'checkbox':
                case 'radio':
                    this.checked = false;
                    break;
            }
        });
    }

    $("#toggleVendor").change(function(){
        if(this.checked){
            $(".formVendor").show();
            $("#vendor_reason").attr('required','');
            $("#vendor_currency").attr('required','');
            $("#vendor_search").attr('required','');
            $('#vendor_currency').select2('destroy').select2();
        }else{
            $(".formVendor").hide();
            $("#vendor_reason").removeAttr('required');
            $("#vendor_currency").removeAttr('required');
            $("#vendor_search").removeAttr('required');
            clear_form_elements('formVendor');
        }

    });
    var objFile = {};var objJsonDetail = {}; var objRow = {};

    $('#modalRequest').on('show.bs.modal', function (e) {
        $("#modalRequest .select2").select2({
            dropdownParent: $('#modal-request-content'),
            placeholder: 'Select Data'
        });
    });

    /*****************************************************************************/
    /*****************************************************************************/
    /* FUNCTION DARI INTRANET BIZNET */
    /*****************************************************************************/
    /*****************************************************************************/
    $(document).ready( function () {
        $('#delivDate').datepicker({
            autoclose: true
        });

        $('.datepicker3').datepicker({
            dateFormat: 'dd MM yy',
            minDate: 0,
        });

        $(".datepicker2").datepicker();
        var min_date="{{date('Y-m-d',strtotime('today  -30 days'))}}";
        var values = min_date.split('-');
        var parsed_min_date = new Date(values[0], values[1]-1, values[2]);

        $('#datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            showWeek: true,
            changeYear: true,
            showButtonPanel: true,
            minDate : parsed_min_date
        });
        $('#datepicker').prop('disabled', false);

        var request_date_from =  $('#date_from').val();
        var request_date_to =  $('#date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        var costcenter= $("#costcenter").val();
        var table = $('#requestList').DataTable({
            "pageLength": 25,
            "lengthChange": true,
            "responsive": true,
            "searching":false,
            // "dom": '<"dt-buttons"Bfl>rtip',
            "dom": '<"abs-search row mb-2 align-items-end" <"button-export-wrapper col-6 align-items-center"Bfl>> <"table-wrapper table-container-h"rt> ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/purchase-requisition-marketlist/approval/getData",
                "dataSrc": function ( json ) {
                    data_cc = [...new Set(json.data.map(item => `${item.COSTCENTERID} - ${item.COSTCENTER}`))];
                    data_sloc = [...new Set(json.data.map(item => `${item.SLOC_ID} - ${item.SLOC}`))];
                    //Make your callback here.
                    if(json.hasOwnProperty('data') && json.data)
                       return json.data;
                    else
                       return [];
                },
                "data" : function(d){
                    d.employeeId = updateBy;
                    d.deptId = deptid;
                    d.midjobId = midjobid;
                    d.costcenter = costcenter;
                    d.filter = "";
                    d.value = "";
                    d.status = status;
                    d.date_from = request_date_from;
                    d.date_to = request_date_to;
                    d.filter_type = $('#filter_type').val();
                    d.REQUESTOR_NAME = $('.filter-select-1').val();
                    d.SHIP_TO_COST_CENTER = $('.filter-select-costcenter').val();
                    d.SHIP_TO_SLOC = $('.filter-select-sloc').val();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
                    Swal.fire({
                        title: "Oops..",
                        text: error,
                        icon: "error",
                        showConfirmButton: true
                    });
                }
            },
            "language": {
                "processing": ""
            },
            "paging": true,
            "autoWidth": false,
            'info':true,
            "processing": true,
            "serverSide": true,
            "scrollX": false,
            "columns": [
                { 
                  "data": "FORM_NUMBER",
                  "render": function (id, type, full, meta)
                  {
                    return '<input type="checkbox" name="id[]" value="'+id+'#'+full.APPROVAL_LEVEL+'"></input>'; // tampilkan checkbox
                  },
                  orderable: false,
                  sortable: false,
                  width: '5%'
                },
                { "data": "FORM_NUMBER",
                    "render": function (id, type, full, meta)
                    {
                        if(id){
                            return '<a href="#" class="text-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile">'+id+'</i></a>';
                        } else 
                            return '-';
                    },
                    width: '9%'
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id){
                        if(id=="APPROVED" || id=="REQUESTED" || id==""){
                            id="WAITING FOR APPROVAL";
                            return '<a href="javascript:void(0);" style="text-decoration: none; font-weight:bold;"">WAITING</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:green;font-weight:bold;">FINISHED</a>';
                        }else{
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:red;font-weight:bold;">'+id+'</a>';
                        }
                    },
                    width: '10%'
                },
                { "data": "REQ_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    },
                    width: '10%'
                },
                { "data": "DELIVERY_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY");
                    },
                    width: '8%',
                    className: 'text-center'
                },
                { "data": "PURPOSE",
                  width: '20%',
                  className: 'text-left'
                },
                { "data": "COSTCENTER",
                  width: '17%'
                },
                { "data": "SLOC",
                  "render": function (id, type, full, meta)
                  {
                        if(id){
                            return id;
                        } else 
                            return '-';
                  },
                  width: '14%'
                },
                { "data": "GRANDTOTAL",
                  "render": function (id, type, full, meta)
                    {
                        return number_format(id, 0, '.', ',');
                    },
                  width: '10%',
                  className: 'text-right'
                }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 1, "desc" ]],
            "fixedHeader": {
              header: true,
              footer:true
            },
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
                      table_obj.ajax.reload();

                }).on("select2:unselecting", function(e) {
                    try {
                      delete data_filter_menu['REQUESTOR_NAME'];
                      const qs = Object.keys(data_filter_menu)
                      .map(key => `${key}=${data_filter_menu[key]}`)
                      .join('&');
                      table_obj.ajax.reload();
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
                this.api().columns([6, 7]).every( function (i) {
                    var column = this;
                    var text = [{'name':'Cost Center', 'filter_name':'costcenter'}, {'name':'SLOC', 'filter_name':'sloc'}];
                    var paramName = ['SHIP_TO_COST_CENTER', 'SHIP_TO_SLOC'];
                    var col_length = 'col-2';
                    var select = $(`<div class="content-filter mb-2 ${col_length}"><label>Filter By ${text[text_order].name}</label><div><select style="width: 100%" class="form-control select2-filter-marketlist filter-select-${text[text_order].filter_name} mr-3" data-filter="${text_order}"><option value="">Choose Data</option></select></div></div>`)
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
                              table_obj.ajax.url(`approval/getData?${qs}`).load();

                        }).on("select2:unselecting", function(e) {
                            var target = $(e.target).data('filter');
                            try {
                              delete data_filter_menu[paramName[target]]
                              const qs = Object.keys(data_filter_menu)
                              .map(key => `${key}=${data_filter_menu[key]}`)
                              .join('&');
                              if(qs)
                                table_obj.ajax.url(`approval/getData?${qs}`).load();
                              else
                                table_obj.ajax.url(`approval/getData`).load();

                            } catch(error){
                              console.log(error);
                            }
                            $(this).data('state', 'unselected');
                        }).on("select2:open", function(e) {
                            try {
                              if ($(this).data('state') === 'unselected') {
                                  $(this).removeData('state'); 

                                  var self = $(this).find('.select2-filter-marketlist')[0];
                                  setTimeout(function() {
                                      $(self).select2('close');
                                  }, 0);
                              }
                            } catch(error){}   
                        });
                    if(i == 6){
                      $(data_cc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }
                    else if(i == 7){
                      $(data_sloc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }

                    text_order++;
                });
                $('.select2-filter-marketlist').select2({
                  placeholder: 'Choose data',
                  allowClear: true
                });
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
  
              // Total over all pages USD
              var total_all = 0;
              api.column( 8 )
              .data()
              .each( function (value, index) {
                total_all += intVal(value);
              });

              // Update footer
              total_all = number_format(total_all, 0, 0 , ',');
              $('tr:eq(0) th:eq(1)', api.table().footer()).html(total_all);
            }
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };
    });

    function getFormDetail(id){
        $('.loader-modal').show();
        $('#modalFile #bodyModalFile').html('');
        var action="approve";
        $.get("{{url('finance/purchase-requisition-marketlist/modal-detail')}}", { id : id, action: action}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $('.loader-modal').hide();
            $("#modalFile .select2").select2({
                dropdownParent: $('#modal-detail-content'),
                placeholder: 'Select Data'
            });
        });
    }

    function getHistoryDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/purchase-requisition/getHistoryApproval',
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
                            if(changeNull(listSIA[i].APPROVAL_DATE) == ""){
                                var APPROVAL_DATE = "";
                            }
                            else{
                                var APPROVAL_DATE = moment(listSIA[i].APPROVAL_DATE).format("DD/MM/YYYY - HH:mm");
                            }
                            tr += '<tr>';
                            tr += '<td>'+changeNull(listSIA[i].LEVEL_APPROVAL)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].TYPE_DESC)+'</td>';
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
                $('#requestHistoryList').DataTable();
            }
        });
    }
    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }

    $("#formRequest").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        try {
            var zero_value = false;
            $('input[name="marketlistMaterialQty[]"]', this).each(function(index, elem){
                try {
                    if(elem.value === '0'){
                        zero_value = true;
                        return false;
                    }
                } catch(error){}
            });
            if(zero_value){
                Swal.fire('Submit Purchase Requisition Marketlist', "Please make sure all quantity inserted is more than zero (0), there is no such calculation for zero amount of request", 'warning');
                return false;
            }
        } catch(error){}

        var form = this;
        var url_post=$(this).attr('data-url-post');
        var loader=$(this).attr('data-loader-file');
        var form = new FormData(form);

        $.ajax({
            type: "POST",
            url: url_post,
            data: form,
            // cache:false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            Swal.fire({
                title: "Loading...",
                text: "Please wait!",
                imageUrl: loader,
                allowOutsideClick: false,
                imageSize: '150x150',
                showConfirmButton: false
            });
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || "Success insert data",
                }).then((result) =>{
                    setTimeout(function(){
                        window.location.href="/finance/purchase-requisition-marketlist/request";
                    }, 500);
                });
            },
            error : function(error){
                let message = 'Something went wrong, ' + error.responseJSON.message || 'Something went wrong when trying to save the document, make sure to fill all the data required, check your connection and try again';
                Swal.fire('Save PR Marketlist Form', message, 'error');
            },
        });
        return false;
    });

    var delayInAjaxCall = (function(){
          var timer = 0;
          return function(callback, milliseconds){
          clearTimeout (timer);
          timer = setTimeout(callback, milliseconds);
       };
    })();

    function qtyInput(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '',
        material_name = item.hasOwnProperty('MATERIALNAME') ? item.MATERIALNAME : '',
        material_uom = item.hasOwnProperty('UOM') ? item.UOM : '';

        if(parseFloat(elem.value) > 0){
            try {
                $(elem.closest('tr')).addClass('bg-success');
            } catch(error){}
            delayInAjaxCall(function(){
                checkLastPrice(elem, material_code, material_name, material_uom);
            }, 1000);
        } else {
            try {
                $(elem.closest('tr')).removeClass('bg-success');
            } catch(error){}
            data_item = data_item.filter(x => x.mtnumber != material_code);
        }
    }

    function setNote(material_code='', notes=''){
        data_item = data_item.map(function(x) {
            try {
                if(x.mtnumber == material_code){
                    x.mtnote = notes;
                }
            } catch(error){}
            return x;
        });
    }

    function noteInput(elem){
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '';
        var notes = $(elem.closest('tr')).find('[name="noteItemTemplate[]"]').val();
        delayInAjaxCall(function(){
            setNote(material_code, notes);
        }, 1000 );

    }

    function checkLastPrice(target, material_number='', material_name='', material_unit='', obj={}){
        // console.log(target, target.value, typeof target.value);
        try {
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', true);
            $('.btn-finish').prop('disabled', true);
            var qty = target.value;
            if(qty && qty !== '0') {
                var material = '000000000'+material_number,
                unit = material_unit;
                plant = $('#Requestor_Plant_ID').val();

                $(target).parent().find('.spinner-qty').prop('hidden', false);
                $.ajax({
                   url: "/finance/add-reservation/request",
                   type: "GET",
                   dataType: 'json',
                   delay: 600,
                   data: {
                    'type': 'material_last_price', 
                    'material': material, 
                    'unit': unit, 
                    'qty': qty,
                    'plant': plant
                   },
                   success : function(resp){
                     if(resp.hasOwnProperty('last_price')){
                        try {
                            var notes = $(target).parents('tr').find('[name="noteItemTemplate[]"]').val();
                            var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice': resp.last_price, 'mtlastpriceplain': resp.last_price_plain, 'mtnote': notes, 'mtqty': qty};
                            data_item = data_item.filter(x => x.mtnumber != material_number);
                            data_item.push(obj_to_add);
                        } catch(error){}

                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="costItemTemplate[]"]').val(last_price);
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                     console.log("Error in check last price");
                     $('.btn-finish').prop('disabled', false);
                     // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                   },
                   complete : function(){
                    setTimeout(function(){
                        // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                        $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
                    }, 100);
                   }
                });
            } else {
                $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                $('.btn-finish').prop('disabled', false);
                $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
            $('.btn-finish').prop('disabled', false);
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);

        }
    }

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

    function calculateGrandTotal(targetChild){
        $('.btn-finish').prop('disabled', false);
        $('.btn-back').prop('disabled', false);
    }

    $('.btn-finish').on('click', function(){
        Swal.fire({
          title: 'Are you sure?',
          text: "Please make sure that you've added material based on your need and then confirm by tapping Yes",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          allowOutsideClick: false
        }).then((result) => {
          if (result.isConfirmed) {
            try{
                var table_data = '';
                if(data_item.length){
                    var grandTotal = 0;
                    $('#marketlist-template').modal('hide');
                    setTimeout(function(){
                        var index = 0;
                        for(var loop=1;loop <= data_item.length;loop++){
                            if(loop < 10){
                                var item_order = "000"+(loop * 10);
                            }
                            else if(loop < 100 && loop > 9){
                                var item_order = "00"+(loop * 10);
                            }
                            else if(loop < 1000 && loop > 90){
                                var item_order = "0"+(loop * 10);
                            }
                            else if(loop < 10000 && loop > 900){
                                var item_order = "0"+(loop * 10);
                            }

                            table_data += `<tr>
                                    <td class="text-center">${item_order}
                                        <input type="hidden" name="marketlistItemOrder[]" value="${item_order}">
                                    </td>
                                    <td class="text-left">${data_item[index].mtname}
                                        <input type="hidden" name="marketlistMaterialName[]" value="${data_item[index].mtname}">
                                    </td>
                                    <td class="text-center">${data_item[index].mtunit}
                                        <input type="hidden" name="marketlistMaterialUnit[]" value="${data_item[index].mtunit}">
                                    </td>
                                    <td class="text-right">${data_item[index].mtqty}
                                        <input type="hidden" name="marketlistMaterialQty[]" value="${data_item[index].mtqty}">
                                    </td>
                                    <td class="text-left">${data_item[index].mtnote}
                                        <input type="hidden" name="marketlistMaterialNote[]" value="${data_item[index].mtnote}">
                                    </td>
                                    <td class="text-right">${data_item[index].mtlastprice}
                                        <input type="hidden" name="marketlistMaterialLastPrice[]" value="${data_item[index].mtlastpriceplain}">
                                        <input type="hidden" name="marketlistMaterialLastPriceFormatted[]" value="${data_item[index].mtlastprice}">
                                    </td>
                                </tr>`;
                            grandTotal += parseFloat(data_item[index].mtlastpriceplain);
                            index++;
                        }
                        if(table_data){
                            $('#reqForm > tbody').html(table_data);
                            var grandTotalFormat =  number_format(grandTotal, 2, '.', ',');
                            // console.log(grandTotalFormat, grandTotal);
                            $('#grandTotal').val(grandTotalFormat);
                        }
                    }, 500);

                }
            } catch(error){
                Swal.fire('Assign Item Error', 'Something went wrong while adding data to the request item list, please try again in a moment', 'error');
                console.log(error.message)
            }
          }
        });
    });

    function deleteBaris(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = 0;
        }

        if(rowCount>2){
            if(rowIndex == 1){
                Swal.fire('First Item Removal', 'Cannot remove first item. If you want to change item, edit it instead of remove', 'warning');
            } else {
                // table.deleteRow(rowCount -1);
                table.deleteRow(rowIndex);
            }
        } else {
            Swal.fire('Item Removal', 'Cannot remove first item, the data that will be sent needs to be at least one. If you want to change item, edit it instead of remove', 'warning');
        }

        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}

        try {
            calculateGrandTotal(table);
        } catch(error){}
    }

    // Handle click on "Select all" control
    $('#requestList-select-all').on('click', function(){
        // Get all rows with search applied
        var table =  $('#requestList').DataTable();
        var rows = table.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    function actionForm(type, type2){
      event.preventDefault();
      var updateBy =  $('#updateBy').val();
      var table =  $('#requestList').DataTable();
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
      if(idArray.length > 0){
          // console.log(type, type2);
          // return
          var typeCheck = type.toLowerCase().trim() || '';
          if(typeCheck == 'approve'){
              Swal.fire({
                title: 'Approve PR Marketlist',
                text: "Are you sure want to approve selected PR Marketlist requests ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve!',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                  const params = {
                      "form_id": idJoin, //Pisahkan dengan ;
                      "employe_id": updateBy, //emp id yg melakukan approve
                      "status_approval": type2, //APPROVE or REJECT
                      "type_form": $('#type_form').val(), //type_form
                      "reason":'',
                      "type": 'quick_approve'
                  };
                  const options = {
                      method: 'POST',
                      credentials: "same-origin",
                      body: JSON.stringify( params ),
                      headers: {
                          "Content-Type": "application/json",
                          "Accept": "application/json",
                          "X-Requested-With": "XMLHttpRequest",
                          "X-CSRF-Token": $('input[name="_token"]').val()
                      }
                  };
                  return fetch( '/finance/purchase-requisition-marketlist/approval/submitApprovalFormBatch', options )
                  .then(response => response.json())
                  .then((res)=>{
                      var json = res;
                      if(json.hasOwnProperty('exception') && json.exception){
                          throw new Error(json.message);
                      } else {
                          return json;
                      }                        
                  }).then(json => {
                      return {'status':'success', 'msg': "Total Data: " + json.Total_Data +" Total Failed: " + json.Total_Failed +" Total Success: " + json.Total_Success}
                  })
                  .catch(error => {
                      return {'status':'error', 'msg': error.message};
                  });
                },
                allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire({
                      title: type+" :",
                      text: result.value.msg,
                      icon: result.value.status
                  }).then((result_data) => {
                      // console.log(result);
                      if(result.value.status !== 'error'){
                          location.reload();
                      }
                  });
                }
              });
          } 
          else{
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
                              url: "/finance/purchase-requisition-marketlist/approval/submitApprovalFormBatch",
                              data:
                              {
                                  "form_id": idJoin, //Pisahkan dengan ;
                                  "employe_id": updateBy, //emp id yg melakukan approve
                                  "status_approval": type2, //APPROVE or REJECT
                                  "type_form": $('#type_form').val(), //type_form
                                  "type": 'quick_approve',
                                  "reason":inputValue
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
                                swal.close();
                                setTimeout(function(){
                                  Swal.fire("PR Marketlist Error", "Something went wrong when trying to reject PR, please try again in a moment.", "error");
                                }, 500)
                              }
                          });
                      }, 300)
                  }
              });
          }
      }
      else{
          Swal.fire("Warning", "Please select at least one data.", "warning");
      }
    }
</script>
@endsection
