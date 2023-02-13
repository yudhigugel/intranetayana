@extends('layouts.default')

@section('title', 'Request Entertainment Form')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/css/sweetalert.min.css">
@endsection
@section('styles')
<style>
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #ececec;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color:  #ececec;;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #ececec;;
}

.red{
    color:red !Important;
}

#section-3, #section-4, #section-5{
    display: none;
}


/* CSS Zoho Creator */
.zcform_Request_Form .first-column .form-label{
    width: 200px !important;
}
form.label-left {
    width:100% !important;
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
      <li class="breadcrumb-item"><a href="#">Entertainment Form</a></li>
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
                                        <option value="Requested" {{ ($data['status']=="Requested")? 'selected' : '' }} >Requested</option>
                                        <option value="Approved" {{ ($data['status']=="Approved")? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/entertainmentForm/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-striped table-bordered datatable requestList" id="requestList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Form No</th>
                            <th style="min-width:90px;">Requestor Name</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Request Date</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="min-width:90px;">Plant Name</th>
                            <th style="min-width:90px;">Department</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalRequest" tabindex="-1" role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalRequestLabel">Form Request Entertainment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodymodalRequest">
                <form action="{{url('finance/entertainmentForm/request/save')}}" method="POST" id="formRequest">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Request Number</label>
                                <input type="text" value="REQ-ENT-{{date('Y')}}-(auto number)" class="form-control" readonly />
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
                                <label>Requestor Company</label>
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

                            </div>
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{$data['job_title']}}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Client Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Company / Affiliation <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="Company_Affiliation" required placeholder="insert company/affiliation.."/>
                            </div>
                            <div class="col-md-6">
                                <label>Entertainment Reason <span class="red">*</span></label>
                                <input type="text" class="form-control" name="Entertainment_Reason" required placeholder="insert reason.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Name <span class="red">*</span></label>
                                <input type="text" class="form-control" name="Name" required id="Name" placeholder="insert name.."/>
                            </div>
                            <div class="col-md-6">
                                <label>Potential <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="Potential" required placeholder="insert potential.."/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="section-3">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Entertainment Type</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Entertainment Type <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="Entertainment_Type" value="In House">
                                      In House
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="Entertainment_Type" value="In Group">
                                      In Group
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="Entertainment_Type" value="Outside">
                                      Outside
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Entertainment Options <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="Entertainment_Options" value="F&B" checked>
                                      F&B
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="section-4">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Details</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Entertainment Date <span class="red">*</span></label>
                                <input type="text" name="Entertainment_Date" id="datepicker" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Entertainment Time <span class="red">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="timepicker" name="Entertainment_Time" onkeydown="event.preventDefault()" required>
                                    <div class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Location <span class="red">*</span></label><span id="spinnerLocation" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                                <select class="form-control" name="Location" required id="cmbLocation">
                                    <option value="" disabled selected>-Select-</option>
                                    @foreach ($data['form_location'] as $location)
                                    <option value="{{$location->LOCATION_ID}}">{{$location->LOCATION_NAME}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="Location_Alternative" id="Location_Alternative" style="display: none" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Type <span class="red">*</span></label>
                                <select class="form-control" name="Type" required>
                                    <option value="" disabled selected>-Select-</option>
                                    <option value="Dine In">Dine In</option>
                                    <option value="Take Away">Take Away</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label id="labelSBU">SBU <span class="red">*</span></label><span id="spinnerSBU" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                                <select class="form-control" name="SBU" required id="cmbSBU">
                                    <option value="" disabled selected>-Select-</option>
                                </select>
                                <label id="labelOutlet_Alternative" style="display:none;">Outlet <span class="red">*</span></label>
                                <input type="text" name="Outlet_Alternative" id="Outlet_Alternative" style="display: none" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>No. of Guests (Incl. Requestor) <span class="red">*</span></label>
                                <input type="number"  class="form-control" name="No_Guest" required placeholder="insert number of guest.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label id="labelOutlet">Outlet <span class="red">*</span></label><span id="spinnerOutlet" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                                <select class="form-control" name="Outlet" required id="cmbOutlet">
                                    <option value="" disabled selected>-Select-</option>
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label id="labelLimit_Estimated">Limit / Estimated Amount (IDR) <span class="red">*</span></label>
                                <input type="number"  class="form-control" id="Limit_Estimated"  name="Limit_Estimated" required placeholder="insert number of amount.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Benefit Entitlement <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="Benefit_Entitlement[]" value="Food">
                                      Food
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input"  name="Benefit_Entitlement[]" value="Alcohol Beverage">
                                      Alcohol Beverage
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input"  name="Benefit_Entitlement[]" value="Non Alcohol Beverage">
                                      Non Alcohol Beverage
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="section-5" style="display:none;">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Remarks</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Remarks</label>
                                <textarea name="Remarks" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">

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
				<h5 class="modal-title" id="modalFileLabel">Request Detail </h5>
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
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script src="/js/vendor/gijgo-master/dist/modular/js/core.js" type="text/javascript"></script>
<script src="/js/vendor/gijgo-master/dist/modular/js/timepicker.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
    new GijgoTimePicker(document.getElementById('timepicker'), { uiLibrary: 'bootstrap4' });
    var objFile = {};var objJsonDetail = {};

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
            "responsive": true,
            "dom": '<"dt-buttons"Bfli>rtp',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/entertainmentForm/request/getData",
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
                "width": "5%",
                "render": function (id, type, full, meta)
                    {
                        objJsonDetail[id] = full.JSON_ENCODE.replace(/&quot;/g,'"');

                        return '<a href="#" data-toggle="modal" style="font-weight:bold;" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    },   className: 'text-left'},
                { "data": "REQUESTOR_NAME", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        var data = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                        return data.Requestor_Name;

                    }
                },
                { "data": "STATUS_APPROVAL", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        if(id == null || id == "null"){ id = "REQUESTED"; }

                        if(id=="APPROVED" || id=="REQUESTED" || id==""){
                            id="WAITING";
                            return '<a href="javascript:void(0);" data-toggle="modal" style="font-weight:bold;" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')">WAITING</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="javascript:void(0);" data-toggle="modal" style="color:green;font-weight:bold;" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')">FINISHED</a>';
                        }else{
                            return '<a href="javascript:void(0);" data-toggle="modal" style="color:red;font-weight:bold;" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')">'+id+'</a>';
                        }
                    }
                },
                { "data": "INSERT_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return moment(id).format("DD/MM/YYYY - HH:mm");
                        }
                },
                { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
                { "data": "LAST_APPROVAL_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            if(id != null){
                                return moment(id).format("DD/MM/YYYY - HH:mm");
                            }
                            else{
                                return "";
                            }

                        }
                },
                { "data": "SAP_PLANT_NAME", className: 'text-left'},
                { "data": "DEPARTMENT_NAME", className: 'text-left'},
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
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');

        $.get("{{url('finance/entertainmentForm/modal-detail')}}", { id : id}, function( data ) {
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

    $('#modalRequest').on('hidden.bs.modal', function () {
        // location.reload();
    });

    $("#Name").bind("change paste keyup", function() {
        if(!$("#section-3").is(":visible")){
            $("#section-3").show();
        }
    });
    $('input:radio[name="Entertainment_Type"]').change(function(){
        if(!$("#section-4").is(":visible")){
            $("#section-4").show();
            $("#section-5").show();
        }

        $("#labelLimit_Estimated").show();
        $("#Limit_Estimated").val(0);
        $("#Limit_Estimated").attr('required','')
        $("#Limit_Estimated").show();

        var type=$(this).val();
        if(type=="In House"){
            var territory=$("#Requestor_Territory_ID").val();

            $("#labelLimit_Estimated").hide();
            $("#Limit_Estimated").val(0);
            $("#Limit_Estimated").removeAttr('required')
            $("#Limit_Estimated").hide();

            $.ajax({
                type:"GET",
                url : "{{ url('finance/entertainmentForm/request/ajaxEntType')}}",
                data : "territory="+territory,
                beforeSend: function() {
                    $('#spinnerLocation').show();
                    $('#spinnerSBU').show();
                    $('#spinnerOutlet').show();
                    $("#cmbSBU").html('<option value="" disabled selected>-Select-</option>');
                    $("#cmbOutlet").html('<option value="" disabled selected>-Select-</option>');
                    $("#cmbLocation").html('<option value="" disabled selected>-Select-</option>');

                    $("#cmbLocation").show();
                    $("#labelSBU").show();
                    $("#labelOutlet").show();
                    $("#cmbSBU").show();
                    $("#cmbOutlet").show();

                    $("#Location_Alternative").hide();
                    $("#Location_Alternative").val('');
                    $("#Location_Alternative").removeAttr('required');
                    $("#labelOutlet_Alternative").hide();
                    $("#Outlet_Alternative").hide();
                    $("#Outlet_Alternative").val('');
                    $("#Outlet_Alternative").removeAttr('required');
                },
                complete: function(){
                    $('#spinnerLocation').hide();
                    $('#spinnerSBU').hide();
                    $('#spinnerOutlet').hide();
                },
                success : function(response) {
                    $("#cmbLocation").html(response);
                },
                error: function() {
                    alert('Error occured');
                }
            });
        }else if(type=="In Group"){
            $.ajax({
                type:"GET",
                url : "{{ url('finance/entertainmentForm/request/ajaxEntType')}}",
                data : "",
                beforeSend: function() {
                    $('#spinnerLocation').show();
                    $('#spinnerSBU').show();
                    $('#spinnerOutlet').show();
                    $("#cmbSBU").html('<option value="" disabled selected>-Select-</option>');
                    $("#cmbOutlet").html('<option value="" disabled selected>-Select-</option>');
                    $("#cmbLocation").html('<option value="" disabled selected>-Select-</option>');

                    $("#cmbLocation").show();
                    $("#labelSBU").show();
                    $("#labelOutlet").show();
                    $("#cmbSBU").show();
                    $("#cmbOutlet").show();

                    $("#cmbLocation").attr('required','');
                    $("#cmbSBU").attr('required','');
                    $("#cmbOutlet").attr('required','');

                    $("#Location_Alternative").hide();
                    $("#Location_Alternative").val('');
                    $("#Location_Alternative").removeAttr('required');
                    $("#labelOutlet_Alternative").hide();
                    $("#Outlet_Alternative").hide();
                    $("#Outlet_Alternative").val('');
                    $("#Outlet_Alternative").removeAttr('required');
                },
                complete: function(){
                    $('#spinnerLocation').hide();
                    $('#spinnerSBU').hide();
                    $('#spinnerOutlet').hide();
                },
                success : function(response) {
                    $("#cmbLocation").html(response);
                },
                error: function() {
                    alert('Error occured');
                }
            });

        }else if(type=="Outside"){
            $('#spinnerLocation').show();
            $('#spinnerSBU').show();
            $('#spinnerOutlet').show();

            $("#cmbSBU").html('<option value="" disabled selected>-Select-</option>');
            $("#cmbOutlet").html('<option value="" disabled selected>-Select-</option>');
            $("#cmbLocation").html('<option value="" disabled selected>-Select-</option>');

            $('#spinnerLocation').hide();
            $('#spinnerSBU').hide();
            $('#spinnerOutlet').hide();

            $("#cmbLocation").hide();
            $("#labelSBU").hide();
            $("#labelOutlet").hide();
            $("#cmbSBU").hide();
            $("#cmbOutlet").hide();

            $("#cmbLocation").removeAttr('required');
            $("#cmbSBU").removeAttr('required');
            $("#cmbOutlet").removeAttr('required');

            $("#Location_Alternative").show();
            $("#Location_Alternative").attr('required','');
            $("#labelOutlet_Alternative").show();
            $("#Outlet_Alternative").show();
            $("#Outlet_Alternative").attr('required','');




        }
    });

    $('#cmbLocation').change(function() {

        $.ajax({
            type:"GET",
            url : "{{ url('finance/entertainmentForm/request/ajaxLocation')}}",
            data : "id="+$(this).val(),
            beforeSend: function() {
                $('#spinnerSBU').show();
                $('#spinnerOutlet').show();
                $("#cmbSBU").html('<option value="" disabled selected>-Select-</option>');
                $("#cmbOutlet").html('<option value="" disabled selected>-Select-</option>');
            },
            complete: function(){
                $('#spinnerSBU').hide();
                $('#spinnerOutlet').hide();
            },
            success : function(response) {

                $("#cmbSBU").html(response);
            },
            error: function() {
                alert('Error occured');
            }
        });
    });
    $('#cmbSBU').change(function() {
        $.ajax({
            type:"GET",
            url : "{{ url('finance/entertainmentForm/request/ajaxSBU')}}",
            data : "id="+$(this).val(),
            beforeSend: function() {
                $('#spinnerOutlet').show();
                $("#cmbOutlet").html('<option value="" disabled selected>-Select-</option>');
            },
            complete: function(){
                $('#spinnerOutlet').hide();
            },
            success : function(response) {

                $("#cmbOutlet").html(response);
            },
            error: function() {
                alert('Error occured');
            }
        });
    });

    $("#formRequest").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                if(data.code == "200")
                {
                    $('#modalRequest').modal('hide');
                    swal({
                        title: "Success",
                        text: "Your Request Has Been Sent",
                        type: "success",
                        closeOnConfirm: false
                        }, function () {
                            swal.close();
                            location.reload();
                        });
                    }
                    else
                    {
                        $('#modalRequest').modal('hide');
                        swal("Error", data.message, "error");

                    }
                }
        });


    });




</script>
@endsection
