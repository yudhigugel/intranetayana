@extends('layouts.default')
@section('title', 'Approval Entertainment Form')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">

<style>
    .table thead th {
        font-size: 12px;
        color: #001737;
    }
    .table td {
        font-size: 10px;
    }
    .accordion .card .card-body i {
        font-size: 0.825rem;
    }

    .select2-container {
        width: 100% !important;
        line-height: 12px;
    }

	.select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 16px;
        margin-left: -11px;
		margin-top: -3px;
    }

	div.dataTables_wrapper div.dataTables_processing {
        top: -10%;
        border: 0;
        border-width: 0;
    }

    .select2-container .select2-selection--single {
        height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .reason_rejectionH6 {
        margin-top: 20px;
    }

    .select2-container .select2-selection--multiple {
        height: 38px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        font-size: 12px;
        line-height: 2;
    }
    .table{
        color:#000 !important;
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
                <li class="breadcrumb-item"><a href="#">Entertainment Form</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Approval</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">Approval Request Entertainment</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Request Date</label>
										<div class="col-sm-10">
											<div class="input-group">
                                                <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ $data['request_date_from'] }}">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ $data['request_date_to'] }}">
                                            </div>
										</div>
									</div>
								</div>
							</div>
                            <div class="row pull-right">
								<div class="col-md-12">
                                    <a href="{{url('finance/entertainmentForm/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                    <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
								</div>
							</div>
                        </form>
                        <br />

						<table class="table table-striped table-bordered datatable approvalList123" id="approvalList" style="white-space: nowrap;">
							<thead>
								<tr>
                                    <th style="width:90px;"><input type="checkbox" name="select_all" value="1" id="approvalList-select-all"></th>
									<th style="min-width:90px;">Form No</th>
									<th style="min-width:90px;">Status</th>
									<th style="min-width:90px;">Requestor</th>
									<th style="min-width:90px;">Request Date</th>
									<th style="min-width:90px;">Department</th>
									<th style="min-width:90px;">Division</th>
								</tr>
							</thead>
						</table>
                        <div class="form-group">
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
				<h5 class="modal-title" id="modalFileLabel">Request Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFile">

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
                <table class="table table-bordered datatable requestHistoryList" id="requestHistoryList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Level Approval</th>
                            <th style="min-width:90px;">Level Desc</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="min-width:90px;">Reason</th>
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
<input type="hidden" name="type_form" id="type_form" class="form-control" value="{{$data['form_code']}}">

@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script>
    var objFile = {};var objJsonDetail = {};
    $(document).ready(function(){
        $(".datepicker2").datepicker();
        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        // var spv =  $('#spv').val(); //belum ada SPV
        var spv ='';


        $('#approvalList').DataTable().clear().destroy();
        var tables =  $('#approvalList').DataTable({
            "processing":true,
            "serverSide":true,
            "searching":false,
            "autoWidth":false,
            "ajax":{
                        "url": "/finance/entertainmentForm/approval/getData",
                        "type": "POST",
                        "dataSrc": "data",
                        "data" : {
                            "employeeId":updateBy,
                            "deptId":deptid,
                            "midjobId":midjobid,
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
                    "width" : "5%",
                    "render": function (id, type, full, meta)
                    {

                        return '<input type="checkbox" name="id[]" value="'+id+'#'+full.APPROVAL_LEVEL+'"></input>';

                    }
                },
                { "data": "UID",
                "width" : "10%",
                    "render": function (id, type, full, meta)
                    {
                        // arrFile.push(full.DOCUMENT_FILE);
                        objJsonDetail[id] = full.JSON_ENCODE.replace(/&quot;/g,'"');


                        return '<a href="#" data-toggle="modal" data-target="#modalFile" style="font-weight:bold;" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    }
                },

                { "data": "STATUS_APPROVAL",
                    "render": function (id, type, full, meta)
                    {
                        if(id == null || id == "null"){ id = "WAITING FOR APPROVAL"; }
                        if(id == "REQUESTED" || id =="APPROVED") { id = "WAITING FOR APPROVAL"}
                        return '<a href="#" data-toggle="modal" style="font-weight:bold;" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';
                    }
                },
                { "data": "REQUESTOR_NAME" },
                { "data": "INSERT_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                { "data": "DIVISION_NAME" },
                { "data": "DEPARTMENT_NAME" }
            ],
            "pagingType":"full_numbers",
            "language": {
                "zeroRecords": "Sorry, your query doesn't match any data",
                "processing": 'LOADING'
            },
            "order": [[ 1, "desc" ]],

        // }).ajax.reload();
        });

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
        console.log(idJoin);
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
                    jQuery.ajax({
                        type:"POST",
                        url: "/finance/entertainmentForm/approval/submitApprovalForm",
                        data:
                        {
                            "form_id": idJoin, //Pisahkan dengan ;
                            "employe_id": updateBy, //emp id yg melakukan approve
                            "status_approval": type2, //APPROVE or REJECT
                            "type_form": $('#type_form').val(), //type_form
                            "reason":inputValue
                        },
                        success: function(data) {
                            // if(data.code == "200")
                            // {
                                swal({
                                    title: type+": ",
                                    text: "Total Data: " + data.Total_Data +" Total Failed: " + data.Total_Failed +" Total Success: " + data.Total_Success,
                                    type: "success",
                                    closeOnConfirm: false
                                    }, function () {
                                        swal.close();
                                        location.reload();
                                });

                            // }
                            // else
                            // {
                            //     swal("Error", data.message, "error");
                            // }
                        }
                    });
                }
            });
        }
        else{
            swal("Warning", "Please select at least one data.", "warning");
        }
    }
    function getFormDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');
        var action="approve";

        $.get("{{url('finance/entertainmentForm/modal-detail')}}", { id : id, action: action}, function( data ) {
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
            url: '/finance/entertainmentForm/request/getHistoryApproval',
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
                                var APPROVAL_DATE = moment(listSIA[i].APPROVAL_DATE).format("DD-MM-YYYY HH:mm:ss");
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
                $('#requestHistoryList').DataTable({
                    "order": [[ 0, "asc" ]]
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
