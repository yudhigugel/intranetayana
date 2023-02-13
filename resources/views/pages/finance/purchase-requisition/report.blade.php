@extends('layouts.default')

@section('title', 'Report Purchase Requisition')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
{{-- <link rel="stylesheet" href="/css/sweetalert.min.css"> --}}
@endsection
@section('styles')
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
.select2-results {
    text-align: left;
}
.table-container-h {
    overflow: auto;
}
div.dataTables_wrapper div.dataTables_processing {
    top: -30px !important;
}
.table-wrapper {
    position: relative;
}

</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Report</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Report List</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="status" name="status">
                                        <option value="">All</option>
                                        <option value="Waiting" {{ ($data['status']=="Waiting")? 'selected' : '' }}>Waiting for Approval</option>
                                        <option value="Finished" {{ ($data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                        <option value="Canceled" {{ ($data['status']=="Canceled")? 'selected' : '' }}>Canceled</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/purchase-requisition/report')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered table-striped datatable requestList" id="requestList">
                    <thead>
                        <tr>
                            <th>PR Number</th>
                            <th>PR Detail</th>
                            <th>Status PR</th>
                            <th>Last Approver PR</th>
                            {{--<th >Reason</th>--}}
                            <th>Req. Date</th>
                            <th>Purpose</th>
                            {{--<th>Tracking No.</th>--}}
                            {{--<th>Tracking No. Desc</th>--}}
                            <th>Doc Type</th>
                            {{--<th>PO Number</th>--}}
                            <th>PO List</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileLabel">Purchase Requisition Detail </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFile">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPO" tabindex="-1" role="dialog" aria-labelledby="modalPOLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalPOLabel">Purchase Order Detail</h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalPO">

            </div>
        </div>
    </div>
</div>

<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">



@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
    var objFile = {};var objJsonDetail = {}; var objRow = {};



    $(document).ready( function () {


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


        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "pageLength": 100,
            "lengthChange": false,
            "responsive": true,
            "dom" : '<"clearfix"lf> <"d-block" <"table-wrapper table-container-h"rt>>ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/purchase-requisition/request/getData",
                "dataSrc": "data",
                "data" : {
                    "employee_id":updateBy,
                    "filter":"",
                    "value":"",
                    "status":status,
                    "request_type":"REPORT",
                    "insert_date_from":request_date_from,
                    "insert_date_to":request_date_to
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
            "fixedHeader": false,
            "processing": true,
            "serverSide": true,
            "scrollX": false,
            "columns": [
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        return '<a style="font-weight:bold;" href="{{url("finance/purchase-requisition/detail/")}}/'+id+'" >'+id+'</a>';
                    }
                },
                {
                    "data" : "UID",
                    "render": function (id, type, full, meta){
                        return '<a href="#" class="btn btn-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile" ><i class="fa fa-eye"></i></a>';
                    }
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id, type, full,meta){
                        try {
                            // Check approve commited in SAP
                            if(full.STATUS_APPROVAL != 'FINISHED' && !full.LAST_APPROVAL_NAME){
                                if(full.hasOwnProperty('ALREADY_APPROVE_SAP')){
                                    if(full.hasOwnProperty('DATA_RELEASE_MAPPING') && full.DATA_RELEASE_MAPPING.length == full.ALREADY_APPROVE_SAP.length){
                                        return '<a href="javascript:void(0);" style="color:green;font-weight:bold;">FINISHED</a>';
                                    }
                                }
                            }
                        } catch(error){
                            console.log(error);
                        }

                        if(id=="APPROVED" || id=="REQUESTED" || id==""){
                            id="WAITING FOR APPROVAL";
                            return '<a href="javascript:void(0);" style="font-weight:bold;"">WAITING</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="javascript:void(0);" style="color:green;font-weight:bold;">FINISHED</a>';
                        }else{
                            return '<a href="javascript:void(0);" style="color:red;font-weight:bold;">'+id+'</a>';
                        }
                    }
                },
                { "data": "LAST_APPROVAL_NAME",
                  "render": function(id, type, full, meta){
                        try {
                            // Check approve commited in SAP
                            if(full.STATUS_APPROVAL != 'FINISHED' && !full.LAST_APPROVAL_NAME){
                                if(full.hasOwnProperty('ALREADY_APPROVE_SAP')){
                                    if(full.hasOwnProperty('DATA_RELEASE_MAPPING') && full.DATA_RELEASE_MAPPING.length == full.ALREADY_APPROVE_SAP.length){
                                        return typeof full.DATA_RELEASE_MAPPING[0].MAIN_EMPLOYEE == 'undefined' ? id : full.DATA_RELEASE_MAPPING[0].MAIN_EMPLOYEE;
                                    }
                                } else {
                                    return id;
                                }
                            }
                        } catch(error){}

                        return id;
                   } 

                },
                // { "data": "REASON" },
                { "data": "REQ_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                // { "data": "TRACKING_NO" },
                // { "data": "TRACKING_DESC"},
                { "data": "PURPOSE" },
                { "data": "DOC_TYPE" },
                // { "data": 'PO_NUMBER' },
                {
                    "data" : "PO_NUMBER",
                    "className": 'truncated-wrapper',
                    "render": function (id, type, full, meta){
                        if(id){
                            try {
                                var po_number = id.split(",");
                                po_number = [...new Set(po_number)];
                                po_number = po_number.map(x => { 
                                    if(x) 
                                        return `<p><a href="#" style="font-weight: bold" class="text-primary" onclick="getDetailPO('${x.trim()}')" data-toggle="modal" data-target="#modalPO" >${x.trim()}</a></p>`; 
                                    else 
                                        return ""; 
                                }).join('');
                            } catch(error) {
                                var po_number = '-'; 
                            }
                            return po_number;
                        }
                        else {
                            return '-';
                        }
                    }
                }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                  targets: 5,
                  // render: $.fn.dataTable.render.ellipsis( 40 ),
                  className: "text-left purpose",
                  width: "25%"
                },
                {
                  targets: 7,
                  // render: $.fn.dataTable.render.ellipsis( 40 ),
                  width: "13%"
                }
            ],
            initComplete: function(setting, json){
                document.querySelectorAll(".truncated").forEach(function(current) {
                    current.addEventListener("click", function(e) {
                        if(current.classList.contains('truncated'))
                            current.classList.remove("truncated");
                        else
                            current.classList.add("truncated");
                    }, false);
                });
            },
            "createdRow": function( row, data, dataIndex ) {
                try {
                    var po_number = data.PO_NUMBER.split(",");
                    po_number = [...new Set(po_number)];
                    if(po_number.length > 1){
                        $(row).find('.truncated-wrapper').addClass('truncated');
                    }
                } catch(error){}
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
        var segment='request';
        $('#modalFile #bodyModalFile').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition/detail')}}", { id : id, segment : segment}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
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
        document.getElementById('tableRow').value = document.getElementById('reqForm').rows.length;
        var form = $(this);
        var url_post=$("#formRequest").attr('data-url-post');
        var loader=$("#formRequest").attr('data-loader-file');
        var form = new FormData(this);


        $.ajax({
            type: "POST",
            url: url_post,
            data: form,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            Swal.fire({
                title: "Loading...",
                text: "Please wait!",
                imageUrl: loader,
                imageSize: '150x150',
                showConfirmButton: false
            });
            },
            success: function(data) {
            console.log(data);
            if (data.success) {
                if (data.msg) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.msg,
                    }).then((result) =>{
                    location.reload();
                    });

                }
            } else {

                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.msg,
                });

            }

            },
            error: function(err) {

                //console.log(err);
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text : err && err.responseJSON && err.responseJSON.message
                });

            }
        });


    });


        var vendor_type = $('input:radio[name=vendor_type]');
        vendor_type.change(
        function(){
            if ($(this).is(':checked') && $(this).val() == 'Other') {
                $("#vendor_type_other").show();
                $("#vendor_type_other").attr('required','required');
            }else{
                $("#vendor_type_other").hide();
                $("#vendor_type_other").removeAttr('required');
            }

        });







</script>
@endsection
