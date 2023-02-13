@extends('layouts.default')

@section('title', 'Request Purchase Requisition Market List')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/template/css/card-form-step/main.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
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
td.purpose {
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
}

select[readonly].select2-hidden-accessible + .select2-container {
    pointer-events: none;
    touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
    background: #e9ecef;
    box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
    display: none;
}

/*.modal-obstruct:nth-of-type(odd) {
    z-i*/ndex: 1054 !important;
}
/*.modal-backdrop.show:nth-of-type(even) {
    z-index: 1052 !important;
}*/
/*.modal-backdrop.show:nth-of-type(3) {
    z-index: 1055 !important;
}
.modal-custom-material:nth-of-type(odd) {
    z-index: 1057 !important;
}*/
#table-marketlist_info{
    text-align: left;
}
.relative-td {
    position: relative;
}
.select-plain {
    padding: 0 !important;
    border: none !important;
    outline: none !important;
    font-size: 0.75rem !important;
    color: #6c7293 !important;
    max-width: 100px !important;
    text-align: left !important;
    margin: 0 -5px !important;
}
.modal .dataTables_info{
    float: left;
}
.modal .dataTables_paginate{
    float: right;
}
.remove-click {
  pointer-events: none;
  cursor: default;
  text-decoration: none;
  color: black;
}
.wrapper-table{
    position: relative;
}
.wrapper-table:after {
    clear: both;
    float: none;
    content: " ";
    display: block;
}
.table-container-h {
    overflow: auto;
}
.reject-info {
    max-height: 200px
}
.reject-info h6 {
    line-height: 1.5;
    max-height: 60px;
    /*overflow: hidden;*/
    text-overflow:ellipsis;
    overflow:hidden;
    display: -webkit-box !important;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    white-space: normal;
}
.custom-material-wrapper {
    position: absolute;
    right: 0;
    top: -4em;
}
@media (min-width: 576px){
  .modal-dialog-custom-width {
    max-width: 1200px;
  }
}
.badge-additional {
    position: absolute;
    top: -1.2em;
    right: -11px;
    border: 2px solid #fff;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition Market List</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Request List</h4>
                        @if ($data['allow_add_request'])
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalRequest">Add Request</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <!-- <label class="col-sm-2 col-form-label">Request Date</label> -->
                                <div class="col-sm-2">
                                    <select class="form-control select-plain" name="filter_type" id="filter_type">
                                      <option value="REQUEST_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'REQUEST_DATE') selected default @endif>Request Date</option>
                                      <option value="DELIVERY_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'DELIVERY_DATE') selected default @endif>Delivery Date</option>
                                    </select>
                                    <div class="mt-1">
                                        <small class="text-muted">* Click to change filter</small>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="date_from" id="date_from" value="{{ $data['request_date_from'] }}" placeholder="Date From...">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="date_to" id="date_to" value="{{ $data['request_date_to'] }}" placeholder="Date To...">
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
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/purchase-requisition-marketlist/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap; width: 100%; min-width: 1500px">
                    <thead>
                        <tr>
                            <th style="width: 3% !important"></th>
                            <th style="width: 10% !important">PR Number</th>
                            {{--<th style="width: 8% !important">PR Detail</th>--}}
                            <th style="width: 9% !important">Form Number</th>
                            <th style="width: 8% !important">Status PR</th>
                            <th style="width: 12% !important">Last Approver PR</th>
                            {{--<th >Reason</th>--}}
                            <th style="width: 10% !important">Req. Date</th>
                            <th style="width: 8% !important">Deliv. Date</th>

                            {{--<th>Purpose</th>--}}
                            {{--<th>Tracking No.</th>--}}
                            {{--<th>Tracking No. Desc</th>--}}
                            <th style="width: 12% !important">Cost Center</th>
                            <th style="width: 8% !important">SLOC</th>
                            <th style="width: 8% !important">Grand Total</th>
                            <th style="width: 8% !important">PO Number</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalRequest" role="dialog" aria-labelledby="modalRequestLabel" tabindex="-1" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="modal-request-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalRequestLabel">Form - Add Purchase Requisition Market List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodymodalRequest" style="overflow: hidden">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition-marketlist/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <div>
                            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px; cursor: pointer;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Requestor Information <small class="text-primary" style="font-size: 10px;display: inline-block;margin-left: 5px;">Click here for more info...</small></h3>
                        </div>
                        <div id="collapseOne" class="collapse">
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
                                    <input type="text" value="{{$data['plant']}}" name="Requestor_Plant_ID" id="Requestor_Plant_ID" class="form-control" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label>Requestor Division</label>
                                    <input type="text" value="{{$data['division']}}" name="Requestor_Division" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
                                <input type="hidden" name="Request_Date" id="Request_Date" value="{{ date('Y-m-d')}}">
                                <input type="hidden" name="Template_Code" id="Template_Code" value="">
                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                <input type="text" value="(auto generate)" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Doc Type <span class="red">*</span></label>
                                <select readonly class="form-control select2 readonly" name="doc_type" id="doc_type" style="width: 100%">
                                    <option value="">--- Select Document Type ---</option>
                                    <option value="YOPX" selected default>PR OPEX MID</option>
									{{--<option value="YCPX">PR CAPEX MID</option>--}}
                                    {{--<option value="YOCN">PR CONSIGNMENT MID</option>--}}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Ship to Plant <span class="red">*</span></label>
                                <select readonly name="plant" class="form-control select2 readonly" id="plant" style="width: 100%">
                                    <option value="">--- Select Plant ---</option>
                                    @foreach ($data['list_plant'] as $list_plant)
                                        @if(strtoupper($list_plant->SAP_PLANT_ID) == strtoupper($data['plant']))
                                            <option value="{{$list_plant->SAP_PLANT_ID}}^-^{{$list_plant->SAP_PLANT_NAME}}" selected default>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" readonly id="tableRow" name="tableRow">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                @php
                                    $readonly_sloc = isset($data['list_sloc']) && count($data['list_sloc']) > 1 || isset($data['list_sloc']) && count($data['list_sloc']) == 0 ? '' : 'readonly';
                                @endphp

                                <label>SLOC <span class="red">*</span></label>
                                <select class="form-control select2" name="sloc" id="sloc" required style="width: 100%" {{ $readonly_sloc }}>
                                    <option value="">--- Choose SLOC ---</option>
                                    @if(isset($data['list_sloc']) && count($data['list_sloc']) == 1)
                                        @foreach ($data['list_sloc'] as $list_sloc)
                                            <option value="{{$list_sloc['STORAGE_LOCATION']}}" selected default>{{$list_sloc['STORAGE_LOCATION']}} - {{$list_sloc['STORAGE_LOCATION_DESC']}}</option>
                                        @endforeach
                                    @else
                                        @foreach ($data['list_sloc'] as $list_sloc)
                                            <option value="{{$list_sloc['STORAGE_LOCATION']}}">{{$list_sloc['STORAGE_LOCATION']}} - {{$list_sloc['STORAGE_LOCATION_DESC']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                @php
                                    $readonly = isset($data['list_cost_center']) && count($data['list_cost_center']) > 1 || isset($data['list_cost_center']) && count($data['list_cost_center']) == 0 ? '' : 'readonly';
                                @endphp

                                <label>Cost Center <span class="red">*</span></label>&nbsp;&nbsp;<i class="fa fa-spinner fa-spin" id="spinner_cost_center" style="display:none;"></i>
                                <select class="form-control select2" {{ $readonly }} name="cost_center" id="cost_center" required style="width: 100%">
                                     <option value="">--- Choose Cost Center ---</option>
                                     @foreach ($data['list_cost_center'] as $list_cost_center)
                                        <option value="{{$list_cost_center->SAP_COST_CENTER_ID}}" @if($data['cost_center_id'] == $list_cost_center->SAP_COST_CENTER_ID) selected default @endif><b>{{ isset($list_cost_center->SAP_COST_CENTER_ID) ? $list_cost_center->SAP_COST_CENTER_ID : '-'  }}</b> - {{ isset($list_cost_center->SAP_COST_CENTER_DESCRIPTION) ? $list_cost_center->SAP_COST_CENTER_DESCRIPTION : '-' }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Delivery Date <span class="red">*</span></label>
                                <input type="text" required name="Delivery_Date" value="{{date('d F Y')}}" class="form-control datepicker3"/>
                            </div>
                            <div class="col-md-6">
                                <label>Purpose / Notes <span class="red">*</span></label>
                                <input type="text" class="form-control" name="purpose" required placeholder="insert your purpose / notes on requesting purchase requisition"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="position: relative;">
                            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                            <div style="position: absolute;right: 0;top: -5px">
                                <a class="btn btn-primary btn-sm text-white select-template" data-toggle="modal" data-target="#marketlist-template"><i class="fa fa-plus"></i>&nbsp;&nbsp;Select / Edit from template</a>
                            </div>
                        </div>
                        <div class="portlet-body table-both-scroll mb-4" style="overflow: auto;">
                            <table class="table table-bordered" id="reqForm">
                                <thead>
                                    <th style="width: 8%">Items</th>
                                    <th style="width: 26%" class="thead-apri">Material Name</th>
                                    <th style="width: 8%" class="thead-apri">Unit</th>
                                    <th style="width: 10%" class="thead-apri">Qty</th>
                                    <th style="width: 10%" class="thead-apri">Purch. Group</th>
                                    <th style="width: 24%" class="thead-apri">Note</th>
                                    {{--<th style="width: 12%" class="thead-apri">Last Purchase Price</th>--}}
                                    <th style="width: 12%" class="thead-apri">Total</th>
                                    <th style="width: 12%" class="thead-apri">Action</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="text-center">No Material Selected</td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8 col-8"></div>
                            <div class="col-md-4 col-4">
                                <div class="form-group">
                                    <label class="control-label" style="font-size: .875rem; color: #001737"><b>Grand Total</b></label>
                                    <div>
                                        <input style="font-weight: bold; background-color: #fff; font-size: 15px" type="text" class="form-control" placeholder="0.00" readonly="" name="grandTotal" id="grandTotal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="row">
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
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="modal-detail-content">
			<div class="modal-header">
				<div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Purchase Requisition Market List Detail </h5>
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

<div id="marketlist-template" class="modal fade modal-obstruct" data-keyboard="false" data-backdrop="static">
    <div id="modal-input" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span id="modalTitleAset">Select Template</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="uic-wrapper no-touch">
                    <ul>
                        <li>
                            <ul class="cards-wrapper">
                                <li class="card card-front text-center active">
                                    <div class="template-content-spinner" id="spinner-template" hidden>
                                        <h6>Loading template&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i></h6>
                                    </div>
                                    <div class="template-content-spinner" id="spinner-template-failed" hidden>
                                        <h6 style="line-height: 1.5">Failed to load template, please check your connection <br>or <br><a style="cursor: pointer;" class="text-primary btn-retry-template"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Retry to load</a></h6>
                                    </div>
                                    <div class="row justify-content-center" id="template-content-load">
                                    </div>
                                </li>
                                <li class="card card-middle text-center">
                                    <div class="template-item-spinner" id="spinner-template-item">
                                        <h6>Loading template's items&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i></h6>
                                    </div>
                                    <div class="row justify-content-center" id="template-item-load">
                                        <div class="col-12" id="table-wrapper" hidden>
                                            <div class="custom-material-wrapper">
                                                <div style="position: relative;" class="additional-material-btn-wrapper">
                                                    <button data-toggle="modal" data-target="#modalCustomMaterial" type="button" class="btn px-3 btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Additional Material</button>
                                                </div>
                                            </div>

                                            <table style="white-space: nowrap; width:100% !important;" id="table-marketlist" class="table table-bordered" cellspacing="0">
                                               <thead>
                                                    <tr>
                                                        <th style="width: 30% !important">Material Name</th>
                                                        <th style="width: 7% !important">Unit</th>
                                                        <th style="width: 15% !important">Qty</th>
                                                        <th style="width: 15% !important">Last Purchase Price</th>
                                                        <th style="width: 28% !important">Note</th>
                                                    </tr>
                                               </thead>
                                            </table>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <nav>
                                <ul>
                                    <li><button class="btn-back" href="#0"><i class="fa fa-arrow-left"></i></button></li>
                                    <li><button disabled class="btn-next btn btn-block btn-primary text-white"><h6 class="mb-0">Choose Items&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></h6></button></li>
                                    <li><button disabled class="btn-finish btn btn-block btn-primary text-white hide"><h6 class="mb-0">Finish&nbsp;&nbsp;<i class="fa fa-check"></i></h6></a></li>
                                </ul>
                            </nav>
                        </li>
                    </ul>
                    
                </div>
            </div>
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>--}}
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

<div class="modal fade modal-custom-material" id="modalCustomMaterial" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-custom-width" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <div>
                        <h5 class="modal-title mr-3 mb-0" id="modalPOLabel">Additional Material Selection</h5>
                        <div>
                            <small class="text-muted">* Use this only if there is no material that you search available on the template</small>
                        </div>
                    </div>
                    {{--<div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>--}}
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalMaterial">
                <div class="form-group item-container">
                  <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Search Material </h3>
                  <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important; overflow: auto;">
                    <form class="form-horizontal" method="get" id='form-add-material' action='' style="width: 100%">
                      <table class="table table-bordered smallfont table-request" id="reqFormCustommaterial">
                          <thead>
                              <tr>
                                <th style="width: 8%">Item</th>
                                <th style="width: 30%">Material</th>
                                <th style="width: 10%">Qty</th>
                                <th style="width: 25%">Note</th>
                                <th style="width: 10%">UoM</th>
                                <th style="width: 25%">Last Purchase Price</th>
                                <th style="width: 2%">Actions</th>
                              </tr>
                          </thead>
                          <tbody id="tbody-additional-material">
                              <tr class="rowToClone">
                                  <td style="max-width: 1px">
                                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="1" readonly="">
                                  </td>
                                  <td style="max-width: 1px">
                                    <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                                        <option value="" default selected>---- Choose Material ----</option>
                                    </select>
                                    <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                                  </td>
                                  <td style="max-width: 1px; position: relative;">
                                    <input type="text" oninput="qtyInputAdditionalMaterial(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                                    <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                  </td>
                                  <td style="max-width: 1px; position: relative;">
                                      <input type='text' class='form-control' oninput='noteInputAdditional(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>
                                  </td>
                                  <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
                                  </td>
                                  <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" value="0.00" id="rsvLastPrice" placeholder="Automatically filled">
                                  </td>
                                  <td>
                                    <div class="btn-group" style="min-width:80px">
                                        <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBarisAdditional('reqFormCustommaterial', this)">-</button>
                                        <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqFormCustommaterial')">+</button>
                                    </div>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white btn-block btn-selection"><i class="fa fa-check"></i>&nbsp;&nbsp;Apply Changes</button>
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
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/ellipsis.js"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/js/card-form-step/main.js"></script>
<script src="/template/js/card-form-step/plugins.js"></script>
<script type="text/javascript">

    // Change scrollbar to existing modal
    $(document).on('hidden.bs.modal', '.modal',
    () => $('.modal:visible').length && $(document.body).addClass('modal-open'));

    var is_error_template = false;
    var data_item = [];
    var data_item_additional = [];
    var item_additonal_deleted = [];
    var item_additonal_added = [];

    var template_selected_code = '';
    var template_selected_code_temp = '';
    var is_hide_back_btn = false;
    var is_detail_popup = false;
    var global_purchasing_group = null;

    $('#marketlist-template').on('show.bs.modal', function(){
        if(is_detail_popup){
            var template_code_detail = $('#Template_Code_Detail').val() || '';
            if(template_code_detail){
                template_selected_code = template_code_detail;
                template_selected_code_temp = template_code_detail;
            }
        }

        try {
            $('#template-content-load').html('');
            $('#spinner-template').prop('hidden', false);
            $('#spinner-template-failed').prop('hidden', true);
        } catch(error){}

        if(template_selected_code){
            $('#spinner-template').prop('hidden', true);
            var element_to_add = `<h6 style="line-height: 1.5">You have choosen ${template_selected_code} as your template before <br> Now preparing items &nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i></h6>`;
            $(element_to_add).appendTo('#template-content-load');
            is_hide_back_btn = true;
            setTimeout(function(){
                $('.btn-next').prop('disabled', false);
                $('.btn-next').trigger('click');
            }, 3000);
        }
        else {
            is_hide_back_btn = false;
            var emp_id = $('input[name="Requestor_Employee_ID"]').val() || 0;
            $.ajax({
                url: '/finance/purchase-requisition-marketlist/request',
                method: 'GET',
                data: {'type':'recipe-template', 'employee_id': emp_id},
                dataType: 'json',
                success : function(response){
                    if(response.hasOwnProperty('data') && response.data.length > 0){
                        is_error_template = false;
                        var element_to_add = '';
                        var col = 'col-4 col-md-4';
                        for(var loop=0;loop<response.data.length;loop++){
                            element_to_add += `<div class="switch-field ${col}">
                                <input type="radio" id="radio-${loop+1}" name="template-selection" value="${response.data[loop]}"/>
                                <label for="radio-${loop+1}">${response.data[loop]}</label>
                            </div>`;
                        }
                        $(element_to_add).appendTo('#template-content-load');
                    }
                },
                error: function(xhr){
                    $('#spinner-template-failed').prop('hidden', false);
                    is_error_template = true;
                },
                complete: function(){
                    $('#spinner-template').prop('hidden', true);
                    setTimeout(function(){
                        if(!is_error_template)
                            $('.btn-next').prop('disabled', false);
                    }, 500)
                }
            })
        }
    });

    $(document).on('click', '.btn-next', function(){
        try {
            if(is_detail_popup)
                var form = $('#modalFile #modalDetailAjax')[0];
            else
                var form = $('#modalRequest #formRequest')[0];
        } catch(error){
            var form = {};
        }

        data_item = [];
        // console.log(item_additonal_added, data_item_additional);
        item_additonal_added = [];
        data_item_additional= [];
        item_additonal_deleted = [];
        try {
            $('.badge-additional').remove();
        } catch(error){}

        if(is_hide_back_btn)
            $('.btn-back').prop('hidden', true);
        else 
            $('.btn-back').prop('hidden', false);

        var check_element = document.getElementsByClassName('toRight').length;
        if(check_element > 0){
            $('.btn-finish').prop('disabled', true);
            try {
                if(template_selected_code){
                    var template_select = template_selected_code;
                } else {
                    var template_select = document.querySelector('input[name="template-selection"]:checked').value;
                    data_item_additional = [];
                    template_selected_code_temp = template_select;
                }
            } catch(error){
                var template_select = '';
            }

            $.ajax({
                url: '/finance/purchase-requisition-marketlist/request',
                method: 'GET',
                data: {'type':'recipe-template-item', 'template': template_select},
                dataType: 'json',
                success : function(response){
                    try {
                        if(response.data.length > 0){
                            var purchasing_group = response.data[0].PURCH_GROUP != 'undefined' ? response.data[0].PURCH_GROUP : null;
                            if(!purchasing_group){
                                setTimeout(function(){
                                    Swal.fire('Purchasing Group Template', 'Opps, purchasing group of selected template is not available or not yet set, please update the purchasing group to get proper materials');
                                }, 1000)
                                return false;
                            }
                            global_purchasing_group = purchasing_group;
                        }
                    } catch(error){}

                    $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    setTimeout(function(){
                        $('#table-wrapper').prop('hidden', false);
                        try{
                          var table = $('#table-marketlist').DataTable({
                            "dom":'<"abs-search row align-items-end mb-2 mt-4" <"button-export-wrapper col-12" <"row align-items-end" <"col-8 btn-show-all text-left"B> <"col-4"fl> >>> <"wrapper-table"rtip >',
                            "paging": true,
                            "pageLength": 20,
                            "lengthChange": false,
                            "buttons": [],
                            "data": response.data,
                            "order": [[ 0, "" ]],
                            "columns": [
                               { "data": "MATERIALNAME",
                                 className: 'text-left',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="materialItemTemplate[]" value="'+id+'"><input type="hidden" name="materialItemCode[]" value="'+full.SAPMATERIALCODE+'">';
                                 },
                                 width : "30%"
                               },
                               { "data": "UOM",
                                 className: 'text-center',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="unitItemTemplate[]" value="'+id+'">';
                                 },
                                 width : "7%",
                               },
                               { "data": null, 
                                 className : 'relative-td',
                                 "defaultContent": "<input type='text' value='0' oninput='qtyInput(this)' class='form-control' name='qtyItemTemplate[]' id='qtyItemTemplate' placeholder='Input Qty...'/><div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>",
                                 width : "15%",
                                 orderable: false,
                                 sortable: false
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' readonly class='form-control' name='costItemTemplate[]' id='costItemTemplate' placeholder='Autofill...' value='0.00' />",
                                 width : "15%",
                                 orderable: false,
                                 sortable: false
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' class='form-control' oninput='noteInput(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>",
                                 width : "28%",
                                 orderable: false,
                                 sortable: false
                               },
                            ],
                            rowCallback: function (row, data) {
                                var input = $('[name="marketlistMaterialNumber[]"]', form);
                                if(input.length > 0 && template_selected_code){
                                    var material_code_selected = data.SAPMATERIALCODE;
                                    for (var i = 0; i < input.length; i++) {
                                        var a = input[i];
                                        if(a.value == material_code_selected){
                                            // var material_number = document.getElementsByName('marketlistMaterialNumber[]')[i].value,
                                            // material_name = document.getElementsByName('marketlistMaterialName[]')[i].value,
                                            // material_unit = document.getElementsByName('marketlistMaterialUnit[]')[i].value,
                                            // last_price = document.getElementsByName('marketlistMaterialLastPriceFormatted[]')[i].value,
                                            // last_price_plain = document.getElementsByName('marketlistMaterialLastPrice[]')[i].value,
                                            // notes = document.getElementsByName('marketlistMaterialNote[]')[i].value,
                                            // qty = document.getElementsByName('marketlistMaterialQty[]')[i].value;

                                            last_price = $('[name="marketlistMaterialLastPriceFormatted[]"]', form)[i].value,
                                            notes = $('[name="marketlistMaterialNote[]"]', form)[i].value,
                                            qty = $('[name="marketlistMaterialQty[]"]', form)[i].value;

                                            // Set default state
                                            $(row).removeClass('bg-success');
                                            $(row).find('[name="qtyItemTemplate[]"]').val('0');
                                            $(row).find('[name="costItemTemplate[]"]').val('0.00');
                                            $(row).find('[name="noteItemTemplate[]"]').val('');

                                            try {
                                                if(parseFloat(qty) > 0){
                                                    $(row).addClass('bg-success');
                                                    $(row).find('[name="qtyItemTemplate[]"]').val(qty);
                                                    $(row).find('[name="costItemTemplate[]"]').val(last_price);
                                                    $(row).find('[name="noteItemTemplate[]"]').val(notes);
                                                }
                                            } catch(error){
                                                console.log(error);
                                            }
                                        }
                                    }
                                }
                            },
                            initComplete : function(settings, json){
                                var data_table = this.api().rows().data();
                                var input = $('[name="marketlistMaterialNumber[]"]', form);
                                var is_additional = $('[name="marketlistAdditional[]"]', form);
                                // console.log(input, is_additional);
                                if(input.length > 0 && template_selected_code){
                                    for (var i = 0; i < input.length; i++) {
                                        var material_number = $('[name="marketlistMaterialNumber[]"]', form)[i].value,
                                        material_name = $('[name="marketlistMaterialName[]"]', form)[i].value,
                                        material_unit = $('[name="marketlistMaterialUnit[]"]', form)[i].value,
                                        purch_group = $('[name="marketlistMaterialPurGroup[]"]', form)[i].value,
                                        last_price = $('[name="marketlistMaterialLastPriceFormatted[]"]', form)[i].value,
                                        last_price_plain = $('[name="marketlistMaterialLastPrice[]"]', form)[i].value,
                                        notes = $('[name="marketlistMaterialNote[]"]', form)[i].value,
                                        qty = $('[name="marketlistMaterialQty[]"]', form)[i].value;

                                        try {
                                            var is_item_additional_check = is_additional[i];
                                            if(is_item_additional_check.value.toString().toUpperCase() == 'Y'){
                                                var obj_additional = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice':last_price, 'mtlastpriceplain': last_price_plain, 'mtnote': notes, 'mtqty': qty, 'purgroup': purch_group, 'is_additional': true};
                                                item_additonal_added.push(obj_additional);
                                                data_item_additional.push(obj_additional);
                                            }
                                        } catch(error){}

                                        data_table.each(function (data, index) {
                                            var material_code_selected = data.SAPMATERIALCODE;
                                            var a = input[i];
                                            
                                            if(a.value == material_code_selected){
                                                try {
                                                    var item_additional = is_additional[i];
                                                    if(item_additional.value.toString().toUpperCase() == 'Y'){
                                                        data_item_additional = data_item_additional.filter(x => x.mtnumber != material_code_selected);
                                                        item_additonal_added = item_additonal_added.filter(x => x.mtnumber != material_code_selected);
                                                        // var obj_item_in_template = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice':last_price, 'mtlastpriceplain': last_price_plain, 'mtnote': notes, 'mtqty': qty, 'purgroup': purch_group};
                                                        // console.log(data_item, data_item_additional);   
                                                    }
                                                } catch(error){}

                                                if(parseFloat(qty) > 0){
                                                    var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice':last_price, 'mtlastpriceplain': last_price_plain, 'mtnote': notes, 'mtqty': qty, 'purgroup': purch_group};
                                                    // data_item = data_item.filter(x => x.mtnumber != material_number);
                                                    data_item.push(obj_to_add);
                                                }
                                            }
                                        });
                                    }
                                }
                                // console.log(data_item_additional);
                                // Assign badge when modal loaded
                                // if any additional material
                                try {
                                    data_item_additional = data_item_additional.filter(x => parseFloat(x.mtqty) > 0);
                                    setTimeout(function(){
                                        if(data_item_additional.length)
                                            $(`<div class="badge badge-pill badge-danger badge-additional">${data_item_additional.length}</div>`).appendTo('.additional-material-btn-wrapper');
                                    }, 200);
                                } catch(error){}

                                setTimeout(function(){
                                    $('.btn-finish').prop('disabled', false);
                                }, 500);
                                $(`<div style="cursor: pointer" class="mb-2">
                                  <div>
                                    <h6 style="margin: 0">
                                      <a class="text-primary btn-show-hide-material"><span class="icon-show-hide" data-status="hidden"><i class="fa fa-eye"></i></span>&nbsp;&nbsp;<span id="show-hide-text">Show</span> All Materials</a>
                                    </h6>
                                  </div>
                                  <small class="text-muted">* This toggle will also show inactive materials</small>
                                </div>`).appendTo('.btn-show-all');
                            }
                          });

                          $(document).on('click','.btn-show-hide-material', function(){
                            var status_now = $('.icon-show-hide', this).attr('data-status')
                            if(status_now){
                                var selected_template = $('#Template_Code_Detail').val() || '';
                                if(!selected_template)
                                    selected_template = template_select;

                                if(status_now == 'hidden'){
                                    $('.btn-finish').prop('disabled', true);
                                    $('.btn-show-hide-material').addClass('remove-click');
                                    var element_to_add = (`<div class="wrapper-loading" style="position:absolute;top:0;left:0;width:100%;height:100%;"><h6 style="position: relative; top: -3em">Loading data... &nbsp;<i class="fa fa-spin fa-spinner"></i></h6></div>`);
                                    $(element_to_add).appendTo('.wrapper-table');
                                    setTimeout(function(){
                                        table.ajax.url(`?type=recipe-template-item&show_inactive=1&template=${selected_template}`).load(function(data){
                                            $('.btn-show-hide-material').removeClass('text-primary');
                                            $('.btn-show-hide-material').addClass('text-success');
                                            $('#show-hide-text').text('Showing');
                                            $('.icon-show-hide').attr('data-status', 'showed')
                                            $('.icon-show-hide').html('<i class="fa fa-check"></i>');
                                            $('.wrapper-loading').remove();
                                            $('.btn-finish').prop('disabled', false);
                                            $('.btn-show-hide-material').removeClass('remove-click');
                                            // console.log(Object.values($('#table-marketlist').DataTable().rows().data()), data_item);
                                        });
                                    }, 500);
                                } 
                            }
                          })

                        } catch(error){
                            Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                            console.log(error.message)
                        }
                    }, 500);
                },
                error: function(xhr){
                    // $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    $('#marketlist-template').modal('hide');
                    setTimeout(function(){
                        Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                    }, 500);
                },
                complete: function(){
                    $('#spinner-template-item').prop('hidden', true);
                    // $('.btn-finish').prop('disabled', false);
                }
            });
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

    $("#toggleVendor").change(function(){
        if(this.checked){
            $(".formVendor").show();
            $("#vendor_reason").attr('required','');
            $("#vendor_currency").attr('required','');
            $("#vendor_search").attr('required','');
            $('#vendor_currency').select2('destroy').select2();
        }else{
            $(".formVendor").hide();
            $("#vendor_reason").removeAttr('required');
            $("#vendor_currency").removeAttr('required');
            $("#vendor_search").removeAttr('required');
            clear_form_elements('formVendor');
        }

    });
    var objFile = {};var objJsonDetail = {}; var objRow = {};

    $('#modalRequest').on('show.bs.modal', function (e) {
        $("#modalRequest .select2").select2({
            dropdownParent: $('#modal-request-content'),
            placeholder: 'Select Data'
        });
    });

    /*****************************************************************************/
    /*****************************************************************************/
    /* FUNCTION DARI INTRANET BIZNET */
    /*****************************************************************************/
    /*****************************************************************************/
    $(document).ready( function () {
        $('#delivDate').datepicker({
            autoclose: true
        });

        $('.datepicker3').datepicker({
            dateFormat: 'dd MM yy',
            minDate: 0,
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


        var request_date_from =  $('#date_from').val();
        var request_date_to =  $('#date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var data_cc = [];
        var data_sloc = [];
        var data_filter_menu = {};
        var table = $('#requestList').DataTable({
            "pageLength": 100,
            // "lengthChange": true,
            "searching": false,
            "responsive": true,
            // "dom": '<"dt-buttons"Bfl>rtip',
            "dom":'<"abs-search row align-items-end mb-2" <"button-export-wrapper col-8"Bfl>> <"table-wrapper table-container-h"rt> ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/purchase-requisition-marketlist/request/getData",
                "dataSrc": function ( json ) {
                    data_cc = [...new Set(json.data.map(item => `${item.COSTCENTERID} - ${item.COSTCENTER}`))];
                    data_sloc = [...new Set(json.data.map(item => `${item.SLOC_ID} - ${item.SLOC}`))];
                    //Make your callback here.
                    if(json.hasOwnProperty('data') && json.data)
                       return json.data;
                    else
                       return [];
                },
                "data" : function(d){
                    d.employee_id = updateBy;
                    d.filter = "";
                    d.value = "";
                    d.status = status;
                    d.date_from = request_date_from;
                    d.date_to = request_date_to;
                    d.filter_type = $('#filter_type').val();
                    d.SHIP_TO_COST_CENTER = $('.filter-select-costcenter').val();
                    d.SHIP_TO_SLOC = $('.filter-select-sloc').val();
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
            "scrollX": false,
            "columns": [
                {
                 "data": null,
                    "render": function (id, type, full, meta)
                    {
                        // var select_btn = `<input type="checkbox" name="marketlistItemPrint[]" value="${full.FORM_NUMBER}">`;
                        var print_btn = `<button type="button" class="btn btn-secondary text-white selected-print" data-ml-no="${full.FORM_NUMBER}"><i class="fa fa-print"></i></button>`;
                        return print_btn;
                    },
                    className : 'text-center',
                    width: '5%',
                    sortable: false,
                    orderable: false
                },
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        if(id)
                            return id;
                        else
                            return '-';
                    },
                    className : 'text-center',
                    width: '10%'
                },
                { "data": "FORM_NUMBER",
                    "render": function (id, type, full, meta)
                    {
                        if(id){
                            return '<a href="#" class="text-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile">'+id+'</i></a>';
                        } else 
                            return '-';
                    },
                    width: '9%'
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id){
                        if(id=="APPROVED" || id=="REQUESTED" || id==""){
                            id="WAITING FOR APPROVAL";
                            return '<a href="javascript:void(0);" style="text-decoration: none; font-weight:bold;"">WAITING</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:green;font-weight:bold;">FINISHED</a>';
                        }else{
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:red;font-weight:bold;">'+id+'</a>';
                        }
                    },
                    width: '8%'
                },
                { "data": "LAST_APPROVAL_NAME",
                  width: '12%'
                },
                { "data": "REQ_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    },
                    width: '10%'
                },
                { "data": "DELIVERY_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY");
                    },
                    width: '8%',
                    className: 'text-center'
                },
                // { "data": "PURPOSE" },
                { "data": "COSTCENTER",
                  width: '12%'
                },
                {
                    "data" : "SLOC",
                    "render": function (id, type, full, meta){
                        if(id){
                            return id;
                        }
                        else {
                            return '-';
                        }
                    },
                    width: '8%'
                },
                { "data": "GRANDTOTAL",
                  "render": function (id, type, full, meta)
                    {
                        return number_format(id, 0, '.', ',');
                    },
                  width: '8%',
                  className: 'text-right'

                },
                {
                    "data" : "PO_NUMBER",
                    "render": function (id, type, full, meta){
                        if(id){
                            return '<a href="#" class="text-primary" onclick="getDetailPO(\''+id+'\')" data-toggle="modal" data-target="#modalPO" >'+id+'</a>';
                        }
                        else {
                            return '-';
                        }
                    },
                    className: 'text-center',
                    width: '8%'
                }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 2, "desc" ]],
            "columnDefs": [{
              targets: 5,
              // render: $.fn.dataTable.render.ellipsis( 40 ),
              className: "text-left purpose",
              width: "25%"
            }],
            initComplete: function() {
                var text_order = 0;
                var table_obj = this.api();
                try {
                    $('.selected-print').prop('disabled', false);
                } catch(error){}
                // var printBtn = $(`<div class="mb-2 col-1 text-right"><button type="button" class="btn btn-secondary btn-block selected-print" disabled><i class="fa fa-print"></i>&nbsp;&nbsp;Print</a></div>`)
                // .appendTo( $('.abs-search') );

                this.api().columns([6, 7]).every( function (i) {
                    var column = this;
                    var text = [{'name':'Cost Center', 'filter_name':'costcenter'}, {'name':'SLOC', 'filter_name':'sloc'}];
                    var paramName = ['SHIP_TO_COST_CENTER', 'SHIP_TO_SLOC'];
                    var col_length = 'col-2';
                    var select = $(`<div class="content-filter mb-2 ${col_length}"><label>Filter By ${text[text_order].name}</label><div><select class="form-control select2-filter-marketlist filter-select-${text[text_order].filter_name} mr-3" data-filter="${text_order}"><option value="">Choose Data</option></select></div></div>`)
                        .appendTo( $('.abs-search') )
                        .on('select2:select', function (e) {
                            var value = e.params.data.id;
                            var target = $(e.target).data('filter');
                            var val = $.fn.dataTable.util.escapeRegex(
                                value
                            );
                            if(val){
                              data_filter_menu[paramName[target]] = val
                            }

                            const qs = Object.keys(data_filter_menu)
                            .map(key => `${key}=${data_filter_menu[key]}`)
                            .join('&');
                            if(qs)
                              table_obj.ajax.url(`request/getData?${qs}`).load();

                        }).on("select2:unselecting", function(e) {
                            var target = $(e.target).data('filter');
                            try {
                              delete data_filter_menu[paramName[target]]
                              const qs = Object.keys(data_filter_menu)
                              .map(key => `${key}=${data_filter_menu[key]}`)
                              .join('&');
                              if(qs)
                                table_obj.ajax.url(`request/getData?${qs}`).load();
                              else
                                table_obj.ajax.url(`request/getData`).load();

                            } catch(error){
                              console.log(error);
                            }
                            $(this).data('state', 'unselected');
                        }).on("select2:open", function(e) {
                            try {
                              if ($(this).data('state') === 'unselected') {
                                  $(this).removeData('state'); 

                                  var self = $(this).find('.select2-filter-marketlist')[0];
                                  setTimeout(function() {
                                      $(self).select2('close');
                                  }, 0);
                              }
                            } catch(error){}   
                        });
                    if(i == 6){
                      $(data_cc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }
                    else if(i == 7){
                      $(data_sloc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }

                    text_order++;
                });
                $('.select2-filter-marketlist').select2({
                  placeholder: 'Choose data',
                  allowClear: true
                });

                var marketlist_selected = [];
                $(document).on('change', '[name="marketlistItemPrint[]"]', function(){
                    var marketlist_no = this.value;
                    if(this.checked){
                        try {
                            marketlist_selected.push(marketlist_no);
                        } catch(error){}
                        $('.selected-print').prop('disabled', false);
                        $('[name="marketlistItemPrint[]"]').not(this).prop('disabled', true);
                    } else {
                        try {
                            marketlist_selected = marketlist_selected.filter((elem) => elem !== marketlist_no);
                        } catch(error){}
                        $('.selected-print').prop('disabled', true);
                        $('[name="marketlistItemPrint[]"]').prop('disabled', false);
                    }   
                });

                function downloadFile(response) {
                  var blob = new Blob([response], {type: 'application/pdf'})
                  var url = URL.createObjectURL(blob);
                  // location.assign(url);
                  var a = document.createElement('a');
                  a.href = url;
                  a.setAttribute('target', '_blank');
                  a.click();
                }
                $(document).on('click', '.selected-print', function(){
                    var obj_target = this;
                    var selected_no = [$(this).data('ml-no')] || [0];
                    $.ajax({
                        url: '/finance/purchase-requisition-marketlist/request/print',
                        data : {'marketlist_no': selected_no},
                        contentType: 'application/json',
                        type : 'GET',
                        beforeSend: function(){
                            $(obj_target).html('<i class="fa fa-spin fa-spinner"></i>');
                            $(obj_target).prop('disabled', true);
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (response, status, xhr) {
                            downloadFile(response);
                            // console.log(response);
                        },
                        error : function(xhr){
                            var message = xhr.hasOwnProperty('message') ? xhr.message : 'Unknown operation, please refresh the page and try again';
                            Swal.fire('Something went wrong', message, 'error')
                        },
                        complete : function(){
                            $(obj_target).prop('disabled', false);
                            $(obj_target).html('<i class="fa fa-print"></i>');
                        }
                    });
                });
            }
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };

        $(".select-decorated-material").select2({
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
            dropdownParent: $('#bodyModalMaterial'),
            allowClear: false,
            placeholder: "Search Material ...",
            ajax: {
               url: "/finance/purchase-requisition-marketlist/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                var plant = $('#Requestor_Plant_ID').val();
                var qty = $(this[0].closest('tr')).find('[name="rsvQuantity[]"]').val() || 0;
                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                  qty: qty
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                 var plant = $('#Requestor_Plant_ID').val();
                 if(plant){
                     var $request = $.ajax(params);
                     $request.then(success);
                     $request.fail(failure);
                     return $request;
                 } else {
                    Swal.fire('Plant Selection', 'Plant is not available, please make sure to select or choose plant before adding new material', 'warning');
                    return false;
                 }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            var material_code = e.params.data.id || 0;
            var material_name = e.params.data.text || 'Unknown';
            var material_uom = e.params.data.unit || 'Unknown';
            var lastPrice = e.params.data.last_price || 0;

            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(material_uom);
            $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(material_name);
            // $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val(lastPrice);

            delayInAjaxCall(function(){
                var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                checkLastPriceAdditionalMaterial(target);
            }, 1000);

        }).on('select2:unselecting', function(e){
            $(e.target).parents('tr').find('#rsvMeasurement').val('');
            $(e.target).parents('tr').find('#rsvLastPrice').val('');
            calculateGrandTotal(e.target);
            $(this).data('state', 'unselected');
        });

    });
    function getFormDetail(id){
        var segment='view';
        $('#modalFile #bodyModalFile').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition-marketlist/modal-detail')}}", { id : id, action : segment}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
            $("#modalFile .select2").select2({
                dropdownParent: $('#modal-detail-content'),
                placeholder: 'Select Data'
            });

            $('.btn-submit').prop('disabled', true);
            try {
                data_item_additional = [];
                data_item = [];
                var form = $('#modalFile #modalDetailAjax')[0];
                $.each($('[name="marketlistAdditional[]"]', form), function(index, element){
                    try {
                        if(element.value == 'Y'){
                            var material_number = $('[name="marketlistMaterialNumber[]"]', form)[index].value,
                            material_name = $('[name="marketlistMaterialName[]"]', form)[index].value,
                            material_unit = $('[name="marketlistMaterialUnit[]"]', form)[index].value,
                            purch_group = $('[name="marketlistMaterialPurGroup[]"]', form)[index].value,
                            last_price = $('[name="marketlistMaterialLastPriceFormatted[]"]', form)[index].value,
                            last_price_plain = $('[name="marketlistMaterialLastPrice[]"]', form)[index].value,
                            notes = $('[name="marketlistMaterialNote[]"]', form)[index].value,
                            qty = $('[name="marketlistMaterialQty[]"]', form)[index].value;

                            if(parseFloat(qty) > 0){
                                var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice': last_price, 'mtlastpriceplain': last_price_plain, 'mtnote': notes, 'mtqty': qty, 'is_additional': true, 'purgroup': purch_group};
                                data_item_additional.push(obj_to_add);
                            }
                        }
                    } catch(error){
                        // console.log(error);
                    }
                });
            } catch(error){
                // console.log(error);
            }
            // console.log(data_item_additional);

            $('.btn-submit').prop('disabled', false);
            is_detail_popup = true;
            var default_html = `<tr>
                <td colspan="8" class="text-center">No Material Selected</td>
            </tr>`
            $('#reqForm > tbody').html(default_html);
            $('#reqForm').parents('form').find('#grandTotal').val('0.00');
            $('.datepicker3-details').datepicker({
                dateFormat: 'dd MM yy',
                minDate: 0,
            });
        });

    }

    $('#modalFile').on('hide.bs.modal', function(){
        // Reset all data when
        // modal detail hide
        is_detail_popup = false;
        template_selected_code = '';
        template_selected_code_temp = '';
        data_item_additional = [];
        data_item = [];
    })

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

        try {
            var item_list = $('input[name="marketlistMaterialName[]"]', this).length;
            if(!item_list){
                Swal.fire('Submit Purchase Requisition Marketlist', "Cannot make any requests, no item selected. Please make sure you have selected item based on template available", 'warning');
                return false;
            }
        } catch(error){}

        try {
            var zero_value = false;
            $('input[name="marketlistMaterialQty[]"]', this).each(function(index, elem){
                try {
                    if(elem.value === '0'){
                        zero_value = true;
                        return false;
                    }
                } catch(error){}
            });
            if(zero_value){
                Swal.fire('Submit Purchase Requisition Marketlist', "Please make sure all quantity inserted is more than zero (0), there is no such calculation for zero amount of request", 'warning');
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
                    setTimeout(function(){
                        window.location.href="/finance/purchase-requisition-marketlist/request";
                    }, 500);
                });
            },
            error : function(error){
                let message = 'Something went wrong, ' + error.responseJSON.message || 'Something went wrong when trying to save the document, make sure to fill all the data required, check your connection and try again';
                Swal.fire('Save PR Marketlist Form', message, 'error');
            },
        });
        return false;
    });

    var delayInAjaxCall = (function(){
          var timer = 0;
          return function(callback, milliseconds){
          clearTimeout (timer);
          timer = setTimeout(callback, milliseconds);
       };
    })();

    function qtyInputAdditionalMaterial(elem){
        elem.value = elem.value.replace(/^0/gi, '').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        delayInAjaxCall(function(){
            checkLastPriceAdditionalMaterial(elem);
        }, 1000);
    }

    function qtyInput(elem){
        elem.value = elem.value.replace(/^0/gi, '').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '',
        material_name = item.hasOwnProperty('MATERIALNAME') ? item.MATERIALNAME : '',
        material_uom = item.hasOwnProperty('UOM') ? item.UOM : '';
        material_purch_group = item.hasOwnProperty('PURCH_GROUP') ? item.PURCH_GROUP : '';


        if(parseFloat(elem.value) > 0){
            try {
                $(elem.closest('tr')).addClass('bg-success');
            } catch(error){}
            delayInAjaxCall(function(){
                checkLastPrice(elem, material_code, material_name, material_uom, material_purch_group);
            }, 1000);
        } else {
            try {
                $('.btn-finish').prop('disabled', true);
            } catch(error){}

            try {
                $(elem.closest('tr')).removeClass('bg-success');
            } catch(error){}
            data_item = data_item.filter(x => x.mtnumber != material_code);

            try {
                setTimeout(function(){
                    $('.btn-finish').prop('disabled', false);
                }, 500);
            } catch(error){}
        }
    }

    function setNote(material_code='', notes=''){
        $('.btn-finish').prop('disabled', true);
        $('.btn-finish').html('<h6 class="mb-0">Finish&nbsp;&nbsp;<i class="fa fa-spin fa-spinner"></i></h6>');

        data_item = data_item.map(function(x) {
            try {
                if(x.mtnumber == material_code){
                    x.mtnote = notes;
                }
            } catch(error){}
            return x;
        });

        $('.btn-finish').prop('disabled', false);
        $('.btn-finish').html('<h6 class="mb-0">Finish&nbsp;&nbsp;<i class="fa fa-check"></i></h6>');
    }

    function noteInput(elem){
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '';
        var notes = $(elem.closest('tr')).find('[name="noteItemTemplate[]"]').val();
        delayInAjaxCall(function(){
            setNote(material_code, notes);
        }, 300);

    }

    function setNoteAdditional(material_code='', notes=''){
        $('.btn-selection').prop('disabled', true);
        $('.btn-selection').html('<h6 class="mb-0">Finish&nbsp;&nbsp;<i class="fa fa-spin fa-spinner"></i></h6>');

        // data_item_additional = data_item_additional.map(function(x) {
        //     try {
        //         if(x.mtnumber == material_code){
        //             x.mtnote = notes;
        //         }
        //     } catch(error){}
        //     return x;
        // });
        item_additonal_added = item_additonal_added.map(function(x) {
            try {
                if(x.mtnumber == material_code){
                    x.mtnote = notes;
                }
            } catch(error){}
            return x;
        });

        $('.btn-selection').prop('disabled', false);
        $('.btn-selection').html('<i class="fa fa-check"></i>&nbsp;&nbsp;Apply Changes');
    }

    function noteInputAdditional(elem){
        var material_code = parseInt($(elem.closest('tr')).find('[name="rsvMaterials[]"]').val(), 10).toString();
        var notes = elem.value;
        delayInAjaxCall(function(){
            setNoteAdditional(material_code, notes);
        }, 300);

    }

    function setNoteDetail(material_code='', notes=''){
        $('.btn-submit').prop('disabled', true);
        $('.select-template').prop('disabled', true);

        data_item_additional = data_item_additional.map(function(x) {
            try {
                if(x.mtnumber == material_code){
                    x.mtnote = notes;
                }
            } catch(error){}
            return x;
        });

        $('.btn-submit').prop('disabled', false);
        $('.select-template').prop('disabled', false);
    }

    function noteInputDetail(elem){
        try{
            var material_code = parseInt($(elem.closest('tr')).find('[name="marketlistMaterialNumber[]"]').val(), 10).toString();
            var notes = elem.value;
            delayInAjaxCall(function(){
                setNoteDetail(material_code, notes);
            }, 300);
        } catch(error){
            console.log('Error set note detail', error);
        }
    }

    function checkLastPrice(target, material_number='', material_name='', material_unit='', purchasing_group='', obj={}){
        // console.log(target, target.value, typeof target.value);
        try {
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', true);
            $('input[name="noteItemTemplate[]"]').prop('disabled', true);

            $('.btn-finish').prop('disabled', true);
            var qty = target.value;
            if(qty && qty !== '0') {
                var material = '000000000'+material_number,
                unit = material_unit;
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
                     // console.log(data_item, data_item_additional);
                     if(resp.hasOwnProperty('last_price')){
                        try {
                            var notes = $(target).parents('tr').find('[name="noteItemTemplate[]"]').val();
                            var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice': resp.last_price, 'mtlastpriceplain': resp.last_price_plain, 'mtnote': notes, 'mtqty': qty, 'purgroup': purchasing_group};

                            var check_item_exist_in_template_selection = data_item_additional.filter(x => x.mtnumber == material_number);
                            if(check_item_exist_in_template_selection.length > 0){
                                // Remove duplicate material from additioal selection
                                data_item_additional = data_item_additional.filter(x => x.mtnumber != material_number);
                            }
                            data_item = data_item.filter(x => x.mtnumber != material_number);
                            data_item.push(obj_to_add);
                        } catch(error){}

                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="costItemTemplate[]"]').val(last_price);
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                     console.log("Error in check last price");
                     $('.btn-finish').prop('disabled', false);
                     // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                   },
                   complete : function(){
                    setTimeout(function(){
                        // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                        $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
                        $('input[name="noteItemTemplate[]"]').prop('disabled', false);
                    }, 100);
                   }
                });
            } else {
                $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                $('.btn-finish').prop('disabled', false);
                $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
                $('input[name="noteItemTemplate[]"]').prop('disabled', false);
            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
            $('.btn-finish').prop('disabled', false);
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
            $('input[name="noteItemTemplate[]"]').prop('disabled', false);
        }
    }

    function checkLastPriceAdditionalMaterial(target){
        // console.log(target, target.value, typeof target.value);
        try {
            $('.btn-selection').prop('disabled', true);
            $('.btn-add').prop('disabled', true);
            $('.btn-del').prop('disabled', true);
            $('[name="rsvQuantity[]"]').not(target).prop('disabled', true);
            $('[name="rsvMaterials[]"]').prop('disabled', true);
            $('[name="noteItemTemplate[]"]').prop('disabled', true);

            var qty = target.value;
            if(qty && qty !== '0') {
                material = $(target).parents('tr').find('[name="rsvMaterials[]"]').val(),
                unit = $(target).parents('tr').find('[name="rsvMeasurement[]"]').val();
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
                        try {
                            var rowIndex = ($(target).parents('tr')[0].rowIndex) - 1;
                            var item = $(target).parents('tr').find('[name="rsvMaterials[]"]').select2('data');
                            var material_number = parseInt(item[0].id, 10).toString() || 0;
                            var material_name = item[0].text || '';
                            var notes = $(target).parents('tr').find('[name="noteItemTemplate[]"]').val();
                            var material_unit = $(target).parents('tr').find('[name="rsvMeasurement[]"]').val();
                            var purgroup = item[0].PUR_GROUP ? item[0].PUR_GROUP : item_additonal_added[rowIndex] == undefined ? '' : item_additonal_added[rowIndex].purgroup;

                            var check_item_exist_in_template_selection = data_item.filter(x => x.mtnumber == material_number);
                            if(check_item_exist_in_template_selection.length > 0){
                                $(target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                                $(target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                                $(target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');

                                setTimeout(function(){
                                    Swal.fire('Duplicate Material', `This material ${material_name} has been added from the template, please make sure that you only choose or select material that is not available on the template otherwise it will be ignored`, 'error');
                                }, 500);
                            } else {
                                var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice': resp.last_price, 'mtlastpriceplain': resp.last_price_plain, 'mtnote': notes, 'mtqty': qty, 'is_additional': true, 'purgroup': purgroup};
                                let last_price = resp.last_price;
                                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val(last_price);
                                // data_item_additional = data_item_additional.filter(x => x.mtnumber != material_number);
                                // data_item_additional.push(obj_to_add);
                                // item_additonal_added = item_additonal_added.filter(x => x.mtnumber != material_number);
                                // console.log("BEFORE ADDED", item_additonal_added);
                                try {
                                    if(item_additonal_added[rowIndex] == undefined)
                                        item_additonal_added.push(obj_to_add);
                                    else 
                                        item_additonal_added[rowIndex] = obj_to_add;
                                } catch(error){}
                                // console.log("AFTER ADDED", item_additonal_added);
                            }
                        } catch(error){}
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                     console.log("Error in check last price");
                   },
                   complete : function(){
                     $('.btn-selection').prop('disabled', false);
                     $('.btn-add').prop('disabled', false);
                     $('.btn-del').prop('disabled', false);
                     $('[name="rsvQuantity[]"]').not(target).prop('disabled', false);
                     $('[name="noteItemTemplate[]"]').prop('disabled', false);
                     $('[name="rsvMaterials[]"]').prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                   }
                });
            } else {
                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                $('.btn-selection').prop('disabled', false);
                $('.btn-add').prop('disabled', false);
                $('.btn-del').prop('disabled', false);
                $('[name="rsvQuantity[]"]').not(target).prop('disabled', false);
                $('[name="noteItemTemplate[]"]').prop('disabled', false);
                $('[name="rsvMaterials[]"]').prop('disabled', false);
            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            $('.btn-selection').prop('disabled', false);
            $('.btn-add').prop('disabled', false);
            $('.btn-del').prop('disabled', false);
            $('[name="rsvQuantity[]"]').not(target).prop('disabled', false);
            $('[name="noteItemTemplate[]"]').prop('disabled', false);
            $('[name="rsvMaterials[]"]').prop('disabled', false);
        }
    }

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

    function calculateGrandTotal(targetChild){
        try {
            var grand_total = 0;
            $(targetChild).parents('form').find('[name="marketlistMaterialLastPriceFormatted[]"]').each(function(index, item){
                if(item.value){
                    let val = item.value.replace(/\,/g, '');
                    grand_total += isNaN(val) ? 0 : parseFloat(val);
                } else 
                    grand_total += 0;
            });
            grand_total = number_format(grand_total, 2, '.', ',');
            $(targetChild).parents('form').find('#grandTotal').val(grand_total);
        } catch(error){
            // console.log('error', error);
        }
        $('.btn-finish').prop('disabled', false);
        $('.btn-back').prop('disabled', false);
    }

    $('.btn-finish').on('click', function(){
        Swal.fire({
          title: 'Are you sure?',
          text: "Please make sure that you've added material based on your need and then confirm by tapping Yes",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          allowOutsideClick: false
        }).then((result) => {
          if (result.isConfirmed) {
            try{
                var table_data = '';
                data_item = data_item.concat(data_item_additional);
                if(data_item.length){
                    var grandTotal = 0;
                    $('#marketlist-template').modal('hide');
                    setTimeout(function(){
                        var index = 0;
                        for(var loop=1;loop <= data_item.length;loop++){
                            if(loop < 10){
                                var item_order = "000"+(loop * 10);
                            }
                            else if(loop < 100 && loop > 9){
                                var item_order = "00"+(loop * 10);
                            }
                            else if(loop < 1000 && loop > 90){
                                var item_order = "0"+(loop * 10);
                            }
                            else if(loop < 10000 && loop > 900){
                                var item_order = "0"+(loop * 10);
                            }


                            if(is_detail_popup){
                                var qty_element = `<input type="text" class="form-control" oninput='qtyInputDetail(this)' name="marketlistMaterialQty[]" value="${data_item[index].mtqty}">
                                    <div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>`
                                var notes = `<input type="text" oninput="noteInputDetail(this)" class="form-control" name="marketlistMaterialNote[]" value="${data_item[index].mtnote}">`
                            } else {
                                var qty_element = `${data_item[index].mtqty}<input type="hidden" name="marketlistMaterialQty[]" value="${data_item[index].mtqty}">`;
                                var notes = `${data_item[index].mtnote}<input type="hidden" name="marketlistMaterialNote[]" value="${data_item[index].mtnote}">`;

                            }

                            if(is_detail_popup)
                                var tableID = 'reqFormDetail';
                            else 
                                var tableID = 'reqForm';

                            var is_additional_material = data_item[index].hasOwnProperty('is_additional') ? `<input type="hidden" name="marketlistAdditional[]" value="Y">` : `<input type="hidden" name="marketlistAdditional[]" value="N">`
                            table_data += `<tr>
                                <td class="text-center">${item_order}
                                    <input type="hidden" name="marketlistItemOrder[]" value="${item_order}">
                                    ${is_additional_material}
                                </td>
                                <td class="text-left">${data_item[index].mtname}
                                    <input type="hidden" name="marketlistMaterialNumber[]" value="${data_item[index].mtnumber}">
                                    <input type="hidden" name="marketlistMaterialName[]" value="${data_item[index].mtname}">
                                </td>
                                <td class="text-center">${data_item[index].mtunit}
                                    <input type="hidden" name="marketlistMaterialUnit[]" value="${data_item[index].mtunit}">
                                </td>
                                <td class="text-right">
                                    ${qty_element}
                                </td>
                                <td class="text-center">${data_item[index].purgroup}
                                    <input type="hidden" name="marketlistMaterialPurGroup[]" value="${data_item[index].purgroup}">
                                </td>
                                <td class="text-left">
                                    ${notes}
                                </td>
                                <td class="text-right"><span id="last-price-text">${data_item[index].mtlastprice}</span>
                                    <input type="hidden" name="marketlistMaterialLastPrice[]" value="${data_item[index].mtlastpriceplain}">
                                    <input type="hidden" name="marketlistMaterialLastPriceFormatted[]" value="${data_item[index].mtlastprice}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm px-3 btn-del" onclick="deleteBaris('${tableID}', this)">Remove</button>
                                </td>
                            </tr>`;
                            grandTotal += parseFloat(data_item[index].mtlastpriceplain);
                            index++;
                        }
                        if(table_data){
                            template_selected_code = template_selected_code_temp;

                            if(is_detail_popup){
                                $('#reqFormDetail > tbody').html(table_data);
                                var grandTotalFormat =  number_format(grandTotal, 2, '.', ',');
                                $('#reqFormDetail').parents('form').find('#grandTotal').val(grandTotalFormat);
                                $('#Template_Code_Detail').val(template_selected_code);
                            } else {
                                $('#reqForm > tbody').html(table_data);
                                var grandTotalFormat =  number_format(grandTotal, 2, '.', ',');
                                $('#reqForm').parents('form').find('#grandTotal').val(grandTotalFormat);
                                $('#Template_Code').val(template_selected_code);
                            }

                        }
                    }, 500);

                } else {
                    Swal.fire('Template Item Selection', 'No data has been selected, please select at least 1 material data', 'warning');
                }
            } catch(error){
                Swal.fire('Assign Item Error', 'Something went wrong while adding data to the request item list, please try again in a moment', 'error');
                // console.log(error.message)
            }
          }
        });
    });

    function setItemOrderPR(data_item=0, tableID){
        if(data_item > 0){
            try {
                $('.btn-submit').prop('disabled', true);
                var iter = 0;
                for(var loop=1;loop<=data_item;loop++){
                    if(loop < 10){
                        var item_order = "000"+(loop * 10);
                    }
                    else if(loop < 100 && loop > 9){
                        var item_order = "00"+(loop * 10);
                    }
                    else if(loop < 1000 && loop > 90){
                        var item_order = "0"+(loop * 10);
                    }
                    else if(loop < 10000 && loop > 900){
                        var item_order = "0"+(loop * 10);
                    }

                    try{
                        var item_replace = `${item_order}<input type="hidden" name="marketlistItemOrder[]" value="${item_order}">`
                        // $(`#${tableID}`).find('input[name="marketlistItemOrder[]"]').eq(iter).parent().html(item_replace);
                        $(`#${tableID}`).find('input[name="marketlistItemOrder[]"]').eq(iter).parent()[0].childNodes[0] = item_order;
                        $(`#${tableID}`).find('input[name="marketlistItemOrder[]"]').eq(iter).parent()[0].childNodes[1] = item_replace;
                    } catch(error){
                        console.log(error);
                    }
                    iter++;
                }
                $('.btn-submit').prop('disabled', false);
            } catch(error){}
        }
    }

    function setItemOrderAdditional(data_item=0, tableID){
        if(data_item > 0){
            try {
                $('.btn-selection').prop('disabled', true);
                var iter = 0;
                for(var loop=1;loop<=data_item;loop++){
                    try{
                        var item_replace = `<input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem-${loop}" value="${loop}" readonly="">`
                        $(`#${tableID}`).find('input[name="rsvItem[]"]').eq(iter).parent().html(item_replace);
                    } catch(error){
                        console.log(error);
                    }
                    iter++;
                }
                $('.btn-selection').prop('disabled', false);
            } catch(error){}
        }
    }

    function deleteBaris(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = null;
        }

        if(rowIndex !== null)
            table.deleteRow(rowIndex);

        try {
            // document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
            setItemOrderPR((document.getElementById(tableID).rows.length - 1), tableID);
        } catch(error){
            // console.log(error); 
        }

        try {
            calculateGrandTotal(table);
        } catch(error){}

        try {
            if((table.rows.length - 1) == 0 ){
                var no_data = `<tr>
                    <td colspan="7" class="text-center">No Material Selected</td>
                </tr>`;
                $(`#${tableID} > tbody`).html(no_data);
                template_selected_code = '';
                template_selected_code_temp = '';
                is_hide_back_btn = false;
                global_purchasing_group = null;

                // Delete template code if no items
                try {
                    $('#Template_Code').val(template_selected_code);
                } catch(error){}

                try {
                    $('#Template_Code_Detail').val(template_selected_code);
                } catch(error){}
            }
        } catch(error){}

        // Remove item template 
        // from array
        try {
            data_item.splice((rowIndex - 1), 1);
        } catch(error){}

        // Remove item additional
        // from array
        try {
            var item_additional = $(objRow).parents('tr').find('[name="marketlistAdditional[]"]').val();
            if(item_additional.toString().toUpperCase() == 'Y'){
                var material_code_selected = $(objRow).parents('tr').find('[name="marketlistMaterialNumber[]"]').val();
                data_item_additional = data_item_additional.filter(x => x.mtnumber != material_code_selected);
            }
        } catch(error){}
    }

    function deleteBarisAdditional(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = 0;
        }

        if(rowCount>2){
            // if(rowIndex == 1){
            //     Swal.fire('First Item Removal', 'Cannot remove first item. If you want to change item, edit it instead of remove', 'warning');
            // } else {
                // table.deleteRow(rowCount -1);
                table.deleteRow(rowIndex);
                try {
                    setItemOrderAdditional((document.getElementById(tableID).rows.length - 1), tableID);
                } catch(error){}
            // }
        } else {
            try {
                $(objRow).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(objRow).parents('tr').find('[name="rsvQuantity[]"]').val('1');
                $(objRow).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                $(objRow).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(objRow).parents('tr').find('[name="noteItemTemplate[]"]').val('');
                $(objRow).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
            } catch(error){}
            // setTimeout(function(){
            //     Swal.fire('Item Removal', 'Cannot remove first item, the data that will be sent needs to be at least one. If you want to change item, edit it instead of remove', 'warning');
            // }, 300)
        }

        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}

        try {
            item_additonal_deleted.push(item_additonal_added[(rowIndex - 1)].mtnumber);
            // data_item_additional.splice((rowIndex - 1), 1);
        } catch(error){}
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

    $(document).on('show.bs.modal', '.modal', function() {
        const zIndex = 1040 + 10 * $('.modal:visible').length;
        $(this).css('z-index', zIndex);
        setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    });

    function cloneRow(tableID, isModal=false) {
        dropdownParent = {dropdownParent:$('#bodyModalMaterial')};

        var table = document.getElementById(tableID);
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
                            html += `<td>
                                <input type="text" oninput="qtyInputAdditionalMaterial(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                            </td>`;
                        } else if(i == 1){
                            html += `<td>
                                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                                <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                            </td>`;
                        } else if(i == 3){
                            html += `<td style="max-width: 1px; position: relative;">
                                <input type='text' class='form-control' oninput='noteInputAdditional(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>
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
                        newcell.childNodes[1].value = '0.00';
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
                        dropdownParent: $('#bodyModalMaterial'),
                        allowClear: false,
                        placeholder: "Search Material ...",
                        ajax: {
                           url: "/finance/purchase-requisition-marketlist/request",
                           type: "GET",
                           dataType: 'json',
                           delay: 600,
                           data: function (params) {
                            var begin_search_purchasing_group = false;
                            try {
                                var rowIndex = ($(this[0]).parents('tr')[0].rowIndex);
                            } catch(error){
                                var rowIndex = null;
                            }

                            if(global_purchasing_group || !global_purchasing_group && rowIndex > 1)
                                begin_search_purchasing_group = true;

                            var plant = $('#Requestor_Plant_ID').val();
                            var qty = $(this[0].closest('tr')).find('[name="rsvQuantity[]"]').val() || 0;
                            return {
                              searchTerm: params.term, // search term
                              type: 'material',
                              plant: plant,
                              qty: qty,
                              rowIndex: rowIndex,
                              purGroup: global_purchasing_group,
                              searchPurchasingGroup: begin_search_purchasing_group
                            };
                           },
                           processResults: function (response) {
                             return {
                                results: response
                             };
                           },
                           cache: false,
                           transport: function (params, success, failure) {
                             var plant = $('#Requestor_Plant_ID').val();
                             if(plant){
                                 var $request = $.ajax(params);
                                 $request.then(success);
                                 $request.fail(failure);
                                 return $request;
                             } else {
                                Swal.fire('Plant Selection', 'Plant is not available, please make sure to select or choose plant before adding new material', 'warning');
                                return false;
                             }
                           }
                        },
                        minimumInputLength: 3
                     }).on('select2:select', function(e){
                        var material_code = e.params.data.id || 0;
                        var material_name = e.params.data.text || 'Unknown';
                        var material_uom = e.params.data.unit || 'Unknown';
                        var lastPrice = e.params.data.last_price || 0;

                        var filter_exist = item_additonal_added.filter(x => x.mtnumber == parseInt(material_code, 10).toString());
                        // console.log(data_item_additional, material_code, filter_exist);
                        if(filter_exist.length > 0){
                            $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                            $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val('');
                            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                            $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                            Swal.fire('Duplicate Additional Item', `This material ${material_name} has been in the additional item selection, edit it instead of adding the same material`, 'error');
                            return false;
                        }

                        $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(material_uom);
                        $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(material_name);
                        // $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val(lastPrice);

                        delayInAjaxCall(function(){
                            var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                            checkLastPriceAdditionalMaterial(target);
                        }, 1000);

                    }).on('select2:unselecting', function(e){
                        $(e.target).parents('tr').find('#rsvMeasurement').val('');
                        $(e.target).parents('tr').find('#rsvLastPrice').val('');
                        calculateGrandTotal(e.target);
                        $(this).data('state', 'unselected');
                        });
                    }

                } catch(error){ console.log(`There's error on column ${i}`, error) }
            }
        }
        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}
    }

    $(document).on('click', '.btn-selection', function(){
        $('#modalCustomMaterial').modal('hide');
        data_item_additional = [];
        // console.log(data_item_additional, item_additonal_added, item_additonal_deleted);
        item_additonal_added = item_additonal_added.filter(x => !item_additonal_deleted.includes(x.mtnumber));
        data_item_additional = data_item_additional.concat(item_additonal_added);
        if(data_item_additional.length){
            setTimeout(function(){
                // try {
                //     $("[name='marketlistAdditional[]']").each(function(index, elem){
                //         if(elem.value == 'Y')
                //         {
                //             var mat_num = $(elem).parents('tr').find('[name="marketlistMaterialNumber[]"]').val();
                //             var occurence = data_item_additional.filter(x => x.mtnumber == mat_num);
                //             if(occurence.length < 1){
                //                 deleteBaris('reqForm', elem);
                //             }
                //         }

                //     });
                // } catch(error){
                //     // console.log(error);
                // }
                $(`<div class="badge badge-pill badge-danger badge-additional">${data_item_additional.length}</div>`).appendTo('.additional-material-btn-wrapper');
            }, 300)
        } else {
            try {
                $('.badge-additional').remove();

                if(data_item.length < 1 && data_item_additional.length < 1){
                    global_purchasing_group = null;
                    var default_html = `<tr>
                        <td colspan="7" class="text-center">No Material Selected</td>
                    </tr>`
                    $('#reqForm > tbody').html(default_html);
                    $('#reqForm').parents('form').find('#grandTotal').val('0.00');
                    try {
                        deleteBaris('reqForm');
                    } catch(error){}
                } 
                // else if(data_item.length > 0 && data_item_additional.length < 1){
                //     setTimeout(function(){
                //         try {
                //             $("[name='marketlistAdditional[]']").each(function(index, elem){
                //                 if(elem.value == 'Y')
                //                 {
                //                     var mat_num = $(elem).parents('tr').find('[name="marketlistMaterialNumber[]"]').val();
                //                     var occurence = data_item_additional.filter(x => x.mtnumber == mat_num);
                //                     if(occurence.length < 1){
                //                         deleteBaris('reqForm', elem);
                //                     }
                //                 }

                //             });
                //         } catch(error){
                //             // console.log(error);
                //         }
                //         $(`<div class="badge badge-pill badge-danger badge-additional">${data_item_additional.length}</div>`).appendTo('.additional-material-btn-wrapper');
                //     }, 300)
                // }

            } catch(error){}
        }
        item_additonal_deleted = [];
    });

    $('#modalCustomMaterial').on('show.bs.modal', function(){
        $('.btn-selection').prop('disabled', true);
        if(data_item_additional.length){
            var html_to_append = '';
            var loop_item = 1;
            var additional_applied = [];
            for(var i=0;i<data_item_additional.length;i++){
                try {
                    additional_applied.push(data_item_additional[i].mtnumber);
                } catch(error){}
                html_to_append += `<tr class="rowToClone">
                  <td style="max-width: 1px">
                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem-${loop_item}" value="${loop_item}" readonly="">
                  </td>
                  <td style="max-width: 1px">
                    <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials-${loop_item}" style="width: 100%">
                        <option value="${"000000000"+data_item_additional[i].mtnumber}" default selected>${data_item_additional[i].mtname}</option>
                    </select>
                    <input type="hidden" name="rsvMaterialsDesc[]" value="${data_item_additional[i].mtname}" id="rsvMaterialsDesc-${loop_item}">
                  </td>
                  <td style="max-width: 1px; position: relative;">
                    <input type="text" oninput="qtyInputAdditionalMaterial(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty-${loop_item}" required value="${data_item_additional[i].mtqty}">
                    <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                  </td>
                  <td style="max-width: 1px; position: relative;">
                      <input type='text' value="${data_item_additional[i].mtnote}" class='form-control' oninput='noteInputAdditional(this)' name='noteItemTemplate[]' id='noteItemTemplate-${loop_item}' placeholder='Add Note...'/>
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" value="${data_item_additional[i].mtunit}" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${loop_item}" placeholder="Automatically filled">
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" value="${data_item_additional[i].mtlastprice}" id="rsvLastPrice-${loop_item}" placeholder="Automatically filled">
                  </td>
                  <td>
                    <div class="btn-group" style="min-width:80px">
                        <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBarisAdditional('reqFormCustommaterial', this)">-</button>
                        <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqFormCustommaterial')">+</button>
                    </div>
                  </td>
                </tr>`;
              loop_item++;
            }
            item_additonal_added = item_additonal_added.filter(x => additional_applied.includes(x.mtnumber));
            // console.log(additional_applied, item_additonal_added);
            $('#tbody-additional-material').html(html_to_append);
        } 
        else if(item_additonal_added.length){
            var html_to_append = '';
            var loop_item = 1;
            for(var i=0;i<item_additonal_added.length;i++){
                html_to_append += `<tr class="rowToClone">
                  <td style="max-width: 1px">
                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem-${loop_item}" value="${loop_item}" readonly="">
                  </td>
                  <td style="max-width: 1px">
                    <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials-${loop_item}" style="width: 100%">
                        <option value="${"000000000"+item_additonal_added[i].mtnumber}" default selected>${item_additonal_added[i].mtname}</option>
                    </select>
                    <input type="hidden" name="rsvMaterialsDesc[]" value="${item_additonal_added[i].mtname}" id="rsvMaterialsDesc-${loop_item}">
                  </td>
                  <td style="max-width: 1px; position: relative;">
                    <input type="text" oninput="qtyInputAdditionalMaterial(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty-${loop_item}" required value="${item_additonal_added[i].mtqty}">
                    <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                  </td>
                  <td style="max-width: 1px; position: relative;">
                      <input type='text' value="${item_additonal_added[i].mtnote}" class='form-control' oninput='noteInputAdditional(this)' name='noteItemTemplate[]' id='noteItemTemplate-${loop_item}' placeholder='Add Note...'/>
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" value="${item_additonal_added[i].mtunit}" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${loop_item}" placeholder="Automatically filled">
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" value="${item_additonal_added[i].mtlastprice}" id="rsvLastPrice-${loop_item}" placeholder="Automatically filled">
                  </td>
                  <td>
                    <div class="btn-group" style="min-width:80px">
                        <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBarisAdditional('reqFormCustommaterial', this)">-</button>
                        <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqFormCustommaterial')">+</button>
                    </div>
                  </td>
                </tr>`;
              loop_item++;
            }
            $('#tbody-additional-material').html(html_to_append);
        } else {
            $('#tbody-additional-material').html(`<tr class="rowToClone">
              <td style="max-width: 1px">
                <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="1" readonly="">
              </td>
              <td style="max-width: 1px">
                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                    <option value="" default selected>---- Choose Material ----</option>
                </select>
                <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
              </td>
              <td style="max-width: 1px; position: relative;">
                <input type="text" oninput="qtyInputAdditionalMaterial(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
              </td>
              <td style="max-width: 1px; position: relative;">
                  <input type='text' class='form-control' oninput='noteInputAdditional(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>
              </td>
              <td style="max-width: 1px">
                <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
              </td>
              <td style="max-width: 1px">
                <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" value="0.00" id="rsvLastPrice" placeholder="Automatically filled">
              </td>
              <td>
                <div class="btn-group" style="min-width:80px">
                    <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBarisAdditional('reqFormCustommaterial', this)">-</button>
                    <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqFormCustommaterial')">+</button>
                </div>
              </td>
            </tr>`);
        }

        try {
            $(".select-decorated-material").select2({
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
                dropdownParent: $('#bodyModalMaterial'),
                allowClear: false,
                placeholder: "Search Material ...",
                ajax: {
                   url: "/finance/purchase-requisition-marketlist/request",
                   type: "GET",
                   dataType: 'json',
                   delay: 600,
                   data: function (params) {
                    var begin_search_purchasing_group = false;
                    try {
                        var rowIndex = ($(this[0]).parents('tr')[0].rowIndex);
                    } catch(error){
                        var rowIndex = null;
                    }

                    if(global_purchasing_group || !global_purchasing_group && rowIndex > 1)
                        begin_search_purchasing_group = true;

                    var plant = $('#Requestor_Plant_ID').val();
                    var qty = $(this[0].closest('tr')).find('[name="rsvQuantity[]"]').val() || 0;
                    return {
                      searchTerm: params.term, // search term
                      type: 'material',
                      plant: plant,
                      qty: qty,
                      rowIndex: rowIndex,
                      purGroup: global_purchasing_group,
                      searchPurchasingGroup: begin_search_purchasing_group
                    };
                   },
                   processResults: function (response) {
                     return {
                        results: response
                     };
                   },
                   cache: false,
                   transport: function (params, success, failure) {
                     var plant = $('#Requestor_Plant_ID').val();
                     if(plant){
                         var $request = $.ajax(params);
                         $request.then(success);
                         $request.fail(failure);
                         return $request;
                     } else {
                        Swal.fire('Plant Selection', 'Plant is not available, please make sure to select or choose plant before adding new material', 'warning');
                        return false;
                     }
                   }
                },
                minimumInputLength: 3
             }).on('select2:select', function(e){
                try {
                    global_purchasing_group = e.params.data.PUR_GROUP;
                } catch(error){}
                var material_code = e.params.data.id || 0;
                var material_name = e.params.data.text || 'Unknown';
                var material_uom = e.params.data.unit || 'Unknown';
                var lastPrice = e.params.data.last_price || 0;

                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(material_uom);
                $(e.target).parents('tr').find('[name="rsvMaterialsDesc[]"]').val(material_name);
                // $(e.target).parents('tr').find('[name="rsvLastPrice[]"]').val(lastPrice);

                delayInAjaxCall(function(){
                    var target = $(e.target).parents('tr').find('[name="rsvQuantity[]"]')[0];
                    checkLastPriceAdditionalMaterial(target);
                }, 1000);

            }).on('select2:unselecting', function(e){
                $(e.target).parents('tr').find('#rsvMeasurement').val('');
                $(e.target).parents('tr').find('#rsvLastPrice').val('');
                calculateGrandTotal(e.target);
                $(this).data('state', 'unselected');
            });
        } catch(error){}
        $('.btn-selection').prop('disabled', false);
    })

</script>
@endsection
