@extends('layouts.default')
@section('title', 'Approval Purchase Order')
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

.dataTables_wrapper .dataTables_processing {
    top: -30px !important
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
                <li class="breadcrumb-item active" aria-current="page"><span>Report</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">History Approval Purchase Order</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 form-group" style="position: relative;overflow:hidden">
                        <form class="form-horizontal" method="get" name="form_merge_list" id="form_merge_list">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 row">
                                        <label class="col-sm-2 col-form-label">Created Date</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker2" name="created_date_from" id="created_date_from" value="{{ isset($data['created_date_from']) ? $data['created_date_from'] : '' }}">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" class="form-control datepicker2" name="created_date_to" id="created_date_to" value="{{ isset($data['created_date_to']) ? $data['created_date_to'] : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pull-right">
                                <div class="col-md-12">
                                    <a href="{{url('finance/purchase-order/report')}}" class="btn btn-danger" id="resetList">Reset</a>
                                    <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        <br />
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped compact" id="approvalList" style="color: #000">
                                <thead>
                                    <tr>
                                        {{--<th style="width:20px;"><input type="checkbox" name="select_all" value="1" id="approvalList-select-all"></th>--}}
                                        <th style="min-width:70px;">PO. Number</th>
                                        <th style="min-width:60px;">From PR</th>
                                        {{-- <th style="min-width:70px;">Created By</th> --}}
                                        <th style="min-width:70px;">Created Date</th>
                                        {{--<th style="max-width:90px;">Company Code</th>--}}
                                        <th style="max-width:90px;">Cost Center ID</th>
                                        <th style="max-width:120px;">Cost Center Desc</th>
                                        {{--<th style="min-width:90px;">Purch. Group</th>--}}
                                        <th style="min-width:50px;">Currency</th>
                                        <th style="min-width:90px;">Grand Total</th>
                                        <th style="max-width:90px;">Doc. Type</th>
                                    </tr>
                                </thead>
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
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script>
    $(function(){
        $(".datepicker2").datepicker();
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
    var objFile = {};var objJsonDetail = {}; var objRow = {};

    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    var tables =  $('#approvalList').DataTable({
        "processing":true,
        "serverSide":true,
        "searching":true,
        "pageLength": 100,
        // "lengthChange": false,
        "autoWidth":true,
        "ajax":{
            "url": "/finance/purchase-order/report",
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
                "created_date_to" : params.hasOwnProperty('created_date_to') ? params.created_date_to : ''
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
            // { "data": "CO_CODE" },
            // { "data": "PUR_GROUP"},
            { "data": "COST_CENTER" },
            { "data": "COST_CENTER_DESC" },
            { "data": "CURRENCY" },
            { "data": "TARGET_VAL",
                "render": function (id, type, full, meta)
                    {
                        return number_format(id);
                    }
            },
            { "data": "DOC_TYPE" }
        ],
        "language": {
            "zeroRecords": "Sorry, there is no data to approve at the moment",
            "processing": ''
        },
        "order": [[ 1, "desc" ]],

    // }).ajax.reload();
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
