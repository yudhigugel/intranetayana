@extends('layouts.default')
@section('title', 'Approval Request Business Partner')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css"> -->
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
div.dataTables_wrapper {
    position: relative;
}

div.dataTables_wrapper div.dataTables_processing {
    top: -10%;
    border: 0;
    border-width: 0;
}
.button-wrapper-export{
    margin-top: 0px;
}
.dataTables_info{
    float: left;
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
</style>

@endsection

@section('content')
<div class="row" id="app">
    <div class="col-lg-12 col-xl-12 grid-margin stretch-card flex-column d-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Forms</a></li>
                <li class="breadcrumb-item"><a href="#">Add Business Partner</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Approval</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">Approval Request Business Partner</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Request Date</label>
										<div class="col-sm-8">
											<div class="input-group">
                                                <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ $data['request_date_from'] }}">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ $data['request_date_to'] }}">
                                            </div>
										</div>
                                        <div class="col-sm-2 mt-1">
                                            <a href="{{url('finance/add-business-partner/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                        </div>
									</div>
								</div>
							</div>
                            {{--<div class="row pull-right">
							</div>--}}
                        </form>
                        <br />

						<table class="table table-bordered datatable approvalList123" id="approvalList" style="white-space: nowrap;">
							<thead>
								<tr>
                                    <th style="width:90px;"><input type="checkbox" name="select_all" id="approvalList-select-all"></th>
									{{--<th style="min-width:90px;">Form No</th>
									<th style="min-width:90px;">Status</th>
									<th style="min-width:90px;">Requestor</th>
                                    <th style="min-width:90px;">Vendor Name</th>
									<th style="min-width:90px;">Request Date</th>
									<th style="min-width:90px;">Department</th>
									<th style="min-width:90px;">Division</th>--}}

                                    <th class="exportable" style="min-width:90px;">Form No</th>
                                    <th class="exportable" style="min-width:90px;">Requestor Name</th>

                                    <th class="exportable" style="display: none;">Company Code</th>
                                    <th class="exportable" style="display: none;">Business Partner Number</th>
                                    <th class="exportable" style="display: none;">Vendor Type</th>

                                    <th class="exportable" >Vendor Name</th>
                                    <th class="exportable" style="display: none;">Vendor Address</th>
                                    <th class="exportable" style="display: none;">Vendor Building</th>
                                    <th class="exportable" style="display: none;">Vendor District</th>
                                    <th class="exportable" style="display: none;">Vendor City</th>
                                    <th class="exportable" style="display: none;">Vendor Postal Code</th>
                                    <th class="exportable" style="display: none;">Vendor Province</th>
                                    <th class="exportable" style="display: none;">Vendor Phone Number</th>
                                    <th class="exportable" style="display: none;">Vendor Email</th>

                                    <th class="exportable" style="display: none;">PIC Name</th>              
                                    <th class="exportable" style="display: none;">PIC Phone Number</th>
                                    <th class="exportable" style="display: none;">PIC Email</th>

                                    <th class="exportable" style="display: none;">NPWP</th>
                                    <th class="exportable" style="display: none;">PKP</th>

                                    <th class="exportable" style="display: none;">Bank Name</th>
                                    <th class="exportable" style="display: none;">Bank Account Number</th>
                                    <th class="exportable" style="display: none;">Swift Code</th>
                                    <th class="exportable" style="display: none;">Account Holder Name</th>

                                    <th class="exportable" style="min-width:90px;">Status</th>
                                    <th class="exportable" style="min-width:90px;">Request Date</th>
                                    {{--<th class="exportable" style="min-width:90px;">Approval Name</th>--}}
                                    {{--<th class="exportable" style="min-width:90px;">Approval Date</th>--}}
                                    
                                    <th style="min-width:90px;">Plant Name</th>
                                    <th style="min-width:90px;">Department</th>
                                    {{--<th style="min-width:90px;">Reason</th>--}}

                                    <th class="exportable" style="display: none;">Request For Plant</th>
                                    <th class="exportable" style="display: none;">Cross Plant Request</th>
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

<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileLabel">Request Business Partner Detail</h5>
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
				<h5 class="modal-title" id="modalApproveLabel">Approve Request Business Partner</h5>
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
                <table class="table table-bordered datatable requestHistoryList" id="requestHistoryList" >
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

<div class="modal fade" id="modalFulfill" tabindex="-1" role="dialog" aria-labelledby="modalFulfillLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFulfillLabel">Form Fulfill Zoho</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetVendorList()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodyModalFulfill">
                {{--  <div class="embed-responsive embed-responsive-16by9">  --}}
                <iframe class="embed-responsive-item" style="width:100%;height:450px;position:relative" scrolling="yes" id="iframeFulfill"></iframe>
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
    var objFile = {};var objJsonDetail = {}; var objRow = {};
    $(document).ready(function(){
        $(".datepicker2").datepicker();
        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        var costcenter= $("#costcenter").val();
        // var spv =  $('#spv').val(); //belum ada SPV
        var spv ='';
        var current_approval_level = 0;

        $('#approvalList').DataTable().clear().destroy();
        var tables =  $('#approvalList').DataTable({
            "dom": 'l<"button-export-wrapper"B>frtip',
            "processing":true,
            "serverSide":true,
            "searching":false,
            "autoWidth":false,
            "pageLength":100,
            "scrollX": true,
            "buttons": {
                buttons: 
                [{
                  extend: 'excelHtml5',                
                  className : 'btn btn-sm btn-secondary',
                  text: '<i class="mdi mdi-export"></i>&nbsp;Export Excel',
                  // messageTop: function () {
                  //   try {
                  //     var date = $("#datepicker").datepicker("getDate");
                  //     date = $.datepicker.formatDate("dd-mm-yy", date);
                  //     return `Report Date : ${date}`;
                  //   } catch(error){}
                  // },
                  title: '',
                  filename : 'AYANA Intranet - Business Partner Approval Export',
                  exportOptions: {
                    // columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    orthogonal: 'export-excel',
                    columns: 'th.exportable'
                  },
                  action: function(e, dt, button, config) {
                    var data_revenue = [];
                    $btn_scope = this;

                    try {
                      var table = $('#approvalList').DataTable()
                      data_revenue = table.rows().data();
                    } catch(error){}

                    if(data_revenue.length > 0) {
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
                        "employeeId":updateBy,
                        "deptId":deptid,
                        "midjobId":midjobid,
                        "costcenter":costcenter,
                        "filter":"",
                        "value":"",
                        "status":status,
                        "insert_date_from":request_date_from,
                        "insert_date_to":request_date_to
                      }

                      $.ajax({
                        "url": "/finance/add-business-partner/approval/getData",
                        "type": "POST",
                        "data" : $data_sent
                      }).then(function (ajaxReturnedData) {
                        // console.log(ajaxReturnedData);
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
                  customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c', sheet).each(function(index, elem){
                      $(this).attr('s', '50');
                    });
                    $('row:first c', sheet).attr( 's', '2' );
                    $('row:not(:first) c', sheet).each(function(index,elem){
                      try {
                        var new_text = $(this).text().toUpperCase();
                        elem.childNodes[0].childNodes[0].innerHTML = new_text;
                        // console.log(new_text);
                      } catch(error){}
                    });
                    return false
                  }
                }]
            },
            "ajax":{
                "url": "/finance/add-business-partner/approval/getData",
                "type": "POST",
                "dataSrc": "data",
                "data" : {
                    "employeeId":updateBy,
                    "deptId":deptid,
                    "midjobId":midjobid,
                    "costcenter":costcenter,
                    "filter":"",
                    "value":"",
                    "status":status,
                    "insert_date_from":request_date_from,
                    "insert_date_to":request_date_to
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
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        current_approval_level=parseInt(full.APPROVAL_LEVEL)+1;
                        if(current_approval_level==1){ //approval level 1 accounting bisa edit semua, tidak menampilkan checkbox, instead menampilkan tombol
                            $("#btnRejectBatch").hide();
                            return '<button type="button" class="btn btn-danger actionFormAcct" data-id="'+id+'" data-division="accounting" data-action="reject" >Reject</button><button type="button" class="btn btn-primary actionFormAcct" data-id="'+id+'" data-division="accounting" data-action="approve">Approve</button>';
                        }else if(current_approval_level==3){ // kalau misal approval level 3 (IT) maka tidak menampilkan checkbox, instead menampilkan tombol
                            $("#btnRejectBatch").hide();
                            return '<button type="button" class="btn btn-danger actionFormAcct" data-id="'+id+'" data-division="it" data-action="reject" >Reject</button><button type="button" class="btn btn-primary actionFormAcct" data-id="'+id+'" data-division="it" data-action="approve">Approve</button>';
                        }else{
                            return '<input type="checkbox" name="id[]" value="'+id+'#'+full.APPROVAL_LEVEL+'"></input>'; // tampilkan checkbox
                        }

                    }
                },
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        // arrFile.push(full.DOCUMENT_FILE);
                        objJsonDetail[id] = full.JSON_ENCODE.replace(/&quot;/g,'"');
                        objRow[id] = full;
                        if(current_approval_level==1){
                            //jika approval level 1 (Accounting), maka modal form yang pakai inputan data master nya yang akan muncul
                            return '<a href="#" class="actionFormAcct" data-id="'+id+'" data-division="accounting" data-action="approve">'+id+'</a>';   
                        }else if(current_approval_level==3){
                            //jika approval level 3 (IT SAP), maka modal form yang pakai inputan data master nya yang akan muncul
                            return '<a href="#" class="actionFormAcct" data-id="'+id+'" data-division="it" data-action="approve">'+id+'</a>';

                        }else{
                            // jika approval yang lain , maka modal detail targent untuk yang beda
                            return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                        }


                    }
                },
                // { "data": "STATUS_APPROVAL",
                //     "render": function (id, type, full, meta)
                //     {
                //         if(id == null || id == "null"){ id = "REQUESTED"; }
                //         if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}
                //         return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';
                //     }
                // },
                // { "data": "REQUESTOR_NAME" },
                // { "data": "VENDOR_NAME" },
                // { "data": "INSERT_DATE" ,
                //     "render": function (id, type, full, meta)
                //     {
                //         return type === 'export-excel' ? moment(id).format("YYYY-MM-DD HH:mm") : moment(id).format("MM/DD/YYYY - HH:mm");
                //     }
                // },
                // { "data": "DEPARTMENT_NAME" },
                // { "data": "DIVISION_NAME" }
                { "data": "REQUESTOR_NAME", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        var data = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                        return data.Requestor_Name;

                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.company_code !== 'undefined'){
                                return $json_decode.company_code;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.bp_number !== 'undefined'){
                                return $json_decode.bp_number;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_type !== 'undefined' && $json_decode.vendor_type.toString().toLowerCase() == 'other' && $json_decode.vendor_type_other !== 'undefined'){
                                return $json_decode.vendor_type_other;
                            }
                            else if(typeof $json_decode === 'object' && typeof $json_decode.vendor_type !== 'undefined'){
                                return $json_decode.vendor_type;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    render :function(id, type, full, meta){
                        $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                        if(typeof $json_decode === 'object' && typeof $json_decode.vendor_name !== 'undefined'){
                            return $json_decode.vendor_name.toUpperCase();
                        }
                        else
                            return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_address !== 'undefined'){
                                return $json_decode.vendor_address;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_building !== 'undefined'){
                                return $json_decode.vendor_building;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_district !== 'undefined'){
                                return $json_decode.vendor_district;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_city !== 'undefined'){
                                return $json_decode.vendor_city;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_postcode !== 'undefined'){
                                return $json_decode.vendor_postcode;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_province !== 'undefined'){
                                return $json_decode.vendor_province;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.vendor_phone !== 'undefined'){
                                return $json_decode.vendor_phone;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.company_email !== 'undefined'){
                                return $json_decode.company_email;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.pic_name !== 'undefined'){
                                return $json_decode.pic_name;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.pic_phone !== 'undefined'){
                                return $json_decode.pic_phone;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.pic_email !== 'undefined'){
                                return $json_decode.pic_email;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.npwp !== 'undefined'){
                                return $json_decode.npwp;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.pkp !== 'undefined'){
                                return $json_decode.pkp;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.bank_name !== 'undefined'){
                                return $json_decode.bank_name;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.bank_account_number !== 'undefined'){
                                return $json_decode.bank_account_number;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.swift_code !== 'undefined'){
                                return $json_decode.swift_code;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { 
                    "data": "JSON_ENCODE",
                    "visible": false,
                    render :function(id, type, full, meta){
                        if(type === 'export-excel'){
                            $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                            if(typeof $json_decode === 'object' && typeof $json_decode.account_holder_name !== 'undefined'){
                                return $json_decode.account_holder_name;
                            }
                            else
                                return '';
                        }
                        return '';
                    }
                },
                { "data": "STATUS_APPROVAL", className: 'text-left',
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
                { "data": "INSERT_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return type === 'export-excel' ? moment(id).format("YYYY-MM-DD HH:mm") : moment(id).format("MM/DD/YYYY - HH:mm");
                        }
                },
                // { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
                // { "data": "LAST_APPROVAL_DATE", className: 'text-left',
                //     "render": function (id, type, full, meta)
                //     {
                //         if(id == null){
                //             return '';
                //         }else{
                //             return type === 'export-excel' ? moment(id).format("YYYY-MM-DD HH:mm") : moment(id).format("MM/DD/YYYY - HH:mm");
                //         }

                //     }
                // },
               { "data": "SAP_PLANT_NAME", className: 'text-left'},
               { "data": "DEPARTMENT_NAME", className: 'text-left'},
               // { "data": "REASON", className: 'text-left'},
               { 
                  "data": "CROSS_PLANT", 
                  "visible": false,
                  render :function(id, type, full, meta){
                      if(type === 'export-excel'){
                          $json_decode = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                          if(typeof $json_decode === 'object' && typeof $json_decode.custom_plant !== 'undefined' && typeof id !== 'undefined' && id.toString() == '1'){
                              return $json_decode.custom_plant;
                          }
                          else if(typeof $json_decode === 'object' && typeof $json_decode.Requestor_Plant_ID !== 'undefined'){
                              return $json_decode.Requestor_Plant_ID;
                          }
                          else
                              return '';
                      }
                      return '';
                  }
              },
              { 
                  "data": 'CROSS_PLANT', 
                  "visible": false,
                  render: function(id, type, full, meta){
                      if(id && id.toString() == '1')
                          return 'Yes';
                      else 
                          return 'No';
                  }
              }
            ],
            // "pagingType":"full_numbers",
            "language": {
                "zeroRecords": "Sorry, your query doesn't match any data",
                "processing": ''
            },
            "order": [[ 1, "desc" ]],

        // }).ajax.reload();
        });
        
        tables.buttons( 0, null ).container().addClass('float-right button-wrapper-export').prependTo(
            $('#requestList_filter')
        );

    })

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
                  title: 'Approve Business Partner',
                  text: "Are you sure want to approve selected business partner requests ?",
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
                    return fetch( '/finance/add-business-partner/approval/submitApprovalForm', options )
                        .then(response => {
                            if (!response.ok) {
                              throw new Error(response.statusText)
                            }
                            return response.json()
                        }).then((res)=>{
                            var json = res;
                            // console.log(json);
                            return {'status':'success', 'msg': "Total Data: " + json.Total_Data +" Total Failed: " + json.Total_Failed +" Total Success: " + json.Total_Success}
                            
                        }).catch(error => {
                            // console.log(error);
                            return {'status':'error', 'msg':'Something went wrong, please try again later'};
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
                                url: "/finance/add-business-partner/approval/submitApprovalForm",
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
            $.get("{{url('finance/add-business-partner/modal-approve-detail')}}", {id : id, divisi : divisi}, function( data ) {
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
                        url: "/finance/add-business-partner/approval/save-with-form-data",
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
                                    title: 'Request Business Partner Rejected',
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
    function getFormDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');
        var url_asset="{{url('upload/business_partner')}}";
        var action="approve";


        $.get("{{url('finance/add-business-partner/modal-detail')}}", { id : id, action : action}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
        });

        $('#requestFileList').DataTable();

    }
    function getHistoryDetail(id){
        $('#requestHistoryList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/add-business-partner/getHistoryApproval',
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
                // $('#requestHistoryList').DataTable({
                //     "columns": [
                //         { "width": "5%" },
                //         { "width": "10%" },
                //         { "width": "25%" },
                //         { "width": "5%" },
                //         { "width": "10%" },
                //         { "width": "45%" }
                //     ],
                //     "order": [[ 0, "asc" ]]
                // });

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
