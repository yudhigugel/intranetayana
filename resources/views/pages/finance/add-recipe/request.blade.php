@extends('layouts.default')

@section('title', 'Request Recipe')
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
#select2-sloc-results,
#select2-rsvMeasurement-results {
    max-height: 120px !important;
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
.select2-results__option--selectable,
.mrtltype-text{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.mrtltype-text{
    line-height: 1.6;
}
.headcol {
  position: absolute;
  width: 5em;
  right: 0;
  top: auto;
  border-top-width: 1px;
  /*only relevant for first row*/
  margin-top: -1px;
  /*compensate for top border*/
}
.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white;
}
.last-col {
  width: 100px;
  min-width: 100px;
  max-width: 100px;
  right: 0.02em;
  bottom: 0;
  outline: 1px solid #e6e6e6;
}
.last-col-shadow-element {
  box-shadow: 0px 5px 10px 0px #d3d3d3;
}
.last-col-shadow {
    /*box-shadow: inset 0px -2px 18px 0 #0000000d;*/
    box-shadow: 0px 5px 10px 0px #d3d3d3;
}

</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Add Recipe</a></li>
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
                        {{--@if ($data['allow_add_request'])--}}
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalRequestReservation">Add Request</button>
                        {{--@endif--}}

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
                                        <option value="">All</option>
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
                {{--<table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap; max-width: 100%">
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
                </table>--}}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalRequestReservation" tabindex="" role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRequestLabel">Form - Add Recipe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalRequest">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/add-reservation/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;cursor: pointer;" data-toggle="collapse" data-target="#collapseGeneral" aria-expanded="true" aria-controls="collapseGeneral"> General Information <small class="text-primary" style="font-size: 10px;display: inline-block;margin-left: 5px;">Click here to hide...</small></h3>
                        <div id="collapseGeneral" class="collapse show">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Plant</label>
                                    <div class="mb-2">
                                        <select class="form-control select2 select-decorated" name="PlantRecipe" id='rcpPlant' required style="width: 100%">
                                            @if(isset($data['plant_list']) && count($data['plant_list']) > 0)
                                             <option value="" selected disabled></option>
                                             @foreach($data['plant_list'] as $key => $val)
                                                <option value="{{ $key }}">{{ $key.' - '.$val}}</option>
                                             @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="spinner-plant" hidden><i class="fa fa-spin fa-spinner"></i>&nbsp; <small>Loading cost center ...</small></div>
                                    <div class="spinner-retry-plant" style="cursor: pointer;" hidden><i class="fa fa-history"></i>&nbsp; <small class="text-primary">Retry to load</small></div>
                                </div>
                                <div class="col-md-6">
                                    <label>Valid From</label>
                                    <input style="padding: 0.8rem 1.375rem" type="text" name="ValidDate" value="{{date('d F Y')}}" class="form-control datepicker3"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;cursor: pointer;" data-toggle="collapse" data-target="#collapseMaterial" aria-expanded="true" aria-controls="collapseMaterial"> Material Information <small class="text-primary" style="font-size: 10px;display: inline-block;margin-left: 5px;">Click here to hide...</small></h3>
                        <div id="collapseMaterial" class="collapse show">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12 col-md-12 form-group">
                                            <label>Material</label>
                                            <select required class="form-control select2 select-decorated-material-header" name="rcpMaterialHeader" id="material-header" style="width: 100%">
                                                <option value="" default selected>---- Choose Material ----</option>
                                            </select>
                                            <input type="hidden" name="rcpMaterialHeaderDesc" value="" id="rcpMaterialHeaderDesc">
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <label>Material Group</label>
                                            <input type="text" class="form-control" readonly name="rcpMaterialGrpDesc" id="rcpMaterialGrpDesc" placeholder="Material Group will show here ...">
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <label>Material Type</label>
                                            <div class="row">
                                                <div class="col-4 col-md-4">
                                                    <input type="text" class="form-control" readonly name="rcpMaterialType" id="rcpMaterialType" placeholder="Type">
                                                    <input type="hidden" name="rcpMaterialTypeDesc" id="rcpMaterialTypeDesc">
                                                </div>
                                                <div class="col-8 col-md-8">
                                                    <div class="pt-3">
                                                        <h6 class="mrtltype-text">No Data</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-4 col-md-4 form-group">
                                            <label>Quantity / Yields</label>
                                            <input style="padding: 0.8rem 1.375rem" type="text" class="form-control qty-per-portion" value="0" name="rcpQuantity" id="rcpQuantity" placeholder="Qty...">
                                        </div>
                                        <div class="col-4 col-md-4 form-group">
                                            <label>Quantity UoM</label>
                                            <select required class="form-control select2 select-decorated" name="rcpHeaderQtyMeasurement" id="rcpHeaderQtyMeasurement"  style="width: 100%">
                                                <option value="" default selected>---- Choose UoM ----</option>
                                                @if(isset($data['material_unit']) && count($data['material_unit']) > 0)
                                                @foreach ($data['material_unit'] as $mt_unit)
                                                    <option value="{{strtoupper($mt_unit['EX_UOM'])}}">{{strtoupper($mt_unit['EX_UOM'])}} - {{strtoupper($mt_unit['UOM_DESC'])}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-4 col-md-4 form-group">
                                            <label>Portions</label>
                                            <input style="padding: 0.8rem 1.375rem" type="text" value="0" class="form-control qty-per-portion" name="rcpPortion" id="rcpPortion" placeholder="Input Portion...">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <label>Yields per Portion</label>
                                            <input style="padding: 0.8rem 1.375rem" readonly value="{{ number_format(0, 2) }}" type="text" class="form-control" name="rcpYieldPerPortion" id="rcpYieldPerPortion" placeholder="Yields per portion...">
                                        </div>
                                        {{--<div class="col-4 col-md-4">
                                            <label>Item Weight</label>
                                            <input style="padding: 0.8rem 1.375rem" readonly type="text" class="form-control" name="rcpWeight" id="rcpWeight" placeholder="Weight...">
                                        </div>
                                        <div class="col-4 col-md-4">
                                            <label>Item Weight UoM</label>
                                            <select required class="form-control select2 select-decorated" name="rcpHeaderWeightMeasurement" id="rcpHeaderWeightMeasurement"  style="width: 100%">
                                                <option value="" default selected>---- Choose UoM ----</option>
                                                @if(isset($data['material_unit']) && count($data['material_unit']) > 0)
                                                @foreach ($data['material_unit'] as $mt_unit)
                                                    <option value="{{strtoupper($mt_unit['EX_UOM'])}}">{{strtoupper($mt_unit['EX_UOM'])}} - {{strtoupper($mt_unit['UOM_DESC'])}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>--}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row align-items-end">
                            <div class="col-12 col-md-12">
                                <h3 class="mb-3" style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Cost Calculation <br>
                                    <small style="font-size: 12px" class="text-muted">* All cost calculation are in IDR</small>
                                </h3>
                            </div>
                            <div class="col-8 col-md-8">
                                <div class="row">
                                    <div class="col-3 col-md-3">
                                        <div class="p-3 bg-secondary text-white">
                                            <h6>Total Cost</h6>
                                            <input type="text" value="{{ number_format(0, 2) }}" name="rcpTotalCost" id="rcpTotalCost" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="p-3 bg-secondary text-white">
                                            <h6>Margin</h6>
                                            <input type="text" value="{{ number_format(0, 2) }}" name="rcpMargin" id="rcpMargin" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="p-3 bg-secondary text-white">
                                            <h6>Total Cost (%)</h6>
                                            <input type="text" value="{{ number_format(0, 2) }}" name="rcpTotalCostPctg" id="rcpTotalCostPctg" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="p-3 bg-secondary text-white">
                                            <h6>Margin (%)</h6>
                                            <input type="text" value="{{ number_format(0, 2) }}" name="rcpMarginPctg" id="rcpMarginPctg" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="p-3" style="box-shadow: 1px 1px 5px 0px #cfcfcf">
                                            <h6>Selling Price</h6>
                                            <div class="input-group">
                                              <input type="text" class="form-control" value="0" placeholder="Input Selling Price..." id="rcpSellingPriceMasking">
                                              <input type="hidden" name="rcpSellingPrice" value="0" id="rcpSellingPrice">
                                              <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">IDR</span>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="mb-3" style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Items (<span id="item-count">1</span>)</h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 8rem !important; overflow: auto;">
                            <table class="table table-bordered smallfont table-request" id="reqForm" style="min-width: 1300px">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Material</th>
                                        <th style="width: 15%">UoM</th>
                                        <th style="width: 7%">Quantity</th>
                                        <th style="width: 20%">Cost Center</th>
                                        {{--<th class="sticky-col last-col last-col-shadow" style="width: 10%;right: 27.6em">Cost</th>
                                        <th class="sticky-col last-col" style="width: 5%;right: 20.3em">Currency</th>
                                        <th class="sticky-col last-col" style="width: 10%;right: 7.2em">Cost (%)</th>--}}

                                        <th class="sticky-col last-col last-col-shadow" style="width: 11%;right: 17.4em">Cost</th>
                                        <th class="sticky-col last-col" style="width: 8%;right: 9.7em">Cost (%)</th>
                                        <th class="sticky-col last-col" style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="rowToClone">
                                        <td style="max-width: 1px">
                                            <select required class="form-control select2 select-decorated-material" name="rcpItemMaterials[]" id="rcpItemMaterials" style="width: 100%">
                                                <option value="" default selected>---- Choose Material ----</option>
                                            </select>
                                            <input type="hidden" name="rcpItemMaterialsDesc[]" value="" id="rcpItemMaterialsDesc">
                                        </td>
                                        <td style="max-width: 1px">
                                            <select required class="form-control select2 select-decorated" name="rcpItemMeasurement[]" id="rcpItemMeasurement" style="width: 100%">
                                                <option value="" default selected>---- Choose UoM ----</option>
                                                @if(isset($data['material_unit']) && count($data['material_unit']) > 0)
                                                    @foreach ($data['material_unit'] as $mt_unit)
                                                        <option value="{{strtoupper($mt_unit['EX_UOM'])}}">{{strtoupper($mt_unit['EX_UOM'])}} - {{strtoupper($mt_unit['UOM_DESC'])}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td style="max-width: 1px; position: relative;">
                                            <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rcpItemQuantity[]" id="rcpItemQty" required value="1">
                                            <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                        </td>
                                        <td style="max-width: 1px">
                                            <select required class="form-control select2 select-decorated" name="rcpItemCostCenter[]" id="rcpItemCostCenter" style="width: 100%">
                                                <option value="" default selected>---- Choose Cost Center ----</option>
                                            </select>
                                        </td>
                                        {{--<td class="sticky-col last-col last-col-shadow-element" style="max-width: 1px; right: 32.2em">
                                            <input type="text" readonly class="form-control text-center" value="0" name="rcpItemCost[]" id="rcpItemCost" placeholder="Autofill...">
                                        </td>
                                        <td class="sticky-col last-col" style="max-width: 1px; right: 23.7em">
                                            <input type="text" readonly class="form-control text-center" name="rcpItemCurrency[]" id="rcpItemCurrency" placeholder="Autofill..." value="IDR">
                                        </td>
                                        <td class="sticky-col last-col" style="max-width: 1px; right: 8.4em">
                                            <input type="text" readonly class="form-control text-center" value="{{ number_format(0, 2) }}" name="rcpItemCostPctg[]" id="rcpItemCostPctg" placeholder="Autofill...">
                                        </td>--}}

                                        <td class="sticky-col last-col last-col-shadow-element" style="max-width: 1px; right: 20.3em">
                                            <input type="text" readonly class="form-control text-center" value="0" name="rcpItemCost[]" id="rcpItemCost" placeholder="Autofill...">
                                        </td>
                                        <td class="sticky-col last-col" style="max-width: 1px; right: 11.3em">
                                            <input type="text" readonly class="form-control text-center" value="{{ number_format(0, 2) }}" name="rcpItemCostPctg[]" id="rcpItemCostPctg" placeholder="Autofill...">
                                        </td>
                                        <td class="sticky-col last-col">
                                            <div class="btn-group" style="min-width:100px">
                                                <button type="button" class="btn btn-success text-white btn-sm px-1 btn-refresh" onclick="refreshMaterial(this)"><i class="fa fa-refresh" style="font-size: 10px"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                                                <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="submit-container">
                        <div class="row">
                            {{--<div class="col-md-12">
                                <button type="submit" class="form-control btn btn-success text-white btn-submit">Send Request</button>
                            </div>--}}
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
    $(document).ready( function () {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        $(".datepicker2").datepicker();
        $('.datepicker3').datepicker({
            dateFormat: 'dd MM yy',
            minDate: 0,
        });

        $(document).on('click', '.spinner-retry-plant', function(){
            $(this).prop('hidden', true);
            $('#rcpPlant').trigger({
                type: 'select2:select',
                params: {
                    data: {
                        id: $('#rcpPlant').val()
                    }
                }
            });
        });

        $(document).on('keyup', '.qty-per-portion', function(e){
            e.target.value = e.target.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            setTimeout(function(){
                yieldPerPortion();
            }, 600);
        });

        $(document).on('keyup', '#rcpSellingPriceMasking', function(e){
            try {
                let val = this.value.replace(/\,/g, '');
                $('#rcpSellingPrice').val(val);
                setTimeout(function(){
                    var sellPriceObj = $('#rcpSellingPrice').length > 0 ? $('#rcpSellingPrice')[0] : {};
                    calculateGrandTotal(sellPriceObj);
                    numericInput($('#rcpSellingPriceMasking')[0]);
                    checkCostPerItem($('#rcpSellingPrice')[0]);
                    // setTimeout(function(){
                    //     setMargin();
                    // }, 200)
                }, 100)
            } catch(error){}
        });

        $(".select-decorated").select2({
            placeholder: "Select an option",
        }).on('select2:select', function(e){
            if(e.target.id == 'rcpPlant'){
                var $_plant = e.params.data.id || '';
                try {
                    $('select[name="rcpItemCostCenter[]"]').select2('destroy').html("<option value='' default selected>---- Choose Cost Center ----</option>");
                    $('select[name="rcpItemCostCenter[]"]').select2({
                        placeholder: "Choose Data",
                        allowClear: true,
                    });
                    $('select[name="rcpItemCostCenter[]"]').prop('required', true);
                    $('select[name="rcpItemCostCenter[]"]').prop('disabled', true);
                    $('.spinner-plant').prop('hidden', false);
                } catch(error){}

                $.ajax({
                    url: '/sap/add-recipe/request',
                    type: 'GET',
                    dataType: 'json',
                    data: {'plant_lookup':$_plant, 'type':'cost_center'},
                    success : function(response){
                        try {
                            var newOptionCostCenter = [];
                            if(response.hasOwnProperty('data') && response.data && response.data.length){
                                $.each(response.data, function(index, data){
                                    newOptionCostCenter[index] = new Option(`${data.SAP_COST_CENTER_ID} - ${data.SAP_COST_CENTER_DESCRIPTION}`, `${data.SAP_COST_CENTER_ID}`, false, false);
                                });
                            } else {
                                $('select[name="rcpItemCostCenter[]').prop('disabled', true);
                            }

                            setTimeout(function(){
                                $('select[name="rcpItemCostCenter[]').append(newOptionCostCenter).trigger('change');
                                // $('#material_group_request_select').select2('open');
                            },100)
                        } catch(error){
                            console.log(error);
                            // Swal.fire('Oops..', 'Something went wrong while generating data, please check your connection', 'error');
                            $.toast({
                              text : "Oops.. Something went wrong while processing cost center data, please try again in a moment",
                              hideAfter : 4000,
                              textAlign : 'left',
                              showHideTransition : 'slide',
                              position : 'bottom-right'  
                            });
                        }
                    },
                    error : function(xhr){
                        setTimeout(function(){
                            $('.spinner-retry-plant').prop('hidden', false);
                        }, 800)
                        $.toast({
                          text : "Oops.. Something went wrong when trying to load cost center, please check your connection and try again",
                          hideAfter : 4000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        });
                    },
                    complete: function(){
                        $('.spinner-plant').prop('hidden', true);
                        $('select[name="rcpItemCostCenter[]"]').prop('disabled', false);
                    }
                })
            } else if(e.target.name == 'rcpItemMeasurement[]'){
                if($(e.target).parents('tr').find('[name="rcpItemMaterials[]"]').val()){
                    setTimeout(function(){
                        var target = $(e.target).parents('tr').find('[name="rcpItemQuantity[]"]')[0];
                        checkLastPrice(target, e.params.data.id);
                    }, 600);
                }
            }
        }).on('select2:open', function (e) {
            if(e.target.name == 'rcpItemCostCenter[]'){
                var left = $('.portlet-body').width();
                console.log(left);
                $('.portlet-body').scrollLeft(left);
            } else {
                var target_parent = $(e.target).parents('td');
                try {
                    if(target_parent.length > 0){
                        var tgtElement = target_parent[0];
                        tgtElement.scrollIntoView();
                    }
                } catch(error){}
            }
        });

        var table_obj = $(".select-decorated-material-header").select2({
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
            // allowClear: true,
            placeholder: "Search Material ...",
            ajax: {
               url: "/sap/add-recipe/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                var plant = $('#rcpPlant').val();
                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                if($('#rcpPlant').val()){
                  var $request = $.ajax(params);
                  $request.then(success);
                  $request.fail(failure);
                  return $request;
                } else {
                    Swal.fire('Plant Selection', 'Please select plant first to get an appropriate material', 'warning');
                    return false;
                }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            var value = e.params.data.id || 0;
            var text = e.params.data.text || 'Unknown';
            var unit = e.params.data.unit || 'Unknown';
            var mat_grp = e.params.data.mat_group || 'Unknown';
            var mat_type_id = e.params.data.mat_type.MATERIAL_TYPE || '';
            var mat_type_desc = e.params.data.mat_type.MATERIAL_TYPE_DESC || 'No Data';

            $('#rcpMaterialGrpDesc').val(mat_grp);
            $('#rcpMaterialType').val(mat_type_id);
            $('#rcpMaterialTypeDesc').val(mat_type_desc);
            $('.mrtltype-text').text(mat_type_desc);

        }).on('select2:unselecting', function(e){
            $('#rcpMaterialGrpDesc').val('');
            $('#rcpMaterialType').val('');
            $('#rcpMaterialTypeDesc').val('');
            $('.mrtltype-text').text('No Data');
            $(this).data('state', 'unselected');
        });

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
            // dropdownParent: $('#modalRequestReservation'),
            // allowClear: true,
            placeholder: "Search Material ...",
            ajax: {
               url: "/sap/add-recipe/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                var plant = $('#rcpPlant').val();
                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                if($('#rcpPlant').val()){
                  var $request = $.ajax(params);
                  $request.then(success);
                  $request.fail(failure);
                  return $request;
                } else {
                    Swal.fire('Plant Selection', 'Please select plant first to get an appropriate material', 'warning');
                    return false;
                }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            var value = e.params.data.id || 0;
            var text = e.params.data.text || 'Unknown';
            var unit = e.params.data.unit || 'Unknown';
            var mat_grp = e.params.data.mat_group || 'Unknown';
            var mat_type_id = e.params.data.mat_type.MATERIAL_TYPE || '';
            var mat_type_desc = e.params.data.mat_type.MATERIAL_TYPE_DESC || 'No Data';
            var is_recipe = e.params.data.hasOwnProperty('is_recipe') ? e.params.data.is_recipe : false;
            try {
                if(is_recipe){
                    $(e.target).parents('tr').addClass('bg-success');
                } else {
                    $(e.target).parents('tr').removeClass('bg-success');
                }
            } catch(error){}

            try {
                $(e.target).parents('tr').find('[name="rcpItemMeasurement[]"]').val(unit).trigger('change');
            } catch(error){}

            try {
                $(e.target).parents('tr').find('[name="rcpItemMaterialsDesc[]"]').val(text);
            } catch(error){}

            setTimeout(function(){
                var target = $(e.target).parents('tr').find('[name="rcpItemQuantity[]"]')[0];
                checkLastPrice(target);
            }, 600);

        }).on('select2:unselecting', function(e){
            $(this).data('state', 'unselected');
        }).on('select2:open', function (e) {
            if(e.target.name == 'rcpItemCostCenter[]'){
                var left = $('.portlet-body').width();
                console.log(left);
                $('.portlet-body').scrollLeft(left);
            } else {
                var target_parent = $(e.target).parents('td');
                try {
                    if(target_parent.length > 0){
                        var tgtElement = target_parent[0];
                        tgtElement.scrollIntoView();
                    }
                } catch(error){}
            }
        });
    })

    function cloneRow(tableID, isModal=false) {
        var dropdownParent = {};
        if(isModal)
            dropdownParent = {dropdownParent:$('#modalDetailAjax')};

        var table = document.getElementById(tableID);
        if(!$(table).find('[name="rcpItemCostCenter[]"]').val()){
            Swal.fire('Cost Center Option', 'Please complete all selection (Material, UoM, Quantity, and Cost center) first to add a new row', 'warning');
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
                if(i == 4 || i == 5 || i == 6){
                    // if(i == 4){
                    //     newcell.classList.add('sticky-col', 'last-col', 'last-col-shadow-element');
                    //     newcell.style.right = '32.2em';
                    // } else if( i == 5) {
                    //     newcell.classList.add('sticky-col', 'last-col');
                    //     newcell.style.right = '23.7em';  
                    // } else if( i == 6) {
                    //     newcell.classList.add('sticky-col', 'last-col');
                    //     newcell.style.right = '8.4em';
                    // } else {
                    //     newcell.classList.add('sticky-col', 'last-col');
                    // }
                    if(i == 4){
                        newcell.classList.add('sticky-col', 'last-col', 'last-col-shadow-element');
                        newcell.style.right = '20.3em';
                    } else if( i == 5) {
                        newcell.classList.add('sticky-col', 'last-col');
                        newcell.style.right = '11.3em';  
                    } else {
                        newcell.classList.add('sticky-col', 'last-col');
                    }
                } else {
                    newcell.style.maxWidth = '1px';
                    newcell.style.position = 'relative';
                }

                if(i == 0 || i == 1 || i == 2 || i == 3){
                    try {
                        var html = '';
                        if(i == 0){
                            html += `<td>
                                <select required class="form-control select2 select-decorated-material" name="rcpItemMaterials[]" id="rcpItemMaterials" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                                <input type="hidden" name="rcpItemMaterialsDesc[]" value="" id="rcpItemMaterialsDesc">
                            </td>`;
                        } else if(i == 1){
                            let option = '';
                            let populate_option = $("select[name='rcpItemMeasurement[]']", $(tableID)[0])[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += `<option value="${populate_option[data].value}">${populate_option[data].innerHTML}</option>`;
                            }
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rcpItemMeasurement[]" id="rcpItemMeasurement" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        } else if(i == 2){
                            html += `<td>
                                <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rcpItemQuantity[]" id="rcpItemQty" required value="1">
                                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                            </td>`;
                        } else if(i == 3){
                            let option = '';
                            let populate_option = $("select[name='rcpItemCostCenter[]']", $(tableID)[0])[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += `<option value="${populate_option[data].value}">${populate_option[data].innerHTML}</option>`;
                            }
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rcpItemCostCenter[]" id="rcpItemCostCenter" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        }

                        newcell.innerHTML = html;
                    } catch(error){
                        console.log(`Error in column 0 / 1 / 2 / 3 ${error}`);
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
                    let newVal = (rowCount - 1) + 1;
                    $('#item-count').text(newVal);

                    if(newcell.childNodes[1].name == 'rcpItemCost[]'){
                        newcell.childNodes[1].value = '0';
                    }

                    else if(newcell.childNodes[1].name == 'rcpItemCostPctg[]'){
                        newcell.childNodes[1].value = '0.00';
                    }

                    else if(newcell.childNodes[1].name == 'rcpItemMaterials[]'){
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
                            placeholder: "Search Material ...",
                            ajax: {
                               url: "/sap/add-recipe/request",
                               type: "GET",
                               dataType: 'json',
                               delay: 600,
                               data: function (params) {
                                var plant = $('#rcpPlant').val();
                                return {
                                  searchTerm: params.term, // search term
                                  type: 'material',
                                  plant: plant,
                                };
                               },
                               processResults: function (response) {
                                 return {
                                    results: response
                                 };
                               },
                               cache: false,
                               transport: function (params, success, failure) {
                                if($('#rcpPlant').val()){
                                  var $request = $.ajax(params);
                                  $request.then(success);
                                  $request.fail(failure);
                                  return $request;
                                } else {
                                    Swal.fire('Plant Selection', 'Please select plant first to get an appropriate material', 'warning');
                                    return false;
                                }
                               }
                            },
                            minimumInputLength: 3
                         }).on('select2:select', function(e){
                            var value = e.params.data.id || 0;
                            var text = e.params.data.text || 'Unknown';
                            var unit = e.params.data.unit || 'Unknown';
                            var mat_grp = e.params.data.mat_group || 'Unknown';
                            var mat_type_id = e.params.data.mat_type.MATERIAL_TYPE || '';
                            var mat_type_desc = e.params.data.mat_type.MATERIAL_TYPE_DESC || 'No Data';
                            var is_recipe = e.params.data.hasOwnProperty('is_recipe') ? e.params.data.is_recipe : false;
                            try {
                                if(is_recipe){
                                    $(e.target).parents('tr').addClass('bg-success');
                                } else {
                                    $(e.target).parents('tr').removeClass('bg-success');
                                }
                            } catch(error){}

                            try {
                                $(e.target).parents('tr').find('[name="rcpItemMeasurement[]"]').val(unit).trigger('change');
                            } catch(error){}

                            try {
                                $(e.target).parents('tr').find('[name="rcpItemMaterialsDesc[]"]').val(text);
                            } catch(error){}

                            setTimeout(function(){
                                var target = $(e.target).parents('tr').find('[name="rcpItemQuantity[]"]')[0];
                                checkLastPrice(target);
                            }, 600);

                        }).on('select2:unselecting', function(e){
                            $(this).data('state', 'unselected');
                        }).on('select2:open', function (e) {
                            if(e.target.name == 'rcpItemCostCenter[]'){
                                var left = $('.portlet-body').width();
                                console.log(left);
                                $('.portlet-body').scrollLeft(left);
                            } else {
                                var target_parent = $(e.target).parents('td');
                                try {
                                    if(target_parent.length > 0){
                                        var tgtElement = target_parent[0];
                                        tgtElement.scrollIntoView();
                                    }
                                } catch(error){}
                            }
                        });
                    }

                    else if(newcell.childNodes[1].name == 'rcpItemCostCenter[]'){
                        let val = $(table.rows[(rowCount-1)]).find(`[name='rcpItemCostCenter[]']`).val() || 0;
                        if(val){
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                allowClear: true,
                                ...dropdownParent
                            }).on('select2:open', function (e) {
                                if(e.target.name == 'rcpItemCostCenter[]'){
                                    var left = $('.portlet-body').width();
                                    console.log(left);
                                    $('.portlet-body').scrollLeft(left);
                                } else {
                                    var target_parent = $(e.target).parents('td');
                                    try {
                                        if(target_parent.length > 0){
                                            var tgtElement = target_parent[0];
                                            tgtElement.scrollIntoView();
                                        }
                                    } catch(error){}
                                }
                            }).val(val).trigger('change');
                        } else {
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                                allowClear: true,
                                ...dropdownParent
                            }).on('select2:open', function (e) {
                                if(e.target.name == 'rcpItemCostCenter[]'){
                                    var left = $('.portlet-body').width();
                                    console.log(left);
                                    $('.portlet-body').scrollLeft(left);
                                } else {
                                    var target_parent = $(e.target).parents('td');
                                    try {
                                        if(target_parent.length > 0){
                                            var tgtElement = target_parent[0];
                                            tgtElement.scrollIntoView();
                                        }
                                    } catch(error){}
                                }
                            });
                        }
                    }

                    else if(newcell.childNodes[1].name == 'rcpItemMeasurement[]'){
                        $(`#${newcell.childNodes[1].id}`).select2({
                            placeholder: "Select an option",
                            ...dropdownParent
                        }).on('select2:select', function(e){
                            if($(e.target).parents('tr').find('[name="rcpItemMaterials[]"]').val()){
                                setTimeout(function(){
                                    var target = $(e.target).parents('tr').find('[name="rcpItemQuantity[]"]')[0];
                                    checkLastPrice(target, e.params.data.id);
                                }, 600);
                            }
                        }).on('select2:open', function (e) {
                            if(e.target.name == 'rcpItemCostCenter[]'){
                                var left = $('.portlet-body').width();
                                console.log(left);
                                $('.portlet-body').scrollLeft(left);
                            } else {
                                var target_parent = $(e.target).parents('td');
                                try {
                                    if(target_parent.length > 0){
                                        var tgtElement = target_parent[0];
                                        tgtElement.scrollIntoView();
                                    }
                                } catch(error){}
                            }
                        });
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

        if(rowCount>2){
            if(rowIndex == 1){
                Swal.fire('First Item Removal', 'Cannot remove first item. If you want to change item, edit it instead of remove', 'warning');
            } else {
                // table.deleteRow(rowCount -1);
                table.deleteRow(rowIndex);
            }
        } else {
            Swal.fire('Item Removal', 'Cannot remove first item, the data that will be sent needs to be at least one. If you want to change item, edit it instead of remove', 'warning');
        }

        try {
            document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
        } catch(error){}

        try {
            var table_obj = document.getElementById(tableID);
            var rowUpdated = (table_obj.rows.length - 1)
            $('#item-count').text(rowUpdated);
        } catch(error){}

        try {
            calculateGrandTotal(table);
        } catch(error){}
    }

    function numericInput(elem){
        try {
            elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            // console.log(elem.value, Number(elem.value), Number(elem.value).toLocaleString("en-US"));
            elem.value = Number(elem.value).toLocaleString("en-US");
        } catch(error){
            elem.value = 0;
        }
    }

    function qtyInput(elem){
        var material = $(elem).parents('tr').find('[name="rcpItemMaterials[]"]').val();
        if(material){
            elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            // .replace(/\d(?=(\d{3})+\.)/g, '$&,')
            setTimeout(function(){
                checkLastPrice(elem);
            }, 600);
        } else {
            elem.value = 1;
            Swal.fire('Material Selection', 'Please select material first to get the last price based on quantity', 'warning');
        }
    }

    function yieldPerPortion(){
        var qty = $('#rcpQuantity').val();
        var portion = $('#rcpPortion').val();
        try {
            var yieldPerPortion = parseFloat(qty) / parseFloat(portion);
            yieldPerPortion = isNaN(yieldPerPortion) || yieldPerPortion == Infinity ? 0.00 : yieldPerPortion.toFixed(2);
            var yieldPerPortionFormat = number_format(yieldPerPortion, 2, '.', ',');
            $('#rcpYieldPerPortion').val(yieldPerPortionFormat);
        } catch(error){
            console.log("Error calculating yield per portion ", error);
        }
    }

    function setMargin(){
        var sellprice = $('#rcpSellingPrice').val().replace(/\,/g, '');
        var totalcost = $('#rcpTotalCost').val().replace(/\,/g, '');
        var margin = 0;
        try {
            margin = parseFloat(sellprice) - parseFloat(totalcost);
            margin = isNaN(margin) || margin == Infinity ? 0.00 : margin.toFixed(2);
            var margin_format = number_format(margin, 2, '.', ',');
            $('#rcpMargin').val(margin_format);
        } catch(error){
            console.log("Error calculating Margin ", error);
        }

        try {
            var marginPctg = (margin / parseFloat(sellprice)) * 100;
            // console.log('MARGIN PCTG', margin, marginPctg);
            marginPctg = isNaN(marginPctg) || marginPctg == Infinity ? 0.00 : marginPctg.toFixed(2);
            var marginPctgFormat = number_format(marginPctg, 2, '.', ',');
            // console.log(marginPctgFormat);
            $('#rcpMarginPctg').val(marginPctgFormat);
        } catch(error){
            console.log("Error calculating Margin Pctg ", error);
        }
    }

    function checkLastPrice(target, measurementVal=null){
        // console.log(target, target.value, typeof target.value);
        try {
            $('.btn-submit').prop('disabled', true);
            $('.btn-add').prop('disabled', true);
            $('.btn-del').prop('disabled', true);
            $('.btn-refresh').prop('disabled', true);

            try {
                $(target).parents('tr').find('[name="rcpItemMaterials[]"]').prop('disabled', true);
            } catch(error){}

            var qty = target.value;
            if(qty && qty !== '0') {
                var material = $(target).parents('tr').find('[name="rcpItemMaterials[]"]').val();
                if(measurementVal)
                   var unit = measurementVal
                else
                   var unit = $(target).parents('tr').find('[name="rcpItemMeasurement[]"]').val();
                plant = $('#rcpPlant').val();

                $(target).parent().find('.spinner-qty').prop('hidden', false);
                $.ajax({
                   url: "/sap/add-recipe/request",
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
                        if($(target).parents('tr').find('[name="rcpItemMaterials[]"]').val()){
                            $(target).parents('tr').find('[name="rcpItemCost[]"]').val(last_price);
                            try {
                                var sellprice = $('#rcpSellingPrice').val();
                                var last_price_plain = resp.last_price_plain;
                                var costItemPctg = (last_price_plain / parseFloat(sellprice)) * 100;
                                costItemPctg = isNaN(costItemPctg) || costItemPctg == Infinity ? 0.00 : costItemPctg.toFixed(2);
                                var costItemPctg_format = number_format(costItemPctg, 2, '.', ',');
                                $(target).parents('tr').find('[name="rcpItemCostPctg[]"]').val(costItemPctg_format);
                            } catch(error){
                                console.log('Error set item cost pctg ', error);
                            }

                        }
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="rcpItemCost[]"]').val('0');
                     console.log("Error in check cost / last price", xhr);
                     $('.btn-submit').prop('disabled', false);
                     $('.btn-add').prop('disabled', false);
                     $('.btn-del').prop('disabled', false);
                     $('.btn-refresh').prop('disabled', false);
                     calculateGrandTotal(target);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);

                     try {
                        $(target).parents('tr').find('[name="rcpItemCostPctg[]"]').val('0.00');
                     } catch(error){
                        console.log('Error set item cost pctg ', error);
                     }
                   },
                   complete : function(){
                     try {
                        $(target).parents('tr').find('[name="rcpItemMaterials[]"]').prop('disabled', false);
                     } catch(error){}
                   }
                });
            } else {
                $(target).parents('tr').find('[name="rcpItemCost[]"]').val('0');
                $('.btn-submit').prop('disabled', false);
                $('.btn-add').prop('disabled', false);
                $('.btn-del').prop('disabled', false);
                $('.btn-refresh').prop('disabled', false);
                try {
                    $(target).parents('tr').find('[name="rcpItemMaterials[]"]').prop('disabled', false);
                } catch(error){}
            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in cost / last price", error);
            $('.btn-submit').prop('disabled', false);
            $('.btn-add').prop('disabled', false);
            $('.btn-del').prop('disabled', false);
            $('.btn-refresh').prop('disabled', false);
            try {
                $(target).parents('tr').find('[name="rcpItemMaterials[]"]').prop('disabled', false);
            } catch(error){}
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
            var grand_total_cost = 0;
            $(targetChild).parents('form').find('input:visible[name="rcpItemCost[]"]').each(function(index, item){
                if(item.value){
                    let val = item.value.replace(/\,/g, '');
                    grand_total_cost += isNaN(val) ? 0 : parseFloat(val);
                } else 
                    grand_total_cost += 0;
            });
            // $(targetChild).parents('form').find('#grand_total_value').val(grand_total);
            if(grand_total_cost){
                try {
                    var sellprice = $('#rcpSellingPrice').val();
                    var costPctg = (grand_total_cost / parseFloat(sellprice)) * 100;
                    costPctg = isNaN(costPctg) || costPctg == Infinity ? 0.00 : costPctg.toFixed(2);
                    var costPctg_format = number_format(costPctg, 2, '.', ',');
                    $('#rcpTotalCostPctg').val(costPctg_format);
                } catch(error){
                    console.log('Error set total cost pctg ', error);
                    $('#rcpTotalCostPctg').val('0.00');
                }
                grand_total_cost = number_format(grand_total_cost, 2, '.', ',');
            }else 
                grand_total_cost= 0;
            $('#rcpTotalCost').val(grand_total_cost);
        } catch(error){
            console.log('error', error);
        }

        $('.btn-submit').prop('disabled', false);
        $('.btn-add').prop('disabled', false);
        $('.btn-del').prop('disabled', false);
        $('.btn-refresh').prop('disabled', false);
        setMargin();
    }

    function refreshMaterial(elem){
        var $_material = $(elem).parents('tr').find('[name="rcpItemMaterials[]"]').val();
        var target = $(elem).parents('tr').find('[name="rcpItemQuantity[]"]')[0];
        if($_material){
            $(elem).prop('disabled', true);
            setTimeout(function(){
                checkLastPrice(target);
            }, 200);
        }
    }

    function checkCostPerItem(sellPrice){
        var sellPriceVal = sellPrice.value || 0;
        $.each($('[name="rcpItemCost[]"]'), function(index, item){
            try {
                var sellprice = sellPriceVal;
                var last_price_plain = item.value.replace(/\,/g, '');
                var costItemPctg = (last_price_plain / parseFloat(sellprice)) * 100;
                costItemPctg = isNaN(costItemPctg) || costItemPctg == Infinity ? 0.00 : costItemPctg.toFixed(2);
                var costItemPctg_format = number_format(costItemPctg, 2, '.', ',');
                $(item).parents('tr').find('[name="rcpItemCostPctg[]"]').val(costItemPctg_format);
            } catch(error){
                console.log('Error set item cost pctg in checkCostPerItem ', error);
            }
        });
    }
</script>
@endsection
