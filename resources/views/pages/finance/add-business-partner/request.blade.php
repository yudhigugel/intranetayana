@extends('layouts.default')

@section('title', 'Request Business Partner')
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
      <li class="breadcrumb-item"><a href="#">Add Business Partner</a></li>
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
                            <a href="{{url('finance/add-business-partner/request')}}" class="btn btn-danger" id="resetList">Reset</a>
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
                            <th style="min-width:90px;">Vendor Name</th>
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
<div class="modal fade" id="modalRequest"  role="dialog" aria-labelledby="modalRequestLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalRequestLabel">Form - Add Business Partner</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="resetListDt()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodymodalRequest">
                <form method="POST" id="formRequest" enctype="multipart/form-data" data-url-post="{{url('finance/add-business-partner/request/save')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
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
                                <input type="text" value="{{$data['form_code']}}-(auto number)" class="form-control" readonly/>
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

                    @if ($data['is_cross_plant_user'])
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Request For Other Plant
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
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Data Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Company Code <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="company_code" id="company_code" required value="{{$data['company_code']}}" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Request Reason <span class="red">*</span></label>
                                <input type="text" class="form-control" name="request_reason" required placeholder="insert reason for adding new business partner.."/>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Vendor Number </label>
                                <input type="text" class="form-control" placeholder="" name="vendor_number" value="" readonly/>
                                <small class="form-text text-muted">Filled by Master Data IT</small>
                            </div>
                        </div> --}}
                        <input type="hidden" name="vendor_number" value=" ">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Business Partner Number </label>
                                <input type="text" class="form-control" placeholder="" name="bp_number" value="" readonly/>
                                <small class="form-text text-muted">Filled by Master Data IT</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <input type="hidden" name="new_type_vendor" value="Y">
                                <label>Vendor Type <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="Vendor" checked>Vendor
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="Customer">Customer
                                    <i class="input-helper"></i></label>
                                    <div class="customer_type_wrapper ml-4 py-2 mt-2" style="display: none;">
                                        <label class="mb-1">Account Assignment Group <span class="red">*</span></label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input customer_type" name="customer_type" value="A1">A1 (REV FINAL AYANA)
                                            <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                            <input type="radio" class="form-check-input customer_type" name="customer_type" value="A2">A2 (REV NON-FINAL AYANA)
                                            <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="Vendor_Customer">Vendor & Customer
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> General Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Vendor Name <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="vendor_name" required  placeholder="insert vendor name.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Vendor Address <span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_address" required placeholder="insert vendor full address.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Building <span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_building" required placeholder="insert vendor building.."/>
                            </div>
                            <div class="col-md-6">
                                <label>Province<span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_province" required placeholder="insert vendor province.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>City<span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_city" required placeholder="insert vendor city"/>
                            </div>
                            <div class="col-md-6">
                                <label>District<span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_district" required placeholder="insert vendor district.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label>Subdistrict<span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_subdistrict" required placeholder="insert vendor subdistrict.."/>
                            </div> --}}
                            <input type="hidden" name="vendor_subdistrict" value=" ">
                            <div class="col-md-6">
                                <label>Postal Code<span class="red">*</span></label>
                                <input type="text" class="form-control" name="vendor_postcode" required placeholder="insert postal code.. "/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Phone Number <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="vendor_phone" required  placeholder="insert vendor phone.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Company Email <span class="red">*</span></label>
                                <input type="email"  class="form-control" name="company_email" required  placeholder="insert vendor company email.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label style="float:none;display:block;clear:both;">PIC Name<span class="red">*</span></label>
                                <div class="col-md-3 float-left">
                                    <select name="pic_title" class="form-control" required>
                                        <option value="">-- Select Title --</option>
                                        <option value="Mr">Mr.</option>
                                        <option value="Mrs">Mrs.</option>
                                        <option value="Ms">Ms.</option>
                                    </select>
                                </div>
                                <div class="col-md-9 float-left">
                                    <input type="text"  class="form-control" name="pic_name" required  placeholder="insert vendor PIC full name.."/>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>PIC Phone Number <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="pic_phone" required  placeholder="insert vendor PIC phone.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>PIC Email <span class="red">*</span></label>
                                <input type="email"  class="form-control" name="pic_email" required  placeholder="insert vendor PIC email.."/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Tax Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>NPWP <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input npwp_radio" name="npwp" value="Yes">Yes
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input npwp_radio" name="npwp" value="No" checked>No
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>PKP <span class="red">*</span></label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="pkp" value="Yes">Yes
                                    <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="pkp" value="No" checked>No
                                    <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="wrap_npwp_number" style="display: none;">
                            <div class="col-md-12">
                                <label>NPWP Number <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="npwp_number" id="npwp_number" placeholder="insert vendor NPWP number.."/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Bank Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Bank Name <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="bank_name" required  placeholder="insert vendor bank name.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Bank Account Number <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="bank_account_number" required  placeholder="insert vendor bank account number.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Swift Code Number <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="swift_code" required placeholder="insert vendor bank swift code number.."/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Account Holder Name <span class="red">*</span></label>
                                <input type="text"  class="form-control" name="account_holder_name" required placeholder="insert vendor account holder name.."/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Attachment </h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label><i>Surat Pernyataan Pakta Integritas</i></label>
                                <input type="file"  class="form-control" name="file_surat_pakta_integritas">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label><i>Copy of Account Number</i></label>
                                <input type="file"  class="form-control" name="file_account_number">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label><i>Copy Akta Pendirian Perusahaan & Akta Perubahan</i></label>
                                <input type="file"  class="form-control" name="file_akta_pendirian_perusahaan">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label><i>Copy NIB & Izin Usaha (OSS)</i></label>
                                <input type="file"  class="form-control" name="file_nib">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Copy of NPWP</label>
                                <input type="file"  class="form-control" name="file_nib">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Other</label>
                                <input type="file"  class="form-control" name="file_other">
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
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
    var objFile = {};var objJsonDetail = {}; var objRow = {};
    var initial_company_code=$("#company_code").val();


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


        $('input[type=radio][name=npwp]').change(function() {
            if (this.value == 'Yes') {
                $("#npwp_number").attr('required','');
                $("#wrap_npwp_number").show();
            }
            else if (this.value == 'No') {
                $("#npwp_number").removeAttr('required');
                $("#npwp_number").val('');
                $("#wrap_npwp_number").hide();
            }
        });


        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            // "dom": '<"dt-buttons"Bfli>rtp',
            "dom": 'l<"button-export-wrapper"B>frtip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/add-business-partner/request/getData",
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
                { "data": "VENDOR_NAME", className: 'text-left'
                },
                { "data": "STATUS_APPROVAL", className: 'text-left',
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

        $('#modalRequest').on('hide.bs.modal', function(){
            $('.customer_type_wrapper').hide();
            $(".vendor_type").each(function(i, elem){
                if(this.value == 'Vendor'){
                    this.checked = true;
                }
            });
        })
    });

    function getFormDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');
        var url_asset="{{url('upload/material')}}";

        $.get("{{url('finance/add-business-partner/modal-detail')}}", { id : id}, function( data ) {
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
            url: '/finance/add-business-partner/getHistoryApproval',
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

        var cekForm = $(this)[0].checkValidity(); //cek apakah semua field yang required sudah diisi
        if(!cekForm){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please complete all required selections and options',
            });
        }

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


    var vendor_type = $('input:radio[name=vendor_type]');
    // Old Function
    // vendor_type.change(
    // function(){
    //     if ($(this).is(':checked') && $(this).val() == 'Other') {
    //         $("#vendor_type_other").show();
    //         $("#vendor_type_other").attr('required','required');
    //     }else{
    //         $("#vendor_type_other").hide();
    //         $("#vendor_type_other").removeAttr('required');
    //     }

    // });
    vendor_type.change(
    function(){
        if ($(this).is(':checked') && $(this).val().toString().toLowerCase() == 'customer') {
            $(".customer_type_wrapper").show()
            $(".customer_type").eq(0).attr('required','required');
        }else{
            $(".customer_type_wrapper").hide();
            try {
                $(".customer_type").each(function(i, elem){
                    this.checked = false
                    $(this).removeAttr('required');
                })
            } catch(error){}
            $(".customer_type").eq(0).removeAttr('required');
        }

    });

    $(".select2").select2({
    });

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

            $("#company_code").val(initial_company_code);
        }

    });


    $("#custom_plant").change(function(){
        var plant=$(this).val();
        if(plant){
            $.ajax({
                type: "POST",
                url: "{{url('finance/add-business-partner/ajax/getCostCenterCustom')}}",
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
                    var company_code = plant.substring(0,3);
                    $("#company_code").val(company_code);
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
                url: "{{url('finance/add-business-partner/ajax/getMidjobCustom')}}",
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

</script>
@endsection
