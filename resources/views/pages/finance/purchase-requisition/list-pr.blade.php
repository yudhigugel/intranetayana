@extends('layouts.default')
@section('title', 'List Purchase Requisition')
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

.modal-dialog {
    max-width: unset !important;
}

.dataTables_wrapper .dataTables_processing {
    top: -30px !important
}

.table-container-h {
    overflow: auto;
}

.table-wrapper {
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

.select2-results {
    text-align: left;
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
                <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>List</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">List Purchase Requisition</h4>
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
                                            <label class="">Request Date</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker2" name="created_date_from" id="created_date_from" value="{{ isset($data['created_date_from']) && !empty($data['created_date_from']) ? date('m/d/Y',strtotime($data['created_date_from'])) : date('m/01/Y') }}" placeholder="Date From">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="created_date_to" id="created_date_to" value="{{ isset($data['created_date_to']) && !empty($data['created_date_to']) ? date('m/d/Y',strtotime($data['created_date_to'])) : date('m/d/Y') }}" placeholder="Date To">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <label class="">Cost Center</label>
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
                                {{--<div class="col-md-3">
                                    <div class="mb-3 row">
                                        <div class="col-md-12">
                                            <label class="">Apply</label>
                                            <div class="input-group">
                                            <a href="{{url('finance/purchase-requisition/list')}}" class="btn btn-danger mr-2" id="resetList">Reset</a>
                                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
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
                                                    <option value="Canceled" {{ (isset($data['status']) && $data['status']=="Canceled")? 'selected' : '' }}>Canceled</option>
                                                    <option value="Rejected" {{ (isset($data['status']) && $data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                            <div class="col-8">
                                                <a href="{{url('finance/purchase-requisition/list')}}" class="btn btn-danger" id="resetList">Reset</a>
                                                <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                        <table class="table table-bordered table-striped" id="approvalList" style="white-space: nowrap;">
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
    </div>
</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
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

    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    var tables =  $('#approvalList').DataTable({
        "processing":true,
        "dom" : '<"clearfix"lf> <"d-block" <"table-wrapper table-container-h"rt>>ip',
        "serverSide":true,
        "scrollX": false,
        "searching":true,
        "pageLength": 50,
        'paging':true,
        // "lengthChange": false,
        "autoWidth":false,
        "responsive": true,
        "ajax":{
            "url": "/finance/purchase-requisition/list",
            "type": "GET",
            "dataSrc": function ( json ) {
                //Make your callback here.
                if(json.hasOwnProperty('data') && json.data)
                 return json.data;
                else
                 return [];
            },
            data : {
                "created_date_from" : params.hasOwnProperty('created_date_from') ? params.created_date_from : document.getElementById('created_date_from').value,
                "created_date_to" : params.hasOwnProperty('created_date_to') ? params.created_date_to : document.getElementById('created_date_to').value,
                "cost_center" : params.hasOwnProperty('cost_center') ? params.cost_center : document.getElementById('cost_center').value,
                "status" : params.hasOwnProperty('status') ? params.status : ''
            }
        },
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
        "language": {
            "zeroRecords": "Sorry, there is no data available at the moment",
            "processing": ''
        },
        "order": [[ 1, "desc" ]],
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
        // "footerCallback": function ( row, data, start, end, display ) {
        //     var api = this.api();
 
        //     // Remove the formatting to get integer data for summation
        //     var intVal = function ( i ) {
        //         return typeof i === 'string' ?
        //             i.replace(/[\$,]/g, '')*1 :
        //             typeof i === 'number' ?
        //                 i : 0;
        //     };
 
        //     // Total over all pages
        //     var total = api
        //         .column( 7 )
        //         .data()
        //         .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //         }, 0 );
 
        //     // Update footer
        //     total = number_format(total, 0, 0 , ',');
        //     $( api.column( 7 ).footer() ).html(total);
        // }
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
