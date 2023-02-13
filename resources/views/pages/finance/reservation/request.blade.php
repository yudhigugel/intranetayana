@extends('layouts.default')

@section('title', 'Request Reservation')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}">
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
  color:  #a7afb7;
}

.red{
    color:red !Important;
}

#section-3, #section-4, #section-5{
    display: none;
}
#select2-materials-results,
#select2-sloc-results {
    max-height: 120px !important;
}
/* CSS Zoho Creator */
.zcform_Request_Form .first-column .form-label{
    width: 200px !important;
}
form.label-left {
    width:100% !important;
}
.table-container-h {
    overflow: auto;
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
.dataTables_info {
    float: left;
}
.dataTables_paginate{
    margin-top: 10px !important;
}
/*.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(34px) !important;
}
.toggle-switch {
    width: 60px;
}*/
tr.rowToClone td {
    padding: 0.3rem 0.3rem;
    border: none !important;
}
.select2-results__options,
.select2-selection {
    text-align: left;
}
.dataTables_wrapper {
    position: relative;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Add Reservation</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li>
    </ol>
</nav>
<div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Request List</h4>
                        @if ($data['allow_add_request'])
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalRequestReservation">Add Request</button>
                        @endif

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Request For Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ isset($data['request_date_from']) ? $data['request_date_from'] : '' }}">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ isset($data['request_date_to']) ? $data['request_date_to'] : '' }}">
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
                                        {{--<option value="All" {{ (isset($data['status']) && $data['status'] == 'All') ? 'selected' : '' }}>All</option>--}}
                                        <option value="" {{ (isset($data['status']) && empty($data['status'])) ? 'selected' : '' }}>All</option>
                                        {{--<option value="Requested" {{ (isset($data['status']) && $data['status']=="Requested")? 'selected' : '' }} >Requested</option>--}}
                                        <option value="Requested" {{ (isset($data['status']) && $data['status']=="Requested")? 'selected' : '' }}>Waiting for Approval</option>
                                        <option value="Rejected" {{ (isset($data['status']) && $data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                        <option value="Finished" {{ (isset($data['status']) && $data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/add-reservation/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap; width: 100%">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Form No</th>
                            <th style="min-width:90px;">SAP Reservation No.</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Request For Date</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="min-width:90px;">Grand Total</th>
                            <th style="min-width:90px;">Movement Type</th>
                            <th style="min-width:90px;">Receiving SLOC / Cost Center</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalRequestReservation" tabindex="" role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRequestLabel">Form - Add Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalRequest">
                <form method="POST" style="position: relative;" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/add-reservation/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
                    <div class="material-sloc-check" style="position: absolute;top: 0; left: 0;width: 100%;height: 100%;background: #ffffffde;z-index: 5" hidden>
                        <div style="display: flex;justify-content: center;align-items: center;height: 100%; flex-direction: column;">
                            <div class="mb-2 text-center d-block">
                                <img style="max-width: 15%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                            </div>
                            <h6>Checking material availability ...</h6>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px; cursor: pointer;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Requestor Information <small class="text-primary" style="font-size: 10px;display: inline-block;margin-left: 5px;">Click here for more info...</small></h3>
                        </div>
                        <div id="collapseOne" class="collapse">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Requestor Name</label>
                                    <input type="text" value="{{ isset($data['employee_name']) ? $data['employee_name'] : '' }}" name="Requestor_Name" class="form-control" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Company</label>
                                    <input type="text" value="{{ isset($data['plant_name']) ? $data['plant_name'] : '' }}" name="Requestor_Company" class="form-control" readonly />
                                    <input type="hidden" value="{{ isset($data['company_code']) ? $data['company_code'] : '' }}" id="Requestor_Company_Code">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Requestor Employee ID</label>
                                    <input type="text" value="{{ isset($data['employee_id']) ? $data['employee_id'] : '' }}" name="Requestor_Employee_ID" class="form-control" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Territory</label>
                                    <input type="text" value="{{ isset($data['territory_name']) ? $data['territory_name'] : '' }}" name="Requestor_Territory" class="form-control" readonly />
                                    <input type="hidden" name="Requestor_Territory_ID" value="{{ isset($data['territory_id']) ? $data['territory_id'] : '' }}" id="Requestor_Territory_ID">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Requestor Cost Center ID</label>
                                    <input type="text" value="{{ isset($data['cost_center_id']) ? $data['cost_center_id'] : '' }}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Department</label>
                                    <input type="text" value="{{ isset($data['department']) ? $data['department'] : '' }}" name="Requestor_Department" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Requestor Plant ID</label>
                                    <input type="text" value="{{ isset($data['plant']) ? $data['plant'] : '' }}" name="Requestor_Plant_ID" id="Requestor_Plant_ID" class="form-control" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Division</label>
                                    <input type="text" value="{{ isset($data['division']) ? $data['division'] : '' }}" name="Requestor_Division" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Job Position</label>
                                    <input type="text" value="{{ isset($data['job_title']) ? $data['job_title'] : '' }}" name="Requestor_Job_Title" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Request For Date</label>
                                <input type="text" name="CreatedDate" value="{{date('d F Y')}}" class="form-control datepicker3"/>
                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                <input type="text" name="FormNumber" value="{{ isset($data['form_code']) ? $data['form_code'] : 'UNKNOWN' }}-(auto number)" class="form-control" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Data Information</h3>
                        <div class="row mb-3">
                            {{--<div class="col-md-1">
                                <label>Check Date</label>
                                <label class="toggle-switch mt-2">
                                    <input type="checkbox" id="check-date" name="CheckDate" value="Y" checked>
                                    <span class="toggle-slider round"></span>
                                </label>
                            </div>--}}
                            <input type="hidden" id="check-date" name="CheckDate" value="Y">

                            <div class="col-md-4">
                                <label>Movement Type <span class="red">*</span></label>
                                <select required class="select2 select-decorated form-control" name="MovementType" id="movementType" style="width: 100%">
                                    <option value="" selected default>---- Choose Data ----</option>
                                    @if(isset($data['movement_type']) && count($data['movement_type']) > 0)
                                        @foreach($data['movement_type'] as $key_mv => $val_mv)
                                            <option value="{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE : '' }}">{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE. ' - ' : '' }} {{ isset($val_mv->MV_DESCRIPTION) ? "(".$val_mv->MV_DESCRIPTION.")" : '' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Request Note <span class="red">*</span></label>
                                <input type="text" class="form-control" style="padding: 0.78rem 1.375rem" name="RequestNote" required placeholder="Insert reservation notes here.."/>
                            </div>
                            <div class="col-md-4">
                                <label>Recipient Name <span class="red">*</span></label>
                                <input type="text" class="form-control" style="padding: 0.78rem 1.375rem" name="Recipient" value="{{ isset($data['employee_name']) ? $data['employee_name'] : '' }}" readonly placeholder="Insert goods recipient name.."/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="rsvPlantSlocContainer" hidden>
                        <div class="row">
                            <div class="col-md-2">
                                <label>Receiving Plant <span class="red">*</span></label>
                                <div style="position: relative;">
                                    <select class="form-control select2 select-decorated" name="rsvReceivingPlant" id="receiving_plant" disabled style="width: 100%">
                                        <option value="" default selected>---- Choose Plant ----</option>
                                        @if(isset($data['plant_list']) && count($data['plant_list']) > 0)
                                            @foreach($data['plant_list'] as $key => $val)
                                                <option value="{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : '' }}">{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : 'Unknown Plant' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="spinner-receiving-plant" style="position: absolute; top: 13px; right: 30px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <label>Receiving SLOC <span class="red">*</span></label>
                                <select required class="form-control select2 select-decorated" name="rsvReceivingPlantSLOC" id="receiving_plant_sloc" disabled style="width: 100%">
                                    <option value="" default selected>---- Choose Sloc ----</option>
                                </select>
                                <small class="text-muted">Note : Please select receiving plant first to show available store location</small>
                            </div>
                            <input type="hidden" name="isPlantToPlantReceive" id="isPlantToPlantReceive" value="Y">
                            <input type="hidden" name="rsvReceivingSLOCDesc" value="" id="receiving_sloc_desc">
                        </div>
                    </div>

                    <div class="form-group" id="rsvSlocContainer" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Receiving SLOC <span class="red">*</span></label>
                                <select class="form-control select2 select-decorated" name="rsvReceivingSLOC" id="receiving_sloc" disabled style="width: 100%">
                                    <option value="" default selected>---- Choose Sloc ----</option>
                                    @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                        @foreach($data['s_loc'] as $key => $val)
                                            <option value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="rsvCostCenterContainer" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Cost Center <span class="red">*</span></label>
                                @if(isset($data['custom_cost_center']) && count($data['custom_cost_center']) > 0)
                                <select class="form-control select2 select-decorated" name="rsvCostCenterExpense" required id="cost_center_expense" style="width: 100%">
                                    <option value="" default selected>---- Choose Cost Center ----</option>
                                    @if(isset($data['custom_cost_center']) && count($data['custom_cost_center']) > 0)
                                        @foreach($data['custom_cost_center'] as $key_cc => $val_cc)
                                            <option value="{{ isset($val_cc->SAP_COSTCENTER_ID) ? $val_cc->SAP_COSTCENTER_ID.' - ' : '' }}{{ isset($val_cc->SAP_COST_CENTER_DESCRIPTION) ? $val_cc->SAP_COST_CENTER_DESCRIPTION : ''}}">{{ isset($val_cc->SAP_COSTCENTER_ID) ? $val_cc->SAP_COSTCENTER_ID.' - ' : '' }}{{ isset($val_cc->SAP_COST_CENTER_DESCRIPTION) ? $val_cc->SAP_COST_CENTER_DESCRIPTION : ''}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @else
                                <input type="text" value="{{ isset($data['cost_center_id']) ? $data['cost_center_id'].' - ' : '' }}{{ isset($data['cost_center_name']) ? $data['cost_center_name'] : ''}}" id="cost_center_expense" name="rsvCostCenterExpense" class="form-control" disabled />
                                @endif
                                <small class="text-muted">Note : This cost center will be used as an expense destination</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group item-container">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important; overflow: auto;">
                            <table class="table table-bordered smallfont table-request" id="reqForm">
                                <thead>
                                    <tr>
                                        <th style="width: 8%">Item</th>
                                        <th style="width: 20%">SLOC</th>
                                        <th style="width: 35%">Material</th>
                                        <th style="width: 10%">Quantity</th>
                                        <th style="width: 15%">Last Purchase Price</th>
                                        <th style="width: 10%">UoM</th>
                                        <th style="width: 2%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="rowToClone">
                                        <td style="max-width: 1px">
                                            <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="1" readonly="">
                                        </td>
                                        <td style="max-width: 1px">
                                           <select required class="form-control select2 select-decorated" name="rsvSLOC[]" id="sloc" style="width: 100%">
                                                <option value="" default selected>---- Choose Sloc ----</option>
                                                @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                                    @foreach($data['s_loc'] as $key => $val)
                                                        <option value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'] : '' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td style="max-width: 1px">
                                            <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                                                <option value="" default selected>---- Choose Material ----</option>
                                            </select>
                                            <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                                        </td>
                                        <td style="max-width: 1px; position: relative;">
                                            <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                                            <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                        </td>
                                        <td style="max-width: 1px">
                                            <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" id="rsvLastPrice" placeholder="Automatically filled">
                                        </td>
                                        <td style="max-width: 1px">
                                            <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group" style="min-width:80px">
                                                <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                                                <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group item-container-with-plant" style="display: none;">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important; overflow: auto;">
                            <table class="table table-bordered smallfont table-request" id="reqFormWithPlant">
                                <thead>
                                    <tr>
                                        <th style="width: 7%">Item</th>
                                        <th style="width: 14%">Plant</th>
                                        <th style="width: 20%">SLOC</th>
                                        <th style="width: 30%">Material</th>
                                        <th style="width: 8%">Quantity</th>
                                        <th style="width: 11%">Last Purchase Price</th>
                                        <th style="width: 8%">UoM</th>
                                        <th style="width: 2%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="rowToClone">
                                        <td style="max-width: 1px">
                                            <input disabled type="text" class="form-control text-center" name="rsvItem[]" id="rsvItemNew" value="1" readonly="">
                                        </td>
                                        <td style="max-width: 1px">
                                            <div style="position: relative;">
                                                <select disabled required class="form-control select2 select-decorated" name="rsvOriginPlant[]" id="plantNew" style="width: 100%">
                                                    <option value="" default selected>---- Choose Plant ----</option>
                                                    @if(isset($data['plant_list']) && count($data['plant_list']) > 0)
                                                        @foreach($data['plant_list'] as $key => $val)
                                                            <option value="{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : '' }}">{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : 'Unknown Plant' }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="spinner-receiving-plant-item" style="position: absolute; top: 13px; right: 30px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                            </div>
                                        </td>
                                        <td style="max-width: 1px">
                                           <select disabled required class="form-control select2 select-decorated" name="rsvSLOC[]" id="slocNew" style="width: 100%">
                                                <option value="" default selected>---- Choose Sloc ----</option>
                                            </select>
                                        </td>
                                        <td style="max-width: 1px">
                                            <select disabled required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materialsNew" style="width: 100%">
                                                <option value="" default selected>---- Choose Material ----</option>
                                            </select>
                                            <input disabled type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDescNew">
                                        </td>
                                        <td style="max-width: 1px; position: relative;">
                                            <input disabled type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQtyNew" required value="1">
                                            <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                        </td>
                                        <td style="max-width: 1px">
                                            <input disabled type="text" readonly class="form-control text-center" name="rsvLastPrice[]" id="rsvLastPriceNew" required placeholder="Automatically filled">
                                        </td>
                                        <td style="max-width: 1px">
                                            <input disabled type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurementNew" required placeholder="Automatically filled">
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group" style="min-width:80px">
                                                <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqFormWithPlant', this)">-</button>
                                                <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRowWithPlant('reqFormWithPlant')">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group mb-5 text-center">
                        <label class="control-label">Grand Total</label>
                        <div class="col-12">
                            <h2><strong>IDR <span id="grand_total">0</span></strong></h2>
                            <input type="hidden" name="rsvGrandTotal" id="grand_total_value" value="0">
                        </div>
                    </div>

                    <div class="submit-container">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="form-control btn btn-success text-white btn-submit">Send Request</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalHistory"  role="dialog" aria-labelledby="modalHistoryLabel" style="display: none;" aria-hidden="true">
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
<div class="modal fade" id="modalFile"  role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Request Detail </h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalFile">
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{ isset($data['employee_id']) ? $data['employee_id'] : '' }}">

@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>
<script type="text/javascript">
    var data_sloc = '';
    var data_is_not_available_in_sloc = [];
    $(document).ready( function () {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};

        $(".datepicker2").datepicker();
        $('.datepicker3').datepicker({
            dateFormat: 'dd MM yy',
            minDate: 0,
        });

        $(".select-decorated").select2({
            placeholder: "Select an option",
        });

        $(document).on('select2:select', 'select[name="rsvReceivingPlant"], select[name="rsvOriginPlant[]"]', function(e){
            $('.btn-submit').prop('disabled', true);
            if(e.target.name == 'rsvReceivingPlant'){
                $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', false);
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').select2('destroy').html('<option value="" selected disabled></option>');
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', true);
                $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').select2({
                    placeholder: "Select an option",
                }).on('select2:select', function(e){
                    try {
                        var text = e.params.data.text || '';
                        $(e.target).parents('#rsvPlantSlocContainer').find('#receiving_sloc_desc').val(text);
                    } catch(error){}
                }).on('select2:unselecting', function(){
                    try {
                        $(e.target).parents('#rsvPlantSlocContainer').find('#receiving_sloc_desc').val('');
                    } catch(error){}
                });
                $.ajax({
                    url: "/finance/add-reservation/request",
                    type: "GET",
                    data : {'type': 'sloc', 'plant_code': e.params.data.id },
                    dataType: 'json',
                    success : function(response){
                        var newOption = [];
                        if(response.hasOwnProperty('sloc') && response.sloc.length){
                            $.each(response.sloc, function(index, data){
                              newOption[index] = new Option(`${data.STORAGE_LOCATION} - ${data.STORAGE_LOCATION_DESC}`, data.STORAGE_LOCATION, false, false);
                            });
                        }

                        setTimeout(function(){
                            $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', true);
                            $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', false);
                            $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').append(newOption).trigger('change');
                        },400)
                    },
                    error : function(xhr){
                        $(e.target).parents('#rsvPlantSlocContainer').find('.spinner-receiving-plant').prop('hidden', true);
                        $(e.target).parents('#rsvPlantSlocContainer').find('select[name="rsvReceivingPlantSLOC"]').prop('disabled', false);
                        $.toast({
                          text : "Oops.. Something went wrong when trying to load data, please try again in a moment",
                          hideAfter : 4000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        });
                        console.log("EXCEPTION OCCURED IN RESERVATION REQUEST");
                    },
                    complete : function(){
                        $('.btn-submit').prop('disabled', false);
                    }

                });
            } else {
                $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', false);
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');

                $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2('destroy').html('<option value="" selected disabled></option>');
                $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', true);

                try {
                    if($(e.target).parents('#modalDetailAjax').length > 0){
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                            placeholder: "Select an option",
                            dropdownParent : $('#modalDetailAjax')
                        });
                    } else {
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                            placeholder: "Select an option",
                        });
                    }
                } catch(e){
                    $(e.target).parents('tr').find('[name="rsvSLOC[]"]').select2({
                        placeholder: "Select an option",
                    });
                }

                $.ajax({
                    url: "/finance/add-reservation/request",
                    type: "GET",
                    data : {'type': 'sloc', 'plant_code': e.params.data.id },
                    dataType: 'json',
                    success : function(response){
                        var newOption = [];
                        if(response.hasOwnProperty('sloc') && response.sloc.length){
                            $.each(response.sloc, function(index, data){
                              newOption[index] = new Option(`${data.STORAGE_LOCATION} - ${data.STORAGE_LOCATION_DESC}`, data.STORAGE_LOCATION, false, false);
                            });
                        }

                        setTimeout(function(){
                            $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', true);
                            $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', false);
                            $(e.target).parents('tr').find('[name="rsvSLOC[]"]').append(newOption).trigger('change');
                        },400)
                    },
                    error : function(xhr){
                        $(e.target).parents('tr').find('.spinner-receiving-plant-item').prop('hidden', true);
                        $(e.target).parents('tr').find('[name="rsvSLOC[]"]').prop('disabled', false);
                        $.toast({
                          text : "Oops.. Something went wrong when trying to load data, please try again in a moment",
                          hideAfter : 4000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        });
                        console.log("EXCEPTION OCCURED IN RESERVATION REQUEST");
                    },
                    complete : function(){
                        $('.btn-submit').prop('disabled', false);
                    }

                });

            }
        });

        var table_obj = $(".select-decorated-material").select2({
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                    return data.text;
                }
                return data.html;
            },
            templateSelection: function(data) {
                if(!data.id) {
                    return data.text;
                } else {
                    return data.text;
                }
            },
            // dropdownParent: $('#modalRequestReservation'),
            allowClear: false,
            placeholder: "Search Material ...",
            ajax: {
               url: "/finance/add-reservation/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                var obj_mv_type = {};
                var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                // var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
                
                if($('#modalFile').hasClass('show'))
                    var mv_type = $('input[name="MovementType"]').length > 0 ? $('input[name="MovementType"]').val() : '';
                else
                    var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
                if(mv_type == '311'){
                    var dest_sloc = $('select[name="rsvReceivingSLOC"]').length > 0 ? $('select[name="rsvReceivingSLOC"]').val() : '';
                    obj_mv_type = {'mv_type': mv_type, 'destination_sloc': dest_sloc};
                }

                if(plant.length > 0)
                    plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                else 
                    plant = $('#Requestor_Plant_ID').val();

                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                  sloc: $_sloc,
                  ...obj_mv_type
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                 var $_sloc = this.data.sloc
                 if($_sloc){
                     var $request = $.ajax(params);
                     $request.then(success);
                     $request.fail(failure);
                     return $request;
                 } else {
                    Swal.fire('Store Location Selection', 'Please select store location first to search materials available within it', 'warning');
                    return false;
                 }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            var value = e.params.data.id || 0;
            var text = e.params.data.text || 'Unknown';
            var unit = e.params.data.unit || 'Unknown';
            var lastPrice = e.params.data.last_price || 0;
            var rowIndex = $(e.target).parents('tr')[0].rowIndex;

            var duplicate_item = 0;
            $(e.target).parents('table').find('[name="rsvMaterials[]"]').not(e.target).each(function(index, element){
                if(data_is_not_available_in_sloc.indexOf(element.value) > -1){
                    data_is_not_available_in_sloc[data_is_not_available_in_sloc.indexOf(element.value)] = element.value;
                }
                if(element.value == value){
                    duplicate_item = 1;
                }
            });

            if(duplicate_item > 0){
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                Swal.fire('Duplicate Item', `This material ${text} has been added to the request, please choose another material`, 'error');
                return false;
            } else {
                var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
                if(mv_type == '311'){
                    var is_avaiable_sloc_dest = e.params.data.is_available_sloc_destination || false;
                    if(!is_avaiable_sloc_dest){
                        if(!data_is_not_available_in_sloc.includes(value)){
                            data_is_not_available_in_sloc.push(value);
                        }
                        $(e.target).parents('tr').addClass('bg-warning');
                    } else {
                        if(data_is_not_available_in_sloc.includes(value)){
                            var index = data_is_not_available_in_sloc.indexOf(value);
                            if (index !== -1) {
                              data_is_not_available_in_sloc.splice(index, 1);
                            }
                        } else {
                            if(data_is_not_available_in_sloc[rowIndex] != undefined){
                                data_is_not_available_in_sloc.splice((rowIndex - 1), 1);
                            }
                        }
                        $(e.target).parents('tr').removeClass('bg-warning');
                    }
                    // console.log(data_is_not_available_in_sloc);
                }

                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(text);

                setTimeout(function(){
                    var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                    checkLastPrice(target);
                }, 600);
            }

        }).on('select2:unselecting', function(e){
            $(e.target).parents('tr').find('#rsvMeasurement').val('');
            $(e.target).parents('tr').find('#rsvLastPrice').val('');
            calculateGrandTotal(e.target);
            $(this).data('state', 'unselected');
        });

        $('#modalRequestReservation').on('hidden.modal.bs', function(){
            try {
                clearAll();
            } catch(error){}
        });

        $('#modalFile').on('hide.modal.bs', function(){
            data_is_not_available_in_sloc = [];
            $('.table > tbody > tr').removeClass('bg-warning');
        });

        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            "dom": '<"clearfix"lf> <"d-block" <"table-wrapper table-container-h"rt>>ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/add-reservation/request/getData",
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
            "fixedHeader": false,
            "processing": true,
            "serverSide": true,
            "scrollX": false,
            'columnDefs': [
                {
                    "targets": 2, // your case first column
                    "className": "text-center",
               },
               {
                    "targets": 6,
                    "className": "text-center",
               }
             ],
            "columns": [
                {
                    "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    },   className: 'text-left'
                },
                { "data": "SAP_RSV_NO", className: 'text-center'},
                { 
                    "data": "STATUS_APPROVAL", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {

                        if(id == null || id == "null"){ id = "REQUESTED"; }
                        if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}
                        // return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';

                        if(id=="APPROVED" || id=="REQUESTED" || id=="" || id=="WAITING FOR APPROVAL"){
                            return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;">'+id+'</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;color:green;">'+id+'</a>';
                        }else{
                            return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" style="font-weight:bold;color:red;">'+id+'</a>';
                        }
                    }
                },
                { 
                    "data": "CREATED_FOR_DATE",
                    "render": function (id, type, full, meta)
                    {
                        return type === 'export-excel' ? moment(id).format("YYYY-MM-DD") : moment(id).format("MM/DD/YYYY");
                    }
                },
                { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
                { 
                    "data": "LAST_APPROVAL_DATE", className: 'text-center',
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
                { 
                    "data": "GRAND_TOTAL",
                    className: 'text-right',
                    "render": function (id, type, full, meta)
                    {
                        return id;
                    }
                },
                { "data": "MOVEMENT_TYPE", className: 'text-left'},
                { "data": "RCV_SLOC_CC", className: 'text-left'},
            ],
            "order": [[ 0, "" ]],
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };   

    });
    // End Document Ready 
    
    $sloc_selected = [];
    $(document).on('select2:select', 'select[name="rsvSLOC[]"]', function(e){
        var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
        if(mv_type == '311'){
            var obj_value = $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val();
            if(data_is_not_available_in_sloc.includes(obj_value)){
                var index = data_is_not_available_in_sloc.indexOf(obj_value);
                if (index !== -1) {
                  data_is_not_available_in_sloc.splice(index, 1);
                }
            }
            $(e.target).parents('tr').removeClass('bg-warning');
        }

        setTimeout(function(){
            $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
            $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
            $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
        }, 300);
    });

    function getFormDetail(id){
        $('.loader-modal').show();
        $('#modalFile #bodyModalFile').html('');
        $.get("{{url('finance/add-reservation/modal-detail')}}", { id : id}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            setTimeout(function(){
                $("#modalFile .select-decorated-detail").select2({
                    placeholder: "Select an option",
                    dropdownParent: $('#modalDetailAjax')
                });
                $("#modalFile .select-decorated-material-detail").select2({
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    templateResult: function(data) {
                        if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                            return data.text;
                        }
                        return data.html;
                    },
                    templateSelection: function(data) {
                        if(!data.id) {
                            return data.text;
                        } else {
                            return data.text;
                        }
                    },
                    dropdownParent: $('#modalDetailAjax'),
                    allowClear: false,
                    placeholder: "Search Material ...",
                    ajax: {
                       url: "/finance/add-reservation/request",
                       type: "GET",
                       dataType: 'json',
                       delay: 600,
                       data: function (params) {
                        var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                        var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                        if(plant.length > 0)
                            plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                        else 
                            plant = $('#Requestor_Plant_ID').val();

                        return {
                          searchTerm: params.term, // search term
                          type: 'material',
                          plant: plant,
                          sloc: $_sloc
                        };
                       },
                       processResults: function (response) {
                         return {
                            results: response
                         };
                       },
                       cache: false,
                       transport: function (params, success, failure) {
                         var $_sloc = this.data.sloc
                         if($_sloc){
                             var $request = $.ajax(params);
                             $request.then(success);
                             $request.fail(failure);
                             return $request;
                         } else {
                            Swal.fire('Store Location Selection', 'Please select store location first to search materials available within it', 'warning');
                            return false;
                         }
                       }
                    },
                    minimumInputLength: 3
                 }).on('select2:select', function(e){
                    var value = e.params.data.id || 0;
                    var text = e.params.data.text || 'Unknown';
                    var unit = e.params.data.unit || 'Unknown';
                    var lastPrice = e.params.data.last_price || 0;

                    $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
                    $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(text);

                    setTimeout(function(){
                        var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                        checkLastPrice(target);
                    }, 600);

                }).on('select2:unselecting', function(e){
                    $(e.target).parents('tr').find('#rsvMeasurement').val('');
                    $(e.target).parents('tr').find('#rsvLastPrice').val('');
                    calculateGrandTotal(e.target);
                    $(this).data('state', 'unselected');
                });
            }, 200);
            try {
                $('.datepicker-detail').datepicker({
                    dateFormat: 'dd MM yy',
                    minDate: 0,
                });
            } catch(error){}
            data_is_not_available_in_sloc = [];
            $('.table > tbody > tr').removeClass('bg-warning');
            $('.loader-modal').hide();


        });
    }

    function getHistoryDetail(id){
        $('#requestHistoryList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/add-reservation/getHistoryApproval',
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

    function clearAll(){
        try {
            // $('#movementType').val(null).trigger('change');
            // $('#rsvSlocContainer').prop('hidden', true);
            // $('#receiving_sloc').prop('disabled', true);

            // $('#rsvCostCenterContainer').prop('hidden', true);
            // $('#receiving_sloc').prop('disabled', true);
        } catch(error){}
    }

    function calculateGrandTotal(targetChild){
        try {
            var grand_total = 0;
            $(targetChild).parents('form').find('input:visible[name="rsvLastPrice[]"]').each(function(index, item){
                if(item.value){
                    let val = item.value.replace(/\,/g, '');
                    grand_total += isNaN(val) ? 0 : parseFloat(val);
                } else 
                    grand_total += 0;
            });
            $(targetChild).parents('form').find('#grand_total_value').val(grand_total);
            if(grand_total)
                grand_total = number_format(grand_total, 2, '.', ',');
            else 
                grand_total = 0;
            $(targetChild).parents('form').find('#grand_total').text(grand_total);
        } catch(error){
            console.log('error', error);
        }

        $('.btn-submit').prop('disabled', false);
        $('.btn-add').prop('disabled', false);
        $('.btn-del').prop('disabled', false);

    }

    $(document).on('select2:select', 'select[name="MovementType"]', function(e){
        data_is_not_available_in_sloc = [];
        $('.table > tbody > tr').removeClass('bg-warning');
        var val_selected = e.params.data.id || 0;
        if(val_selected == '311'){
            $('#rsvSlocContainer').prop('hidden', false);
            $('#receiving_sloc').prop('required', true);
            $('#receiving_sloc').prop('disabled', false);
            $('#rsvCostCenterContainer').prop('hidden', true);
            $('#cost_center_expense').prop('readonly', false);
            $('#cost_center_expense').prop('disabled', true);
            $('.item-container').show();
            $(".item-container input, .item-container select, .item-container button").prop('disabled', false);

            // Disable new item and receiving plant
            $(".item-container-with-plant input, .item-container-with-plant select, .item-container-with-plant button").prop('disabled', true);
            $('.item-container-with-plant').hide();
            $('#receiving_plant').prop('disabled', true);
            $('#receiving_plant').prop('required', false);
            $('select[name="rsvReceivingPlantSLOC"]').prop('disabled', true);
            $('select[name="rsvReceivingPlantSLOC"]').prop('required', false);
            $('#isPlantToPlantReceive').prop('disabled', true);
            $('#receiving_sloc_desc').prop('disabled', true);
            $('#rsvPlantSlocContainer').prop('hidden', true);
            setTimeout(function(){
                if($('[name="rsvReceivingSLOC"]').val()){
                    var data = {
                        "id": $('[name="rsvReceivingSLOC"]').select2('data')[0].id,
                        "text": $('[name="rsvReceivingSLOC"]').select2('data')[0].text
                    }
                    $('[name="rsvReceivingSLOC"]').trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                }
            }, 500);

        } else if(val_selected == '301'){ 
            $('#rsvSlocContainer').prop('hidden', true);
            $('#receiving_sloc').prop('required', false);
            $('#receiving_sloc').prop('disabled', true);
            $('#rsvCostCenterContainer').prop('hidden', true);
            $('#cost_center_expense').prop('readonly', false);
            $('#cost_center_expense').prop('disabled', true);
            $(".item-container input, .item-container select, .item-container button").prop('disabled', true);
            $('.item-container').hide();

            // Enable new item and receiving plant
            $('#rsvPlantSlocContainer').prop('hidden', false);
            $('#receiving_plant').prop('disabled', false);
            $('#receiving_plant').prop('required', true);
            $('select[name="rsvReceivingPlantSLOC"]').prop('disabled', false);
            $('select[name="rsvReceivingPlantSLOC"]').prop('required', true);
            $('#isPlantToPlantReceive').prop('disabled', false);
            $('#receiving_sloc_desc').prop('disabled', false);
            $('.item-container-with-plant').show();
            $(".item-container-with-plant input, .item-container-with-plant select, .item-container-with-plant button").prop('disabled', false);
        } else {
            $('#rsvSlocContainer').prop('hidden', true);
            $('#receiving_sloc').prop('required', false);
            $('#receiving_sloc').prop('disabled', true);
            $('#rsvCostCenterContainer').prop('hidden', false);
            $('#cost_center_expense').prop('disabled', false);
            $('#cost_center_expense').prop('readonly', true);
            $('.item-container').show();
            $(".item-container input, .item-container select, .item-container button").prop('disabled', false);

            // Disable new item and receiving plant
            $(".item-container-with-plant input, .item-container-with-plant select, .item-container-with-plant button").prop('disabled', true);
            $('.item-container-with-plant').hide();
            $('#receiving_plant').prop('disabled', true);
            $('#receiving_plant').prop('required', false);
            $('select[name="rsvReceivingPlantSLOC"]').prop('disabled', true);
            $('select[name="rsvReceivingPlantSLOC"]').prop('required', false);
            $('#isPlantToPlantReceive').prop('disabled', true);
            $('#receiving_sloc_desc').prop('disabled', true);
            $('#rsvPlantSlocContainer').prop('hidden', true);
        }
        calculateGrandTotal(e.target);
    });

    $("#formRequest").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
        if(mv_type == '311'){
            if(data_is_not_available_in_sloc.length > 0){
                Swal.fire('Material Selection SLOC', 'Some ot the materials might be not available or have not been extended to the destination SLOC, please check the warning sign (if any) within each material and solve the issues.', 'error');
                // console.log(data_is_not_available_in_sloc);
                return false;
            }
        }

        try {
            var zero_value = false;
            $('input[name="rsvQuantity[]"]', this).each(function(index, elem){
                try {
                    if(elem.value === '0'){
                        zero_value = true;
                        return false;
                    }
                } catch(error){}
            });
            if(zero_value){
                Swal.fire('Submit Reservation', "Please make sure all quantity inserted is more than zero (0), there is no such calculation for zero amount of request", 'warning');
                return false;
            }
        } catch(error){}

        var form = this;
        var url_post=$(this).attr('data-url-post');
        var loader=$(this).attr('data-loader-file');
        var form = new FormData(form);

        $.ajax({
            type: "POST",
            url: url_post,
            data: form,
            // cache:false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            Swal.fire({
                title: "Loading...",
                text: "Please wait!",
                imageUrl: loader,
                allowOutsideClick: false,
                imageSize: '150x150',
                showConfirmButton: false
            });
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || "Success insert data",
                }).then((result) =>{
                    $('#modalRequestReservation').modal('hide');
                    setTimeout(function(){
                        window.location.href="/finance/add-reservation/request";
                    }, 500);
                });
            },
            error : function(error){
                let message = 'Something went wrong, ' + error.responseJSON.message || 'Something went wrong when trying to save the document, make sure to fill all the data required, check your connection and try again';
                Swal.fire('Save Reservation Form', message, 'error');
            },
        });
        return false;
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

    $(document).on('select2:select', '[name="rsvReceivingPlantSLOC"]', function(e){
        try {
            var $val = e.params.data.text;
            $(e.target).parents('#rsvPlantSlocContainer').find('[name="rsvReceivingSLOCDesc"]').val($val)
        } catch(e){}
    });

    var delayInAjaxCall = (function(){
          var timer = 0;
          return function(callback, milliseconds){
          clearTimeout (timer);
          timer = setTimeout(callback, milliseconds);
       };
    })();

    $(document).on('select2:select', '[name="rsvReceivingSLOC"]', function(e){
        try {
            delayInAjaxCall(function(){
                var $val = e.params.data.id.split('-', 1);
                $val = $val.map(x => x.toString().trim())[0] == undefined ? '' : $val.map(x => x.toString().trim())[0];
                var material_element = $(e.target).parents('form').find('#reqForm').find('[name="rsvMaterials[]"]');
                var material_element_edit = $(e.target).parents('form').find('#reqFormDetail').find('[name="rsvMaterials[]"]');
                if(material_element.length > 0){
                    $(material_element).each(function(index, elem){
                        if(elem.value){
                            var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                            if(plant.length > 0)
                                plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                            else 
                                plant = $('#Requestor_Plant_ID').val();
                            $.ajax({
                                url: "/finance/add-reservation/request",
                                type: 'GET',
                                dataType: 'json',
                                beforeSend: function(){
                                    $(e.target).parents('form').find('.material-sloc-check').prop('hidden', false);
                                },
                                data: {'type': 'material_sloc_existence','material_no': elem.value, 'sloc': $val, 'plant': plant},
                                success: function(response){
                                    if(response.hasOwnProperty('is_available') && response.is_available){
                                        $(elem).parents('tr').removeClass('bg-warning');
                                        // var obj_value = $(elem).parents('tr').find('[name="rsvMaterials[]"]').val();
                                        var obj_value = elem.value;
                                        if(data_is_not_available_in_sloc.includes(obj_value)){
                                            var index = data_is_not_available_in_sloc.indexOf(obj_value);
                                            if (index !== -1) {
                                              data_is_not_available_in_sloc.splice(index, 1);
                                            }
                                        }
                                    } else {
                                        $(elem).parents('tr').addClass('bg-warning');
                                        if(!data_is_not_available_in_sloc.includes(elem.value)){
                                            data_is_not_available_in_sloc.push(elem.value);
                                        }
                                    }
                                },
                                error: function(xhr){
                                    data_is_not_available_in_sloc = [];
                                    $(elem).parents('tr').removeClass('bg-warning');
                                    var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
                                    Swal.fire({
                                        title: "Oops..",
                                        text: error,
                                        icon: "error",
                                        showConfirmButton: true
                                    });
                                },
                                complete: function(){
                                    setTimeout(function(){
                                        $(e.target).parents('form').find('.material-sloc-check').prop('hidden', true);
                                    }, 1000);
                                }
                            })
                        }
                    })
                } else if(material_element_edit.length > 0){
                    $(material_element_edit).each(function(index, elem){
                        if(elem.value){
                            var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                            if(plant.length > 0)
                                plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                            else 
                                plant = $('#Requestor_Plant_ID').val();
                            $.ajax({
                                url: "/finance/add-reservation/request",
                                type: 'GET',
                                dataType: 'json',
                                beforeSend: function(){
                                    $(e.target).parents('form').find('.material-sloc-check').prop('hidden', false);
                                },
                                data: {'type': 'material_sloc_existence','material_no': elem.value, 'sloc': $val, 'plant': plant},
                                success: function(response){
                                    if(response.hasOwnProperty('is_available') && response.is_available){
                                        $(elem).parents('tr').removeClass('bg-warning');
                                        // var obj_value = $(elem).parents('tr').find('[name="rsvMaterials[]"]').val();
                                        var obj_value = elem.value;
                                        if(data_is_not_available_in_sloc.includes(obj_value)){
                                            var index = data_is_not_available_in_sloc.indexOf(obj_value);
                                            if (index !== -1) {
                                              data_is_not_available_in_sloc.splice(index, 1);
                                            }
                                        }
                                    } else {
                                        $(elem).parents('tr').addClass('bg-warning');
                                        if(!data_is_not_available_in_sloc.includes(elem.value)){
                                            data_is_not_available_in_sloc.push(elem.value);
                                        }
                                    }
                                },
                                error: function(xhr){
                                    data_is_not_available_in_sloc = [];
                                    $(elem).parents('tr').removeClass('bg-warning');
                                    var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
                                    Swal.fire({
                                        title: "Oops..",
                                        text: error,
                                        icon: "error",
                                        showConfirmButton: true
                                    });
                                },
                                complete: function(){
                                    $(e.target).parents('form').find('.material-sloc-check').prop('hidden', true);
                                }
                            })
                        }
                    })
                }
            }, 300)
        } catch(e){}
    });

    function checkLastPrice(target){
        // console.log(target, target.value, typeof target.value);
        try {
            $('.btn-submit').prop('disabled', true);
            $('.btn-add').prop('disabled', true);
            $('.btn-del').prop('disabled', true);

            var qty = target.value;
            if(qty && qty !== '0') {
                material = $(target).parents('tr').find('[name="rsvMaterials[]"]').val(),
                unit = $(target).parents('tr').find('[name="rsvMeasurement[]"]').val();
                var plant = $(target).parents('tr').find('[name="rsvOriginPlant[]"]');
                if(plant.length > 0)
                    plant = $(target).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                else 
                    plant = $('#Requestor_Plant_ID').val();

                $(target).parent().find('.spinner-qty').prop('hidden', false);
                $.ajax({
                   url: "/finance/add-reservation/request",
                   type: "GET",
                   dataType: 'json',
                   delay: 600,
                   data: {
                    'type': 'material_last_price', 
                    'material': material, 
                    'unit': unit, 
                    'qty': qty,
                    'plant': plant
                   },
                   success : function(resp){
                     if(resp.hasOwnProperty('last_price')){
                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="rsvLastPrice[]"]').val(last_price);
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                     console.log("Error in check last price");
                     $('.btn-submit').prop('disabled', false);
                     $('.btn-add').prop('disabled', false);
                     $('.btn-del').prop('disabled', false);
                   },
                   complete : function(){}
                });
            } else {
                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                $('.btn-submit').prop('disabled', false);
                $('.btn-add').prop('disabled', false);
                $('.btn-del').prop('disabled', false);

            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            $('.btn-submit').prop('disabled', false);
            $('.btn-add').prop('disabled', false);
            $('.btn-del').prop('disabled', false);
        }
    }

    function qtyInput(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        setTimeout(function(){
            checkLastPrice(elem);
        }, 600);
    }

    function disableButton2(idTag){
        var rowId = idTag.id;
        var x = idTag.value;
        // console.log(x, rowId, `.s${rowId}`);

        try {
            if(x.length > 2){
                $(idTag).parents('td').find(`.s${rowId}`).prop('disabled', false);
            }
            else{
                $(idTag).parents('td').find(`.s${rowId}`).prop('disabled', true);
            }
        } catch(error){}
    }

    function cloneRow(tableID, isModal=false) {
        var dropdownParent = {};
        if(isModal)
            dropdownParent = {dropdownParent:$('#modalDetailAjax')};

        var table = document.getElementById(tableID);
        if(!$(table).find('[name="rsvSLOC[]"]').val()){
            Swal.fire('Storage Location Option', 'Please select storage location first to add a new row', 'warning');
            return false;
        }

        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        row.classList.add('rowToClone');
        var colCount = table.rows[1].cells.length;
        if(rowCount<10000){
            for(var i=0; i<colCount; i++) {
                // console.log("ITERASI", i);
                var newcell = row.insertCell(i);
                newcell.style.maxWidth = '1px';
                newcell.style.position = 'relative';

                if(i == 1 || i == 2 || i == 3){
                    try {
                        var html = '';
                        if(i == 2){
                            if(isModal)
                                var material_id = 'materials-detail-';
                            else
                                var material_id = 'materials';
                            html += `<td>
                                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="${material_id}" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                                <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                            </td>`;
                        } else if(i == 3){
                            html += `<td>
                                <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                            </td>`;

                        } else if(i == 1){
                            let option = '';
                            let populate_option = $("select[name='rsvSLOC[]']", $(tableID)[0])[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += `<option value="${populate_option[data].value}">${populate_option[data].innerHTML}</option>`;
                            }

                            if(isModal)
                                var sloc_id = 'sloc-detail-';
                            else
                                var sloc_id = 'sloc';
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rsvSLOC[]" id="${sloc_id}" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        }

                        newcell.innerHTML = html;
                    } catch(error){
                        console.log(`Error in column 1 / 2 / 3 ${error}`);
                    }
                }

                else {
                    newcell.innerHTML = table.rows[1].cells[i].innerHTML;
                }           

                try {
                    // Set unique id based on row
                    newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount;
                } catch(error){}

                try {
                    if(newcell.childNodes[1].name == 'rsvItem[]'){
                        let newVal = (rowCount - 1) + 1;
                        newcell.childNodes[1].value = newVal;
                    }

                    else if(newcell.childNodes[1].name == 'rsvLastPrice[]'){
                        newcell.childNodes[1].value = '';
                    }

                    else if(newcell.childNodes[1].name == 'rsvMaterials[]'){
                        $(`#${newcell.childNodes[1].id}`).select2({
                            escapeMarkup: function(markup) {
                                return markup;
                            },
                            templateResult: function(data) {
                                if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                                    return data.text;
                                }
                                return data.html;
                            },
                            templateSelection: function(data) {
                                if(!data.id) {
                                    return data.text;
                                } else {
                                    return data.text;
                                }
                            },
                            ...dropdownParent,
                            allowClear: false,
                            placeholder: "Search Material ...",
                            ajax: {
                               url: "/finance/add-reservation/request",
                               type: "GET",
                               dataType: 'json',
                               delay: 600,
                               data: function (params) {
                                var obj_mv_type = {};
                                var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                                var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                                var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
                                if(mv_type == '311'){
                                    var dest_sloc = $('select[name="rsvReceivingSLOC"]').length > 0 ? $('select[name="rsvReceivingSLOC"]').val() : '';
                                    obj_mv_type = {'mv_type': mv_type, 'destination_sloc': dest_sloc};
                                }

                                if(plant.length > 0)
                                    plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                                else 
                                    plant = $('#Requestor_Plant_ID').val();

                                return {
                                  searchTerm: params.term, // search term
                                  type: 'material',
                                  plant: plant,
                                  sloc: $_sloc,
                                  ...obj_mv_type
                                };
                               },
                               processResults: function (response) {
                                 return {
                                    results: response
                                 };
                               },
                               cache: true
                            },
                            minimumInputLength: 3,
                         }).on('select2:select', function(e){
                            var value = e.params.data.id || 0;
                            var text = e.params.data.text || 'Unknown';
                            var unit = e.params.data.unit || 'Unknown';
                            var lastPrice = e.params.data.last_price || 0;

                            var duplicate_item = 0;
                            $(e.target).parents('table').find('[name="rsvMaterials[]"]').not(e.target).each(function(index, element){
                                if(data_is_not_available_in_sloc.indexOf(element.value) > -1){
                                    data_is_not_available_in_sloc[data_is_not_available_in_sloc.indexOf(element.value)] = element.value;
                                }
                                if(element.value == value){
                                    duplicate_item = 1;
                                }
                            });
                            if(duplicate_item > 0){
                                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                                $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                                Swal.fire('Duplicate Item', `This material ${text} has been added to the request, please choose another material`, 'error');
                                return false;
                            } else {
                                if($('#modalFile').hasClass('show'))
                                    var mv_type = $('input[name="MovementType"]').length > 0 ? $('input[name="MovementType"]').val() : '';
                                else
                                    var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';

                                if(mv_type == '311'){
                                    var is_avaiable_sloc_dest = e.params.data.is_available_sloc_destination || false;
                                    if(!is_avaiable_sloc_dest){
                                        if(!data_is_not_available_in_sloc.includes(value)){
                                            data_is_not_available_in_sloc.push(value);
                                        }
                                        $(e.target).parents('tr').addClass('bg-warning');
                                    } else {
                                        if(data_is_not_available_in_sloc.includes(value)){
                                            var index = data_is_not_available_in_sloc.indexOf(value);
                                            if (index !== -1) {
                                              data_is_not_available_in_sloc.splice(index, 1);
                                            }
                                        }
                                        $(e.target).parents('tr').removeClass('bg-warning');
                                    }
                                    // console.log(data_is_not_available_in_sloc);
                                }

                                $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val(unit);
                                $(e.target).parents('tr').find(`[name='rsvMaterialsDesc[]']`).val(text);

                                setTimeout(function(){
                                    var target = $(e.target).parents('tr').find(`[name='rsvQuantity[]']`)[0];
                                    checkLastPrice(target);
                                }, 600);
                            }
                        }).on('select2:unselecting', function(e){
                            $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val('');
                            $(e.target).parents('tr').find(`[name='rsvLastPrice[]']`).val('');
                            calculateGrandTotal(e.target);
                            $(this).data('state', 'unselected');
                        }).on("select2:open", function(e) {                            
                            try {
                              if ($(this).data('state') === 'unselected') {
                                  $(this).removeData('state'); 
                                  var self = $(this).parent().find('.select2')[0];
                                  setTimeout(function() {
                                      $(self).select2('close');
                                  }, 0);
                              }
                            } catch(error){}   
                        });
                    }

                    else if(newcell.childNodes[1].name == 'rsvSLOC[]'){
                        let val = $(table.rows[(rowCount-1)]).find(`[name='rsvSLOC[]']`).val() || 0;
                        if(val){
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            }).val(val).trigger('change');
                        } else {
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            });
                        }
                    }
                } catch(error){ console.log(`There's error on column ${i}`, error) }
            }
        }
        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}
    }

    function cloneRowWithPlant(tableID) {
        // Fix incompatibility select2 plugin between browser
        var browser = fnBrowserDetect();
        var dropdownParent = {};
        if(browser.trim() == 'firefox'){
            if($('#modalRequestReservation').length > 0)
                dropdownParent = {dropdownParent:$('#reqFormWithPlant')};
            else
                dropdownParent = {dropdownParent:$('#modalDetailAjax')};
        }

        var table = document.getElementById(tableID);
        if(!$(table).find('[name="rsvSLOC[]"]').val()){
            Swal.fire('Storage Location Option', 'Please select storage location first to add a new row', 'warning');
            return false;
        }

        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        row.classList.add('rowToClone');
        var colCount = table.rows[1].cells.length;
        if(rowCount<10000){
            for(var i=0; i<colCount; i++) {
                // console.log("ITERASI", i);
                var newcell = row.insertCell(i);
                newcell.style.maxWidth = '1px';
                newcell.style.position = 'relative';

                if(i == 1 || i == 2 || i == 3 || i == 4){
                    try {
                        var html = '';
                        if(i == 3){
                            html += `<td>
                                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materialsNew" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                                <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                            </td>`;
                        } else if(i == 4){
                            html += `<td>
                                <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQtyNew" required value="1">
                                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                            </td>`;

                        } else if(i == 2){
                            let option = '';
                            let populate_option = $(table).find("select[name='rsvSLOC[]']")[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += `<option value="${populate_option[data].value}">${populate_option[data].innerHTML}</option>`;
                            }
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rsvSLOC[]" id="slocNew" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        } else if(i == 1){
                            let option = '';
                            let populate_option = $(table).find("select[name='rsvOriginPlant[]']")[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += `<option value="${populate_option[data].value}">${populate_option[data].innerHTML}</option>`;
                            }
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rsvOriginPlant[]" id="plantNew" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        }

                        newcell.innerHTML = html;
                    } catch(error){
                        console.log(`Error in column 1 / 2 / 3 / 4 ${error}`);
                    }
                }

                else {
                    newcell.innerHTML = table.rows[1].cells[i].innerHTML;
                }           

                try {
                    // Set unique id based on row
                    newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount;
                } catch(error){}

                try {
                    if(newcell.childNodes[1].name == 'rsvItem[]'){
                        let newVal = (rowCount - 1) + 1;
                        newcell.childNodes[1].value = newVal;
                    }

                    else if(newcell.childNodes[1].name == 'rsvLastPrice[]'){
                        newcell.childNodes[1].value = '';
                    }

                    else if(newcell.childNodes[1].name == 'rsvMaterials[]'){
                        $(`#${newcell.childNodes[1].id}`).select2({
                            escapeMarkup: function(markup) {
                                return markup;
                            },
                            templateResult: function(data) {
                                if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                                    return data.text;
                                }
                                return data.html;
                            },
                            templateSelection: function(data) {
                                if(!data.id) {
                                    return data.text;
                                } else {
                                    return data.text;
                                }
                            },
                            ...dropdownParent,
                            allowClear: false,
                            placeholder: "Search Material ...",
                            ajax: {
                               url: "/finance/add-reservation/request",
                               type: "GET",
                               dataType: 'json',
                               delay: 600,
                               data: function (params) {
                                var $_sloc = $(this[0]).parents('tr').find('[name="rsvSLOC[]"]').val();
                                var plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]');
                                if(plant.length > 0)
                                    plant = $(this[0]).parents('tr').find('[name="rsvOriginPlant[]"]').val();
                                else 
                                    plant = $('#Requestor_Plant_ID').val();

                                return {
                                  searchTerm: params.term, // search term
                                  type: 'material',
                                  plant: plant,
                                  sloc: $_sloc
                                };
                               },
                               processResults: function (response) {
                                 return {
                                    results: response
                                 };
                               },
                               cache: true
                            },
                            minimumInputLength: 3
                         }).on('select2:select', function(e){
                            var value = e.params.data.id || 0;
                            var text = e.params.data.text || 'Unknown';
                            var unit = e.params.data.unit || 'Unknown';
                            var lastPrice = e.params.data.last_price || 0;

                            var duplicate_item = 0;
                            $(e.target).parents('table').find('[name="rsvMaterials[]"]').not(e.target).each(function(index, element){
                                data_is_not_available_in_sloc = data_is_not_available_in_sloc.filter(x => x == element.value);
                                if(element.value == value){
                                    duplicate_item = 1;
                                }
                            });
                            if(duplicate_item > 0){
                                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                                $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                                Swal.fire('Duplicate Item', `This material ${text} has been added to the request, please choose another material`, 'error');
                                return false;
                            } else {
                                $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val(unit);
                                // $(e.target).parents('tr').find(`[name='rsvLastPrice[]']`).val(lastPrice);
                                $(e.target).parents('tr').find(`[name='rsvMaterialsDesc[]']`).val(text);

                                setTimeout(function(){
                                    var target = $(e.target).parents('tr').find(`[name='rsvQuantity[]']`)[0];
                                    checkLastPrice(target);
                                }, 600);
                            }
                        }).on('select2:unselecting', function(e){
                            $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val('');
                            $(e.target).parents('tr').find(`[name='rsvLastPrice[]']`).val('');
                            calculateGrandTotal(e.target);
                            $(this).data('state', 'unselected');
                        }).on("select2:open", function(e) {
                            try {
                              if ($(this).data('state') === 'unselected') {
                                  $(this).removeData('state'); 
                                  var self = $(this).parent().find('.select2')[0];
                                  setTimeout(function() {
                                      $(self).select2('close');
                                  }, 0);
                              }
                            } catch(error){}   
                        });
                    }

                    else if(newcell.childNodes[1].name == 'rsvSLOC[]'){
                        let val = $(table.rows[(rowCount-1)]).find(`[name='rsvSLOC[]']`).val() || 0;
                        if(val){
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            }).val(val).trigger('change');
                        } else {
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            });
                        }
                    }

                    else if(newcell.childNodes[1].name == 'rsvOriginPlant[]'){
                        let val = $(table.rows[(rowCount-1)]).find(`[name='rsvOriginPlant[]']`).val() || 0;
                        if(val){
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            }).val(val).trigger('change');
                        } else {
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                ...dropdownParent
                            });
                        }
                    }
                } catch(error){ console.log(`There's error on column ${i}`, error) }
            }
        }
        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}
    }

    function deleteBaris(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = 0;
        }

        if($('#modalFile').hasClass('show'))
            var mv_type = $('input[name="MovementType"]').length > 0 ? $('input[name="MovementType"]').val() : '';
        else
            var mv_type = $('select[name="MovementType"]').length > 0 ? $('select[name="MovementType"]').val() : '';
        if(mv_type == '311'){
            var obj_value = $(objRow).parents('tr').find('[name="rsvMaterials[]"]').val();
            if(data_is_not_available_in_sloc.includes(obj_value)){
                var index = data_is_not_available_in_sloc.indexOf(obj_value);
                if (index !== -1) {
                  data_is_not_available_in_sloc.splice(index, 1);
                }
            }
        }

        if(rowCount>2){
            if(rowIndex == 1){
                // Swal.fire('First Item Removal', 'Cannot remove first item. If you want to change item, edit it instead of remove', 'warning');
                try {
                    $(objRow).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                    $(objRow).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                    $(objRow).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                    $(objRow).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                    $(objRow).parents('tr').removeClass('bg-warning');
                } catch(error){}
            } else {
                // table.deleteRow(rowCount -1);
                table.deleteRow(rowIndex);
            }
        } else {
            // Swal.fire('Item Removal', 'Cannot remove first item, the data that will be sent needs to be at least one. If you want to change item, edit it instead of remove', 'warning');
            try {
                $(objRow).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(objRow).parents('tr').find('[name="rsvLastPrice[]"]').val('');
                $(objRow).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(objRow).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                $(objRow).parents('tr').removeClass('bg-warning');
            } catch(error){}
        }

        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}

        try {
            calculateGrandTotal(table);
        } catch(error){}
    }
</script>
@endsection
