@extends('layouts.default')

@section('title', 'List Reimbursement Cash Advance GC')
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

.hijau{
    color:#2ecb3a !important;
    font-size:16px;
}

.merah{
    color:red !important;
    font-size:16px;
}
.dropdown-menu > li > a {
    display: block;
    padding: 9px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Cash Advance</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>List Reimbursement</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">All Reimbursement Cash Advance</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Reimbursement Date</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ $data['request_date_from'] }}">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ $data['request_date_to'] }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                                    <a href="{{url('finance/cash-advance-gc/list-reimbursement')}}" class="btn btn-danger" id="resetList">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered table-striped datatable approvalList" id="approvalList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>Reimburse No.</th>
                            <th>CA No.</th>
                            <th>O/S</th>
                            <th>DHBR Date</th>
                            <th>Reimburse Desc.</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Reimburse Date</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg mb-5" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileLabel">Reimbursement Detail </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFile">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFileCA" tabindex="-1" role="dialog" aria-labelledby="modalFileCALabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg mb-5" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileCALabel">Cash Advance Detail </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFileCA">

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
        var category =  $('#category').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#approvalList').DataTable({
            "aaSorting": [],
            "pageLength": 100,
            // "lengthChange": true,
            "pageLength": 100,
            "responsive": true,
            "dom": '<"dt-buttons"Bfl>rtip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/cash-advance-gc/list-reimbursement/getData",
                "dataSrc": "data",
                "data" : {
                    "employee_id":updateBy,
                    "category":category,
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
            "fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            'columnDefs': [{
            }],
            "columns": [
                {
                    "data" : "REIMBURSEMENT_ID",
                    "render": function (id, type, full, meta){
                        return '<a href="#" style="font-weight:bold" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile" >'+id+'</a>';
                    }
                },
                {   "data": "FORM_ID",
                    "render":function(id,type,full,meta){
                        console.log(full);
                        var is_posted = full.CA_STATUS_POSTING;
                        console.log(is_posted);
                        if (is_posted=='YES'){
                            var tombol='<span class="p-1 bg-success d-inline-block mr-1 rounded" data-toggle="tooltip" data-placement="top" title="CA is posted"></span>'
                        }else{
                            var tombol='<span class="p-1 bg-danger d-inline-block mr-1 rounded" data-toggle="tooltip" data-placement="top" title="CA is not posted"></span>';
                        }
                        return '<a href="#" style="font-weight:bold" onclick="getFormDetailCA(\''+id+'\')" data-toggle="modal" data-target="#modalFileCA" >'+id+'</a> '+tombol;
                    }
                },
                { "data": "OS"
                },
                {
                    "data": "CA_FINAL_SETTLEMENT_DATE",
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                { "data": "REIMBURSE_DESC"
                },
                { "data": "CA_ITEM_DESC"
                },
                { "data": "CA_ITEM_AMOUNT"
                },
                { "data": "CA_CURRENCY"
                },
                {
                    "data": "REIMBURSE_INSERT_DATE",
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                }
                // {
                //     "data": "REIMBURSEMENT_ID",
                //     "render": function (id, type, full, meta)
                //         {
                //             var status_backup = full.STATUS_BACKUP;
                //             var parse='';
                //             parse +=`<a href="javascript:void(0);" onclick="payReimbursement('`+id+`')" data-toggle="tooltip" data-original-title="Pay" class="btn btn-success btn-circle btn-xs text-white">PAY</a> `;
                //             parse +=`<a href="javascript:void(0);" onclick="cancelReimbursement('`+id+`')" data-toggle="tooltip" data-original-title="Cancel" class="btn btn-danger btn-circle btn-xs text-white">Cancel</a>`;
                //             return parse;
                //         }
                // }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ]
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
        var readonly = true;

        $.get("{{url('finance/cash-advance-gc/modal-detail-list-reimbursement')}}", { id : id, segment : segment, readonly:readonly}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
        });

    }

    function getFormDetailCA(id){
        var segment='request';
        $('#modalFileCA #bodyModalFileCA').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/cash-advance-gc/modal-detail-ca-reimbursement')}}", { id : id, segment : segment}, function( data ) {
            $('#modalFileCA #bodyModalFileCA').html(data);
            $(".loader-modal").hide();
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

    function actionForm(){
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

        if(idArray.length > 0){
            Swal.fire({
                title: 'Add to Reimbursement',
                text: "Are you sure want to add cash advance to reimbursement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add!',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                const params = {
                    "form_id": idJoin, //Pisahkan dengan ;
                    "employe_id": updateBy, //emp id yg melakukan approve
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
                return fetch( '/finance/cash-advance-gc/add-reimbursement/add-reimbursement-batch', options )
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
                    Swal.fire({
                        title: " Success :",
                        text: result.value.msg,
                        icon: 'success',
                    }).then((result) => {
                        location.reload();
                    });
                }
            });
        }
        else{
            Swal.fire("Warning", "Please select at least one data.", "warning");
        }
    }




</script>
@endsection
