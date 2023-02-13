@extends('layouts.default')

@section('title', 'Detail Purchase Requisition')
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


.action-ul{
    display:table;
    margin:0 auto;
}

.action-ul li{
    display: inline;
    list-style-type: none;
    margin:0px 10px;
}
</style>
@endsection

@section('content')

<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Finance</a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
    <li class="breadcrumb-item"><a href="#">Detail</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>{{ $data['pr_number'] }}</span></li></ol>
</nav>
<div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Purchase Requisition Detail {{ $data['pr_number'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if(isset($data['data_header']) && !empty($data['data_header']))
                @if($data['data_header']->LAST_APPROVAL_STATUS=='CANCELED' || $data['data_header']->LAST_APPROVAL_STATUS=='REJECTED')
                <div class="form-group">
                    <a href="javascript:void(0);" class="btn btn-danger" style="display: table;margin:0 auto;"><i class="fa fa-warning"></i> This PR is already {{ $data['data_header']->LAST_APPROVAL_STATUS }}</a>
                    <br>
                    <a href="javascript:void(0);" class="btn btn-warning" style="display: table;margin:0 auto;"><i class="fa fa-pencil"></i> Reason : {{ (!empty($data['data_header']->LAST_APPROVAL_REASON))? $data['data_header']->LAST_APPROVAL_REASON : "-" }}</a>
                </div>
                @elseif ($data['data_header']->LAST_APPROVAL_STATUS=='APPROVED' || $data['data_header']->LAST_APPROVAL_STATUS=='REQUESTED' || empty($data['data_header']->LAST_APPROVAL_STATUS))
                    @if(isset($release_strategy) && count($release_strategy))
                        @foreach ($release_strategy as $release)
                            @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve))
                                <a href="javascript:void(0);" class="btn btn-success text-white" style="display: table;margin:0 auto;"><i class="fa fa-check"></i> This PR is APPROVED in SAP </a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-primary" style="display: table;margin:0 auto;"><i class="fa fa-hourglass"></i> This PR is awaiting for approval </a>
                            @endif
                        @endforeach
                    @else
                    <a href="javascript:void(0);" class="btn btn-primary" style="display: table;margin:0 auto;"><i class="fa fa-hourglass"></i> This PR is awaiting for approval </a>
                    @endif
                @elseif ($data['data_header']->LAST_APPROVAL_STATUS=='FINISHED')
                <a href="javascript:void(0);" class="btn btn-success text-white" style="display: table;margin:0 auto;"><i class="fa fa-check"></i> This PR is APPROVED </a>
                @endif

                @if(!empty($data['nomor_po']))
                <div class="form-group">
                    {{--<a href="javascript:void(0);" data-toggle="modal" data-target="#modalPO" onclick="getDetailPO('{{$data['nomor_po']}}');" class="btn btn-warning" style="display: table;margin:0 auto;margin-top:20px;"><i class="fa fa-warning"></i> This PR has converted into Purchase Order with number <b>{{$data['nomor_po']}}</b></a>--}}
                    <a target="_blank" href="{{ url('finance/purchase-order/detail/'.$data['nomor_po'])}}" class="btn btn-warning" style="display: table;margin:0 auto;margin-top:20px;"><i class="fa fa-warning"></i> This PR has converted into Purchase Order</a>
                    <div class="text-center mt-2">
                        <small>* PO Number can be seen on item details</small>
                    </div>
                </div>
                @endif
            @endif

                <form id="modalDetailAjax">
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                @if (isset($data['data_form']['intranet'][0]))
                                    <input type="text" value="{{ date('d F Y - H:i',strtotime($data['data_form']['intranet'][0]->INSERT_DATE)) }}" class="form-control" readonly/>
                                @else
                                    <input type="text" value="{{ @date('d F Y',strtotime($data['data_form']['GI_ITEMS'][0]['PREQ_DATE'])) }}" class="form-control" readonly/>
                                @endif


                            </div>
                            <div class="col-md-6">
                                <label>Form Number</label>
                                    <input type="text" value="{{$data['pr_number']}}" class="form-control" readonly/>
                                    <input type="hidden" id="uid" value="{{$data['pr_number']}}">
                                    <input type="hidden" id="current_user_release_code" value="{{$data['current_user_release_code']}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Doc Type <span class="red">*</span></label>
                                {{-- <input type="text" value="{{@$data['data_form']['GI_ITEMS'][0]['BATXT']}}" class="form-control" readonly/> --}}
                                <input type="text" value="{{ isset($data['data_header']->PRDOCTYPE_DESCRIPTION)? $data['data_header']->PRDOCTYPE_DESCRIPTION : @$data['data_form']['GI_ITEMS'][0]['BATXT']}}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Cost Center <span class="red">*</span></label>
                                {{-- <input type="text" value="{{@$data['data_form']['GI_ITEMS'][0]['COST_CTR']}}" class="form-control" readonly/> --}}
                                <input type="text" value="{{ isset($data['data_json']->cost_center)? $data['data_json']->cost_center : @$data['data_form']['GI_ITEMS'][0]['TRACKINGNO']}}" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Purpose / Notes</label>
                                @if (isset($data['data_json']->purpose))
                                    <input type="text" class="form-control" name="purpose" placeholder="insert your purpose / notes on requesting purchase requisition" value="{{$data['data_json']->purpose}}" readonly/>
                                @else
                                    <input type="text" class="form-control" name="purpose" placeholder="insert your purpose / notes on requesting purchase requisition" value="Purpose Can only be viewed in SAP" readonly/>
                                @endif

                            </div>
                            <div class="col-md-6">
                                <label>Ship to Plant & Cost Center</label>
                                <input type="text" value="{{ isset($data['data_json']->plant) ? $data['data_json']->plant : @$data['data_form']['GI_ITEMS'][0]['PLANT'] }} {{ isset($data['data_json']->cost_center) ? ' - '. $data['data_json']->cost_center : '' }}" class="form-control" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Requestor Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Name</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Name) ? $data['data_json']->Requestor_Name : @$data['data_requestor_sap']->EMPLOYEE_NAME }}" name="Requestor_Name" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Plant Name</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Company) ? $data['data_json']->Requestor_Company :  @$data['data_requestor_sap']->SAP_PLANT_NAME }}" name="Requestor_Company" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Employee ID</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Employee_ID) ? $data['data_json']->Requestor_Employee_ID :  @$data['data_requestor_sap']->EMPLOYEE_ID}}" name="Requestor_Employee_ID" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Territory</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Territory) ? $data['data_json']->Requestor_Territory : @$data['data_requestor_sap']->TERRITORY_NAME}}" name="Requestor_Territory" class="form-control" readonly />
                                <input type="hidden" name="Requestor_Territory_ID" value="{{ isset($data['data_json']->Requestor_Territory_ID) ? $data['data_json']->Requestor_Territory_ID : @$data['data_requestor_sap']->TERRITORY_ID}}" id="Requestor_Territory_ID">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Cost Center ID</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Cost_Center_ID) ? $data['data_json']->Requestor_Cost_Center_ID : @$data['data_requestor_sap']->SAP_COST_CENTER_ID}}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Department</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Department)? $data['data_json']->Requestor_Department :  @$data['data_requestor_sap']->DEPARTMENT_NAME}}" name="Requestor_Department" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Requestor Plant ID</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Plant_ID)? $data['data_json']->Requestor_Plant_ID : @$data['data_requestor_sap']->SAP_PLANT_ID }}" name="Requestor_Plant_ID" class="form-control" readonly />
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Division</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Division)? $data['data_json']->Requestor_Division : @$data['data_requestor_sap']->DIVISION_NAME}}" name="Requestor_Division" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="row mb-3" hidden>
                            <div class="col-md-6">
                                <label>PPN</label>
                                <input type="text" value="{{ isset($data['data_json']->ppn)? $data['data_json']->ppn : 'PLEASE SEE IN SAP' }}" name="ppn" class="form-control" readonly />
                            </div>
                            <div class="col-md-6">
                                <label>Requestor Job Position</label>
                                <input type="text" value="{{ isset($data['data_json']->Requestor_Job_Title)? $data['data_json']->Requestor_Job_Title : @$data['data_requestor_sap']->JOB_TITLE }}" name="Requestor_Job_Title" class="form-control" readonly />
                            </div>
                        </div>
                    </div>
                    @if (isset($data['data_json']->vendor_name))

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Vendor Information  </h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Vendor</label>
                                <div class="input-group">
                                    <input type="text" id="vendor_search" class="form-control" style="text-transform:uppercase" value="{{$data['data_json']->vendor_name}}"  readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile </label>
                                <input type="text" class="form-control" id="vendor_mobile" value="{{$data['data_json']->vendor_mobile}}"  readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="" id="vendor_address"  value="{{$data['data_json']->vendor_address}}"readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Phone</label>
                                <input type="text" class="form-control" placeholder="" id="vendor_phone" value="{{$data['data_json']->vendor_phone}}" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" placeholder="" id="vendor_cp" value="{{$data['data_json']->vendor_cp}}" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Fax</label>
                                <input type="text" class="form-control" placeholder="" id="vendor_fax" value="{{$data['data_json']->vendor_fax}}" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Currency <span class="red">*</span></label>
                                <input type="text" class="form-control" placeholder="" id="currency" value="{{$data['data_json']->currency}}" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" id="vendor_email" value="{{$data['data_json']->vendor_email}}" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Reason <span class="red">*</span></label>
                                <input type="text" class="form-control" placeholder="Insert your reason why choosing this vendor" name="reason" value="{{$data['data_json']->reason}}" readonly />

                            </div>
                        </div>
                    </div>

                    @endif
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="overflow: scroll;">
                            <table class="table table-striped table-bordered table-hover smallfont" id="reqForm" style="white-space:nowrap;min-width: 2000px">
                                <thead>
                                    <tr>
                                        <th >Item</th>
                                        <th class="thead-apri">PO Number</th>
                                        <th class="thead-apri">Material</th>
                                        <th class="thead-apri">Material Desc.</th>
                                        <th class="thead-apri">Material Purch. Group</th>
                                        <th class="thead-apri">SLOC</th>
                                        <th class="thead-apri">Qty</th>
                                        <th class="thead-apri">Unit</th>
                                        <th class="thead-apri">Delivery Date</th>
                                        <th class="thead-apri">Last Purchase Price</th>
                                        {{-- <th class="thead-apri">Amount</th> --}}
                                        <th class="thead-apri">Cost Center</th>
                                        <th class="thead-apri" >Asset Number</th>
                                        {{--<th class="thead-apri">Order Number</th>--}}
                                        <th class="thead-apri">Tracking Number</th>
                                        {{--<th class="thead-apri">Commitment Item</th>--}}
                                        <th class="thead-apri">Purpose</th>
                                        <th class="thead-apri" style="white-space:inherit">Additional Info</th>
                                        {{--
                                        <th class="thead-apri">Funds Center</th>
                                        <th class="thead-apri">Funds Curr</th>
                                        <th class="thead-apri">Remain Budget  Month to Date</th>
                                        <th class="thead-apri">Remain Year to Date</th>
                                        --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Filter jika detail SAP Kosong, biasanya data nya kosong jika PR nya sudah cancel, jadi mengambil di DB Intranet --}}
                                    @if ($data['source_item_pr']=="database")
                                        <?php for($i=0;$i<count($data['data_json']->preqItem);$i++){ ?>
                                            <tr id="rowToClone">
                                                <td>
                                                    <span>{{ isset($data['data_json']->preqItem[$i]) ? $data['data_json']->preqItem[$i] : '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>-</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->materials[$i]) ? $data['data_json']->materials[$i] : '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_json']->materialDesc[$i]) ? $data['data_json']->materialDesc[$i] : '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_json']->materialPurchGroup[$i]) ? $data['data_json']->materialPurchGroup[$i] : '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_json']->item_sloc[$i]) ? $data['data_json']->item_sloc[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->quantity[$i]) ? $data['data_json']->quantity[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->unit[$i]) ? $data['data_json']->unit[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->delivDate[$i]) ? $data['data_json']->delivDate[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->cAmitBapx[$i]}}</span>
                                                </td>
                                                {{-- <td>
                                                    <span>{{@$data['data_json']->totalAmounx[$i]}}</span>
                                                </td> --}}
                                                <td>
                                                    <span>{{ isset($data['data_json']->costCenter[$i]) ? $data['data_json']->costCenter[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->assetNo[$i]) ? $data['data_json']->assetNo[$i] : '-' }}</span>
                                                </td>
                                                {{--<td>
                                                    <span>{{ isset($data['data_json']->orderNo[$i]) ? $data['data_json']->orderNo[$i] : '-' }}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{ isset($data['data_json']->trackingNo[$i]) ? $data['data_json']->trackingNo[$i] : '-' }}</span>
                                                </td>
                                                {{--<td>
                                                    <span>{{ isset($data['data_json']->cmmtItem[$i]) ? $data['data_json']->cmmtItem[$i] : '-' }}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{ isset($data['data_json']->materialPurpose[$i]) ? $data['data_json']->materialPurpose[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->additionalInfo[$i]) ? $data['data_json']->additionalInfo[$i] : '-' }}</span>
                                                </td>
                                                {{--
                                                <td>
                                                    <span>{{ isset($data['data_json']->fundsCtr[$i]) ? $data['data_json']->fundsCtr[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->fundsCurr[$i]) ? $data['data_json']->fundsCurr[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->amountTxt[$i]) ? $data['data_json']->amountTxt[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['data_json']->amountYearTxt[$i]) ? $data['data_json']->amountYearTxt[$i] : '-' }}</span>
                                                </td>
                                                --}}
                                            </tr>
                                        <?php } ?>
                                    @elseif (isset($data['source_item_pr']) && $data['source_item_pr']=="rfc" && isset($data['data_form']['GI_ITEMS'][0]))
                                        <?php for($i=0;$i<count($data['data_form']['GI_ITEMS']);$i++){ ?>
                                            <tr id="rowToClone">
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['PREQ_ITEM'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_form']['GI_ITEMS'][$i]['PO']) ? $data['data_form']['GI_ITEMS'][$i]['PO'] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['MATERIAL'] }}</span>
                                                </td>
                                                <td class="text-left">
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['MAKTX'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['PUR_GROUP'] }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ $data['data_form']['GI_ITEMS'][$i]['STORE_LOC'].' '.$data['data_form']['GI_ITEMS'][$i]['STORE_LOC_DESC']  }} </span>
                                                </td>
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['QUANTITY_TXT'] }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['UNIT2'] }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['DELIV_DATE'] }}</span>
                                                </td>
                                                {{-- <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['C_AMT_BAPI_TXT'] }}</span>
                                                </td> --}}
                                                <td>
                                                    <span>{{ @number_format(str_replace('.','',trim($data['data_form']['GI_ITEMS'][$i]['AMOUNT_TOT_TXT'])), 2) }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['COST_CTR'] }}</span>
                                                </td>
                                                <td>
                                                    {{-- <span>{{ @$data['data_form']['GI_ITEMS'][$i]['ASSET_NO'] }}</span> --}}
                                                    <button type="button" class="btn btn-primary btn-xs" onclick="assetDetail('{{ @$data['data_form']['GI_ITEMS'][$i]['PREQ_ITEM'] }}', '{{ @$data['data_form']['GI_ITEMS'][$i]['MATERIAL'] }}', '{{@$data['data_asset'][$data['data_form']['GI_ITEMS'][$i]['PREQ_ITEM']]['ASSET']}}','{{@$data['data_asset'][$data['data_form']['GI_ITEMS'][$i]['PREQ_ITEM']]['ASSET_DESC']}}')">
                                                        <span class="fa fa-eye"></span>
                                                    </button>
                                                </td>
                                                {{--<td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['ORDER_NO'] }}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['TRACKINGNO'] }}</span>
                                                </td>
                                                {{--<td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['CMMT_ITEM'] }}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{@$data['data_json']->materialPurpose[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->additionalInfo[$i]}}</span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    @elseif (isset($data['source_item_pr']) && $data['source_item_pr']=="bapi" && isset($data['data_form_alt']['REQUISITION_ITEMS'][0]))
                                    <?php for($i=0;$i<count($data['data_form_alt']['REQUISITION_ITEMS']);$i++){ ?>
                                        <tr id="rowToClone">
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PREQ_ITEM'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span>{{ isset($data['data_form']['GI_ITEMS'][$i]['PO']) ? $data['data_form']['GI_ITEMS'][$i]['PO'] : '-' }}</span>
                                            </td>
                                            <td colspan="2">
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['MATERIAL'] }}</span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['MAKTX'] }}</span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PUR_GROUP'] }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $data['data_form_alt']['REQUISITION_ITEMS'][$i]['STORE_LOC']}} </span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['QUANTITY_TXT'] }}</span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['UNIT2'] }}</span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['DELIV_DATE'] }}</span>
                                            </td>
                                            {{-- <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['C_AMT_BAPI_TXT'] }}</span>
                                            </td> --}}
                                            <td>
                                                <span>{{ @number_format(str_replace('.','',trim($data['data_form_alt']['REQUISITION_ITEMS'][$i]['AMOUNT_TOT_TXT'])), 2) }}</span>
                                            </td>
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['COST_CTR'] }}</span>
                                            </td>
                                            <td colspan="2">
                                                {{-- <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['ASSET_NO'] }}</span> --}}
                                                <button type="button" class="btn btn-primary btn-xs" onclick="assetDetail('{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PREQ_ITEM'] }}', '{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['MATERIAL'] }}', '{{@$data['data_asset'][$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PREQ_ITEM']]['ASSET']}}','{{@$data['data_asset'][$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PREQ_ITEM']]['ASSET_DESC']}}')">
                                                    <span class="fa fa-eye"></span>
                                                </button>
                                            </td>
                                            {{--<td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['ORDER_NO'] }}</span>
                                            </td>--}}
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['TRACKINGNO'] }}</span>
                                            </td>
                                            {{--<td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['CMMT_ITEM'] }}</span>
                                            </td>--}}
                                            <td>
                                                <span>{{@$data['data_json']->materialPurpose[$i]}}</span>
                                            </td>
                                            <td>
                                                <span>{{@$data['data_json']->additionalInfo[$i]}}</span>
                                            </td>

                                        </tr>
                                    <?php } ?>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- Section Grand Total -->
                        {{-- <div class="row mb-3">
                            <div class="col-12 text-center">
                                <div class="form-group">
                                    <label class="control-label">Grand Total</label>
                                    <div class="col-12">
                                        @if ($data['source_item_pr']=="database")
                                            <h2><strong>{{ (isset($data['currency']) && !empty($data['currency'])) ?  $data['currency'] : '' }} {{ $data['data_json']->grandTotal }}</strong></h2>
                                        @elseif ($data['source_item_pr']=="rfc")
                                            <h2><strong>{{ (isset($data['currency']) && !empty($data['currency'])) ?  $data['currency'] : '' }} {{ @trim($data['data_form']['GV_TOTAL_TXT']) }}</strong></h2>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- End Section Grand Total -->
                    </div>
                </form>
                    <div class="col-md-12">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Attachment </h3>
                        <div id="current_attachment">
                            @if(isset($data['data_header']) && !empty($data['data_header']))
                                {{-- @if(!empty($data['data_header']->ATTACHMENT))
                                    <a target="_blank" href="{{asset('upload/purchase_requisition/'.$data['data_header']->ATTACHMENT)}}">{{$data['data_header']->ATTACHMENT}}</a>
                                @else
                                    <p> No attachment was added</p>
                                @endif --}}
                                @php
                                    $is_production = config('intranet.is_production');
                                    $at=$data['data_header']->ATTACHMENT;
                                    $append_link=($is_production)? "PR-" : "PR-DEV-";
                                    $default_link_attachment = 'https://sap-intranet.ayana.id/Attachment?folder='.$append_link.$data['pr_number'].'&year=2021';
                                @endphp
                                {{-- Validasi untuk attachment lama yang masih upload zip --}}
                                @if (empty($at))
                                    <iframe src="{{$default_link_attachment}}" frameborder="0" style="width:100%;height:400px;overflow:auto;"></iframe>
                                @elseif(strpos($at , '.zip') == false && strpos($at , '.rar') == false)
                                    <iframe src="{{$at}}" frameborder="0" style="width:100%;height:400px;overflow:auto;"></iframe>
                                @else
                                    <a target="_blank" href="{{asset('upload/purchase_requisition/'.$data['data_header']->ATTACHMENT)}}">{{$data['data_header']->ATTACHMENT}}</a>
                                @endif

                            @endif
                            <small><b><i>Note : please refresh the page or <u><a href="javascript:window.location.reload();">click here</a></u> if the attachment's interface is not showing.</i></b></small>
                        </div>
                        @if (isset($data['allow_modify_attachment']) && $data['allow_modify_attachment'] && isset($data['data_form']['intranet'][0]->STATUS_APPROVAL) && $data['data_form']['intranet'][0]->STATUS_APPROVAL !=='CANCELED')

                            {{-- <p id="show_attach" class="" style="cursor: pointer"> click here to replace attachment </p> --}}

                            <div class="col-md-6" id="attachment_form" style="border-top:1px #f5f5f5 solid;display: none;" >
                                <form id="attachForm" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition/update_attachment')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                    <input type="hidden" name="id" value="{{$data['data_form']['intranet'][0]->UID}}">
                                    <div class="form-group">
                                        <input type="file" name="file" id="attachForm_file" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-md text-white">Replace attachment</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12" style="margin-top:30px;">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Approval / Release Strategy  </h3>
                        <table class="table table-bordered smallfont" >
                            <thead>
                                <tr>
                                    <th>Release Code</th>
                                    {{--<th>Release Code Description</th>--}}
                                    <th>Assigned To Person</th>
                                    <th>Release Code Status</th>
                                    <th>Release Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($release_strategy) && count($release_strategy))
                                    @foreach ($release_strategy as $release)
                                    @if(isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE,$release_code_collected))
                                    <tr @if(isset($now_release_approve) && isset($release_indicator) && $release_indicator != 'R' && isset($release->RELEASE_CODE) && $now_release_approve == $release->RELEASE_CODE) class="bg-light" @endif>
                                        <td class="text-center">{{ isset($release->RELEASE_CODE) ? $release->RELEASE_CODE : '-' }}</td>
                                        {{--<td class="text-left">{{ isset($release->RELEASE_CODE_DESC) ? $release->RELEASE_CODE_DESC : '-' }}</td>--}}
                                        <td class="text-left">
                                            @php
                                                $employee = '';
                                                $current_employee = isset($current_login_employee) ? $current_login_employee : '';
                                                $main_employee = isset($release->EMPLOYEE_ID) ? $release->EMPLOYEE_ID : '-';
                                                $alt_employee = isset($release->ALT_EMPLOYEE_ID) ? $release->ALT_EMPLOYEE_ID : '-';
                                                if($current_employee == $main_employee)
                                                    $employee = isset($release->MAIN_EMPLOYEE) ? $release->MAIN_EMPLOYEE : '-';
                                                else if($current_employee == $alt_employee)
                                                    $employee = isset($release->ALT_EMPLOYEE) ? $release->ALT_EMPLOYEE : '-';
                                                else
                                                    $employee = isset($release->MAIN_EMPLOYEE) ? $release->MAIN_EMPLOYEE : '-';
                                            @endphp
                                            {{ $employee }}
                                        </td>
                                        <td>
                                            @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve))
                                                <span class="text-success mb-0"><b>APPROVED</b></span>
                                            @elseif(isset($approval_history[$release->RELEASE_CODE]) && $approval_history[$release->RELEASE_CODE] == "REJECTED")
                                            <span class="text-danger mb-0"><b>REJECTED</b></span>
                                            @else
                                                <span class="mb-0"><b>WAITING FOR APPROVAL</b></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($release_history) && isset($release_history[$release->EMPLOYEE_ID]))
                                                <span>{{ isset($release_history[$release->EMPLOYEE_ID]) ? date('d M Y, H:i:s', strtotime($release_history[$release->EMPLOYEE_ID])) : '-' }}</span>
                                            @elseif(isset($release_history) && isset($release_history[$release->ALT_EMPLOYEE_ID]))
                                                <span>{{ isset($release_history[$release->ALT_EMPLOYEE_ID]) ? date('d M Y, H:i:s', strtotime($release_history[$release->ALT_EMPLOYEE_ID])) : '-' }}</span>
                                            @else
                                                @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve) && isset($release_history[$release->EMPLOYEE_ID]) == false || isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve) && isset($release_history[$release->ALT_EMPLOYEE_ID]) == false)
                                                    <span>Approved in SAP</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No release strategy found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if (isset($data['allow_cancel']) && $data['allow_cancel'] && isset($data['data_header']) && !empty($data['data_header']) && $data['data_header']->LAST_APPROVAL_STATUS!=='CANCELED' && $data['data_header']->LAST_APPROVAL_STATUS!=='REJECTED' && $data['data_header']->LAST_APPROVAL_STATUS!=='APPROVED'  && $data['data_header']->LAST_APPROVAL_STATUS!=='FINISHED')
                        @if(isset($release_strategy) && count($release_strategy))
                            @foreach ($release_strategy as $release)
                                @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && !in_array($release->RELEASE_CODE, $prior_release_approve))
                                    <div class="col-md-12" style="margin-top:5%;">
                                        <a href="javascript:void(0);" id="cancelPR" class="btn btn-danger" data-url-post="{{url('finance/purchase-requisition/reject')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}" style="display: table;margin:0 auto;"><i class="fa fa-trash"></i> Cancel PR </a>
                                    </div>
                                @endif
                            @endforeach
                        @else
                        <div class="col-md-12" style="margin-top:5%;">
                            <a href="javascript:void(0);" id="cancelPR" class="btn btn-danger" data-url-post="{{url('finance/purchase-requisition/reject')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}" style="display: table;margin:0 auto;"><i class="fa fa-trash"></i> Cancel PR </a>
                        </div>
                        @endif
                    @endif

                    @if ($data['allow_approve_reject'])
                    {{-- @if ($data['data_header']->LAST_APPROVAL_STATUS!=='CANCELED' && $data['data_header']->LAST_APPROVAL_STATUS!=='REJECTED') --}}
                    @if ($data['last_approval_status']!=='CANCELED' && $data['last_approval_status']!=='REJECTED')
                    <div class="col-md-12" style="margin-top:5%;">
                        <div class="row">
                            <ul class="action-ul">
                                <li>
                                    <a href="javascript:void(0);" id="approvePR" class="btn btn-success text-white" data-url-post="{{url('finance/purchase-requisition/approve')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                        <i class="fa fa-check"></i> Approve
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" id="rejectPR" class="btn btn-danger" data-url-post="{{url('finance/purchase-requisition/reject')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                        <i class="fa fa-trash"></i> Reject
                                    </a>
                                </li>

                            </ul>


                        </div>
                    </div>
                    @endif
                    @endif

                @if ($data['allow_comment'])
                    @if(isset($release_strategy) && count($release_strategy))
                        @foreach ($release_strategy as $release)
                            @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && !in_array($release->RELEASE_CODE, $prior_release_approve))
                                <div style="margin-top: 30px">
                                    <form id="commentForm" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition/save_comment')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                        <input type="hidden" name="id" value="{{$data['pr_number']}}">
                                        <input type="hidden" name="id_user" value="{{ Session::get('user_id')}}">
                                        <div class="form-group">
                                            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Add Comments </h3>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <textarea name="text" class="form-control" placeholder="please input your comment here" cols="30" rows="10" ></textarea>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="submit" value="Add Comments" class="btn btn-primary btn-full form-control" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div style="margin-top: 30px">
                            <form id="commentForm" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition/save_comment')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                <input type="hidden" name="id" value="{{$data['pr_number']}}">
                                <input type="hidden" name="id_user" value="{{ Session::get('user_id')}}">
                                <div class="form-group">
                                    <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Add Comments </h3>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <textarea name="text" class="form-control" placeholder="please input your comment here" cols="30" rows="10" ></textarea>
                                            </div>
                                            <div class="input-group">
                                                <input type="submit" value="Add Comments" class="btn btn-primary btn-full form-control" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    @endif
                
                @endif
                <div class="form-group" style="margin-top: 30px">
                    <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Comments </h3>
                    <div class="row mb-3">
                        <div class="col-md-12" id="parse_comment">
                            @if (!empty($data['data_comments']))
                                @foreach ($data['data_comments'] as $comments)
                                    @php
                                        $allow_edit_comment=false;
                                        if($comments['comment_owner_id']== $data['current_user_id']){
                                            $allow_edit_comment=true;
                                        }
                                    @endphp
                                    <div class="comment-container col-md-12 float-left" id="comment-container-{{$comments['comment_id']}}" >
                                        <div class="float-left col-md-2">
                                            <div class="comment-user">
                                                <img src="{{$comments['comment_photo']}}" alt="">
                                                <p><b>{{$comments['comment_user']}}</b></p>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-6">
                                            <div class="comment-text">
                                                <p>
                                                    <small>
                                                        {{ date('d F Y - H:i',strtotime($comments['comment_date'])) }}
                                                        @if (!empty($comments['last_update']))
                                                            - <i>last edited on {{ date('d F Y - H:i',strtotime($comments['last_update'])) }} </i>
                                                        @endif
                                                    </small>
                                                </p>
                                                <p class="comment-text-{{$comments['comment_id']}}">{{$comments['comment_text']}}</p>
                                            </div>
                                            <div class="comment-edit-{{$comments['comment_id']}}" style="display:none">
                                                <form class="formEditComment" data-url-post="{{url('finance/purchase-requisition/update_comment')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
                                                    <div class="form-group">
                                                        <textarea name="comment" class="form-control" id="commentEdit">{{$comments['comment_text']}}</textarea>
                                                        <input type="hidden" name="id" value="{{$comments['comment_id']}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-success text-white" type="submit">Save Comment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="float-left col-md-4">
                                            @if ($allow_edit_comment)
                                            <div class="comment-tools">
                                                <a href="javascript:void(0);" onclick="return toggleComment({{$comments['comment_id']}})" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                                <a href="javascript:void(0);" data-url-post="{{url('finance/purchase-requisition/delete_comment')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}" onclick="return deleteComment({{$comments['comment_id']}})" class="btn btn-sm btn-danger btn-delete-comment"><i class="fa fa-trash"></i></a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Looks like it's quiet here, if there's a comment for the request it will appears here.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" onclick="window.history.back();" class="btn btn-dark text-white"><i class="fa fa-arrow-left"></i> Go Back </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


<div id="assetDetail" class="modal fade" style="padding-top: 30px;">
	<div id="modal-input" class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <h4 class="modal-title">
                <span id="modalTitleAset">Asset Detail</span>
                </h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-bordered table-hover smallfont" id="assetDet">
					<thead>
						<tr>
							<th style="text-align:center; vertical-align:middle;">Asset Number</th>
							<th style="text-align:center; vertical-align:middle;">Asset Description</th>
						</tr>
					</thead>
					<tbody id="AssetDetBody">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPO" tabindex="-1" role="dialog" aria-labelledby="modalPOLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
                <div class="d-flex">
    				<h5 class="modal-title mr-3" id="modalPOLabel">Purchase Order Detail </h5>
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

<!-- Modal -->
@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">

    $(document).ready( function () {
        $('#fileList').DataTable();
        $("#commentForm").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url_post=$("#commentForm").attr('data-url-post');
            var loader=$("#commentForm").attr('data-loader-file');
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
                            $("#parse_comment").append(data.html);
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

        $("#show_attach").click(function(){
            $("#attachment_form").slideDown();
        });

        $("#approvePR").click(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url_post=$(this).attr('data-url-post');
            var loader=$(this).attr('data-loader-file');

            var uid = $("#uid").val();
            var relcode = $("#current_user_release_code").val();
            Swal.fire({
                title: 'Confirm to approve?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                confirmButtonColor: '#0aab90',
                cancelButtonColor: '#d33',
                showCancelButton: true,
                confirmButtonText: `Yes, Approve`,
                cancelButtonText: `No, Don't approve yet`,
                }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: url_post,
                        data: {uid : uid, relcode : relcode},
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

                        if (data.success) {
                            if (data.msg) {
                                Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.msg,
                                }).then((result) =>{
                                    window.location.href = '/finance/purchase-requisition/approval';
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
                } else if (result.isDenied) {
                    return false;
                }
            });
        });
        $("#rejectPR").click(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url_post=$(this).attr('data-url-post');
            var loader=$(this).attr('data-loader-file');

            var uid = $("#uid").val();
            var relcode = $("#current_user_release_code").val();
            var type="reject"; //flag type ini penting, karena akan dibaca di proses reject backend
            Swal.fire({
                title: 'Confirm to reject?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                confirmButtonColor: '#0aab90',
                cancelButtonColor: '#d33',
                showCancelButton: true,
                confirmButtonText: `Yes, Reject`,
                cancelButtonText: `No, Don't reject yet`,
                }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: "Reject Reason",
                        text: "Input your reason to reject this request",
                        input: "text",
                        showCancelButton: true,
                        confirmButtonText: 'Reject PR',
                        inputPlaceholder: "Input a reason"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var reason=result.value;
                            $.ajax({
                                type: "POST",
                                url: url_post,
                                data: {uid : uid, reason : reason, relcode : relcode, type : type},
                                beforeSend: function() {
                                    Swal.fire({
                                        title: "Loading...",
                                        text: "Please wait!",
                                        imageUrl: loader,
                                        showConfirmButton: false
                                    });
                                },
                                success: function(data) {
                                    if (data.success) {
                                        if (data.msg) {
                                            Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.msg,
                                            }).then((result) =>{
                                                // window.location.reload();
                                                window.location.href = '/finance/purchase-requisition/approval';
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
                        }
                    });
                    return false;
                } else if (result.isDenied) {
                    return false;
                }
            });
        });
        $("#cancelPR").click(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url_post=$(this).attr('data-url-post');
            var loader=$(this).attr('data-loader-file');

            var uid = $("#uid").val();
            var relcode = $("#current_user_release_code").val();
            var type="cancel"; //flag reject ini penting, karena akan dibaca di proses reject backend
            Swal.fire({
                title: 'Confirm to cancel?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                confirmButtonColor: '#0aab90',
                cancelButtonColor: '#d33',
                showCancelButton: true,
                confirmButtonText: `Yes, Cancel`,
                cancelButtonText: `No, Don't cancel yet`,
                }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: "Cancellation Reason",
                        text: "Input your reason to cancel this request",
                        input: "text",
                        showCancelButton: true,
                        confirmButtonText: 'Cancel PR',
                        inputPlaceholder: "Input a reason"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var reason=result.value;
                            $.ajax({
                                type: "POST",
                                url: url_post,
                                data: {uid : uid, reason : reason, relcode : relcode, type : type},
                                beforeSend: function() {
                                    Swal.fire({
                                        title: "Loading...",
                                        text: "Please wait!",
                                        imageUrl: loader,
                                        showConfirmButton: false
                                    });
                                },
                                success: function(data) {

                                    if (data.success) {
                                        if (data.msg) {
                                            Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.msg,
                                            }).then((result) =>{
                                                // window.location.reload();
                                                window.location.href = '/finance/purchase-requisition/request';
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
                        }
                    });
                    return false;
                } else if (result.isDenied) {
                    return false;
                }
            });
        });

        $("#attachForm").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url_post=$("#attachForm").attr('data-url-post');
            var loader=$("#attachForm").attr('data-loader-file');
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
                if (data.success) {
                    if (data.msg) {
                        Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.msg,
                        }).then((result) =>{
                            $("#current_attachment").html(data.html);
                            $("#attachment_form").slideUp();
                            $("#attachForm_file").val('');
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
    });

    function assetDetail(id, material, data, desc){
        $('#assetDet').DataTable().clear().destroy();
        $('#AssetDetBody').html("");
        $('#modalTitleAset').html("");
        $('#modalTitleAset').html("Asset Detail " + material);
        if(data || desc){
            var tr = "";
                tr += '<tr>';
                tr += '<td>'+data+'</td>';
                tr += '<td>'+desc+'</td>';
                tr += '</tr>';

            $('#AssetDetBody').html(tr);
        }
        $('#assetDet').DataTable({
            "pageLength": 10
        });
        $('#assetDetail').modal('show');
        return false;
    }

    function toggleComment(id){
        var element = $(".comment-edit-"+id);
        var comment_skrg = $(".comment-text-"+id);
        var element_hidden=$(element).is(":visible");
        if(element_hidden){
            element.hide();
            comment_skrg.show();
        }else{
            element.show();
            comment_skrg.hide();
        }
    }

    function deleteComment(id){
        var url_post=$(".btn-delete-comment").attr('data-url-post');
        var loader=$(".btn-delete-comment").attr('data-loader-file');
        Swal.fire({
            title: 'Confirm to remove comment?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            confirmButtonColor: '#0aab90',
            cancelButtonColor: '#d33',
            showCancelButton: true,
            confirmButtonText: `Yes, Remove`,
            cancelButtonText: `No, Don't remove `,
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: url_post,
                        data: {id : id},
                        cache:false,
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

                            if (data.success) {
                                if (data.msg) {
                                    Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.msg,
                                    }).then((result) =>{
                                        var id = data.id;
                                        $("#comment-container-"+id).remove();

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
                }
            })

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


    $(".formEditComment").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url_post=$(this).attr('data-url-post');
        var loader=$(this).attr('data-loader-file');
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

            if (data.success) {
                if (data.msg) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.msg,
                    }).then((result) =>{

                        var comment = data.html;
                        var id = data.id;
                        var comment_skrg = $(".comment-text-"+id);
                        comment_skrg.html('');
                        comment_skrg.html(comment);
                        toggleComment(id);
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

</script>
@endsection
