@extends('layouts.default')
@section('title', 'Approval Request Cash Advance')
@section('styles')
<link rel="stylesheet" href="/css/sweetalert.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
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
    }*/
    /*.table td {
        font-size: 10px;
    }*/
    .accordion .card .card-body i {
        font-size: 0.825rem;
    }

	div.dataTables_wrapper div.dataTables_processing {
        top: -30%;
        border: 0;
        border-width: 0;
    }
    table {
        color: #000 !important;
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
                <li class="breadcrumb-item"><a href="#">Add Cash Advance</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Approval</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">Approval Request Cash Advance</h4>
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
                                        <div class="col-md-2">
                                            <a href="{{url('finance/cash-advance/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                        </div>
									</div>
								</div>
							</div>
                            {{--<div class="row pull-right">
							</div>--}}
                        </form>
                        <br />
                        <div class="table-responsive">
    						<table class="table table-bordered datatable approvalList123" id="approvalList" style="white-space: nowrap;color: #000 !important">
    							<thead>
    								<tr>
                                        <th style="width:90px;"><input type="checkbox" name="select_all" value="1" id="approvalList-select-all"></th>
    									<th style="min-width:90px;">Form No</th>
    									<th style="min-width:90px;">Status</th>
    									<th style="min-width:90px;">Requestor</th>
    									<th style="min-width:90px;">Request Date</th>
    									<th style="min-width:90px;">Department</th>
    									<th style="min-width:90px;">Division</th>
                                        <th style="min-width:90px;">Currency</th>
                                        <th style="min-width:90px;">Total Amount</th>
    								</tr>
    							</thead>
    						</table>
                        </div>
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
				<h5 class="modal-title" id="modalFileLabel">Request Cash Advance Detail</h5>
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
				<h5 class="modal-title" id="modalApproveLabel">Approve Request Cash Advance</h5>
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
                            <th style="min-width:90px;">Level Approval</th>
                            <th style="min-width:100px;">Level Desc</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="max-width:150px;">Reason</th>
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
<script>
    var objFile = {};var objJsonDetail = {}; var objRow = {};

    function uniq_fast(a) {
        var seen = {};
        var out = [];
        var len = a.length;
        var j = 0;
        for(var i = 0; i < len; i++) {
             var item = a[i];
             if(seen[item] !== 1) {
                   seen[item] = 1;
                   out[j++] = item;
             }
        }
        return out;
    }

    $(document).ready(function(){
        $(".selectmul").select2({
            multiple:true
        })
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
            "processing":true,
            "serverSide":true,
            "searching":false,
            "autoWidth":false,
            "ajax":{
                "url": "/finance/cash-advance/approval/getData",
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
                        return '<input type="checkbox" name="id[]" value="'+id+'#'+full.APPROVAL_LEVEL+'"></input>'; // tampilkan checkbox
                    }
                },
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        // arrFile.push(full.DOCUMENT_FILE);
                        objJsonDetail[id] = full.JSON_ENCODE;
                        objRow[id] = full;
                        return '<a href="#" style="font-weight:bold" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    }
                },

                { "data": "STATUS_APPROVAL",
                    "render": function (id, type, full, meta)
                    {   
                        if(id == null || id == "null"){ id = "REQUESTED"; }
                        if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}
                        
                        if(typeof full.UID !== 'undefined' && typeof full.JSON_ENCODE.Form_Number !== 'undefined' && full.UID != full.JSON_ENCODE.Form_Number)
                            return '<a href="#" style="font-weight:bold" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetailExt(\''+full.UID+'\')" >'+id+'</a>';
                        else {
                            return '<a href="#" style="font-weight:bold" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';
                        }
                    }
                },
                { "data": "REQUESTOR_NAME" },
                { "data": "INSERT_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                { "data": "DEPARTMENT_NAME" },
                { "data": "DIVISION_NAME" },
                { "data": "JSON_ENCODE",
                    "render": function (id, type, full, meta)
                    {
                        var currency = id.hasOwnProperty('currency') && id.currency.length > 0 ? id.currency : [];
                        try {
                            // currency = uniq_fast(currency).join(', ');
                            currency = currency[0];
                        } catch(error){}
                        return currency;
                    }
                },
                { "data": "JSON_ENCODE",
                    "render": function (id, type, full, meta)
                    {
                        try {
                            var grandTotal = id.hasOwnProperty('grandTotal') ? id.grandTotal : 0;
                            grandTotal = parseFloat(grandTotal).toLocaleString('en-US', {minimumFractionDigits: 2});
                        } catch(error){ var grandTotal = 0 }
                        return grandTotal;
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
            var typeCheck = type.toLowerCase().trim() || '';
            if(typeCheck == 'approve'){
                Swal.fire({
                  title: 'Approve Cash Advance',
                  text: "Are you sure want to approve selected data ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, approve!',
                  showLoaderOnConfirm: true,
                  preConfirm: (login) => {
                    const params = {
                        // "approval_id": idJoin, //Pisahkan dengan ;
                        // "status_approval": type2, //APPROVE or REJECT
                        // "reason":'Approved',
                        "form_id": idJoin, //Pisahkan dengan ;
                        "employe_id": updateBy, //emp id yg melakukan approve
                        "status_approval": type2, //APPROVE or REJECT
                        "type_form": $('#type_form').val(), //type_form
                        "reason": '-'
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
                    return fetch( '/finance/cash-advance/approval/submitApprovalForm', options )
                        .then(response => {
                            if (!response.ok) {
                              throw new Error(response.statusText)
                            }
                            return response.json()
                        }).then((res)=>{
                            var json = res;
                            return {'status':'success', 'msg': "Total Data: " + json.Total_Data +" Total Failed: " + json.Total_Failed +" Total Success: " + json.Total_Success}

                        }).catch(error => {
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
                                url: "/finance/cash-advance/approval/submitApprovalForm",
                                data:
                                {
                                    // "approval_id": idJoin, //Pisahkan dengan ;
                                    // "status_approval": type2, //APPROVE or REJECT
                                    // "reason":inputValue,
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
            $.get("{{url('finance/cash-advance/modal-approve-detail')}}", {id : id, divisi : divisi}, function( data ) {
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
                        url: "/finance/cash-advance/approval/save-with-form-data",
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
                                    title: 'Request Cash Advance Rejected',
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


        $.get("{{url('finance/cash-advance/modal-detail')}}", { id : id, action : action}, function( data ) {
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
            url: '/finance/cash-advance/getHistoryApproval',
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
                    "columns": [
                        { "width": "5%" },
                        { "width": "10%" },
                        { "width": "25%" },
                        { "width": "5%" },
                        { "width": "10%" },
                        { "width": "45%" }
                    ],
                    "order": [[ 0, "asc" ]]
                });
            }
        });
    }

    function getHistoryDetailExt(id){
        $('#requestHistoryList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/cash-advance/getHistoryApprovalExtended',
            dataSrc: "data",
            data: {
                "form_number":id
            },
            success: function(responseSIA){
                console.log(responseSIA);
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
                    "columns": [
                        { "width": "5%" },
                        { "width": "10%" },
                        { "width": "25%" },
                        { "width": "5%" },
                        { "width": "10%" },
                        { "width": "45%" }
                    ],
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
