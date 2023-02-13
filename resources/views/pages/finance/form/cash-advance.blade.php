@extends('layouts.default')

@section('title', 'Request Cash Advance')
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
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Cash Advance</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Request List</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalRequest">Add Request</button>
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
                            <a href="{{url('finance/cash-advance/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered table-striped datatable requestList" id="requestList">
                    <thead>
                        <tr>
                            <th>Form No.</th>
                            <th>Req. Detail</th>
                            <th>Req. Status</th>
                            <th>Last Approver</th>
                            <th>Req. Date</th>
                            <th>Currency</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalRequest" role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalRequestLabel">Form - Add Cash Advance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodymodalRequest" style="overflow: hidden">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/cash-advance/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
                                <input type="hidden" name="Request_Date" id="Request_Date" value="{{ date('Y-m-d')}}">
                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                <input type="text" value="(auto generate)" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Purpose / Notes <span class="red">*</span></label>
                                <input type="text" class="form-control" name="purpose" required placeholder="insert your purpose / notes on requesting cash advance"/>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Requestor Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Name</label>
                                <input type="text" value="{{$data['employee_name']}}" name="Requestor_Name" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Plant Name</label>
                                <input type="text" value="{{$data['plant_name']}}" name="Requestor_Company" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Employee ID</label>
                                <input type="text" value="{{$data['employee_id']}}" name="Requestor_Employee_ID" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Territory</label>
                                <input type="text" value="{{$data['territory_name']}}" name="Requestor_Territory" class="form-control" readonly />
                                <input type="hidden" name="Requestor_Territory_ID" value="{{$data['territory_id']}}" id="Requestor_Territory_ID">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Cost Center ID</label>
                                <input type="text" value="{{$data['cost_center_id']}}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Department</label>
                                <input type="text" value="{{$data['department']}}" name="Requestor_Department" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Plant ID</label>
                                <input type="text" value="{{$data['plant']}}" name="Requestor_Plant_ID" class="form-control" readonly />
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Division</label>
                                <input type="text" value="{{$data['division']}}" name="Requestor_Division" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{$data['job_title']}}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        <div class="row portlet-body table-both-scroll mb-3">
                            <table class="table table-striped table-bordered table-hover smallfont" id="reqForm"  style="display:block; overflow:scroll;">
                                <thead>
                                    <tr>
                                        <th style="width:400px;">Description</th>
                                        <th style="width:100px;" class="thead-apri">Quantity</th>
                                        <th style="width:150px;" class="thead-apri">Price</th>
                                        <th class="thead-apri">Amount</th>
                                        <th class="thead-apri">Currency</th>
                                        <th class="thead-apri"></th>
                                        <th class="thead-apri"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="rowToClone">
                                        <td>
                                            <textarea name="description[]" id="description" class="form-control td-apri"></textarea>
                                            {{-- <input type="text" class="form-control td-apri" style="min-width:200px;" name="materialDesc[]" id="materialDesc"    > --}}
                                        </td>
                                        <td>
                                            <input required type="text" class="form-control td-apri" style="min-width:75px;" name="quantity[]" id="quantity" onkeyup="CalculateTotal(this);">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" name="harga_parse[]" id="harga_parse" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" placeholder="0.00" value="0">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="amount_parse[]" id="amount_parse" readonly="">
                                        </td>
                                        <td>
                                            <select name="currency[]" id="currency" class="form-control" style="min-width:100px;">
                                                <option value="IDR" selected>IDR</option>
                                                <option value="USD">USD</option>
                                            </select>

                                        </td>
                                        <td>
                                            <div class="btn-group" style="min-width:140px">
                                                <button type="button" class="btn btn-success" onclick="addRow('reqForm')">+</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteBaris('reqForm')">-</button>
                                                {{-- <button type="button" class="btn btn-warning" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">Copy</button> --}}
                                            </div>
                                        </td>
                                        <td>
                                            <input type="hidden" name="harga[]" id="harga">
                                        </td>
                                        <td>
                                            <input type="hidden" name="amount[]" id="amount">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Grand Total</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="0.00" readonly="" name="grandTotal" id="grandTotal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <input type="hidden" id="tableRow" name="tableRow" readonly="">
                                <button type="submit" class="form-control btn btn-success text-white">Send Request</button>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileLabel">Cash Advance Detail </h5>
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


    var objFile = {};var objJsonDetail = {}; var objRow = {};

    $('#modalRequest').on('shown.bs.modal', function (e) {
        $(".select2").select2({});
    })

    /*****************************************************************************/
    /*****************************************************************************/
    /* FUNCTION DARI INTRANET BIZNET */
    /*****************************************************************************/
    /*****************************************************************************/

    function disableButton(idTag,buttonId){
        var x = document.getElementById(idTag).value;

        if(x.length > 2){
            document.getElementById(buttonId).removeAttribute("disabled");
        }
        else{
            document.getElementById(buttonId).setAttribute("disabled","disabled");
        }
    }
    function addRow(tableID) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var colCount = table.rows[1].cells.length;

        if(rowCount<10000){

            for(var i=0; i<colCount; i++) {
                var newcell = row.insertCell(i);
                newcell.innerHTML = table.rows[1].cells[i].innerHTML;

                if(newcell.childNodes[1].name == "preqItem[]"){
                    if(rowCount < 10){
                        newcell.childNodes[1].value = "000"+(rowCount * 10);
                    }
                    else if(rowCount < 100 && rowCount>9){
                        newcell.childNodes[1].value = "00"+(rowCount * 10);
                    }
                    else if(rowCount < 1000 && rowCount>90){
                        newcell.childNodes[1].value = "0"+(rowCount * 10);
                    }
                    else if(rowCount < 10000 && rowCount>900){
                        newcell.childNodes[1].value = "0"+(rowCount * 10);
                    }
                }
                newcell.childNodes[1].name = newcell.childNodes[1].name;

                newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount ;

                if(newcell.childNodes[1].id == 'harga'+rowCount){
                    newcell.childNodes[1].value = "";
                }
            }
        }
        // bug bootstrap datepicker
        $(document).ready(function($) {
            $('#delivDate'+rowCount).removeClass("hasDatepicker");
            $('#delivDate'+rowCount).datepicker({
                autoclose: true
            });
            $('#smaterials'+rowCount).prop('disabled', true);

            $('#materials'+rowCount).prop('disabled', false);
            $('#orderNo'+rowCount).prop('disabled', false);
        });
    }

    function deleteBaris(tableID) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        if(rowCount>2){
            table.deleteRow(rowCount -1);
        }
    }

    function cloneRow(tableID) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var colCount = table.rows[1].cells.length;
        if(rowCount<10000){
            for(var i=0; i<colCount; i++) {
                var newcell = row.insertCell(i);
                newcell.innerHTML = table.rows[1].cells[i].innerHTML;

                newcell.childNodes[1].name = newcell.childNodes[1].name; //hilangin rowCount supaya bisa pakai name[] untuk keperluan input
                newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount ;

                if(newcell.childNodes[1].id == 'description'+rowCount || newcell.childNodes[1].id == 'quantity'+rowCount || newcell.childNodes[1].id == 'harga'+rowCount || newcell.childNodes[1].id == 'harga_parse'+rowCount || newcell.childNodes[1].id == 'amount'+rowCount || newcell.childNodes[1].id == 'amount_parse'+rowCount || newcell.childNodes[1].id == 'currency'+rowCount ){
                    newcell.childNodes[1].value = table.rows[1].cells[i].children[0].value;
                }

            }
        }

        var table = document.getElementById('reqForm');
        var rowCount = table.rows.length;
        var grandTotal = 0;

        for(var i = 1 ; i <rowCount ; i++){

            if(i == 1){
                numberRow = "";
            }
            else{
                numberRow = i;
            }
            amount = parseFloat(document.getElementById('amount'+numberRow).value);
            grandTotal = grandTotal + amount;
        }
        grandTotal = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('grandTotal').value = grandTotal;

    }


    function deleteAllRow(tableID) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length-1;

        for(var i = rowCount ; i>1 ; i--){

            if(i>1){

                table.deleteRow(i);
            }
        }
    }

    function disableButton2(idTag){
        var rowId = idTag.id;
        var idrow = rowId.substring(8, 16);

        var x = idTag.value;

        if(x.length > 2){
            document.getElementById('s'+rowId).removeAttribute("disabled");
        }
        else{
            document.getElementById('s'+rowId).setAttribute("disabled","disabled");
        }
    }

    function CalculateTotal(idTag){
        setTimeout(function(){
            var rowId = idTag.id;

            if(rowId.substring(0,8) == "quantity"){
                var idrow = rowId.substring(8, 16);
            }
            else if(rowId.substring(0,11) == "harga" || rowId.substring(0,11) == "harga_parse"){
                var idrow = rowId.substring(11, 17);
            }

            // console.log(idrow);

            quantity = parseFloat(document.getElementById('quantity'+idrow).value);
            price = parseFloat(document.getElementById('harga'+idrow).value) || 0;

            amount = quantity * price;
            amount2 = amount.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('amount'+idrow).value = amount;
            document.getElementById('amount_parse'+idrow).value = amount2;

            // console.log(amount);
            // console.log(amount2);

            var table = document.getElementById('reqForm');
            var rowCount = table.rows.length;
            var grandTotal = 0;

            for(var i = 1 ; i <rowCount ; i++){

                if(i == 1){
                    numberRow = "";
                }
                else{
                    numberRow = i;
                }
                amount = parseFloat(document.getElementById('amount'+numberRow).value);
                grandTotal = grandTotal + amount;
            }
            grandTotal = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('grandTotal').value = grandTotal;
        }, 1000);
    }

    function keyUpCurrency(values, id){
        var idx = "";
        var valuesWithFormat = values.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        var valuesWithoutFormat = valuesWithFormat;
        if(valuesWithFormat.includes(",") == true)
        {
            valuesWithoutFormat = valuesWithFormat.split(",").join("");
        }

        $('#'+id).val(valuesWithFormat);

        // console.log(valuesWithFormat);
        if(id.substring(0,11) == "harga" || id.substring(0,11) == "harga_parse"){
            var idx = id.substring(11, 20);
        }
        $('#harga'+idx).val(valuesWithoutFormat);
    }

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

    /*****************************************************************************/
    /*****************************************************************************/
    /* END FUNCTION DARI INTRANET BIZNET*/
    /*****************************************************************************/
    /*****************************************************************************/

    $(document).ready( function () {


        $('#delivDate').datepicker({
            autoclose: true
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


        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "pageLength": 100,
            // "lengthChange": true,
            "pageLength": 100,
            "responsive": true,
            "dom": '<"dt-buttons"Bfl>rtip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/cash-advance/request/getData",
                "dataSrc": "data",
                "data" : {
                    "employee_id":updateBy,
                    "filter":"",
                    "value":"",
                    "status":status,
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
            "columns": [
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        return '<a style="font-weight:bold;" href="{{url("finance/cash-advance/detail/")}}/'+id+'" >'+id+'</a>';
                    }
                },
                {
                    "data" : "UID",
                    "render": function (id, type, full, meta){
                        return '<a href="#" class="btn btn-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile" ><i class="fa fa-eye"></i></a>';
                    }
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id){
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
                    "render": function (id, type, full, meta)
                    {
                        $_name = id ? id : '-';
                        return $_name;
                    }
                },
                { "data": "INSERT_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                { "data": "JSON_ENCODE",
                    "render": function (id, type, full, meta)
                    {
                        var currency = id.hasOwnProperty('currency') && id.currency.length > 0 ? id.currency : [];
                        try {
                            currency = uniq_fast(currency).join(', ');
                        } catch(error){}
                        return currency;
                    }
                },
                { "data": "JSON_ENCODE",
                    "render": function (id, type, full, meta)
                    {
                        var grandTotal = id.hasOwnProperty('grandTotal') ? id.grandTotal : '-';
                        return grandTotal;
                    }
                }
                // },
                // { "data": "LAST_APPROVAL_NAME" },
                // { "data": "REASON" },
                // { "data": "TRACKING_NO" },
                // { "data": "TRACKING_DESC"},
                // { "data": "DOC_TYPE" },
                // { "data": 'PO_NUMBER' },
                // {
                //     "data" : "PO_NUMBER",
                //     "render": function (id, type, full, meta){
                //         if(id){
                //         return '<a href="#" class="btn btn-primary" onclick="getDetailPO(\''+id+'\')" data-toggle="modal" data-target="#modalPO" ><i class="fa fa-eye"></i></a>';
                //         }
                //         else {
                //             return '';
                //         }
                //     }
                // }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 0, "desc" ]],
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

        $.get("{{url('finance/cash-advance/detail')}}", { id : id, segment : segment}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
        });

    }

    function getDetailPO(id){
        var segment='request';
        $('#modalPO #bodymodalPO').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/cash-advance/modal-detail-po')}}", { id : id, segment : segment}, function( data ) {
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
            // console.log(data);
            if (data.success) {
                if (data.msg) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.msg,
                    }).then((result) =>{
                        window.location.href="/finance/cash-advance/detail/"+data.insert_id;
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
