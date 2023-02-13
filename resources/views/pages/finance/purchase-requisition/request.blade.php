@extends('layouts.default')

@section('title', 'Request Purchase Requisition')
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

.dataTables_info {
    float: left;
}

.table-container-h {
    overflow: auto;
}

.table-wrapper {
    position: relative;
}

div.dataTables_wrapper div.dataTables_processing {
    top: -30px !important;
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

.select2-results {
    text-align: left;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
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
                                        <option value="" {{ empty($data['status']) ? 'selected' : '' }}>All</option>
                                        <option value="Waiting" {{ ($data['status']=="Waiting")? 'selected' : '' }}>Waiting for Approval</option>
                                        <option value="Finished" {{ ($data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                        <option value="Canceled" {{ ($data['status']=="Canceled")? 'selected' : '' }}>Canceled</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <div class="mt-2"> 
                                        <small class="text-muted">
                                        <span class="d-block mb-1"><i class="fa fa-info-circle text-primary"></i>&nbsp;&nbsp;Status Information</span>
                                        - <span><b>All</b></span> : Shows all PR requests <br>
                                        - <span><b>Waiting For Approval</b></span> : Shows only PR or PO which need to be approved or incomplete approval <br>
                                        - <span><b>Finished</b></span> : Shows only PR that has been completed the final approval <br>
                                        - <span><b>Cancelled</b></span> : Shows only PR that has been cancelled by requestor <br>
                                        - <span><b>Rejected</b></span> : Shows only PR that has been rejected by approver
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/purchase-requisition/request')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />

                @if(session('message') && isset(session('message')['type']))
                <div class="alert alert-fill-{{ session('message')['type'] }} alert-dismissable p-3 mb-3" role="alert">
                  @if(session('message')['type'] == 'success')
                  <i class="mdi mdi-check"></i>
                  @else
                  <i class="mdi mdi-alert-circle"></i>
                  @endif

                  {{ session('message')['msg'] }}
                  <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

                <table class="table table-bordered table-striped datatable requestList" id="requestList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>PR Number</th>
                            <th>PR Detail</th>
                            <th>Status PR</th>
                            <th>Last Approver PR</th>
                            {{--<th >Reason</th>--}}
                            <th>Req. Date</th>
                            <th>Purpose</th>
                            {{--<th>Tracking No.</th>--}}
                            {{--<th>Tracking No. Desc</th>--}}
                            <th>Doc Type</th>
                            {{--<th>PO Number</th>--}}
                            <th>PO List</th>
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
                <h5 class="modal-title" id="modalRequestLabel">Form - Add Purchase Requisition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalRequest" style="overflow: hidden">
                <form method="POST" autocomplete="off" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
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
                            <div class="col-md-6">
                                <label>Doc Type <span class="red">*</span></label>
                                <select class="form-control select2" name="doc_type" id="doc_type" required onchange="SelectDocType(this);" style="width: 100%">
                                    <option value="" selected disabled>-- SELECT DOC TYPE --</option>
                                    <option value="YOPX">PR OPEX MID</option>
                                    <option value="YCPX">PR CAPEX MID</option>
                                    <option value="YOCN">PR CONSIGNMENT MID</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Ship to Plant <span class="red">*</span></label>
                                <select name="plant3" class="form-control select2" id="plant3" onchange="SelectPlant(this.value);" style="width: 100%">
                                    <option value="" selected >-- SELECT PLANT --</option>
                                    @foreach ($data['list_plant'] as $list_plant)
                                        <option value="{{$list_plant->SAP_PLANT_ID}}^-^{{$list_plant->SAP_PLANT_NAME}}">{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" class="form-control" id="plant2" name="plant2" readonly="" value="">
                                <input type="hidden" class="form-control" id="plant" name="plant" readonly="" value="">
                                <input type="hidden" readonly id="tableRow" name="tableRow">

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Purpose / Notes <span class="red">*</span></label>
                                <input type="text" class="form-control" name="purpose" required placeholder="insert your purpose / notes on requesting purchase requisition"/>
                            </div>
                            <div class="col-md-6">
                                <label>Cost Center <span class="red">*</span></label>&nbsp;&nbsp;<i class="fa fa-spinner fa-spin" id="spinner_cost_center" style="display:none;"></i>
                                <select class="form-control select2" name="cost_center" id="cost_center" onchange="selectCostCenter()" required style="width: 100%">
                                    <option value="" selected>-- PLEASE SELECT PLANT FIRST --</option>
                                    {{-- @foreach ($data['list_cost_center'] as $list_cost_center)
                                        <option value="{{$list_cost_center->SAP_COST_CENTER_ID}}"><b>{{$list_cost_center->SAP_COST_CENTER_ID}}</b> - {{$list_cost_center->SAP_COST_CENTER_NAME}} - {{$list_cost_center->DEPARTMENT_NAME}} - {{$list_cost_center->DIVISION_NAME}} </option>
                                    @endforeach --}}
                                </select>

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
                        <div class="row mb-3" hidden>
                            <div class="col-md-6">
                                <label>PPN</label>
                                <select name="ppn" class="form-control select2" id="ppn">
                                    <option value="" selected disabled>--SELECT PPN-- </option>
                                    <option value="YES">YES</option>
                                    <option value="NO" selected>NO</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{$data['job_title']}}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>

                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Vendor Information
                            <label class="toggle-switch toggle-switch-success">
                                {{-- <input type="checkbox" id="toggleVendor" checked> --}}
                                <input type="checkbox" id="toggleVendor">
                                <span class="toggle-slider round"></span>
                              </label>
                        </h3>
                    <div class="form-group formVendor" id="formVendor" style="display: none">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Vendor</label>
                                <div class="input-group">
                                    <input type="text" id="vendor_search" class="form-control" style="text-transform:uppercase" oninput="disableButton('vendor_search','sVendor');" placeholder="Search Vendor" onkeypress="return (event.charCode !=13)">
                                    <input type="hidden" name="vendor_code" id="vendor_code" class="form-control">
                                    <input type="hidden" name="vendor_name" id="vendor_name" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn blue btn-primary" type="button" onclick="SearchVendor();" id="sVendor">Search</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile </label>
                                <input type="text" class="form-control" name="vendor_mobile" id="vendor_mobile"  readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="" name="vendor_address" id="vendor_address" value="" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Phone</label>
                                <input type="text" class="form-control" placeholder="" name="vendor_phone" id="vendor_phone" value="" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" placeholder="" name="vendor_cp" id="vendor_cp" value="" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Fax</label>
                                <input type="text" class="form-control" placeholder="" name="vendor_fax" id="vendor_fax" value="" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Currency <span class="red">*</span></label>
                                <select name="currency" class="form-control select2" id="vendor_currency">
                                    <option value="IDR">IDR - Indonesian Rupiah</option>
                                    <option value="USD">USD - United State Dollar</option>
                                    <option value="SGD">SGD - Singapore Dollar</option>
                                    <option value="RMB">RMB - Chinese Yuan Renminbi</option>
                                    <option value="CNY">CNY - Chinese Yuan Renminbi</option>
                                    <option value="KRW">KRW - South Korean Won</option>
                                    <option value="EUR">EUR - Euro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="vendor_email"  id="vendor_email" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Reason <span class="red">*</span></label>
                                <input type="text" class="form-control" placeholder="Insert your reason why choosing this vendor" name="reason" id="vendor_reason" />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        {{--<div class="row portlet-body table-both-scroll mb-3">--}}
                        <div class="table-container-h portlet-body table-both-scroll mb-3">
                            <table class="table table-striped table-bordered table-hover smallfont" id="reqForm" style="min-width: 4000px">
                                <thead>
                                    <tr>
                                        <th >Item</th>
                                        <th class="thead-apri">Account Assignment</th>
                                        <th class="thead-apri" style="width: 7%">Material</th>
                                        <th class="thead-apri" style="width: 9%">Material Desc.</th>
                                        <th class="thead-apri" style="width: 5%">Material Purch. Group</th>
                                        <th class="thead-apri">Material Purpose</th>
                                        <th class="thead-apri" style="display:none;">Additional Information</th>
                                        <th class="thead-apri" style="width: 6%">SLOC</th>
                                        <th class="thead-apri">Quantity</th>
                                        <th class="thead-apri">Unit</th>
                                        <th class="thead-apri" style="width: 6%">Delivery Date</th>
                                        <th class="thead-apri">Last Purchase Price</th>
                                        <th class="thead-apri">Price Unit</th>
                                        <th class="thead-apri">Amount</th>
                                        <th class="thead-apri">Cost Center</th>
                                        <th class="thead-apri" style="width: 7%">Asset Number</th>
                                        <th class="thead-apri" style="width: 9%">Asset Description</th>
                                        {{--<th class="thead-apri">Order Number</th>--}}
                                        <th class="thead-apri">Tracking Number</th>
                                        {{--<th class="thead-apri">Commitment Item</th>
                                        <th class="thead-apri">Commitment Item Desc</th>--}}
                                        <th class="thead-apri">Funds Center</th>
                                        <th class="thead-apri">Funds Currency</th>
                                        {{--<th class="thead-apri">Remain Budget  Month to Date</th>
                                        <th class="thead-apri">Remain Year to Date</th>--}}
                                        <th class="thead-apri"></th>
                                        <th class="thead-apri"></th>
                                        <th class="thead-apri"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="rowToClone">
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:100px;" name="preqItem[]" id="preqItem" value="00010" readonly="">
                                        </td>
                                        <td>
                                            <select class="bs-select form-control td-apri"  id="acctasscat" name="acctasscat[]" onchange="SelectAcctAss(this);" style="min-width:250px;" required>
                                                <option value="">Choose Account Assignment</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{--<input required type="text" class="form-control td-apri" style="min-width:200px;" name="materials[]" id="materials" oninput="disableButton2(this);" placeholder="Search Material Name"  onkeypress="return (event.charCode !=13)">--}}
                                            <select required class="form-control select-decorated-material td-apri" name="materials[]" id="materials" style="width: 100%">
                                                <option value="" default selected>---- Choose Material ----</option>
                                            </select>
                                        </td>
                                        {{--<td>
                                            <button type="button" class="btn blue btn-primary" id="smaterials" name="smaterials[]" onclick="SearchMaterial2(this)" disabled="">Search</button>
                                        </td>--}}
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="materialDesc[]" id="materialDesc" readonly="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="materialPurchGroup[]" id="materialPurchGroup" readonly="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" placeholder="material purpose" style="min-width:150px;" name="materialPurpose[]" id="materialPurpose" maxlength="132">
                                        </td>
                                        {{-- <td style="display: none;">
                                            <input type="text" class="form-control td-apri" style="min-width:150px;" name="additionalInfo[]" id="additionalInfo" maxlength="132" placeholder="additional purpose" onkeyup="CalculateTotal(this);" onkeypress="return ((event.charCode>47 &amp;&amp; event.charCode<58)||(event.charCode>64 &amp;&amp; event.charCode<91)||(event.charCode>96 &amp;&amp; event.charCode<123)||event.charCode==0||event.charCode==32)">
                                        </td> --}}
                                        <td>
                                            <select class="bs-select form-control td-apri" required  id="item_sloc" name="item_sloc[]" style="min-width:150px;">
                                                <option value="">Choose SLOC</option>
                                            </select>
                                        </td>
                                        <td style="position: relative;">
                                            <input required type="text" class="form-control td-apri" style="min-width:75px;" value="1" name="quantity[]" id="quantity" oninput="qtyInputAdditionalMaterial(this)">
                                            <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:75px;" name="unit[]" id="unit" readonly="">
                                        </td>
                                        <td>
                                            <input class="form-control form-control-inline input-small date-picker td-apri" size="8" type="text" style="min-width:80px;" name="delivDate[]" id="delivDate" data-date-format="yyyy-mm-dd" data-date-start-date="+0d" placeholder="Choose Delivery Date">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" readonly name="cAmitBapx[]" id="cAmitBapx" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" placeholder="0.00" value="0.00">
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control td-apri" style="min-width:200px;" name="priceUnit[]" id="priceUnit" value="1" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="totalAmounx[]" id="totalAmounx" readonly="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:110px;" name="costCenter[]" id="costCenter" readonly="" value="">
                                        </td>
                                        <td>
                                            {{--<input type="text" class="form-control td-apri" style="min-width:200px;" required name="assetNo[]" id="assetNo" oninput="disableButton2(this);" placeholder="Search Asset Number" onkeypress="return (event.charCode !=13)">--}}
                                            <select disabled class="form-control select-decorated-asset td-apri" required name="assetNo[]" id="assetNo" style="width: 100%">
                                                <option value="" default selected>---- Choose Asset Number ----</option>
                                            </select>
                                        </td>
                                        {{--<td>
                                            <button type="button" class="btn blue btn-primary" id="sassetNo" name="sassetNo[]" onclick="SearchAsset(this);" disabled="">Submit</button>
                                        </td>--}}
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="assetDesc[]" id="assetDesc" readonly="">
                                        </td>
                                        {{--<td>
                                            <input type="text" class="form-control td-apri" style="min-width:150px;" name="orderNo[]" id="orderNo" maxlength="12">
                                        </td>--}}
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:110px;" name="trackingNo[]" id="trackingNo" readonly="" value="">
                                        </td>
                                        {{--<td>
                                            <input type="text" class="form-control td-apri" style="min-width:150px;" name="cmmtItem[]" id="cmmtItem" readonly="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:250px;" name="cmmtItemText[]" id="cmmtItemText" readonly="">
                                        </td>--}}
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:110px;" name="fundsCtr[]" id="fundsCtr" readonly="" value="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:80px;" name="fundsCurr[]" id="fundsCurr" readonly="">
                                        </td>
                                        {{--<td>
                                            <input type="text" class="form-control td-apri" style="min-width:150px;" name="amountTxt[]" id="amountTxt" readonly="">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control td-apri" style="min-width:150px;" name="amountYearTxt[]" id="amountYearTxt" readonly="">
                                        </td>--}}
                                        <td>
                                            <div class="btn-group" style="min-width:140px">
                                                <button type="button" class="btn btn-success btn-add" onclick="addRow('reqForm')">+</button>
                                                <button type="button" class="btn btn-danger btn-del" onclick="deleteBaris('reqForm')">-</button>
                                                {{--<button type="button" class="btn btn-warning" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">Copy</button>--}}
                                            </div>
                                        </td>
                                        <td>
                                            <input type="hidden" name="cAmitBapi[]" id="cAmitBapi">
                                            <input type="hidden" name="cmmtItem[]" id="cmmtItem">
                                            <input type="hidden" name="cmmtItemText[]" id="cmmtItemText">
                                            <input type="hidden" name="orderNo[]" id="orderNo">
                                        </td>
                                        <td>
                                            <input type="hidden" name="totalAmount[]" id="totalAmount">
                                            <input type="hidden" name="amountTxt[]" id="amountTxt">
                                            <input type="hidden" name="amountYearTxt[]" id="amountYearTxt">
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
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Attachment</h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>You can upload attachment after saving the Purchase Requisition Form</label>
                                {{-- <label><i>Please attach in a compressed file format (.zip, .rar, .7z)</i></label> --}}

                                {{-- <input type="file"  class="form-control" name="file"> --}}
                            </div>
                        </div>
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
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFileLabel">Purchase Requisition Detail </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalFile">

            </div>
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
<script type="text/javascript">
    var global_purchasing_group = null;
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

    $('#modalRequest').on('shown.bs.modal', function (e) {
    })

    /*****************************************************************************/
    /*****************************************************************************/
    /* FUNCTION DARI INTRANET BIZNET */
    /*****************************************************************************/
    /*****************************************************************************/
    function SearchVendor(){
        var keywordParameter = document.getElementById('vendor_search').value;
        if(keywordParameter.length > 2){
            var vUrl='search-vendor?keywordParameter='+keywordParameter;
            doPopUpWindow = window.open(vUrl,"cari","width=900,height=500,resizable=yes,scrollbars=yes,left=80,top=80");
        }
        else{
            if(keywordParameter.length > 0  && keywordParameter.length <= 3){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Vendor Name length must be greater than 3 characters',
                });
            }
            else{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please input vendor name!',
                });
            }
        }
    }

    function SearchAsset(tableId){
        var rowId = tableId.id;
        var idrow = rowId.substring(8, 16);

        var assetNo = document.getElementById('assetNo'+idrow).value;
        var fundCenter = document.getElementById('fundsCtr'+idrow).value;
        if(assetNo == ""){
            swal("Input Asset Number");
            return false;
        }

        var company = document.getElementById('plant3').value.substr(0,3);

        var vUrl='search-asset?idRow='+idrow+'&assetNo='+assetNo+'&fundsCtr='+fundCenter+'&company='+company;
        doPopUpWindow = window.open(vUrl,"cari","width=900,height=500,resizable=yes,scrollbars=yes,left=80,top=80");
    }

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
                if(i == 2 || i == 14){
                    try {
                        var html = '';

                        if(i == 2){
                            html += `<td>
                                <select required class="form-control select-decorated-material td-apri" name="materials[]" id="materials" style="width: 100%">
                                    <option value="" default selected>---- Choose Material ----</option>
                                </select>
                            </td>`;
                        }
                        else if(i == 14){
                            var acct_id = document.getElementById('assetNo').value || '';
                            var disabled_state = document.getElementById('acctasscat').value == 4 || document.getElementById('acctasscat').value == '4' ? '' : 'disabled';
                            if(acct_id){
                                html += `<td>
                                    <select ${disabled_state} class="form-control select-decorated-asset td-apri" required name="assetNo[]" id="assetNo" style="width: 100%">
                                        <option value="${acct_id}" default selected>${acct_id}</option>
                                    </select>
                                </td>`;
                            } else {
                                html += `<td>
                                    <select ${disabled_state} class="form-control select-decorated-asset td-apri" required name="assetNo[]" id="assetNo" style="width: 100%">
                                        <option value="" default selected>---- Choose Asset Number ----</option>
                                    </select>
                                </td>`;
                            }
                        }
                        newcell.innerHTML = html;
                    } catch(error){
                        console.log(`Error in column 2 index ${error}`);
                    }
                } else {
                    newcell.innerHTML = table.rows[1].cells[i].innerHTML;
                }

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
                try {
                    newcell.childNodes[3].id = newcell.childNodes[3].id+rowCount ;
                    newcell.childNodes[5].id = newcell.childNodes[5].id+rowCount ;
                } catch(error){}


                if(newcell.childNodes[1].name == 'materials[]'){
                    // newcell.childNodes[1].disabled = false;
                    // newcell.childNodes[1].removeAttribute('readonly');
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
                                return data.id;
                            }
                        },
                        dropdownParent: $('#reqForm'),
                        allowClear: false,
                        placeholder: "Search Material ...",
                        ajax: {
                           url: "/finance/purchase-requisition-marketlist/request",
                           type: "GET",
                           dataType: 'json',
                           delay: 600,
                           data: function (params) {
                            try {
                                var plant = $('#plant3').val().split('^-^', 2)[0];
                            } catch(error){
                                var plant = null
                            }
                            var qty = $(this[0].closest('tr')).find('[name="quantity[]"]').val() || 0;
                            // var rowIndex = $(this).parents('tr')[0].rowIndex;
                            // var tableRowCount = $(this).parents('tbody').find('tr').length;
                            // var tableValueCount = $('[name="materials[]"]').filter(function(index, item){
                            //     return item.value.length > 0 ;
                            // });
                            // if(tableValueCount.length == 1 && tableRowCount == 1 || tableValueCount.length == 1 && tableRowCount > 1)
                            //     global_purchasing_group = null;

                            // if(rowIndex == 1 && !global_purchasing_group){
                            //     var searchData = {'searchPurchasingGroup': false};
                            // } else if(rowIndex == 1 && global_purchasing_group || rowIndex != 1 && global_purchasing_group){
                            var searchData = {'searchPurchasingGroup': true, 'purGroup': global_purchasing_group}
                            // }

                            return {
                              searchTerm: params.term, // search term
                              type: 'material',
                              plant: plant,
                              ...searchData,
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
                             // var plant = $('#Requestor_Plant_ID').val();
                             try {
                                var plant = $('#plant3').val().split('^-^', 2)[0];
                             } catch(error){
                                var plant = null
                             }

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
                            var rowId = e.target.id
                            var idRow = rowId.substring(9, 18);
                        } catch(error){
                            console.log(error);
                            var idRow = '-';
                        }

                        try {
                            var materialDesc = e.params.data.text,
                            materialNo = e.params.data.id,
                            materialUnit = e.params.data.unit,
                            materialFipex = e.params.data.fipex,
                            cmmtItemText = e.params.data.cmmtItemText,
                            cAmitBapi = e.params.data.cmmtBapi,
                            fundsCurr = e.params.data.fund_curr,
                            amountTxt = e.params.data.amount_txt,
                            last_purchase = e.params.data.last_price,
                            amountYearTxt = e.params.data.amount_year_txt;
                            purchasing_group = e.params.data.PUR_GROUP == undefined ? '' : e.params.data.PUR_GROUP;

                            same_material = 0;
                            $('[name="materials[]"]').not(e.target).each(function(index, item){
                                if(item.value == materialNo){
                                    same_material++;
                                    return false;
                                }
                            });
                            if(same_material > 0){
                                $(e.target).val('').trigger('change');
                                Swal.fire('Duplicate Material', `This material ${materialDesc} has been added to the request list, choose another material`, 'error');
                                return false;
                            }
                            
                            document.getElementById("materialDesc"+idRow).value = materialDesc;
                            document.getElementById("materialPurchGroup"+idRow).value = purchasing_group;
                            document.getElementById("unit"+idRow).value = materialUnit;
                            document.getElementById("cmmtItem"+idRow).value = materialFipex;
                            document.getElementById("cmmtItemText"+idRow).value = cmmtItemText;
                            document.getElementById("cAmitBapx"+idRow).value = last_purchase;
                            document.getElementById("cAmitBapi"+idRow).value = cAmitBapi;
                            document.getElementById("fundsCurr"+idRow).value = fundsCurr;
                            document.getElementById("amountTxt"+idRow).value = amountTxt;
                            document.getElementById("amountYearTxt"+idRow).value = amountYearTxt;
                            document.getElementById("costCenter"+idRow).value = document.getElementById('cost_center').value;
                            calculateGrandTotalNew();
                        } catch(error){
                            console.log(error);
                            Swal.fire('Element Not Found', 'Some elements is not found in material selection while trying to assign value, please check the data and try again', 'error');
                        }
                    }).on('select2:unselecting', function(e){
                        $(this).data('state', 'unselected');
                    });
                }
                
                if(newcell.childNodes[1].name == 'acctasscat[]'){
                    newcell.childNodes[1].value = document.getElementById('acctasscat').value;
                }

                if(newcell.childNodes[1].name == 'item_sloc[]'){
                    newcell.childNodes[1].value = document.getElementById('item_sloc').value;
                }

                if(newcell.childNodes[1].name == 'assetNo[]'){
                    $(`#${newcell.childNodes[1].id}`).select2({
                        templateSelection: function(data) {
                            if(!data.id) {
                                return data.text;
                            } else {
                                return data.id;
                            }
                        },
                        dropdownParent: $('#reqForm'),
                        allowClear: false,
                        placeholder: "Search Asset ...",
                        ajax: {
                           url: "/finance/purchase-requisition/search-asset",
                           type: "GET",
                           dataType: 'json',
                           delay: 600,
                           data: function (params) {
                            try {
                                var company = document.getElementById('plant3').value.substr(0,3);
                            } catch(error){
                                var company = null
                            }
                            return {
                              assetNo: params.term, // search term
                              company: company,
                            };
                           },
                           processResults: function (response) {
                             return {
                                results: $.map(response.data, function (item) {
                                    return {
                                        id: item.ANLN1,
                                        text: `${item.TXT50} - ${item.TXK50}`,
                                        amount_txt: item.AMOUNT_TXT,
                                        amount_txt_year: item.AMOUNT_TXT_YEAR,
                                        fipex: item.FIPEX,
                                        fund_curr: item.FUNDS_CURR,
                                    }
                                })
                             };
                           },
                           cache: false,
                           transport: function (params, success, failure) {
                             // var plant = $('#Requestor_Plant_ID').val();
                             try {
                                var company = document.getElementById('plant3').value.substr(0,3);
                             } catch(error){
                                var company = null
                             }

                             if(company){
                                 var $request = $.ajax(params);
                                 $request.then(success);
                                 $request.fail(failure);
                                 return $request;
                             } else {
                                Swal.fire('Plant Selection', 'Plant is not available, please make sure to select or choose plant before finding assets', 'warning');
                                return false;
                             }
                           }
                        },
                        minimumInputLength: 3
                     }).on('select2:select', function(e){
                        try {
                            var rowId = e.target.id
                            var idRow = rowId.substring(7, 18);
                        } catch(error){
                            console.log(error);
                            var idRow = '-';
                        }
                        try {
                            var assetNumber = e.params.data.id,
                            assetName = e.params.data.text;
                            document.getElementById("assetDesc"+idRow).value = assetName;
                        } catch(error){
                            Swal.fire('Element Not Found', 'Some elements is not found in asset selection while trying to assign value, please check the data and try again', 'error');
                        }

                    }).on('select2:unselecting', function(e){
                        $(this).data('state', 'unselected');
                    });
                    // .trigger({
                    //     type: 'select2:select',
                    //     params: {
                    //         data: data_select
                    //     }
                    // });
                }

                if(newcell.childNodes[1].name == 'assetDesc[]'){
                    newcell.childNodes[1].value = document.getElementById('assetDesc').value;
                }

                if(newcell.childNodes[1].name == 'fundsCurr[]'){
                    newcell.childNodes[1].value = document.getElementById('fundsCurr').value;
                }

                if(newcell.childNodes[1].name == 'delivDate[]'){
                    newcell.childNodes[1].value = document.getElementById('delivDate').value;
                }

                if(newcell.childNodes[1].id == 'costCenter'+rowCount || newcell.childNodes[1].id == 'fundsCtr'+rowCount){
                    newcell.childNodes[1].value = document.getElementById('cost_center').value;
                }
                
                if(newcell.childNodes[1].id == 'trackingNo'+rowCount){
                    var cost_center = document.getElementById('cost_center').value;
                    var trackingNo = cost_center.replace("-", "_");
                    newcell.childNodes[1].value = trackingNo;
                }
                
                if(newcell.childNodes[1].id == 'cAmitBapi'+rowCount){
                    newcell.childNodes[1].value = "";
                }
            }
        }
        // bug bootstrap datepicker
        $(document).ready(function($) {
            $('#delivDate'+rowCount).removeClass("hasDatepicker");
            $('#delivDate'+rowCount).datepicker({
                autoclose: true,
                minDate: 0,
            });
            $('#smaterials'+rowCount).prop('disabled', true);

            $('#materials'+rowCount).prop('disabled', false);
            $('#orderNo'+rowCount).prop('disabled', false);

        });
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

    function cloneRow(tableID) {
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
                // newcell.childNodes[1].name = newcell.childNodes[1].name+rowCount ;
                newcell.childNodes[1].name = newcell.childNodes[1].name; //hilangin rowCount supaya bisa pakai name[] untuk keperluan input
                newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount ;

                if(newcell.childNodes[1].id == 'materials'+rowCount){
                    newcell.childNodes[1].value = table.rows[1].cells[i].children[0].value;
                }
                if(newcell.childNodes[1].id == 'acctasscat'+rowCount){
                    newcell.childNodes[1] = document.getElementById('acctasscat');
                    newcell.childNodes[1].value = table.rows[1].cells[i].children[0].value;
                }
                if(newcell.childNodes[1].id == 'smaterials'+rowCount){
                    newcell.childNodes[1].disabled = false;
                }
                if(newcell.childNodes[1].id == 'materialDesc'+rowCount || newcell.childNodes[1].id == 'quantity'+rowCount || newcell.childNodes[1].id == 'unit'+rowCount || newcell.childNodes[1].id == 'delivDate'+rowCount || newcell.childNodes[1].id == 'costCenter'+rowCount || newcell.childNodes[1].id == 'fundsCtr'+rowCount || newcell.childNodes[1].id == 'fundsCurr'+rowCount || newcell.childNodes[1].id == 'trackingNo'+rowCount || newcell.childNodes[1].id == 'amountTxt'+rowCount || newcell.childNodes[1].id == 'amountYearTxt'+rowCount || newcell.childNodes[1].id == 'cmmtItem'+rowCount || newcell.childNodes[1].id == 'cmmtItemText'+rowCount || newcell.childNodes[1].id == 'additionalInfo'+rowCount){
                    newcell.childNodes[1].value = table.rows[1].cells[i].children[0].value;
                }
                if(newcell.childNodes[1].id == 'orderNo'+rowCount){
                    // var disabledStatus = table.rows[1].cells[i].children[0].disabled;
                    newcell.childNodes[1].value = table.rows[1].cells[i].children[0].value;
                    // newcell.childNodes[1].disabled = disabledStatus;

                    if(table.rows[1].cells[i].children[0].disabled == true){
                        // console.log("Masuk True");
                        newcell.childNodes[1].disabled = true;
                    }
                    else{
                        // console.log("Masuk false");
                        newcell.childNodes[1].disabled = false;
                    }
                }

            }
        }
        document.getElementById('tableRow').value = document.getElementById('reqForm').rows.length;

    }

    function SelectDocType(idSelect){

        deleteAllRow('reqForm');
        var table = document.getElementById('reqForm');
        var rowCount = table.rows.length;


        for(var a = 1; a < rowCount; a++){

            if(a == 1){
                rowNum = "";
                idrow = "";
            }
            else{
                rowNum = a;
                idrow = a;
            }

            document.getElementById('materials'+idrow).value = "";
            try {
                $(`#materials${idrow}`).val('').trigger('change');
                document.getElementById('cAmitBapx'+idrow).value = "0.00";
                $(`#assetNo${idrow}`).val('').trigger('change');
                $(`#assetNo${idrow}`).prop('disabled', true);
            } catch(error){}
            document.getElementById('materialDesc'+idrow).value = "";
            document.getElementById('materialPurchGroup'+idrow).value = "";
            global_purchasing_group = null;
            // document.getElementById('additionalInfo'+idrow).value = "";
            document.getElementById('quantity'+idrow).value = "1";
            document.getElementById('unit'+idrow).value = "";
            document.getElementById('delivDate'+idrow).value = "";
            document.getElementById('cAmitBapi'+idrow).value = "";
            document.getElementById('totalAmount'+idrow).value = "";
            document.getElementById('assetNo'+idrow).value = "";
            document.getElementById('assetDesc'+idrow).value = "";
            document.getElementById('orderNo'+idrow).value = "";
            document.getElementById('cmmtItem'+idrow).value = "";
            document.getElementById('cmmtItemText'+idrow).value = "";
            document.getElementById('fundsCurr'+idrow).value = "";
            document.getElementById('amountTxt'+idrow).value = "";
            document.getElementById('amountYearTxt'+idrow).value = "";
            document.getElementById('grandTotal').value = "";
            document.getElementById('materials'+idrow).removeAttribute("readonly");

            var select = document.getElementById('acctasscat'+rowNum);

            var length = select.options.length;


            for (i = length; i > 0; i--) {
            select.remove(i);
            }


            // option untuk YCPX
            var opt4 = document.createElement('option');
            opt4.value = "4";
            opt4.innerHTML = "Asset MID GROUP";

            // var opt5 = document.createElement('option');
            // opt5.value = "5";
            // opt5.innerHTML = "GL ACCRUED ASSET";
            // ======================

            // option untuk YOPX
            var opt1 = document.createElement('option');
            opt1.value = "0";
            opt1.innerHTML = "Expense";

            /* Sementara di disable */
            // var opt1 = document.createElement('option');
            // opt1.value = "1";
            // opt1.innerHTML = "DirectExpense(CCREV)";

            // var opt2 = document.createElement('option');
            // opt2.value = "2";
            // opt2.innerHTML = "OPEX(CCNONREV)";
            /* Sementara di disable */

            var opt3 = document.createElement('option');
            opt3.value = "3";
            // opt3.innerHTML = "GL ACCRUED (NON CC)";
            opt3.innerHTML = "GL BALANCE SHEET";

            var optBlank = document.createElement('option');
            optBlank.value = " ";
            optBlank.innerHTML = "Inventory";

            //========================
            // option untuk YOCN
            var optK = document.createElement('option');
            optK.value = " ";
            optK.innerHTML = "Inventory Consignment";


            //========================
            if(idSelect.value == "YOPX"){
                select.appendChild(optBlank);
                select.appendChild(opt1);
                // select.appendChild(opt2);
                select.appendChild(opt3);
            }
            else if(idSelect.value == "YCPX"){
                select.appendChild(opt4);
                // select.appendChild(opt5);
            }
            else if(idSelect.value == "YOCN"){

                select.appendChild(optK);
            }

        }

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

    function selectCostCenter(){
        deleteAllRow('reqForm');

        document.getElementById('materials').value = "";
        try {
            $(`#materials`).val('').trigger('change');
            document.getElementById('cAmitBapx').value = "0.00";
            $(`#assetNo`).val('').trigger('change');
            $(`#assetNo`).prop('disabled', true);
        } catch(error){}
        document.getElementById('materialDesc').value = "";
        document.getElementById('materialPurchGroup').value = "";
        global_purchasing_group = null;
        // document.getElementById('additionalInfo').value = "";
        document.getElementById('quantity').value = "1";
        document.getElementById('unit').value = "";
        document.getElementById('delivDate').value = "";
        document.getElementById('cAmitBapi').value = "";
        document.getElementById('totalAmount').value = "";
        document.getElementById('assetNo').value = "";
        document.getElementById('assetDesc').value = "";
        document.getElementById('orderNo').value = "";
        document.getElementById('cmmtItem').value = "";
        document.getElementById('cmmtItemText').value = "";
        document.getElementById('fundsCurr').value = "";
        document.getElementById('amountTxt').value = "";
        document.getElementById('amountYearTxt').value = "";
        document.getElementById('grandTotal').value = "";
        document.getElementById('acctasscat').value = "";
        document.getElementById('materials').removeAttribute("readonly");

        cost_center = document.getElementById('cost_center').value;
        document.getElementById('costCenter').value = cost_center;
        document.getElementById('fundsCtr').value = cost_center;
        document.getElementById('trackingNo').value = cost_center.replace("-", "_");
    }

    function SearchMaterial2(tableId){
        var rowId = tableId.id;
        var idrow = rowId.substring(10, 18);

        var keywordParameter = document.getElementById('materials'+idrow).value;
        var plantParameter = document.getElementById('plant').value;
        var trackingNo = document.getElementById('fundsCtr').value;
        var acctasscat = document.getElementById('acctasscat'+idrow).value;

        if(plantParameter == "" || keywordParameter == "" || trackingNo == ""){
            if(plantParameter == ""){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please choose Plant!',
                });
                return false;
            }
            else if(keywordParameter == ""){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please input the keyword!',
                });
                return false;
            }
            else if(trackingNo == ""){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please choose Cost Center!',
                });
                return false;
            }
        }
        else{
            var vUrl='search-material?idRow='+idrow+'&materialName='+keywordParameter+'&plantParameter='+plantParameter+'&trackingNo='+trackingNo+'&acctasscat='+acctasscat;
            doPopUpWindow = window.open(vUrl,"cari","width=900,height=500,resizable=yes,scrollbars=yes,left=80,top=80");
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

    function SelectAcctAss(docType){
        var rowId = docType.id;
        var idrow = rowId.substring(10, 18);
        // console.log(docType.value);
        // console.log(idrow);
        document.getElementById('materials'+idrow).value = "";
        try {
            $(`#materials${idrow}`).val('').trigger('change');
            document.getElementById('cAmitBapx'+idrow).value = "0.00";
            $(`#assetNo${idrow}`).val('').trigger('change');
            $(`#assetNo${idrow}`).prop('disabled', true);
        } catch(error){
            console.log(error);
        }
        document.getElementById('materialDesc'+idrow).value = "";
        document.getElementById('materialPurchGroup'+idrow).value = "";
        global_purchasing_group = null;
        // document.getElementById('additionalInfo'+idrow).value = "";
        document.getElementById('quantity'+idrow).value = "1";
        document.getElementById('unit'+idrow).value = "";
        document.getElementById('delivDate'+idrow).value = "";
        document.getElementById('cAmitBapi'+idrow).value = "";
        document.getElementById('totalAmount'+idrow).value = "";
        document.getElementById('assetNo'+idrow).value = "";
        document.getElementById('assetDesc'+idrow).value = "";
        document.getElementById('orderNo'+idrow).value = "";
        document.getElementById('cmmtItem'+idrow).value = "";
        document.getElementById('cmmtItemText'+idrow).value = "";
        document.getElementById('fundsCurr'+idrow).value = "";
        document.getElementById('amountTxt'+idrow).value = "";
        document.getElementById('amountYearTxt'+idrow).value = "";
        document.getElementById('grandTotal').value = "";
        document.getElementById('materials'+idrow).removeAttribute("readonly");
        // console.log(docType.value);
        if(docType.value == "4"){
            document.getElementById('item_sloc'+idrow).removeAttribute("required");
            document.getElementById('assetNo'+idrow).disabled = false;
            document.getElementById('assetNo'+idrow).required = true;
            document.getElementById('assetNo'+idrow).placeholder = "Choose Asset Number";
            document.getElementById('assetNo'+idrow).style.backgroundColor = "white";
            document.getElementById('quantity'+idrow).removeAttribute("readonly");
            document.getElementById('costCenter'+idrow).readOnly= true;
            document.getElementById('costCenter'+idrow).value = "";
            document.getElementById('orderNo'+idrow).disabled = true;
            document.getElementById('orderNo'+idrow).value = "";
            document.getElementById('quantity'+idrow).value = 1;
            // document.getElementById('quantity'+idrow).setAttribute("readonly","readonly");
        }
        else if(docType.value == "1" || docType.value == "2" || docType.value == "0"){
            document.getElementById('item_sloc'+idrow).removeAttribute("required");
            var costCenter = document.getElementById('trackingNo').value;
            // document.getElementById('sassetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).value = "";
            document.getElementById('assetNo'+idrow).required = false;
            document.getElementById('assetNo'+idrow).placeholder = "";
            document.getElementById('costCenter'+idrow).disabled = false;
            document.getElementById('costCenter'+idrow).value = costCenter.replace("_", "-");
            document.getElementById('orderNo'+idrow).disabled = true;
            document.getElementById('orderNo'+idrow).value = "";
            document.getElementById('quantity'+idrow).value = "1";
            document.getElementById('quantity'+idrow).removeAttribute("readonly");
        }
        else if(docType.value == "F"){
            document.getElementById('item_sloc'+idrow).removeAttribute("required");
            // document.getElementById('sassetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).value = "";
            document.getElementById('assetNo'+idrow).required = false;
            document.getElementById('costCenter'+idrow).readOnly= true;
            document.getElementById('costCenter'+idrow).value = "";
            document.getElementById('orderNo'+idrow).disabled = false;
            document.getElementById('quantity'+idrow).value = "1";
            document.getElementById('quantity'+idrow).removeAttribute("readonly");
        }
        else if(docType.value==" "){
            document.getElementById('item_sloc'+idrow).setAttribute("required","required");
            // document.getElementById('sassetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).value = "";
            document.getElementById('assetNo'+idrow).placeholder = "";
            document.getElementById('assetNo'+idrow).required = false;
            document.getElementById('costCenter'+idrow).readOnly= true;
            document.getElementById('costCenter'+idrow).value = "";
            document.getElementById('orderNo'+idrow).disabled = true;
            document.getElementById('orderNo'+idrow).value = "";
            document.getElementById('quantity'+idrow).value = "1";
            document.getElementById('quantity'+idrow).removeAttribute("readonly");
        }else {
            document.getElementById('item_sloc'+idrow).removeAttribute("required");
            // document.getElementById('sassetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).disabled = true;
            document.getElementById('assetNo'+idrow).value = "";
            document.getElementById('assetNo'+idrow).required = false;
            document.getElementById('assetNo'+idrow).placeholder = "";
            document.getElementById('costCenter'+idrow).readOnly= true;
            document.getElementById('costCenter'+idrow).value = "";
            document.getElementById('orderNo'+idrow).disabled = true;
            document.getElementById('orderNo'+idrow).value = "";
            document.getElementById('quantity'+idrow).value = "1";
            document.getElementById('quantity'+idrow).removeAttribute("readonly");
        }
    }

    function calculateGrandTotalNew(){
        try {
            var total = 0;
            $('[name="cAmitBapx[]"]').each(function(index, elem){
                total += parseFloat(elem.value.replace(/,/g,''));
            });

            if(total > 0){
                var grandTotal = total.toLocaleString('en-US', {minimumFractionDigits: 2});
                document.getElementById('grandTotal').value = grandTotal;
            }
        } catch(error){}
    }

    function CalculateTotal(idTag){
        setTimeout(function(){
            var rowId = idTag.id;
            // console.log(rowId);
            if(rowId.substring(0,8) == "quantity"){
                var idrow = rowId.substring(8, 16);
            }
            else if(rowId.substring(0,9) == "cAmitBapi" || rowId.substring(0,9) == "cAmitBapx"){
                var idrow = rowId.substring(9, 17);
            }

            // console.log(idrow);
            // quantity = parseFloat(document.getElementById('quantity'+idrow).value);
            // price = parseFloat(document.getElementById('cAmitBapi'+idrow).value) || 0;
            // console.log(price);
            //price2 = price.toLocaleString('en-US', {minimumFractionDigits: 2});
            //document.getElementById('cAmitBapx'+idrow).value = price2;

            totalAmount = quantity * price;
            totalAmount2 = totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
            // document.getElementById('totalAmount'+idrow).value = totalAmount;
            // document.getElementById('totalAmounx'+idrow).value = totalAmount2;

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
                totalAmount = parseFloat(document.getElementById('totalAmount'+numberRow).value);
                grandTotal = grandTotal + totalAmount;
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
        if(id.substring(0,9) == "cAmitBapi" || id.substring(0,9) == "cAmitBapx"){
            var idx = id.substring(9, 17);
        }
        $('#cAmitBapi'+idx).val(valuesWithoutFormat);
    }

    function SelectPlant(id){
        var pisah = id.split("^-^");
        document.getElementById('plant').value = pisah[0];
        document.getElementById('plant2').value = pisah[1];

        deleteAllRow('reqForm');
        document.getElementById('materials').value = "";
        try {
            $(`#materials`).val('').trigger('change');
            document.getElementById('cAmitBapx').value = "0.00";
            $(`#assetNo`).val('').trigger('change');
            $(`#assetNo`).prop('disabled', true);
        } catch(error){
            console.log(error);
        }
        document.getElementById('materialDesc').value = "";
        document.getElementById('materialPurchGroup').value = "";
        global_purchasing_group = null;
        document.getElementById('item_sloc').value = "";
        // document.getElementById('additionalInfo').value = "";
        document.getElementById('quantity').value = "1";
        document.getElementById('unit').value = "";
        document.getElementById('delivDate').value = "";
        document.getElementById('cAmitBapi').value = "";
        document.getElementById('totalAmount').value = "";
        document.getElementById('assetNo').value = "";
        document.getElementById('assetDesc').value = "";
        document.getElementById('orderNo').value = "";
        document.getElementById('cmmtItem').value = "";
        document.getElementById('cmmtItemText').value = "";
        document.getElementById('fundsCurr').value = "";
        document.getElementById('amountTxt').value = "";
        document.getElementById('amountYearTxt').value = "";
        document.getElementById('grandTotal').value = "";
        document.getElementById('acctasscat').value = "";
        document.getElementById('materials').removeAttribute("readonly");

        var i, L = document.getElementById('item_sloc').options.length - 1;
        for(i = L; i >= 1; i--) {
            document.getElementById('item_sloc').remove(i);
        }

        // var i, L = document.getElementById('cost_center').options.length;
        // for(i = L; i >= 0; i--) {
        //     document.getElementById('cost_center').remove(i);
        // }
        var select = document.getElementById('cost_center');
        select.options.length = 0;

        var opt = document.createElement('option');
        opt.value ='';
        opt.innerHTML = '-- PLEASE SELECT PLANT FIRST --';
        select.appendChild(opt);

        cost_center = document.getElementById('cost_center').value;
        document.getElementById('costCenter').value = cost_center;
        document.getElementById('fundsCtr').value = cost_center;
        document.getElementById('trackingNo').value = cost_center.replace("-", "_");

        // start ajax untuk ambil sloc
        var plant = pisah[0];
        $.ajax({
            url:"{{url('finance/purchase-requisition/ajax_sloc')}}",
            type:'post',
            data: {plant : plant},
            success:function(response){
                var select = document.getElementById('item_sloc');
                if(response){
                    var hasil = JSON.parse(response);
                    if(hasil.length>0){
                        Object.keys(hasil).forEach(function(key){
                            // console.log(hasil[key]);
                            var opt = document.createElement('option');
                            opt.value = hasil[key].STORAGE_LOCATION;
                            opt.innerHTML = hasil[key].STORAGE_LOCATION+' - '+hasil[key].STORAGE_LOCATION_DESC;
                            select.appendChild(opt);
                        });
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }

        })

        // start ajax untuk ambil cost center
        $.ajax({
            url:"{{url('finance/purchase-requisition/ajax_costcenter')}}",
            type:'post',
            data: {plant : plant},
            beforeSend: function() {
                // setting a timeout
                $("#cost_center").attr('disabled','disabled');
                $("#spinner_cost_center").show();
            },
            success:function(response){
                var select = document.getElementById('cost_center');
                select.options.length = 0;

                var opt = document.createElement('option');
                opt.value ='';
                opt.innerHTML = '-- SELECT COST CENTER --';
                select.appendChild(opt);
                if(response){
                    var hasil = JSON.parse(response);
                    if(hasil.length>0){
                        Object.keys(hasil).forEach(function(key){
                            var opt = document.createElement('option');
                            opt.value = hasil[key].SAP_COST_CENTER_ID;
                            opt.innerHTML = hasil[key].SAP_COST_CENTER_ID+' - '+hasil[key].SAP_COST_CENTER_DESCRIPTION;
                            select.appendChild(opt);
                        });
                    }
                }
                $("#spinner_cost_center").hide();
                $("#cost_center").removeAttr('disabled');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                Swal.fire('Failed','Something went wrong when trying to load Cost Center data', 'error');
            }

        })

    }

    function qtyInputAdditionalMaterial(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        setTimeout(function(){
            checkLastPriceAdditionalMaterial(elem);
        }, 600);
    }

    function checkLastPriceAdditionalMaterial(target){
        // console.log(target, target.value, typeof target.value);
        try {
            $('.btn-submit').prop('disabled', true);
            $('.btn-add').prop('disabled', true);
            $('.btn-del').prop('disabled', true);
            $('[name="quantity[]"]').not(target).prop('disabled', true);
            $(target).parents('tr').find('[name="acctasscat[]"]').prop('disabled', true);
            $(target).parents('tr').find('[name="materials[]"]').prop('disabled', true);

            var qty = target.value;
            if(qty && qty !== '0') {
                var material = $(target).parents('tr').find('[name="materials[]"]').val(),
                unit = $(target).parents('tr').find('[name="unit[]"]').val();
                try {
                    var plant = $('#plant3').val().split('^-^', 2)[0];
                } catch(error){
                    var plant = null;
                }

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
                        try {
                            var rowId = target.id
                            var idRow = rowId.substring(8, 18);
                        } catch(error){
                            console.log(error);
                            var idRow = '-';
                        }

                        if(resp.hasOwnProperty('last_price')){
                            try {
                                var last_purchase = resp.last_price;
                                document.getElementById("cAmitBapx"+idRow).value = last_purchase;
                            } catch(error){}
                        } else {
                            try {
                                document.getElementById("cAmitBapx"+idRow).value = '0.00';
                            } catch(error){}
                        }
                   }, 
                   error : function(xhr){
                        try {
                            var rowId = target.id
                            var idRow = rowId.substring(8, 18);
                        } catch(error){
                            console.log(error);
                            var idRow = '-';
                        }

                        try {
                            document.getElementById("cAmitBapx"+idRow).value = '0.00';
                        } catch(error){}
                        console.log("Error in check last price");
                   },
                   complete : function(){
                    setTimeout(function(){
                     $('.btn-submit').prop('disabled', false);
                     $('.btn-add').prop('disabled', false);
                     $('.btn-del').prop('disabled', false);
                     $('[name="quantity[]').not(target).prop('disabled', false);
                     $(target).parents('tr').find('[name="acctasscat[]"]').prop('disabled', false);
                     $(target).parents('tr').find('[name="materials[]"]').prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                     calculateGrandTotalNew();
                    }, 1000)
                   }
                });
            } else {
                try {
                    var rowId = target.id
                    var idRow = rowId.substring(8, 18);
                } catch(error){
                    console.log(error);
                    var idRow = '-';
                }
                try {
                    document.getElementById("cAmitBapx"+idRow).value = '0.00';
                } catch(error){}

                $('.btn-submit').prop('disabled', false);
                $('.btn-add').prop('disabled', false);
                $('.btn-del').prop('disabled', false);
                $(target).parents('tr').find('[name="acctasscat[]"]').prop('disabled', false);
                $(target).parents('tr').find('[name="materials[]"]').prop('disabled', false);
                $('[name="quantity[]"]').not(target).prop('disabled', false);
            }
        } catch(error){
            console.log("Error in last price", error);
            $('.btn-submit').prop('disabled', false);
            $('.btn-add').prop('disabled', false);
            $('.btn-del').prop('disabled', false);
            $(target).parents('tr').find('[name="acctasscat[]"]').prop('disabled', false);
            $(target).parents('tr').find('[name="materials[]"]').prop('disabled', false);
            $('[name="quantity[]"]').not(target).prop('disabled', false);
        }
    }


    /*****************************************************************************/
    /*****************************************************************************/
    /* END FUNCTION DARI INTRANET BIZNET*/
    /*****************************************************************************/
    /*****************************************************************************/

    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    $(document).ready( function () {
        $(".select2").select2({});
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
                    return data.id;
                }
            },
            dropdownParent: $('#reqForm'),
            allowClear: false,
            placeholder: "Search Material ...",
            ajax: {
               url: "/finance/purchase-requisition-marketlist/request",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                try {
                    var plant = $('#plant3').val().split('^-^', 2)[0];
                } catch(error){
                    var plant = null
                }
                var qty = $(this[0].closest('tr')).find('[name="quantity[]"]').val() || 0;
                var rowIndex = $(this).parents('tr')[0].rowIndex;
                var tableRowCount = $(this).parents('tbody').find('tr').length;
                var tableValueCount = $('[name="materials[]"]').filter(function(index, item){
                    return item.value.length > 0 ;
                });
                if(tableValueCount.length == 1 && tableRowCount == 1 || tableValueCount.length == 1 && tableRowCount > 1)
                    global_purchasing_group = null;

                if(rowIndex == 1 && !global_purchasing_group){
                    var searchData = {'searchPurchasingGroup': false};
                } else if(rowIndex == 1 && global_purchasing_group || rowIndex != 1 && global_purchasing_group){
                    var searchData = {'searchPurchasingGroup': true, 'purGroup': global_purchasing_group}
                }

                return {
                  searchTerm: params.term, // search term
                  type: 'material',
                  plant: plant,
                  ...searchData,
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
                 try {
                    var plant = $('#plant3').val().split('^-^', 2)[0];
                 } catch(error){
                    var plant = null
                 }

                 if(Object.keys(params.data).includes('searchPurchasingGroup') && params.data.searchPurchasingGroup === true && !global_purchasing_group){
                    Swal.fire('Empty Purchasing Group', 'Purchasing group is not available on the first selection, make sure all materials are in the same purchasing group', 'error');
                    return false;
                 }

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
                var rowId = e.target.id
                var idRow = rowId.substring(9, 18);
            } catch(error){
                console.log(error);
                var idRow = '-';
            }

            try {
                var materialDesc = e.params.data.text,
                materialUnit = e.params.data.unit,
                materialFipex = e.params.data.fipex,
                cmmtItemText = e.params.data.cmmtItemText,
                cAmitBapi = e.params.data.cmmtBapi,
                fundsCurr = e.params.data.fund_curr,
                amountTxt = e.params.data.amount_txt,
                last_purchase = e.params.data.last_price,
                amountYearTxt = e.params.data.amount_year_txt,
                purchasing_group = e.params.data.PUR_GROUP == undefined ? '' : e.params.data.PUR_GROUP;
                global_purchasing_group = purchasing_group;

                document.getElementById("materialDesc"+idRow).value = materialDesc;
                document.getElementById("materialPurchGroup"+idRow).value = purchasing_group;
                document.getElementById("unit"+idRow).value = materialUnit;
                document.getElementById("cmmtItem"+idRow).value = materialFipex;
                document.getElementById("cmmtItemText"+idRow).value = cmmtItemText;
                document.getElementById("cAmitBapx"+idRow).value = last_purchase;
                document.getElementById("cAmitBapi"+idRow).value = cAmitBapi;
                document.getElementById("fundsCurr"+idRow).value = fundsCurr;
                document.getElementById("amountTxt"+idRow).value = amountTxt;
                document.getElementById("amountYearTxt"+idRow).value = amountYearTxt;
                document.getElementById("costCenter"+idRow).value = document.getElementById('cost_center').value;

                calculateGrandTotalNew();
            } catch(error){
                Swal.fire('Element Not Found', 'Some elements is not found while trying to assign value, please check the data and try again', 'error');
            }

        }).on('select2:unselecting', function(e){
            $(this).data('state', 'unselected');
        });

        $(".select-decorated-asset").select2({
            templateSelection: function(data) {
                if(!data.id) {
                    return data.text;
                } else {
                    return data.id;
                }
            },
            dropdownParent: $('#reqForm'),
            allowClear: false,
            placeholder: "Search Asset ...",
            ajax: {
               url: "/finance/purchase-requisition/search-asset",
               type: "GET",
               dataType: 'json',
               delay: 600,
               data: function (params) {
                try {
                    var company = document.getElementById('plant3').value.substr(0,3);
                } catch(error){
                    var company = null
                }
                return {
                  assetNo: params.term, // search term
                  company: company,
                };
               },
               processResults: function (response) {
                 return {
                    results: $.map(response.data, function (item) {
                        return {
                            id: item.ANLN1,
                            text: `${item.TXT50} - ${item.TXK50}`,
                            amount_txt: item.AMOUNT_TXT,
                            amount_txt_year: item.AMOUNT_TXT_YEAR,
                            fipex: item.FIPEX,
                            fund_curr: item.FUNDS_CURR,
                        }
                    })
                 };
               },
               cache: false,
               transport: function (params, success, failure) {
                 // var plant = $('#Requestor_Plant_ID').val();
                 try {
                    var company = document.getElementById('plant3').value.substr(0,3);
                 } catch(error){
                    var company = null
                 }

                 if(company){
                     var $request = $.ajax(params);
                     $request.then(success);
                     $request.fail(failure);
                     return $request;
                 } else {
                    Swal.fire('Plant Selection', 'Plant is not available, please make sure to select or choose plant before finding assets', 'warning');
                    return false;
                 }
               }
            },
            minimumInputLength: 3
         }).on('select2:select', function(e){
            try {
                var rowId = e.target.id
                var idRow = rowId.substring(9, 18);
            } catch(error){
                console.log(error);
                var idRow = '-';
            }

            try {
                var assetNumber = e.params.data.id,
                assetName = e.params.data.text;
                // document.getElementById("assetNo"+idRow).value = assetNumber;
                document.getElementById("assetDesc"+idRow).value = assetName;
            } catch(error){
                Swal.fire('Element Not Found', 'Some elements is not found while trying to assign value, please check the data and try again', 'error');
            }

        }).on('select2:unselecting', function(e){
            $(this).data('state', 'unselected');
        });

        $('#delivDate').datepicker({
            autoclose: true,
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


        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "pageLength": 100,
            // "lengthChange": true,
            "responsive": true,
            // "dom": '<"dt-buttons"Bfl>rtip',
            "dom" : '<"clearfix"lf> <"d-block" <"table-wrapper table-container-h"rt>>ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/purchase-requisition/request/getData",
                "dataSrc": function(json){
                    return json.data;
                },
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
            "scrollX": false,
            "columns": [
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        return '<a style="font-weight:bold;" href="{{url("finance/purchase-requisition/detail/")}}/'+id+'" >'+id+'</a>';
                    }
                },
                {
                    "data" : "UID",
                    "render": function (id, type, full, meta){
                        return '<a href="#" class="btn btn-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile" ><i class="fa fa-eye"></i></a>';
                    }
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id, type, full,meta){
                        try {
                            // Check approve commited in SAP
                            if(full.STATUS_APPROVAL != 'FINISHED' && !full.LAST_APPROVAL_NAME){
                                if(full.hasOwnProperty('ALREADY_APPROVE_SAP')){
                                    if(full.hasOwnProperty('DATA_RELEASE_MAPPING') && full.DATA_RELEASE_MAPPING.length == full.ALREADY_APPROVE_SAP.length){
                                        return '<a href="javascript:void(0);" style="color:green;font-weight:bold;">FINISHED</a>';
                                    }
                                }
                            }
                        } catch(error){
                            console.log(error);
                        }

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
                  "render": function(id, type, full, meta){
                        try {
                            // Check approve commited in SAP
                            if(full.STATUS_APPROVAL != 'FINISHED' && !full.LAST_APPROVAL_NAME){
                                if(full.hasOwnProperty('ALREADY_APPROVE_SAP')){
                                    if(full.hasOwnProperty('DATA_RELEASE_MAPPING') && full.DATA_RELEASE_MAPPING.length == full.ALREADY_APPROVE_SAP.length){
                                        return typeof full.DATA_RELEASE_MAPPING[0].MAIN_EMPLOYEE == 'undefined' ? id : full.DATA_RELEASE_MAPPING[0].MAIN_EMPLOYEE;
                                    }
                                } else {
                                    return id;
                                }
                            }
                        } catch(error){}

                        return id;
                   } 

                },
                // { "data": "REASON" },
                { "data": "REQ_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    }
                },
                // { "data": "TRACKING_NO" },
                // { "data": "TRACKING_DESC"},
                { "data": "PURPOSE" },
                { "data": "DOC_TYPE" },
                // { "data": 'PO_NUMBER' },
                {
                    "data" : "PO_NUMBER",
                    "className": 'truncated-wrapper',
                    "render": function (id, type, full, meta){
                        if(id){
                            try {
                                var po_number = id.split(",");
                                po_number = [...new Set(po_number)];
                                po_number = po_number.map(x => { 
                                    if(x) 
                                        return `<p><a href="#" style="font-weight: bold" class="text-primary" onclick="getDetailPO('${x.trim()}')" data-toggle="modal" data-target="#modalPO" >${x.trim()}</a></p>`; 
                                    else 
                                        return ""; 
                                }).join('');
                            } catch(error) {
                                var po_number = '-'; 
                            }
                            return po_number;
                        }
                        else {
                            return '-';
                        }
                    }
                }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 0, "desc" ]],
            "columnDefs": [{
                  targets: 5,
                  // render: $.fn.dataTable.render.ellipsis( 40 ),
                  className: "text-left purpose",
                  width: "25%"
                },
                {
                  targets: 7,
                  // render: $.fn.dataTable.render.ellipsis( 40 ),
                  width: "13%"
                }
            ],
            initComplete: function(setting, json){
                document.querySelectorAll(".truncated").forEach(function(current) {
                    current.addEventListener("click", function(e) {
                        if(current.classList.contains('truncated'))
                            current.classList.remove("truncated");
                        else
                            current.classList.add("truncated");
                    }, false);
                });
            },
            "createdRow": function( row, data, dataIndex ) {
                try {
                    var po_number = data.PO_NUMBER.split(",");
                    po_number = [...new Set(po_number)];
                    if(po_number.length > 1){
                        $(row).find('.truncated-wrapper').addClass('truncated');
                    }
                } catch(error){}
            }
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };

        $("#formRequest").on("keypress", function (event) {
            if(this.checkValidity()){
                var keyPressed = event.keyCode || event.which;
                if (keyPressed === 13) {
                    event.preventDefault();
                    return false;
                }
            }
        });

        $(document).on('submit', "#formRequest", function(e) {
            e.preventDefault();

            var form_obj = this;
            try {
                $('.btn-submit', form_obj).prop('disabled', true);
            } catch(error){}

            // avoid to execute the actual submit of the form.
            document.getElementById('tableRow').value = document.getElementById('reqForm').rows.length;
            var form = this;
            var url_post=$("#formRequest").attr('data-url-post');
            var loader=$("#formRequest").attr('data-loader-file');
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
                                window.location.href="/finance/purchase-requisition/detail/"+data.insert_id;
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

                },
                complete: function(){
                    try {
                        $('.btn-submit', form_obj).prop('disabled', false);
                    } catch(error){}
                }
            });
            return false;
        });

    });
    function getFormDetail(id){
        var segment='request';
        $('#modalFile #bodyModalFile').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition/detail')}}", { id : id, segment : segment}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
        });

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
