@extends('layouts.default')
@section('title', 'Approval Request Resevation')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css"> -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}">
<style>
/*.table thead th {
    font-size: 12px;
    color: #001737;
}
.table td {
    font-size: 10px;
}*/
.accordion .card .card-body i {
    font-size: 0.825rem;
}
div.dataTables_wrapper div.dataTables_processing {
    top: 0 !important;
    border: 0;
    border-width: 0;
}
.red{
    color:red !important;
}
.button-wrapper-export{
  margin-top: 0px;
}
.dataTables_wrapper {
    position: relative;
}

.truncated-wrapper > p{
    margin: 0 !important;
}
.truncated > * {
    display: none;
}
.truncated > p:first-child {
    display: block;
    cursor: pointer;
}
.truncated > p:first-child::after {
    content: "\00a0  [More…]";
    color: #216ed3;
}
.dataTables_wrapper {
    position: relative;
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
                <li class="breadcrumb-item"><a href="#">Add Reservation</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Approval</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">Approval Request Reservation</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Request For Date</label>
										<div class="col-sm-8">
											<div class="input-group">
                                              <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ $data['request_date_from'] }}">
                                              <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                              <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ $data['request_date_to'] }}">
                                          </div>
                    					</div>
                                        <div class="col-sm-2 mt-1">
                                            <a href="{{url('finance/add-reservation/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                        </div>
									</div>
								</div>
							</div>
                        </form>
                        <br />
						<table class="table table-bordered datatable" id="approvalList" style="white-space: nowrap;width: 100%">
							<thead>
            				    <tr>
                                    <th style="width:20px;"><input type="checkbox" name="select_all" id="approvalList-select-all"></th>
            						<th class="exportable" style="min-width:90px;">Form No</th>
                                    <th class="exportable" style="min-width:90px;">Status</th>
                                    <th class="exportable" style="min-width:90px;">Request For Date</th>
                                    <th class="exportable" style="min-width:90px;">Approval Name</th>
                                    <th class="exportable" style="min-width:90px;">Approval Date</th>
                                    <th class="exportable" style="min-width:90px;">Grand Total</th>
                                    <th class="exportable" style="min-width:90px;">Movement Type</th>
                                    <th class="exportable" style="min-width:90px;">Receiving SLOC / Cost Center</th>
            				    </tr>
							</thead>
            			</table>
                        <div class="form-group" id="btnRejectBatch">
                            <button type="button" class="btn btn-danger" id="rejectForm" onClick="actionForm('Reject', 'REJECTED')">Reject</button>
                            <button type="button" class="btn btn-primary" id="approveForm" onClick="actionForm('Approve','APPROVED')">Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFile"  role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Request Reservation Detail </h5>
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

<div class="modal fade" id="modalApprove" role="dialog" aria-labelledby="modalApproveLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalApproveLabel">Approve Request Material Master</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalApprove">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalHistory" tabindex="-1" role="dialog" aria-labelledby="modalHistoryLabel" style="display: none;" aria-hidden="true">
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
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">
<input type="hidden" name="deptid" id="deptid" class="form-control" value="{{$data['department_id']}}">
<input type="hidden" name="midjobid" id="midjobid" class="form-control" value="{{$data['midjob_id']}}">
<input type="hidden" name="costcenter" id="costcenter" class="form-control" value="{{$data['costcenter']}}">
<input type="hidden" name="type_form" id="type_form" class="form-control" value="{{$data['form_code']}}">

@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>
<script>
    var data_filter_menu = {};
    $(document).ready(function(){
        $(":checkbox").attr("autocomplete", "off");
        var objJsonDetail = {}, objRow = {};

        function htmlDecode(input){
          var e = document.createElement('textarea');
          e.innerHTML = input;
          // handle case of empty input
          return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        }
        
        $(".datepicker2").datepicker();

        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        var costcenter= $("#costcenter").val();

        var current_approval_level = 0;
        var table = $('#approvalList').DataTable({
            "responsive": true,
            "dom": '<"abs-search row mb-2" <"button-export-wrapper col-9 align-items-center"B>>rtip',
            "buttons": {
                buttons: 
                [{
                  extend: 'excelHtml5',                
                  className : 'mb-2 mt-4 btn btn-primary',
                  text: '<i class="mdi mdi-export"></i>&nbsp;Export Excel',
                  title: '',
                  filename : 'AYANA Intranet - Reservation Approval Export',
                  exportOptions: {
                    // columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    orthogonal: 'export-excel',
                    columns: 'th.exportable'
                  },
                  action: function(e, dt, button, config) {
                    var data_approval = [];
                    $btn_scope = this;

                    try {
                      var table = $('#approvalList').DataTable()
                      data_approval = table.rows().data();
                    } catch(error){}

                    if(data_approval.length <= 0) {
                      Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Data is empty, nothing to export',
                      })
                      return false;
                    } else {
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call($btn_scope, e, dt, button, config);
                    }
                  },
                }]
            },
            "ajax": {
                "type" : "POST",
                "url" : "/finance/add-reservation/approval/getData",
                "dataSrc": "data",
                "data" : function(d){
                    d.employeeId = updateBy;
                    d.deptId = deptid;
                    d.midjobId = midjobid;
                    d.costcenter = costcenter;
                    d.filter = "";
                    d.value = "";
                    d.status = status;
                    d.insert_date_from = request_date_from;
                    d.insert_date_to = request_date_to;
                    d.REQUESTOR_NAME = $('.filter-select-1').val();
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
                "zeroRecords": "Sorry, your query doesn't match any data",
                "processing": ''
            },
            "paging": true,
            "autoWidth": false,
            'info':true,
            "fixedHeader": false,
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "searching":false,
            'lengthChange':false,
            'columnDefs': [
                {
                    "targets": 2, // your case first column
                    "className": "text-center",
               },
               {
                    "targets": 6,
                    "className": "text-center",
               },
               {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                    }
               }
             ],
            "columns": [
                { 
                  "data": "UID",
                  "render": function (id, type, full, meta)
                  {
                    current_approval_level=parseInt(full.APPROVAL_LEVEL)+1;
                    return '<input type="checkbox" name="id[]" value="'+id+'#'+full.APPROVAL_LEVEL+'"></input>'; // tampilkan checkbox
                  }
                },
                {
                    "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    },   className: 'text-left'
                },
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
            "order": [[ 0, "" ]],
            initComplete : function(settings, json){
                var table_obj = this.api();
                var requestor = [];
                if(typeof json.data.length != 'undefined' && json.data.length > 0){
                    requestor = [...new Set(json.data.map(element => ({'ALIAS':element.ALIAS,'NAME':element.NAME})).filter(x => x !== null))];
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
                      data_filter_menu['REQUESTOR_NAME'] = val
                    }
                    const qs = Object.keys(data_filter_menu)
                    .map(key => `${key}=${data_filter_menu[key]}`)
                    .join('&');
                    if(qs)
                      table_obj.ajax.reload();

                }).on("select2:unselecting", function(e) {
                    try {
                      delete data_filter_menu['PR_REQ_NAME'];
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

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };


        $(".select-decorated-detail").select2({
            placeholder: "Select an option",
        });

        $(document).on('select2:select', 'select[name="rsvReceivingPlant"], select[name="rsvOriginPlant[]"]', function(e){
            $('.btn-submit').prop('disabled', true);
            if(e.target.name == 'rsvReceivingPlant'){
                $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', false);
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').select2('destroy').html('<option value="" selected disabled></option>');
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', true);
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').select2({
                    placeholder: "Select an option",
                }).on('select2:select', function(e){
                    try {
                        var text = e.params.data.text || '';
                        $(e.target).parents('#rsvPlantSlocContainer').find('#receiving_sloc_desc').val(text);
                    } catch(error){}
                }).on('select2:unselecting', function(){
                    try {
                        $(e.target).parents('#rsvPlantSlocContainer').find('#receiving_sloc_desc').val('');
                    } catch(error){}
                });
                $.ajax({
                    url: "/finance/add-reservation/request",
                    type: "GET",
                    data : {'type': 'sloc', 'plant_code': e.params.data.id },
                    dataType: 'json',
                    success : function(response){
                        var newOption = [];
                        if(response.hasOwnProperty('sloc') && response.sloc.length){
                            $.each(response.sloc, function(index, data){
                              newOption[index] = new Option(`${data.STORAGE_LOCATION} - ${data.STORAGE_LOCATION_DESC}`, data.STORAGE_LOCATION, false, false);
                            });
                        }

                        setTimeout(function(){
                            $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', true);
                            $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', false);
                            $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').append(newOption).trigger('change');
                        },400)
                    },
                    error : function(xhr){
                        $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', true);
                        $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', false);
                        $.toast({
                          text : "Oops.. Something went wrong when trying to load data, please try again in a moment",
                          hideAfter : 4000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        });
                        console.log("EXCEPTION OCCURED IN RESERVATION REQUEST");
                    },
                    complete : function(){
                        $('.btn-submit').prop('disabled', false);
                    }

                });
            } else {
                $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', false);
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');

                $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2('destroy').html('<option value="" selected disabled></option>');
                $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', true);

                try {
                    if($(e.target).parents('#modalDetailAjax').length > 0){
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                            placeholder: "Select an option",
                            dropdownParent : $('#modalDetailAjax')
                        });
                    } else {
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                            placeholder: "Select an option",
                        });
                    }
                } catch(e){
                    $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                        placeholder: "Select an option",
                    });
                }

                $.ajax({
                    url: "/finance/add-reservation/request",
                    type: "GET",
                    data : {'type': 'sloc', 'plant_code': e.params.data.id },
                    dataType: 'json',
                    success : function(response){
                        var newOption = [];
                        if(response.hasOwnProperty('sloc') && response.sloc.length){
                            $.each(response.sloc, function(index, data){
                              newOption[index] = new Option(`${data.STORAGE_LOCATION} - ${data.STORAGE_LOCATION_DESC}`, data.STORAGE_LOCATION, false, false);
                            });
                        }

                        setTimeout(function(){
                            $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', true);
                            $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', false);
                            $(e.target).parents('tr').find('[name="rsvSLOC[]"]').append(newOption).trigger('change');
                        },400)
                    },
                    error : function(xhr){
                        $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', true);
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', false);
                        $.toast({
                          text : "Oops.. Something went wrong when trying to load data, please try again in a moment",
                          hideAfter : 4000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        });
                        console.log("EXCEPTION OCCURED IN RESERVATION REQUEST");
                    },
                    complete : function(){
                        $('.btn-submit').prop('disabled', false);
                    }

                });

            }
        });

        var table_obj = $(".select-decorated-material-detail").select2({
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                    return data.text;
                }
                return data.html;
            },
            templateSelection: function(data) {
                if(!data.id) {
                    return data.text;
                } else {
                    return data.text;
                }
            },
            // dropdownParent: $('#modalRequestReservation'),
            allowClear: true,
            placeholder: "Search Material ...",
            ajax: {
               url: "/finance/add-reservation/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                if(plant.length > 0)
                    plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                else 
                    plant = $('#Requestor_Plant_ID').val();

                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                  sloc: $_sloc
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                 var $_sloc = this.data.sloc
                 if($_sloc){
                     var $request = $.ajax(params);
                     $request.then(success);
                     $request.fail(failure);
                     return $request;
                 } else {
                    Swal.fire('Store Location Selection', 'Please select store location first to search materials available within it', 'warning');
                    return false;
                 }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            var value = e.params.data.id || 0;
            var text = e.params.data.text || 'Unknown';
            var unit = e.params.data.unit || 'Unknown';
            var lastPrice = e.params.data.last_price || 0;

            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
            $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(text);

            setTimeout(function(){
                var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                checkLastPrice(target);
            }, 600);

        }).on('select2:unselecting', function(e){
            $(e.target).parents('tr').find('#rsvMeasurement').val('');
            $(e.target).parents('tr').find('#rsvLastPrice').val('');
            calculateGrandTotal(e.target);
            $(this).data('state', 'unselected');
        });
    });
    // End Document Ready
    
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

    $(document).on('select2:select', '[name="rsvReceivingPlantSLOC"]', function(e){
        try {
            var $val = e.params.data.text;;
            $(e.target).parents('#rsvPlantSlocContainer').find('[name="rsvReceivingSLOCDesc"]').val($val)
        } catch(e){}
    });

    function checkLastPrice(target){
        // console.log(target, target.value, typeof target.value);
        try {
            $('.btn-submit').prop('disabled', true);
            $('.btn-add').prop('disabled', true);
            $('.btn-del').prop('disabled', true);

            var qty = target.value;
            if(qty && qty !== '0') {
                material = $(target).parents('tr').find('[name="rsvMaterials[]"]').val(),
                unit = $(target).parents('tr').find('[name="rsvMeasurement[]"]').val();
                var plant = $(target).parents('tr').find('[name="rsvOriginPlant[]"]');
                if(plant.length > 0)
                    plant = $(target).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                else 
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
                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="rsvLastPrice[]"]').val(last_price);
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                     console.log("Error in check last price");
                     $('.btn-submit').prop('disabled', false);
                     $('.btn-add').prop('disabled', false);
                     $('.btn-del').prop('disabled', false);
                   },
                   complete : function(){}
                });
            } else {
                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                $('.btn-submit').prop('disabled', false);
                $('.btn-add').prop('disabled', false);
                $('.btn-del').prop('disabled', false);

            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            $('.btn-submit').prop('disabled', false);
            $('.btn-add').prop('disabled', false);
            $('.btn-del').prop('disabled', false);
        }
    }

    function qtyInput(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        setTimeout(function(){
            checkLastPrice(elem);
        }, 600);
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

    // function getFormDetail(id){
    //     $('.loader-modal').show();
    //     $('#modalFile #bodyModalFile').html('');

    //     var action="approve";
    //     $.get("{{url('finance/add-reservation/modal-detail')}}", { id : id, action: action}, function( data ) {
    //         $('#modalFile #bodyModalFile').html(data);
    //         $('.loader-modal').hide();
    //     });
    // }
    $sloc_selected = [];
    $(document).on('select2:select', 'select[name="rsvSLOC[]"]', function(e){
        $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
        $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
        $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
        $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
    });

    function getFormDetail(id){
        $('.loader-modal').show();
        $('#modalFile #bodyModalFile').html('');
        var action="approve";
        $.get("{{url('finance/add-reservation/modal-detail')}}", { id : id, action: action}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $("#modalFile .select-decorated-detail").select2({
                placeholder: "Select an option",
                dropdownParent: $('#modalDetailAjax')
            });
            $("#modalFile .select-decorated-material-detail").select2({
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                        return data.text;
                    }
                    return data.html;
                },
                templateSelection: function(data) {
                    if(!data.id) {
                        return data.text;
                    } else {
                        return data.text;
                    }
                },
                dropdownParent: $('#modalDetailAjax'),
                allowClear: true,
                placeholder: "Search Material ...",
                ajax: {
                   url: "/finance/add-reservation/request",
                   type: "GET",
                   dataType: 'json',
                   delay: 600,
                   data: function (params) {
                    var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                    var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                    if(plant.length > 0)
                        plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                    else 
                        plant = $('#Requestor_Plant_ID').val();

                    return {
                      searchTerm: params.term, // search term
                      type: 'material',
                      plant: plant,
                      company_code: $('#Requestor_Company_Code').val(),
                      sloc: $_sloc
                    };
                   },
                   processResults: function (response) {
                     return {
                        results: response
                     };
                   },
                   cache: false,
                   transport: function (params, success, failure) {
                     var $_sloc = this.data.sloc
                     if($_sloc){
                         var $request = $.ajax(params);
                         $request.then(success);
                         $request.fail(failure);
                         return $request;
                     } else {
                        Swal.fire('Store Location Selection', 'Please select store location first to search materials available within it', 'warning');
                        return false;
                     }
                   }
                },
                minimumInputLength: 3
             }).on('select2:select', function(e){
                var value = e.params.data.id || 0;
                var text = e.params.data.text || 'Unknown';
                var unit = e.params.data.unit || 'Unknown';
                var lastPrice = e.params.data.last_price || 0;

                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(text);

                setTimeout(function(){
                    var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                    checkLastPrice(target);
                }, 600);

            }).on('select2:unselecting', function(e){
                $(e.target).parents('tr').find('#rsvMeasurement').val('');
                $(e.target).parents('tr').find('#rsvLastPrice').val('');
                calculateGrandTotal(e.target);
                $(this).data('state', 'unselected');
            });
            try {
                $('.datepicker-detail').datepicker({
                    dateFormat: 'dd MM yy'
                });
            } catch(error){}
            $('.loader-modal').hide();
        });
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

    function actionForm(type, type2){
        event.preventDefault();
        var updateBy =  $('#updateBy').val();
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
        //console.log(idJoin);
        if(idArray.length > 0){
            // console.log(type, type2);
            // return
            var typeCheck = type.toLowerCase().trim() || '';
            if(typeCheck == 'approve'){
                Swal.fire({
                  title: 'Approve Reservation',
                  text: "Are you sure want to approve selected reservation requests ?",
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
                        "type":'quick_approve'
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
                    return fetch( '/finance/add-reservation/approval/submitApprovalForm', options )
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
                    swal({
                        title: type+" :",
                        text: result.value.msg,
                        type: result.value.status,
                    }, function() {
                        if(result.value.status !== 'error')
                          location.reload();
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
                                url: "/finance/add-reservation/approval/submitApprovalForm",
                                data:
                                {
                                    "form_id": idJoin, //Pisahkan dengan ;
                                    "employe_id": updateBy, //emp id yg melakukan approve
                                    "status_approval": type2, //APPROVE or REJECT
                                    "type_form": $('#type_form').val(), //type_form
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
                                    console.log(xhr);
                                }
                            });
                        }, 300)
                    }
                });
            }
        }
        else{
            swal("Warning", "Please select at least one data.", "warning");
        }
    }

    function deleteBaris(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = 0;
        }

        if(rowCount>2){
            // table.deleteRow(rowCount -1);
            table.deleteRow(rowIndex);
        } else {
            Swal.fire('Item Removal', 'Cannot remove all data, the data that will be sent needs to be at least one. If you want to change item, edit it instead of remove. Reload data to recover item', 'warning');
        }

        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}

        try {
            calculateGrandTotal(table);
        } catch(error){}
    }

    function calculateGrandTotal(targetChild){
        try {
            var grand_total = 0;
            $(targetChild).parents('form').find('input:visible[name="rsvLastPrice[]"]').each(function(index, item){
                if(item.value){
                    let val = item.value.replace(/\,/g, '');
                    grand_total += isNaN(val) ? 0 : parseFloat(val);
                } else 
                    grand_total += 0;
            });
            $(targetChild).parents('form').find('#grand_total_value').val(grand_total);
            if(grand_total)
                grand_total = number_format(grand_total, 2, '.', ',');
            else 
                grand_total = 0;
            $(targetChild).parents('form').find('#grand_total').text(grand_total);
        } catch(error){
            console.log('error', error);
        }

        $('.btn-submit').prop('disabled', false);
        $('.btn-add').prop('disabled', false);
        $('.btn-del').prop('disabled', false);
    }
    
</script>
@endsection
