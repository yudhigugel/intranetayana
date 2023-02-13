@extends('layouts.default')

@section('title', 'Detail Purchase Order')
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

#modalFile{
    padding-right:0px !important;
}

#modalFile .modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  max-width:100%;
}

#modalFile .modal-content {
  height: auto;
  min-height: 100%;
  border-radius: 0;
}

#modalFile .modal-lg{
    max-width:100%;
    width:100%;

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
    <li class="breadcrumb-item"><a href="#">Purchase Order</a></li>
    <li class="breadcrumb-item"><a href="#">Detail</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>{{ isset($data['PO_NUMBER']) ? $data['PO_NUMBER'] : '-' }}</span></li></ol>
</nav>
<div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Purchase Order Detail {{ isset($data['PO_NUMBER']) ? $data['PO_NUMBER'] : '-' }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($data['PO_DETAILS'][0]['PREQ_NO']) && !empty($data['PO_DETAILS'][0]['PREQ_NO']))
                <div class="form-group">
                    <a href="javascript:void(0);" class="btn btn-warning" style="display: table;margin:0 auto;margin-top:20px;"><i class="fa fa-warning"></i> This PO is generated from Purchase Requisition number <b>{{$data['PO_DETAILS'][0]['PREQ_NO']}}</b></a>
                </div>
                @endif

                <form id="modalDetailAjax">
                    <!-- Section General Info -->
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> General Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Created Date</label>
                                <input type="text" value="{{ isset($data['CREATED_ON']) ? date('d F Y',strtotime($data['CREATED_ON'])) : '-' }}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Created By</label>
                                <input type="text" value="{{ isset($data['CREATED_BY']) ? $data['CREATED_BY'] : '-' }}" class="form-control" readonly/>
                                <input type="hidden" id="po_number" value="{{ isset($data['PO_NUMBER']) ? $data['PO_NUMBER'] : '' }}">
                                <input type="hidden" id="current_user_release_code" value="{{ isset($now_release_approve) ? $now_release_approve : '' }}">
                                <input type="hidden" id="current_user_release_group" value="{{ isset($data['REL_GROUP']) ? $data['REL_GROUP'] : '' }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Doc Type</label>
                                <input type="text" value="{{ isset($data['DOC_TYPE']) ? $data['DOC_TYPE'] : '-' }}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Purchasing Group</label>
                                <input type="text" value="{{ isset($data['PUR_GROUP']) && !empty($data['PUR_GROUP']) ? $data['PUR_GROUP'] : '' }}" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Company Code</label>
                                <input type="text" class="form-control" value="{{ isset($data['CO_CODE']) ? $data['CO_CODE'] : '-' }}" readonly/>
                            </div>
                            {{--<div class="col-md-6">
                                <label>Sales Person</label>
                                <input type="text" value="{{ isset($data['SALES_PERS']) ? $data['SALES_PERS'] : '-' }}" class="form-control" readonly/>
                            </div>--}}
                            <div class="col-md-6">
                                <label>Document Date</label>
                                <input type="text" value="{{ isset($data['DOC_DATE']) ? $data['DOC_DATE'] : '-' }}" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12   ">
                                <label>PO Purpose / Reason</label>
                                <textarea name="" class="form-control" id=""  readonly>{{ !empty($data['PO_HEADER_TEXTS']) ?  $data['PO_HEADER_TEXTS'] : '-'}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Vendor Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Vendor ID</label>
                                <input type="text" value="{{ isset($data['VENDOR']) && !empty($data['VENDOR']) ? $data['VENDOR'] : '-' }}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Vendor Name</label>
                                <input type="text" value="{{ isset($data['VEND_NAME']) ? $data['VEND_NAME'] : '-' }}" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Address</label>
                                <input type="text" value="{{ isset($data['VEND_ADDRESS']) ? $data['VEND_ADDRESS'] : '-' }}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label>Telephone</label>
                                <input type="text" value="{{ isset($data['VEND_TEL']) ? $data['VEND_TEL'] : '-' }}" class="form-control" readonly/>
                            </div>
                        </div>

                    </div>

                    <!-- End Section General Info -->
                    <div class="form-group">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Item Details</h3>
                        <div class="row portlet-body table-both-scroll mb-3" style="overflow-x: scroll">
                            <table class="table table-striped table-bordered" style="min-width: 1500px">
                                <thead>
                                    <tr>
                                        <th class="thead-apri" style="width: 7%">Tracking No.</th>
                                        <th class="thead-apri" style="width: 7%">Material</th>
                                        <th class="thead-apri" style="width: 15%">Material Desc.</th>
                                        <th class="thead-apri" style="width: 15%">Requisitioner</th>
                                        <th class="thead-apri" style="width: 20%">Reason</th>
                                        <th class="thead-apri" style="width: 7%">PO Qty</th>
                                        <th class="thead-apri" style="width: 7%">Order Unit</th>
                                        <th class="thead-apri" style="width: 8%">Store Location</th>
                                        <th class="thead-apri" style="width: 6%">Plant</th>
                                        <th class="thead-apri" style="width: 7%">Material Group</th>
                                        <th class="thead-apri" style="width: 7%">Currency</th>
                                        <th class="thead-apri" style="width: 11%">Net Price</th>
                                        <th class="thead-apri" style="width: 11%">VAT</th>
                                        <th class="thead-apri" style="width: 11%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grand_total = 0;
                                    @endphp
                                    @if(isset($data['PO_DETAILS']) && count($data['PO_DETAILS']) > 0)
                                        @php
                                            $i=0;
                                        @endphp
                                        @foreach($data['PO_DETAILS'] as $detail)
                                        @php
                                            $VAT = isset($detail['NOND_ITAX']) && $detail['NOND_ITAX'] > 0 ? (float)$detail['NOND_ITAX'] : 0;
                                            if(!$VAT > 0){
                                                $VAT = taxCodeCalculation( isset($detail['TAX_CODE']) ? $detail['TAX_CODE'] : '', isset($detail['NET_VALUE']) ? $detail['NET_VALUE'] : 0 );
                                            }
                                            $subtotal = isset($detail['NET_VALUE']) ? ((float)$detail['NET_VALUE']) + $VAT : 0;
                                            $grand_total += isset($detail['NET_VALUE']) ? ((float)$detail['NET_VALUE']) + $VAT : 0;
                                        @endphp

                                        <tr>
                                            <td>
                                                <span>{{ isset($detail['TRACKINGNO']) ? $detail['TRACKINGNO'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['MATERIAL']) ? ltrim($detail['MATERIAL'], '0') : '-' }}</span>
                                            </td>
                                            <td class="text-left">
                                                <span>{{ isset($detail['SHORT_TEXT']) ? $detail['SHORT_TEXT'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($data['ITEM_ALT'][$i]) ? $data['ITEM_ALT'][$i]['PREQ_NAME'] : '-' }}</span>
                                            </td>
                                            <td class="text-left">
                                                <span>
                                                    @php
                                                        $parse_reason_item='';
                                                        if(isset($data['PO_ITEM_TEXTS'][$detail['PO_ITEM']])){
                                                            foreach($data['PO_ITEM_TEXTS'][$detail['PO_ITEM']] as $reason_item){
                                                                $parse_reason_item.="<br>".$reason_item['TEXT_LINE'];
                                                            }
                                                            $parse_reason_item=substr($parse_reason_item,4);
                                                        }
                                                    @endphp
                                                    {!! $parse_reason_item !!}
                                                </span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['QUANTITY']) ? number_format($detail['QUANTITY'], 2) : number_format(0, 2) }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['ORDERPR_UN']) ? $detail['ORDERPR_UN'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['STORE_LOC']) ? $detail['STORE_LOC'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['PLANT']) ? $detail['PLANT'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($detail['MAT_GRP']) ? $detail['MAT_GRP'] : '-' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ isset($data['CURRENCY']) ? $data['CURRENCY'] : '' }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span>{{ isset($detail['NET_VALUE']) ? number_format($detail['NET_VALUE'], 2) : number_format(0, 2) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span>{{ isset($detail['NOND_ITAX']) && $detail['NOND_ITAX'] > 0 ? number_format($detail['NOND_ITAX'], 2) : number_format(taxCodeCalculation( isset($detail['TAX_CODE']) ? $detail['TAX_CODE'] : '', isset($detail['NET_VALUE']) ? $detail['NET_VALUE'] : 0 ), 2) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span>{{ isset($subtotal) ? number_format($subtotal, 2) : number_format(0, 2) }}</span>
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                        @endforeach
                                    @else
                                    <tr>
                                        <td class="text-center" colspan="10">No Data Available</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Section Grand Total -->
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <div class="form-group">
                                <label class="control-label">Grand Total</label>
                                <div class="col-12">
                                    {{--<h2><strong>{{ isset($data['CURRENCY']) ? $data['CURRENCY'] : '' }} {{ isset($data['TARGET_VAL']) ? number_format($data['TARGET_VAL']) : '-' }}</strong></h2>--}}

                                    <h2><strong>{{ isset($data['CURRENCY']) ? $data['CURRENCY'] : '' }} {{ isset($grand_total) ? number_format($grand_total) : '-' }}</strong></h2>
                                </div>
                                <div class="mt-2">
                                    <h6 class="text-muted" style="font-size: 0.85rem">PO Number. {{ isset($data['PO_NUMBER']) ? $data['PO_NUMBER'] : '-' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Section Grand Total -->
                    <!-- Section Release Strategy -->
                    <div class="form-group mb-5">
                        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Approval / Release Strategy</h3>
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
                                @php
                                    $is_allowed_approve = [];
                                @endphp
                                @if(isset($release_strategy) && count($release_strategy))
                                    @foreach ($release_strategy as $release)
                                    @if(isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE,$release_code_collected))
                                    <tr @if(isset($now_release_approve) && isset($release_indicator) && $release_indicator != 'R' && isset($release->RELEASE_CODE) && $now_release_approve == $release->RELEASE_CODE) class="bg-light" @endif>
                                        <td class="text-center">{{ isset($release->RELEASE_CODE) ? $release->RELEASE_CODE : '-' }}</td>
                                        {{--<td class="text-left">{{ isset($release->RELEASE_CODE_DESC) ? $release->RELEASE_CODE_DESC : '-' }}</td>--}}
                                        <td class="text-left">
                                            @php
                                                $employee = '';
                                                $employee_id = '';

                                                $format_check_release_main_employee = isset($release->EMPLOYEE_ID) && isset($release->RELEASE_CODE) ? $release->RELEASE_CODE.'-'.$release->EMPLOYEE_ID : '';
                                                $format_check_release_alt_employee = isset($release->ALT_EMPLOYEE_ID) && isset($release->RELEASE_CODE) ? $release->RELEASE_CODE.'-'.$release->ALT_EMPLOYEE_ID : '';

                                                $current_employee = isset($current_login_employee) ? $current_login_employee : '';
                                                $main_employee = isset($release->EMPLOYEE_ID) ? $release->EMPLOYEE_ID : '-';
                                                $alt_employee = isset($release->ALT_EMPLOYEE_ID) ? $release->ALT_EMPLOYEE_ID : '-';

                                                if(isset($release_history[$format_check_release_main_employee]) || $current_employee == $main_employee) {
                                                    $employee = isset($release->MAIN_EMPLOYEE) ? $release->MAIN_EMPLOYEE : '-';
                                                    $employee_id = $main_employee;
                                                }
                                                else if(isset($release_history[$format_check_release_alt_employee]) || $current_employee == $alt_employee){
                                                    $employee = isset($release->ALT_EMPLOYEE) ? $release->ALT_EMPLOYEE : '-';
                                                    $employee_id = $alt_employee;
                                                }
                                                else{
                                                    $employee = isset($release->MAIN_EMPLOYEE) ? $release->MAIN_EMPLOYEE : '-';
                                                    $employee_id = $main_employee;
                                                }
                                            @endphp
                                            {{ $employee }}
                                        </td>
                                        <td>
                                            @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve))
                                                @php
                                                    array_push($is_allowed_approve, $employee_id);
                                                @endphp
                                                <span class="text-success mb-0"><b>APPROVED</b></span>
                                            @else
                                                <span class="mb-0"><b>WAITING FOR APPROVAL</b></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($release_history) && isset($release_history[$format_check_release_main_employee]))
                                                <span>{{ isset($release_history[$format_check_release_main_employee]) ? date('d M Y, H:i:s', strtotime($release_history[$format_check_release_main_employee])) : '-' }}</span>
                                            @elseif(isset($release_history) && isset($release_history[$format_check_release_alt_employee]))
                                                <span>{{ isset($release_history[$format_check_release_alt_employee]) ? date('d M Y, H:i:s', strtotime($release_history[$format_check_release_alt_employee])) : '-' }}</span>
                                            @else
                                                @if(isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve) && isset($release_history[$format_check_release_main_employee]) == false || isset($prior_release_approve) && isset($release->RELEASE_CODE) && in_array($release->RELEASE_CODE, $prior_release_approve) && isset($release_history[$format_check_release_alt_employee]) == false)
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
                    <!-- End Section Release Strategy -->
                </form>
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
@endsection
