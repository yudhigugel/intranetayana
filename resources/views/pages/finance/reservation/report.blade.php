@extends('layouts.default')

@section('title', 'Report Reservation')
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
  color:  #a7afb7;;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #a7afb7;;
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
      <li aria-current="page" class="breadcrumb-item active"><span>Report</span></li>
    </ol>
</nav>
<div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Report List</h4>
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
                                    <select class="form-control" id="status" name="status">
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
                            <a href="{{url('finance/add-reservation/report')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap; width: 100%">
                    <thead>
                        <tr>
                            <th class="exportable" style="min-width:90px;">Form No</th>
                            <th class="exportable" style="min-width:90px;">Status</th>
                            <th class="exportable" style="min-width:90px;">Request For Date</th>
                            <th class="exportable" style="min-width:90px;">Approval Name</th>
                            <th class="exportable" style="min-width:90px;">Approval Date</th>
                            <th class="exportable" style="min-width:90px;">Grand Total</th>
                            <th class="exportable" style="min-width:90px;">Movement Type</th>
                            <th class="exportable" style="min-width:90px;">Receiving SLOC / Cost Center</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalRequestReservation"  role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRequestLabel">Form - Add Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalRequest">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/add-reservation/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                    {{ csrf_field() }}
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
                                <label>Created Date</label>
                                <input type="text" name="CreatedDate" value="{{date('d F Y')}}" class="form-control" readonly/>
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

                            <div class="col-md-3">
                                <label>Movement Type <span class="red">*</span></label>
                                <select required class="select2 select-decorated form-control" name="MovementType" id="movementType" style="width: 100%;padding: 0.8rem 1.375rem" >
                                    <option value="" selected default>---- Choose Data ----</option>
                                    @if(isset($data['movement_type']) && count($data['movement_type']) > 0)
                                        @foreach($data['movement_type'] as $key_mv => $val_mv)
                                            <option value="{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE : '' }}">{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE. ' - ' : '' }} {{ isset($val_mv->MV_DESCRIPTION) ? "(".$val_mv->MV_DESCRIPTION.")" : '' }}</option>
                                        @endforeach
                                    @endif

                                    {{--<option value="311">311 - (Transfer Posting Storage Location (One-step))</option>
                                    <option value="Y04">Y04 - (GI CC OPEX - reversal)</option>--}}

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

                    <div class="form-group" id="rsvSlocContainer" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Receiving SLOC <span class="red">*</span></label>
                                <select class="form-control select2 select-decorated" name="rsvReceivingSLOC" id="receiving_sloc" disabled style="width: 100%">
                                    <option value="" default selected>---- Choose Sloc ----</option>
                                    @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                        @foreach($data['s_loc'] as $key => $val)
                                            <option value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'] : '' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="rsvCostCenterContainer" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Cost Center</label>
                                <input type="text" value="{{ isset($data['cost_center_id']) ? $data['cost_center_id'] : '' }}" id="cost_center_expense" name="rsvCostCenterExpense" class="form-control" disabled />
                                <small class="text-muted">Note : This cost center will be used as an expense destination</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important">
                            <table class="table table-bordered smallfont" id="reqForm">
                                <thead>
                                    <tr>
                                        <th style="width: 8%">Item</th>
                                        <th style="width: 20%">SLOC</th>
                                        <th style="width: 29%">Material</th>
                                        <th style="width: 10%">Quantity</th>
                                        <th style="width: 15%">Last Purchase Price</th>
                                        <th style="width: 10%">UoM</th>
                                        <th style="width: 8%">Actions</th>
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
                                            <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" id="rsvLastPrice" required placeholder="Automatically filled">
                                        </td>
                                        <td style="max-width: 1px">
                                            <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" required placeholder="Automatically filled">
                                        </td>
                                        <td>
                                            <div class="btn-group" style="min-width:140px">
                                                <button type="button" class="btn btn-danger btn-sm px-1" onclick="deleteBaris('reqForm')">-</button>
                                                <button type="button" class="btn btn-warning btn-sm px-1" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">Copy</button>
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
<input type="hidden" name="deptid" id="deptid" class="form-control" value="{{$data['department_id']}}">
<input type="hidden" name="midjobid" id="midjobid" class="form-control" value="{{$data['midjob_id']}}">
<input type="hidden" name="costcenter" id="costcenter" class="form-control" value="{{$data['costcenter']}}">

@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
    var data_filter_menu = {};
    $(document).ready( function () {
        $(".datepicker2").datepicker();

        $(".select-decorated").select2({
            placeholder: "Select an option",
        });

        $(".select-decorated-material").select2({
            dropdownParent: $('#reqForm'),
            allowClear: true,
            placeholder: "Search Material ...",
            ajax: {
               url: "/finance/add-reservation/request",
               type: "GET",
               dataType: 'json',
               delay: 300,
               data: function (params) {
                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: $('#Requestor_Plant_ID').val()
                };
               },
               processResults: function (response) {
                 return {
                    results: response
                 };
               },
               cache: true
            }
         }).on('select2:select', function(e){
            var value = e.params.data.id || 0;
            var text = e.params.data.text || 'Unknown';
            var unit = e.params.data.unit || 'Unknown';
            var lastPrice = e.params.data.last_price || 0;

            $(e.target).parents('tr').find('#rsvMeasurement').val(unit);
            $(e.target).parents('tr').find('#rsvLastPrice').val(lastPrice);
            $(e.target).parents('tr').find('#rsvMaterialsDesc').val(text);

            setTimeout(function(){
                checkLastPrice($(e.target).parents('tr').find('#rsvQty')[0]);
            }, 300);

        }).on('select2:unselecting', function(e){
            $(e.target).parents('tr').find('#rsvMeasurement').val('');
            $(e.target).parents('tr').find('#rsvLastPrice').val('');
            calculateGrandTotal();
            $(this).data('state', 'unselected');
        }).on("select2:open", function(e) {
            try {
              if ($(this).data('state') === 'unselected') {
                  $(this).removeData('state'); 
                  console.log($(this));
                  var self = $(this).parent().find('.select2')[0];
                  setTimeout(function() {
                      $(self).select2('close');
                  }, 0);
              }
            } catch(error){}   
        });

        $('#modalRequestReservation').on('hidden.modal.bs', function(){
            try {
                clearAll();
            } catch(error){}
        });

        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        var costcenter= $("#costcenter").val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            "dom": '<"abs-search row mb-2" <"button-export-wrapper col-9 align-items-center"B>>rtip',
            "buttons": {
                buttons: 
                [{
                  extend: 'excelHtml5',                
                  className : 'mb-2 mt-4 btn btn-primary',
                  text: '<i class="mdi mdi-export"></i>&nbsp;Export Excel',
                  title: '',
                  filename : 'AYANA Intranet - Reservation Report Export',
                  exportOptions: {
                    // columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                    orthogonal: 'export-excel',
                    columns: 'th.exportable'
                  }
                }]
            },
            "ajax": {
                "type" : "POST",
                "url" : "/finance/add-reservation/report/getData",
                "data" : function(d){
                    d.employeeId = updateBy;
                    d.deptId = deptid;
                    d.midjobId = midjobid;
                    d.costcenter = costcenter;
                    d.filter = "";
                    d.value = "";
                    d.status = status;
                    d.insert_date_from = request_date_from;
                    d.insert_date_to = request_date_to;
                    d.REQUESTOR_NAME = $('.filter-select-1').val();

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
            "scrollX": true,
            "searching":false,
            "lengthChange": false,
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
            initComplete : function(settings, json){
                var table_obj = this.api();
                var requestor = [];
                if(typeof json.data.length != 'undefined' && json.data.length > 0){
                    requestor = [...new Set(json.data.map(element => ({'ALIAS':element.ALIAS,'NAME':element.NAME})).filter(x => x !== null))];
                    requestor = [...new Map(requestor.map((item) => [item["NAME"], item]).filter(x => x[0] !== null && x[0] !== '-')).values()];
                }
                var select = $(`<div class="content-filter col-3 mb-2"><label>Filter By Requestor Name</label><div><select class="form-control select2 filter-select-1 mr-3" data-filter="0"><option value="">Pilih Data Disini</option></select></div></div>`)
                .appendTo( $('.abs-search') )
                .on('select2:select', function (e) {
                    var value = e.params.data.id;
                    var val = $.fn.dataTable.util.escapeRegex(
                        value
                    );
                    if(val){
                      data_filter_menu['REQUESTOR_NAME'] = val
                    }
                    const qs = Object.keys(data_filter_menu)
                    .map(key => `${key}=${data_filter_menu[key]}`)
                    .join('&');
                    if(qs)
                      table_obj.ajax.reload();

                }).on("select2:unselecting", function(e) {
                    try {
                      delete data_filter_menu['PR_REQ_NAME'];
                      const qs = Object.keys(data_filter_menu)
                      .map(key => `${key}=${data_filter_menu[key]}`)
                      .join('&');
                      table_obj.ajax.reload();
                    } catch(error){}
                    $(this).data('state', 'unselected');
                }).on("select2:open", function(e) {
                    try {
                      if ($(this).data('state') === 'unselected') {
                          $(this).removeData('state'); 

                          var self = $(this).find('.select2')[0];
                          setTimeout(function() {
                              $(self).select2('close');
                          }, 0);
                      }
                    } catch(error){}   
                });

                $(requestor).each( function ( d, j ) {
                    $(`.filter-select-1`).append( '<option value="'+j.ALIAS+'">'+j.NAME+'</option>' )
                });
                $('.select2').select2({
                  placeholder: 'Choose Data',
                  allowClear: true
                });
            }
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
    $(document).on('select2:select', 'select[name="rsvSLOC[]"]', function(){});

    function getFormDetail(id){
        $('.loader-modal').show();
        $('#modalFile #bodyModalFile').html('');
        $.get("{{url('finance/add-reservation/modal-detail')}}", { id : id, reservation_type: 'report'}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
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
            $('#movementType').val(null).trigger('change');
            $('#rsvSlocContainer').prop('hidden', true);
            $('#receiving_sloc').prop('required', false);
        } catch(error){}
    }

    function calculateGrandTotal(){
        try {
            var grand_total = 0;
            $('input[name="rsvLastPrice[]"]').each(function(index, item){
                if(item.value){
                    let val = item.value.replace(/\,/g, '');
                    grand_total += isNaN(val) ? 0 : parseFloat(val);
                } else 
                    grand_total += 0;
            });
            $('#grand_total_value').val(grand_total);
            if(grand_total)
                grand_total = number_format(grand_total, 2, '.', ',');
            else 
                grand_total = 0;
            $('#grand_total').text(grand_total);
        } catch(error){
            console.log('error', error);
        }

        $('.btn-submit').prop('disabled', false);
    }

    $('#movementType').on('select2:select', function(e){
        var val_selected = e.params.data.id || 0;
        if(val_selected == '311'){
            $('#rsvSlocContainer').prop('hidden', false);
            $('#receiving_sloc').prop('required', true);
            $('#receiving_sloc').prop('disabled', false);
            $('#rsvCostCenterContainer').prop('hidden', true);
            $('#cost_center_expense').prop('readonly', false);
            $('#cost_center_expense').prop('disabled', true);
        } else {
            $('#rsvSlocContainer').prop('hidden', true);
            $('#receiving_sloc').prop('required', false);
            $('#receiving_sloc').prop('disabled', true);
            $('#rsvCostCenterContainer').prop('hidden', false);
            $('#cost_center_expense').prop('disabled', false);
            $('#cost_center_expense').prop('readonly', true);
        }
    });

    $("#formRequest").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        try {
            var zero_value = false;
            $('input[name="rsvQuantity[]"]').each(function(index, elem){
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

    function checkLastPrice(target){
        try {
            $('.btn-submit').prop('disabled', true);
            var qty = target.value;
            if(qty && qty !== '0') {
                material = $(target).parents('tr').find('[name="rsvMaterials[]"]').val(),
                unit = $(target).parents('tr').find('[name="rsvMeasurement[]"]').val();
                $(target).parent().find('.spinner-qty').prop('hidden', false);

                $.ajax({
                   url: "/finance/add-reservation/request",
                   type: "GET",
                   dataType: 'json',
                   delay: 300,
                   data: {
                    'type': 'material_last_price', 
                    'material': material, 
                    'unit': unit, 
                    'qty': qty,
                    'plant': $('#Requestor_Plant_ID').val()
                   },
                   success : function(resp){
                     if(resp.hasOwnProperty('last_price')){
                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="rsvLastPrice[]"]').val(last_price);
                        calculateGrandTotal();
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                     console.log("Error in check last price", xhr);
                   },
                   complete : function(){
                     // $(target).parent().find('.spinner-qty').prop('hidden', true);
                     // $('.btn-submit').prop('disabled', false);
                   }
                });
            } else {
                $(target).parents('tr').find('[name="rsvLastPrice[]"]').val('0.00');
                $('.btn-submit').prop('disabled', false);

            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
        }
    }

    function qtyInput(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        setTimeout(function(){
            checkLastPrice(elem);
        }, 300);
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

    function cloneRow(tableID) {
        var table = document.getElementById(tableID);
        if(!$(table).find('#sloc').val()){
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

                if(i == 1 || i == 2 || i == 5){
                    try {
                        var html = '';
                        if(i == 1){
                            html += `<td>
                                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials${i}" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                                <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                            </td>`;
                        } else if(i == 2){
                            html += `<td>
                                <input type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" required value="1">
                                <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                            </td>`;

                        } else if(i == 5){
                            let option = '';
                            let populate_option = $("select[name='rsvSLOC[]']")[0].options;
                            for(data=0;data<populate_option.length;data++){
                                option += populate_option[data].outerHTML;
                            }
                            html += `<td>
                                <select required class="form-control select2 select-decorated" name="rsvSLOC[]" id="sloc${i}" style="width: 100%">
                                    ${option}
                                </select>
                            </td>`;
                        }

                        newcell.innerHTML = html;
                    } catch(error){
                        console.log(`Error in column 1 / 5 ${error}`);
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

                    else if(newcell.childNodes[1].name == 'rsvMaterials[]'){
                        $(`#${newcell.childNodes[1].id}`).select2({
                            dropdownParent: $('#reqForm'),
                            allowClear: true,
                            placeholder: "Search Material ...",
                            ajax: {
                               url: "/finance/add-reservation/request",
                               type: "GET",
                               dataType: 'json',
                               delay: 300,
                               data: function (params) {
                                return {
                                  searchTerm: params.term, // search term
                                  type: 'material',
                                  plant: $('#Requestor_Plant_ID').val()
                                };
                               },
                               processResults: function (response) {
                                 return {
                                    results: response
                                 };
                               },
                               cache: true
                            }
                         }).on('select2:select', function(e){
                            var value = e.params.data.id || 0;
                            var text = e.params.data.text || 'Unknown';
                            var unit = e.params.data.unit || 'Unknown';
                            var lastPrice = e.params.data.last_price || 0;

                            $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val(unit);
                            $(e.target).parents('tr').find(`[name='rsvLastPrice[]']`).val(lastPrice);
                            $(e.target).parents('tr').find(`[name='rsvMaterialsDesc[]']`).val(text);

                            setTimeout(function(){
                                checkLastPrice($(e.target).parents('tr').find(`[name='rsvQuantity[]']`)[0]);
                            }, 300);
                        }).on('select2:unselecting', function(e){
                            $(e.target).parents('tr').find(`[name='rsvMeasurement[]']`).val('');
                            $(e.target).parents('tr').find(`[name='rsvLastPrice[]']`).val('');
                            calculateGrandTotal();
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
                            }).val(val).trigger('change');
                        } else {
                            $(`#${newcell.childNodes[1].id}`).select2({
                                placeholder: "Select an option",
                            });
                        }
                    }
                } catch(error){ console.log(`There's error on column ${i}`, error) }
            }
        }
        document.getElementById('tableRow').value = document.getElementById('reqForm').rows.length;
    }

    function deleteBaris(tableID) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        if(rowCount>2){
            table.deleteRow(rowCount -1);
        }
        document.getElementById('tableRow').value = document.getElementById('reqForm').rows.length;
    }


</script>
@endsection
