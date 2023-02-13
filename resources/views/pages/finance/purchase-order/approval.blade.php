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
                <li class="breadcrumb-item active" aria-current="page"><span>Approval</span></li>
            </ol>
        </nav>
        <div class="row flex-grow">
            <div class="col-sm-12 stretch-card">
                <div class="card">
                    <div class="card-body px-0 pb-0 border-bottom">
                        <div class="px-4">
                            <div class="d-flex justify-content-between mb-2">
                                <h4 class="card-title ml-2">Approval Purchase Order</h4>
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
                                                <input type="text" required class="form-control datepicker2" name="created_date_from" id="created_date_from" value="{{ isset($data['created_date_from']) ? date('m/d/Y', strtotime($data['created_date_from'])) : '' }}">
                                                <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                                <input type="text" required class="form-control datepicker2" name="created_date_to" id="created_date_to" value="{{ isset($data['created_date_to']) ? date('m/d/Y', strtotime($data['created_date_to'])) : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pull-right">
                                <div class="col-md-12">
                                    <a href="{{url('finance/purchase-order/approval')}}" class="btn btn-danger" id="resetList">Reset</a>
                                    <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        <br />
                        <div class="col-md-12">
                        @if(Session::has('error_po_approval'))
                          <div class="alert alert-fill-danger alert-dismissable p-3 mb-3" role="alert">
                            <i class="mdi mdi-alert-circle"></i>
                            {{ Session::get('error_po_approval') }}
                            <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                        @endif
                        <table class="table table-bordered table-striped compact" id="approvalList" style="color: #000">
                            <thead>
                                <tr>
                                    <th style="width:40px;" class="no-sort"><input type="checkbox" name="select_all" value="1" id="approvalList-select-all"></th>
                                    <th style="min-width:70px;">PO. Number</th>
                                    <th style="max-width:90px;" class="no-sort">Detail</th>
                                    <th style="min-width:60px;">From PR</th>
                                    {{--<th style="min-width:70px;">Created By</th>--}}
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
                            <tbody>
                                @if(isset($data['po']) && count($data['po']))
                                    @foreach($data['po'] as $po)
                                    <tr>
                                        <td><input type="checkbox" name="id[]" value="{{ isset($po['RELEASE_VALUE']) ? $po['RELEASE_VALUE'] : '' }}" /></td>
                                        <td><a href="{{url('finance/purchase-order/detail/')}}/{{ isset($po['PO_NUMBER']) ? $po['PO_NUMBER'] : '' }}" ><strong>{{ isset($po['PO_NUMBER']) ? $po['PO_NUMBER'] : '' }}</strong></a></td>
                                        <td><a href="#" class="btn btn-primary" onclick="getFormDetail('{{ isset($po['PO_NUMBER']) ? $po['PO_NUMBER'] : '' }}')" data-toggle="modal" data-target="#modalDetailPO" ><i class="fa fa-eye"></i></a></td>
                                        <td>{{ isset($po['REQ_NO']) ? $po['REQ_NO'] : '' }}</td>
                                        <td>{{ isset($po['CREATED_ON']) ? date('d F Y',strtotime($po['CREATED_ON'])) : '' }}</td>
                                        <td>{{ isset($po['COST_CENTER']) ? $po['COST_CENTER']: '' }}</td>
                                        <td class="text-left">{{ isset($po['COST_CENTER_DESC']) ? $po['COST_CENTER_DESC']: '' }}</td>
                                        <td>{{ isset($po['CURRENCY']) ? $po['CURRENCY']: '' }}</td>
                                        <td>{{ isset($po['TARGET_VAL']) ? number_format($po['TARGET_VAL']) : '' }}</td>
                                        <td>{{ isset($po['DOC_TYPE']) ? $po['DOC_TYPE']: '' }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="form-group">
                            {{--<button type="button" class="btn btn-danger" id="rejectForm" onClick="actionForm('Reject', 'REJECTED')">Reject</button>--}}
                            <button type="button" class="btn btn-primary" id="approveForm" onClick="actionForm('Approve','APPROVED')"><i class="fa fa-check"></i>&nbsp; Batch Approve</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailPO" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Purchase Order Detail</h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalDetailPO">
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
        $(".datepicker2").datepicker({
            format : 'YYYY-MM-DD'
        });
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
     
    function getFormDetail(id){
        var segment='approval';
        $('#modalDetailPO #bodyModalDetailPO').html('');
        $(".loader-modal").show();
        $.get("{{url('finance/purchase-order/detail')}}", { id : id, segment : segment}, function( data ) {
            $(".loader-modal").hide();
            setTimeout(function(){
                $('#modalDetailPO #bodyModalDetailPO').html(data);
            }, 100);
        }).fail(function(e){ 
            console.log(e);
        });
    }

    var tables =  $('#approvalList').DataTable({
        "aaSorting": [],
        "searching":true,
        "autoWidth":false,
        "pageLength": 100,
        "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
        }],
        "pagingType":"full_numbers",
        "language": {
            "zeroRecords": "Sorry, your query doesn't match any data",
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
            // console.log(type, type2);
            // return
            var typeCheck = type.toLowerCase().trim() || '';
            if(typeCheck == 'approve'){
                Swal.fire({
                  title: 'Approve Purchase Request',
                  text: "Are you sure want to approve selected purchase requests ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, approve!',
                  showLoaderOnConfirm: true,
                  preConfirm: (login) => {
                    const params = {
                        "approval_id": idJoin, //Pisahkan dengan ;
                        "status_approval": type2, //APPROVE or REJECT
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
                    return fetch( '/finance/purchase-order/approval/submitApprovalPO', options )
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
