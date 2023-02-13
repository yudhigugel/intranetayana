@extends('layouts.default')

@section('title', 'Request Material Master')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
{{-- <link rel="stylesheet" href="/css/sweetalert.min.css"> --}}
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
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Add Material Master</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Request List</h4>
                        @if ($data['allow_add_request'] || $data['is_cross_plant_user'])
                        {{--<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalRequest">Add Request</button>--}}
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalMaterialType">Add Request</button>
                        @endif

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
                                        <option value="Approved" {{ ($data['status']=="Approved")? 'selected' : '' }}>Waiting for Approval</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                        <option value="Finished" {{ ($data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/add-material-master/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Form No</th>
                            <th style="min-width:90px;">Requestor Name</th>
                            <th style="min-width:90px;">Material Name</th>
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
<div class="modal fade" id="modalRequest" role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalRequestLabel">Form - Add Material Master</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodymodalRequest">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/add-material-master/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                <input type="hidden" name="Is_Recipe" value="N">
                                <input type="text" value="MTRL-(auto number)" class="form-control" readonly/>
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
                                <div class="mb-2">
                                    <label>Request POS Status <span class="red">*</span></label>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="toggle-switch">
                                            <input required type="radio" name="pos_type" value="POS">
                                            <span class="toggle-slider round"></span>
                                        </label>
                                        <small class="ml-2">POS</small>
                                    </div>
                                    <div class="col-6">
                                        <label class="toggle-switch">
                                            <input type="radio" name="pos_type" value="NONPOS">
                                            <span class="toggle-slider round"></span>
                                        </label>
                                        <small class="ml-2">Non POS</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{$data['job_title']}}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                    @if ($data['is_cross_plant_user'])
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Request for other Plant
                            <label class="toggle-switch toggle-switch-success">
                                <input type="checkbox" id="togglePlant">
                                <span class="toggle-slider round"></span>
                            </label>
                        </h3>
                    </div>
                    <div class="form-group formPlant" id="formPlant" style="display: none">
                        <div class="row mb-3">
                            <div class="col-md-4 float-left">
                                <label >Plant <span class="red">*</span></label>
                                <select name="custom_plant" class="form-control select2 col-md-12" id="custom_plant" style="width:100%;">
                                    <option value="" selected disabled> -- Select Plant --</option>
                                    @foreach ($data['list_plant'] as $list_plant)
                                        <option value="{{$list_plant->SAP_PLANT_ID}}"> {{$list_plant->SAP_PLANT_ID}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 float-left">
                                <label>Cost Center <span class="red">*</span></label>
                                <select name="custom_cost_center" class="form-control select2" id="custom_cost_center" style="width:100%;" >
                                    <option value="" selected disabled> -- Select Plant First --</option>
                                </select>
                            </div>
                            <div class="col-md-4 float-left">
                                <label>Midjob Title <span class="red">*</span></label>
                                <select name="custom_midjob" class="form-control select2" id="custom_midjob" style="width:100%;" >
                                    <option value="" selected disabled> -- Select Cost Center First--</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    @endif

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Material Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Material Name <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="material_name" required placeholder="insert material name.." maxlength="40" style="text-transform: uppercase" />
                                <small class="text-muted"><i>Note: Maximum length is 40 character (including spaces)</i></small>
                            </div>
                            <div class="col-md-6 dropdownParentGroupUnit">
                                <label>Material Unit <span class="red">*</span></label>
                                <select name="material_unit" id="material_unit" style="width: 100%" required class="form-control material-unit">
                                    <option selected default value="">---- Choose Data ----</option>
                                    @foreach ($data['material_unit'] as $mt_unit)
                                        <option value="{{strtoupper($mt_unit['EX_UOM'])}}">{{strtoupper($mt_unit['EX_UOM'])}} - {{strtoupper($mt_unit['UOM_DESC'])}}</option>
                                    @endforeach
                                </select>
                                
                                {{-- <input type="text" class="form-control" name="material_unit" required placeholder="insert material unit.." maxlength="40" style="text-transform: uppercase"/> --}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Request Notes</label>
                                <textarea maxlength="40" class="form-control" style="text-transform: uppercase" rows="2" name="request_notes" placeholder="insert request notes here.."></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Number </label>
                                <input type="text" class="form-control" placeholder="" name="material_number" value="" readonly/>
                                <small class="form-text text-muted">Filled by Master Data IT</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Type</label>

                                <select class="form-control select-2-modal" name="material_type" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_type'] as $material_type)
                                        <option value="{{$material_type['MATERIAL_TYPE']}}-{{$material_type['MATERIAL_TYPE_DESC']}}">{{$material_type['MATERIAL_TYPE']}} - {{$material_type['MATERIAL_TYPE_DESC']}}</option>
                                    @endforeach --}}
                                  </select>
                                  <small class="form-text text-muted">Filled by Accounting</small>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Group </label>
                                <select class="form-control select-2-modal" name="material_group" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_group'] as $material_group)
                                        <option value="{{$material_group['MATKL']}}-{{$material_group['WGBEZ']}}">{{$material_group['MATKL']}} - {{$material_group['WGBEZ']}}</option>
                                    @endforeach --}}
                                  </select>
                                <small class="form-text text-muted">Filled by Accounting</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Valuation Class </label>
                                <select class="form-control select-2-modal" name="material_valuation" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_valuation'] as $material_valuation)
                                        <option value="{{$material_valuation['BKLAS']}}-{{$material_valuation['BKBEZ']}}">{{$material_valuation['BKLAS']}} - {{$material_valuation['BKBEZ']}}</option>
                                    @endforeach --}}
                                  </select>
                                <small class="form-text text-muted">Filled by Accounting</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Image </label>
                                <input type="file" name="material_image" class="form-control" id="material_image">
                                <small class="form-text text-muted">You can only upload 1 image (e.g. jpg, jpeg, png, pdf)</small>
                            </div>
                        </div>
                        {{-- <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Quotation File <span class="red">*</span></label>
                                <input type="file" name="quotation" class="form-control" id="quotation" required>
                                <small class="form-text text-muted">You can only upload 1 file</small>
                            </div>
                        </div> --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="form-control btn btn-success btn-submit text-white">Send Request</button>
                            </div>
                        </div>
                    </div>

                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFile"  role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
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
<div class="modal fade" id="modalHistory" role="dialog" aria-labelledby="modalHistoryLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalHistoryLabel">History List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodyModalHistory">
                <table class="table table-bordered datatable requestHistoryList" id="requestHistoryList" style="">
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
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">

<div class="modal fade" id="modalMaterialType"  role="dialog" aria-labelledby="modalMaterialTypeLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFileLabel">Material Request Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalMaterialType">
                <div class="row">
                    <div class="mb-2 col-12">
                        <h6 class="text-muted">Please choose one option below to continue : </h6>
                    </div>
                    <div class="col-6">
                        <button type="button" data-dismiss="modal" class="btn btn-primary btn-block" onclick="triggerModal(this)" data-target="#modalRequestNew">Recipe</button>
                    </div>
                    <div class="col-6">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-block" onclick="triggerModal(this)" data-target="#modalRequest">Material</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalRequestNew" role="dialog" aria-labelledby="modalRequestNewLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRequestLabel">Form - Add Material Master (Recipe)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalRequest">
                <form method="POST" id="formRequestNew" enctype="multipart/form-data" data-url-post="{{url('finance/add-material-master/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                <input type="hidden" name="Is_Recipe" value="Y">
                                <input type="text" value="MTRLRCP-(auto number)" class="form-control" readonly/>
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
                                <div class="mb-2">
                                    <label>Request POS Status <span class="red">*</span></label>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="toggle-switch">
                                            <input required type="radio" name="pos_type" value="POS">
                                            <span class="toggle-slider round"></span>
                                        </label>
                                        <small class="ml-2">POS</small>
                                    </div>
                                    <div class="col-6">
                                        <label class="toggle-switch">
                                            <input type="radio" name="pos_type" value="NONPOS">
                                            <span class="toggle-slider round"></span>
                                        </label>
                                        <small class="ml-2">Non POS</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{$data['job_title']}}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                    @if ($data['is_cross_plant_user'])
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Request for other Plant
                            <label class="toggle-switch toggle-switch-success">
                                <input type="checkbox" id="togglePlant">
                                <span class="toggle-slider round"></span>
                            </label>
                        </h3>
                    </div>
                    <div class="form-group formPlant" id="formPlant" style="display: none">
                        <div class="row mb-3">
                            <div class="col-md-4 float-left">
                                <label >Plant <span class="red">*</span></label>
                                <select name="custom_plant" class="form-control select2 col-md-12" id="custom_plant" style="width:100%;">
                                    <option value="" selected disabled> -- Select Plant --</option>
                                    @foreach ($data['list_plant'] as $list_plant)
                                        <option value="{{$list_plant->SAP_PLANT_ID}}"> {{$list_plant->SAP_PLANT_ID}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 float-left">
                                <label>Cost Center <span class="red">*</span></label>
                                <select name="custom_cost_center" class="form-control select2" id="custom_cost_center" style="width:100%;" >
                                    <option value="" selected disabled> -- Select Plant First --</option>
                                </select>
                            </div>
                            <div class="col-md-4 float-left">
                                <label>Midjob Title <span class="red">*</span></label>
                                <select name="custom_midjob" class="form-control select2" id="custom_midjob" style="width:100%;" >
                                    <option value="" selected disabled> -- Select Cost Center First--</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    @endif

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Material Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Material Name <span class="red">*</span></label>
                                <div class="mb-1">
                                    <input type="text"  class="form-control" name="material_name" required placeholder="insert material name.." maxlength="40" style="text-transform: uppercase" />
                                </div>
                                <small class="text-muted"><i>Note: Maximum length is 40 character (including spaces)</i></small>
                            </div>
                            <div class="col-md-6 dropdownParentGroupUnit">
                                <label>Material Unit <span class="red">*</span></label>
                                <select name="material_unit" id="material_unit" style="width: 100%" required class="form-control material-unit">
                                    <option selected default value="">---- Choose Data ----</option>
                                    @foreach ($data['material_unit'] as $mt_unit)
                                        <option value="{{strtoupper($mt_unit['EX_UOM'])}}">{{strtoupper($mt_unit['EX_UOM'])}} - {{strtoupper($mt_unit['UOM_DESC'])}}</option>
                                    @endforeach
                                </select>

                                {{--<input type="text" class="form-control" name="material_unit" required placeholder="insert material unit.." maxlength="40" style="text-transform: uppercase"/>--}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Request Notes</label>
                                <textarea maxlength="40" class="form-control" style="text-transform: uppercase" rows="2" name="request_notes" placeholder="insert request notes here.."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Material Type Request <span class="red">*</span></label>
                                <div class="row py-3">
                                    @if(isset($data['material_type_recipe']))
                                        @php
                                            $required = 0;
                                        @endphp
                                        @foreach($data['material_type_recipe'] as $mtr)
                                        <div class="col-6 mb-2">
                                            <label class="toggle-switch">
                                                <input @if($required == 0) required @endif type="radio" id="mtype-{{ isset($mtr['MATERIAL_TYPE']) ? $mtr['MATERIAL_TYPE'] : rand(0,10) }}" name="material_type_request" value="{{ isset($mtr['MATERIAL_TYPE']) ? $mtr['MATERIAL_TYPE'] : '0' }}">
                                                <span class="toggle-slider round"></span>
                                            </label>
                                            <small class="ml-2">{{ isset($mtr['MATERIAL_TYPE_DESC']) ? $mtr['MATERIAL_TYPE_DESC'] : 'Unknown' }}</small>
                                        </div>
                                        @php
                                            $required++;
                                        @endphp
                                        @endforeach
                                    @else 
                                    <div class="col-12">
                                        <h6>No material type data available</h6>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 dropdownParentGroup">
                                <label style="position: relative;">
                                    Material Group Request <span class="red mr-3">*</span>
                                    <div style="position: absolute;top: -7px;right: -25px;">
                                        <small class="text-muted loading-mat-group" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}"></small>
                                    </div>
                                </label>
                                <div class="mb-1">
                                    <select class="form-control select2" id="material_group_request_select" style="width: 100%" required name="material_group_request">
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                                <small class="text-muted">Material group will dynamically change based on material type selection</small>
                            </div>
                        </div>                       

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Number </label>
                                <input type="text" class="form-control" placeholder="" name="material_number" value="" readonly/>
                                <small class="form-text text-muted">Filled by Master Data IT</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Type</label>

                                <select class="form-control select-2-modal" name="material_type" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_type'] as $material_type)
                                        <option value="{{$material_type['MATERIAL_TYPE']}}-{{$material_type['MATERIAL_TYPE_DESC']}}">{{$material_type['MATERIAL_TYPE']}} - {{$material_type['MATERIAL_TYPE_DESC']}}</option>
                                    @endforeach --}}
                                  </select>
                                  <small class="form-text text-muted">Filled by Accounting</small>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Group </label>
                                <select class="form-control select-2-modal" name="material_group" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_group'] as $material_group)
                                        <option value="{{$material_group['MATKL']}}-{{$material_group['WGBEZ']}}">{{$material_group['MATKL']}} - {{$material_group['WGBEZ']}}</option>
                                    @endforeach --}}
                                  </select>
                                <small class="form-text text-muted">Filled by Accounting</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Valuation Class </label>
                                <select class="form-control select-2-modal" name="material_valuation" readonly>
                                    <option value="" selected disabled> -- To be filled -- </option>
                                    {{-- @foreach ($data['material_valuation'] as $material_valuation)
                                        <option value="{{$material_valuation['BKLAS']}}-{{$material_valuation['BKBEZ']}}">{{$material_valuation['BKLAS']}} - {{$material_valuation['BKBEZ']}}</option>
                                    @endforeach --}}
                                  </select>
                                <small class="form-text text-muted">Filled by Accounting</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Material Image </label>
                                <input type="file" name="material_image" class="form-control" id="material_image">
                                <small class="form-text text-muted">You can only upload 1 image (e.g. jpg, jpeg, png, pdf)</small>
                            </div>
                        </div>
                        {{-- <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Quotation File <span class="red">*</span></label>
                                <input type="file" name="quotation" class="form-control" id="quotation" required>
                                <small class="form-text text-muted">You can only upload 1 file</small>
                            </div>
                        </div> --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="form-control btn btn-success btn-submit text-white">Send Request</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

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

    $('#modalRequest, #modalRequestNew').on('show.bs.modal', function (e) {
        try {
            var modal = this;
            var mtype_recipe = $('input[name="material_type_request"]', this);
            if(mtype_recipe.length > 0){
                $.each(mtype_recipe, function(index, val){
                    val.checked = false;
                })
            }
            var mtype_pos = $('input[name="pos_type"]', this);
            if(mtype_pos.length > 0){
                $.each(mtype_pos, function(index, val){
                    val.checked = false;
                })
            }

            $('#material_group_request_select').html('<option value="" selected disabled></option>');
            $("#material_group_request_select").select2({
                placeholder: "Select an option",
                allowClear: true,
                dropdownParent: $(".dropdownParentGroup")
            });

            try {
                $(".material-unit").select2('destroy');
            } catch(error){}
            $(".material-unit").select2({
                placeholder: "Select an option",
                allowClear: true,
                dropdownParent: $('.dropdownParentGroupUnit', modal)
            });
        } catch(error){}
    });

    function triggerModal(el){
        try {
            setTimeout(function(){
                $(`${el.getAttribute('data-target')}`).modal('show');
            }, 500);
        } catch(error){}
    }

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

        $("#togglePlant").change(function(){
            if(this.checked){
                $(".formPlant").show();

                $("#custom_plant").attr('required','');
                $('#custom_plant').select2('destroy').select2();
                $("#custom_cost_center").attr('required','');
                $('#custom_cost_center').select2('destroy').select2();
                $("#custom_midjob").attr('required','');
                $('#custom_midjob').select2('destroy').select2();
            }else{
                $(".formPlant").hide();
                $("#custom_plant").removeAttr('required');
                $("#custom_cost_center").removeAttr('required');
                $("#custom_midjob").removeAttr('required');
                clear_form_elements('formPlant');
            }

        });

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


        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            "dom": 'l<"dt-buttons"B>frtip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/add-material-master/request/getData",
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
            "autoWidth": true,
            'info':true,
            "fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "columns": [
                { "data": "UID",
                "render": function (id, type, full, meta)
                    {
                        objJsonDetail[id] = full.JSON_ENCODE.replace(/&quot;/g,'"');
                        objRow[id] = full;
                        return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    },   className: 'text-left'},
                { "data": "REQUESTOR_NAME", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        var data = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                        return data.Requestor_Name;

                    }
                },
                { "data": "MATERIAL_NAME", className: 'text-left'
                },
                { "data": "STATUS_APPROVAL", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {

                        if(id == null || id == "null"){ id = "REQUESTED"; }
                        if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}



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
                { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
                { "data": "LAST_APPROVAL_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            if(id != null){
                                return type === 'export-excel' ? moment(id).format("YYYY-MM-DD HH:mm") : moment(id).format("MM/DD/YYYY - HH:mm");
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
            "order": [[ 4, "desc" ]],
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };

        $("#material_group_request_select").select2({
            placeholder: "Select an option",
            allowClear: true,
        });

        // Selection on recipe type request material
        $('input[name="material_type_request"]').on('change', function(e){
            try {
                var data = this.value;
                var refer_to = this;

                $('.loading-mat-group').prop('hidden', false);
                $('input[name="material_type_request"]').not(refer_to).prop('disabled', true);

                try {
                    $('#material_group_request_select').select2('destroy').html('<option value="" selected disabled></option>');
                    $("#material_group_request_select").select2({
                        placeholder: "Select an option",
                        allowClear: true,
                        dropdownParent: $(".dropdownParentGroup")
                    });
                } catch(error){}

                $.ajax({
                    url : '/finance/add-material-master/modal-approve-detail',
                    type : 'GET',
                    contentType : 'application/json',
                    dataType : 'json',
                    data : {'material_type': data, 'refer': 'mtrl_req'},
                    success : function(response){
                      var newOptionMatGroup = [];

                      if(response.hasOwnProperty('MAT_GROUP') && response.MAT_GROUP && response.MAT_GROUP.length){
                        $.each(response.MAT_GROUP, function(index, data){
                          newOptionMatGroup[index] = new Option(`${data.MATKL} - ${data.WGBEZ}`, `${data.MATKL}-${data.WGBEZ}`, false, false);
                        });
                      }

                      setTimeout(function(){
                        $('#material_group_request_select').append(newOptionMatGroup).trigger('change');
                        // $('#material_group_request_select').select2('open');
                      },100)

                    },
                    error : function(xhr){
                      Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
                      console.log("EXCEPTION OCCURED SELECTION MATERIAL TYPE")
                    },
                    complete : function(){
                      $('input[name="material_type_request"]').not(refer_to).prop('disabled', false);
                      $('.loading-mat-group').prop('hidden', true);
                    }
                });

            } catch(error){
                Swal.fire('Error Fetch Data', 'Failed to fetch material group data, something might be wrong. Please try again later', 'error');
            }
        })
    });
    function getFormDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');
        var url_asset="{{url('upload/material')}}";

        $.get("{{url('finance/add-material-master/modal-detail')}}", { id : id}, function( data ) {
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
            url: '/finance/add-material-master/getHistoryApproval',
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




    $("#formRequest").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

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

    $("#formRequestNew").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url_post=$("#formRequestNew").attr('data-url-post');
        var loader=$("#formRequestNew").attr('data-loader-file');
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

    $('.btn-submit').on('click', function(e){
        try {
            var radio_pos_type = $(this).parents('.modal').find('input[name="pos_type"]');
            if(radio_pos_type.length){
                var selected_pos_type = 0;
                $.each(radio_pos_type, function(index, elem){
                    if(this.checked === true){
                        selected_pos_type++;
                    }
                });
                if(selected_pos_type <= 0){
                    e.preventDefault();
                    Swal.fire('POS Type Selection', 'Field is required. Please select one of the options whether request is POS or NON POS', 'warning');
                    return false;
                }
            }

            var radio_material_type = $(this).parents('.modal').find('input[name="material_type_request"]');
            if(radio_material_type.length > 0){
                var selected_material_type = 0;
                $.each(radio_material_type, function(index, elem){
                    if(this.checked === true){
                        selected_material_type++;
                    }
                });
                if(selected_material_type <= 0){
                    e.preventDefault();
                    Swal.fire('Material Type Selection', 'Field is required. Please select one of the options for material type request', 'warning');
                    return false;
                }
            }
        } catch(error){
            e.preventDefault();
            Swal.fire('Selection Error', 'Failed to process selection, something might be wrong. Please try again later', 'error');
            return false;
        }
        return true;
    })

    $("#custom_plant").change(function(){
        var plant=$(this).val();
        if(plant){
            $.ajax({
                type: "POST",
                url: "{{url('finance/add-material-master/ajax/getCostCenterCustom')}}",
                data: { plant : plant},
                beforeSend: function() {
                    $("#custom_cost_center").attr('disabled','disabled');
                },
                success: function(data) {
                    var hasil= JSON.parse(data);
                    if(hasil){
                        var parse="";
                        hasil.forEach(element => {
                            parse += "<option value='"+element.COST_CENTER+"'>"+element.COST_CENTER+" - "+element.DESCRIPTION+"</option>";
                        });

                        var ele = $("#custom_cost_center");
                        ele.html("<option value='' selected >Select Cost Center</option>");
                        ele.append(parse);
                    }
                    $("#custom_cost_center").removeAttr('disabled');

                },
                error: function(err) {
                    Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text : err && err.responseJSON && err.responseJSON.message
                    });

                }
            });

        }
    });

    $("#custom_cost_center").change(function(){
        var cost_center=$(this).val();
        if(cost_center){
            $.ajax({
                type: "POST",
                url: "{{url('finance/add-material-master/ajax/getMidjobCustom')}}",
                data: { cost_center : cost_center},
                beforeSend: function() {
                    $("#custom_midjob").attr('disabled','disabled');
                },
                success: function(data) {
                    var hasil= JSON.parse(data);
                    if(hasil){
                        var parse="";
                        hasil.forEach(element => {
                            parse += "<option value='"+element.MIDJOB+"'>"+element.MIDJOB+" - "+element.DESCRIPTION+"</option>";
                        });

                        var ele = $("#custom_midjob");
                        ele.html("<option value='' selected >Select Midjob</option>");
                        ele.append(parse);
                    }
                    $("#custom_midjob").removeAttr('disabled');

                },
                error: function(err) {
                    Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text : err && err.responseJSON && err.responseJSON.message
                    });

                }
            });

        }
    })




</script>
@endsection
