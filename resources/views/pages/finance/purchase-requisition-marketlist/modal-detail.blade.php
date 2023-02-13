@php
    $editable = 'readonly';
    if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve' || strtolower($data['action']) == 'approve'){
        $editable = 'required';
    }
@endphp

<form id="modalDetailAjax" method="POST" enctype="multipart/form-data" data-url-post="{{url('finance/purchase-requisition-marketlist/request/update')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
    @if(isset($data['data_json']->PR_NUMBER))
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <a class="btn btn-sm btn-secondary text-white px-3" href="javascript:void(0)"><h5 style="margin: 0; font-weight: normal;">SAP Purchase Requisition No. {{ ltrim($data['data_json']->PR_NUMBER, "0") }}</h5></a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data['data_form']->STATUS_APPROVAL) && strtoupper($data['data_form']->STATUS_APPROVAL) == 'REJECTED')
    <div class="form-group">
        <div class="row">
            <div class="col-4 offset-4 mb-2">
                <a href="javascript:void(0);" class="btn btn-danger btn-block" style="display: table;margin:0 auto; position: relative;">
                    <div class="text-center">
                        <i class="fa fa-close"></i>&nbsp;&nbsp;This Purchase Requisition is {{ $data['data_form']->STATUS_APPROVAL }}
                    </div>
                </a>
                <div class="row">
                    <div class="col-6 mt-3 reject-info">
                        <div class="text-left">
                            <label class="text-muted">Rejected By</label>
                            <h6><i class="fa fa-user"></i>&nbsp; {{ isset($data['data_form']->LAST_APPROVAL_NAME) ? $data['data_form']->LAST_APPROVAL_NAME : '-' }}</h6>
                        </div>
                    </div>
                    <div class="col-6 mt-3 reject-info">
                        <div class="text-left">
                            <label class="text-muted">Comment</label>
                            <h6><i class="fa fa-pencil"></i>&nbsp; {{ isset($data['data_form']->REASON) ? ucwords(strtolower($data['data_form']->REASON)) : '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="form-group">
        <div>
            {{ csrf_field() }}
            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px; cursor: pointer;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Requestor Information <small class="text-primary" style="font-size: 10px;display: inline-block;margin-left: 5px;">Click here for more info...</small></h3>
        </div>
        <div id="collapseOne" class="collapse">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Requestor Name</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Name) ? $data['data_json']->Requestor_Name : '' }}" name="Requestor_Name" class="form-control" readonly/>
                </div>
                <div class="col-md-6">
                    <label>Requestor Company</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Company) ? $data['data_json']->Requestor_Name : '' }}" name="Requestor_Company" class="form-control" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Requestor Employee ID</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Employee_ID) ? $data['data_json']->Requestor_Employee_ID : '' }}" name="Requestor_Employee_ID" class="form-control" readonly/>
                </div>
                <div class="col-md-6">
                    <label>Requestor Territory</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Territory) ? $data['data_json']->Requestor_Territory : '' }}" name="Requestor_Territory" class="form-control" readonly />
                    <input type="hidden" name="Requestor_Territory_ID" value="{{ isset($data['data_json']->Requestor_Territory_ID) ? $data['data_json']->Requestor_Territory_ID : '' }}" id="Requestor_Territory_ID">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Requestor Cost Center ID</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Cost_Center_ID) ? $data['data_json']->Requestor_Cost_Center_ID : '' }}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
                </div>
                <div class="col-md-6">
                    <label>Requestor Department</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Department) ? $data['data_json']->Requestor_Department : '' }}" name="Requestor_Department" class="form-control" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Requestor Plant ID</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Plant_ID) ? $data['data_json']->Requestor_Plant_ID : '' }}" name="Requestor_Plant_ID" id="Requestor_Plant_ID" class="form-control" readonly />
                </div>
                <div class="col-md-6">
                    <label>Requestor Division</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Division) ? $data['data_json']->Requestor_Division : '' }}" name="Requestor_Division" class="form-control" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Created Date</label>
                <input type="text" value="{{ isset($data['data_json']->Request_Date) ? date('d F Y', strtotime($data['data_json']->Request_Date)) : date('d F Y') }}" class="form-control" readonly/>
                <input type="hidden" name="Request_Date" id="Request_Date" value="{{ isset($data['data_json']->Request_Date) ? date('Y-m-d', strtotime($data['data_json']->Request_Date)) : date('Y-m-d') }}">
            </div>
            <div class="col-md-6">
                <label>Form Number</label>
                <input type="text" name="uid" value="{{ isset($data['data_form']->UID) ? $data['data_form']->UID : '(auto generate)' }}" class="form-control" readonly/>
                <input type="hidden" name="type_form" id="type_form" value="{{$data['data_form']->TYPE_FORM}}">
                <input type="hidden" name="idJoin" id="idJoin" value="{{$data['data_form']->UID}}#{{$data['data_form']->APPROVAL_LEVEL}}">
                <input type="hidden" name="Template_Code" id="Template_Code_Detail" value="{{ isset($data['data_json']->Template_Code) ? $data['data_json']->Template_Code : '' }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Doc Type <span class="red">*</span></label>
                <select readonly class="form-control select2 readonly" name="doc_type" id="doc_type_detail" style="width: 100%">
                    <option value="">--- Select Document Type ---</option>
                    <option value="YOPX" @if(isset($data['data_json']->doc_type) && strtoupper($data['data_json']->doc_type) == 'YOPX') selected default @endif>PR OPEX MID</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Ship to Plant <span class="red">*</span></label>
                <select readonly name="plant" class="form-control select2 readonly" id="plant_detail" style="width: 100%">
                    <option value="">--- Select Plant ---</option>
                    @foreach ($data['plant_list'] as $list_plant)
                        @if(isset($data['data_form']->PLANT_ID) && strtoupper($list_plant->SAP_PLANT_ID) == strtoupper($data['data_form']->PLANT_ID))
                            <option value="{{$list_plant->SAP_PLANT_ID}}^-^{{$list_plant->SAP_PLANT_NAME}}" selected default>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                        @endif
                    @endforeach
                </select>
                <input type="hidden" readonly id="tableRow" name="tableRow">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>SLOC <span class="red">*</span></label>
                <select class="form-control select2" name="sloc" id="sloc_detail" required readonly style="width: 100%">
                     <option value="">--- Choose SLOC ---</option>
                     @foreach ($data['s_loc'] as $list_sloc)
                        @if(isset($data['data_json']->sloc) && $data['data_json']->sloc == $list_sloc['STORAGE_LOCATION'])
                            <option value="{{$list_sloc['STORAGE_LOCATION']}}" selected default>{{$list_sloc['STORAGE_LOCATION']}} - {{$list_sloc['STORAGE_LOCATION_DESC']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Cost Center <span class="red">*</span></label>&nbsp;&nbsp;<i class="fa fa-spinner fa-spin" id="spinner_cost_center" style="display:none;"></i>
                <select class="form-control select2" name="cost_center" id="cost_center_detail" readonly style="width: 100%">
                     <option value="">--- Choose Cost Center ---</option>
                     @foreach($data['custom_cost_center'] as $list_cost_center)
                        <option value="{{$list_cost_center->SAP_COST_CENTER_ID}}" @if(isset($data['data_json']->cost_center) && $data['data_json']->cost_center == $list_cost_center->SAP_COST_CENTER_ID) selected default @endif><b>{{ isset($list_cost_center->SAP_COST_CENTER_ID) ? $list_cost_center->SAP_COST_CENTER_ID : '-'  }}</b> - {{ isset($list_cost_center->SAP_COST_CENTER_DESCRIPTION) ? $list_cost_center->SAP_COST_CENTER_DESCRIPTION : '-' }}</option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Delivery Date <span class="red">*</span></label>
                <input type="text" required name="Delivery_Date" value="{{ isset($data['data_json']->Delivery_Date) ? date('d F Y', strtotime($data['data_json']->Delivery_Date)) : date('d F Y') }}" class="form-control datepicker3-details"/>
            </div>
            <div class="col-md-6">
                <label>Purpose / Notes <span class="red">*</span></label>
                <input type="text" class="form-control" value="{{ isset($data['data_json']->purpose) ? $data['data_json']->purpose : '' }}" name="purpose" required readonly placeholder="insert your purpose / notes on requesting purchase requisition"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="position: relative;">
            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
            @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
                <div style="position: absolute;right: 0;top: -5px">
                    <button type="button" class="btn btn-primary btn-sm text-white select-template" data-toggle="modal" data-target="#marketlist-template"><i class="fa fa-plus"></i>&nbsp;&nbsp;Select / Edit from template</button>
                </div>
            @endif
        </div>
        <div class="portlet-body table-both-scroll mb-4" style="overflow: auto;">
            <table class="table table-bordered" id="reqFormDetail">
                <thead>
                    <th style="width: 8%">Items</th>
                    <th style="width: 26%" class="thead-apri">Material Name</th>
                    <th style="width: 8%" class="thead-apri">Unit</th>
                    <th style="width: 10%" class="thead-apri">Qty</th>
                    <th style="width: 10%" class="thead-apri">Purch. Group</th>
                    <th style="width: 24%" class="thead-apri">Note</th>
                    {{--<th style="width: 12%" class="thead-apri">Last Purchase Price</th>--}}
                    <th style="width: 12%" class="thead-apri">Total</th>
                    @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
                        <th style="width: 12%" class="thead-apri">Action</th>
                    @endif
                </thead>
                <tbody>
                    @if(isset($data['data_json']->marketlistItemOrder) && count($data['data_json']->marketlistItemOrder) > 0)
                        @php
                            $error_data = 0;
                        @endphp
                        @foreach($data['data_json']->marketlistItemOrder as $key => $item_list)
                            @if(isset($data['data_json']->marketlistMaterialQty[$key]) && (float)$data['data_json']->marketlistMaterialQty[$key] > 0)
                            <tr>
                                <td class="text-center">{{ $item_list }}
                                    <input type="hidden" name="marketlistItemOrder[]" value="{{ $item_list }}">
                                    <input type="hidden" name="marketlistAdditional[]" value="{{ isset($data['data_json']->marketlistAdditional[$key]) ? $data['data_json']->marketlistAdditional[$key] : 'N' }}">
                                </td>
                                <td class="text-left">{{ isset($data['data_json']->marketlistMaterialName[$key]) ? $data['data_json']->marketlistMaterialName[$key] : '-' }}
                                    <input type="hidden" name="marketlistMaterialNumber[]" value="{{ isset($data['data_json']->marketlistMaterialNumber[$key]) ? $data['data_json']->marketlistMaterialNumber[$key] : '' }}">
                                    <input type="hidden" name="marketlistMaterialName[]" value="{{ isset($data['data_json']->marketlistMaterialName[$key]) ? $data['data_json']->marketlistMaterialName[$key] : '' }}">
                                </td>
                                <td class="text-center">{{ isset($data['data_json']->marketlistMaterialUnit[$key]) ? $data['data_json']->marketlistMaterialUnit[$key] : '-' }}
                                    <input type="hidden" name="marketlistMaterialUnit[]" value="{{ isset($data['data_json']->marketlistMaterialUnit[$key]) ? $data['data_json']->marketlistMaterialUnit[$key] : '' }}">
                                </td>
                                <td class="text-right" style="position: relative;">
                                    <input type="text" {{ $editable }} class="form-control" oninput='qtyInputDetail(this)' name="marketlistMaterialQty[]" value="{{ isset($data['data_json']->marketlistMaterialQty[$key]) ? $data['data_json']->marketlistMaterialQty[$key] : '' }}">
                                    <div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>
                                </td>
                                <td class="text-center">{{ isset($data['data_json']->marketlistMaterialPurGroup[$key]) ? $data['data_json']->marketlistMaterialPurGroup[$key] : '' }}
                                    <input type="hidden" name="marketlistMaterialPurGroup[]" value="{{ isset($data['data_json']->marketlistMaterialPurGroup[$key]) ? $data['data_json']->marketlistMaterialPurGroup[$key] : '' }}">
                                </td>
                                <td class="text-left">
                                    <input oninput="noteInputDetail(this)" type="text" @if(isset($data['status_approval']) && $data['status_approval'] == true) readonly @endif class="form-control" name="marketlistMaterialNote[]" value="{{ isset($data['data_json']->marketlistMaterialNote[$key]) ? $data['data_json']->marketlistMaterialNote[$key] : '' }}">
                                </td>
                                <td class="text-right"><span id="last-price-text">{{ isset($data['data_json']->marketlistMaterialLastPriceFormatted[$key]) ? $data['data_json']->marketlistMaterialLastPriceFormatted[$key] : '' }}</span>
                                    <input type="hidden" name="marketlistMaterialLastPrice[]" value="{{ isset($data['data_json']->marketlistMaterialLastPrice[$key]) ? $data['data_json']->marketlistMaterialLastPrice[$key] : '' }}">
                                    <input type="hidden" name="marketlistMaterialLastPriceFormatted[]" value="{{ isset($data['data_json']->marketlistMaterialLastPriceFormatted[$key]) ? $data['data_json']->marketlistMaterialLastPriceFormatted[$key] : '' }}">
                                </td>
                                @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm px-3 btn-del" onclick="deleteBaris('reqFormDetail', this)">Remove</button>
                                </td>
                                @endif
                            </tr>
                            @else
                                @php
                                    $error_data += 1
                                @endphp
                            @endif
                        @endforeach

                        @if(count($data['data_json']->marketlistItemOrder) == $error_data)
                            <tr>
                                <td colspan="8" class="text-center">No Material. Material has been removed / edited on approval</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No Material Selected</td>
                        </tr>
                    @endif
                </tbody>                                
            </table>
        </div>
        <div class="row mb-3">
            <div class="col-md-8 col-8"></div>
            <div class="col-md-4 col-4">
                <div class="form-group">
                    <label class="control-label" style="font-size: .875rem; color: #001737"><b>Grand Total</b></label>
                    <div>
                        <input style="font-weight: bold; background-color: #fff; font-size: 15px" type="text" value="{{ isset($data['data_json']->grandTotal) ? $data['data_json']->grandTotal : '0.00' }}" class="form-control" placeholder="0.00" readonly name="grandTotal" id="grandTotal">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && $data['action'] != 'approve' && $data['action'] != 'view_list')
    <div class="submit-container">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="form-control btn btn-danger text-white btn-submit">Change Request</button>
            </div>
        </div>
    </div>
    @endif

    @if($data['action']=="approve")
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Comment</label>
            <textarea name="reason" id="reason" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" id="approveDetailModal" onclick="actionFormModal('Approve','APPROVED')" class="btn btn-success text-white btn-approve">APPROVE REQUEST</button>
            <button type="button" id="rejectDetailModal" onclick="actionFormModal('Reject', 'REJECTED')" class="btn btn-danger btn-reject">REJECT REQUEST</button>
        </div>
    </div>
    @endif
</form>


<script type="text/javascript">
    function actionFormModal(type, type2){
        try {
            var reason = $('#reason').val();
            if(type.toString().toLowerCase()=='reject') {
                if(!reason){
                    Swal.fire('PR Marketlist Approval', 'Please provide a brief and clear reason of the rejection, so the requestor can have better understanding of what their missing or lack of', 'warning');
                    return false
                }
            }
        } catch(error){}

        // if(!$('#modalDetailAjax')[0].checkValidity()){
        //     Swal.fire('PR Market List Approval', 'Please make sure all data required is filled or selected', 'warning');
        //     return false;
        // }

        var loader="{{ url('/image/gif/cube.gif') }}"
        var idJoin = $("#idJoin").val();
        var updateBy =  $('#updateBy').val();
        var inputValue = $("#reason").val();
        var form = $('#modalDetailAjax')[0];
        var form= new FormData(form);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, '+type,
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                form.append('form_id', idJoin);
                form.append('employe_id', updateBy);
                form.append('status_approval', type2);
                form.append('type_form', $('#type_form').val());
                form.append('reason', inputValue);

                const options = {
                    method: 'POST',
                    body: form,
                    headers: {
                       "Accept": "application/json",
                       "X-CSRF-Token": $('input[name="_token"]').val()
                    }
                };
                return fetch( '/finance/purchase-requisition-marketlist/approval/submitApprovalForm', options )
                .then(response => response.json())
                .then((res)=>{
                    var json = res;
                    if(json.hasOwnProperty('exception') && json.exception){
                        throw new Error(json.message);
                    } else {
                        return json;
                    }                        
                }).then(json => {
                    return {'status':'success', 'msg': json.message}
                })
                .catch(error => {
                    return {'status':'error', 'msg': error.message};
                });
              },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: type+" :",
                    text: result.value.msg,
                    icon: result.value.status
                }).then((result_data) => {
                    if(result.value.status !== 'error'){
                        location.reload();
                    }
                });
            }
        });
    }

    function qtyInputDetail(elem){
        elem.value = elem.value.replace(/^0/gi, '').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        var material_code = $(elem.closest('tr')).find('[name="marketlistMaterialNumber[]"]').val(),
        material_name = $(elem.closest('tr')).find('[name="marketlistMaterialName[]"]').val(),
        material_uom = $(elem.closest('tr')).find('[name="marketlistMaterialUnit[]"]').val(),
        material_note = $(elem.closest('tr')).find('[name="marketlistMaterialNote[]"]').val(),
        material_additional = $(elem.closest('tr')).find('[name="marketlistAdditional[]"]').val();

        if(parseFloat(elem.value) > 0){
            try {
                $(elem.closest('tr')).removeClass('bg-danger');
            } catch(error){}
            delayInAjaxCall(function(){
                try {
                    $('input[name="marketlistMaterialQty[]"]').not(elem).prop('disabled', true);
                    // try {
                    //     data_item_additional = data_item_additional.map(function(x) {
                    //         x.mtqty = elem.value;
                    //         x.mtnote = material_note;
                    //         x.last_price = 
                    //         return x;
                    //     });
                    // } catch(error){}

                } catch(error){}
                var is_additional = material_additional.toString().toUpperCase();
                var obj = {'material_additional': is_additional, 'material_note': material_note};
                checkLastPriceDetail(elem, material_code, material_name, material_uom, obj);
            }, 1000);
        } else {
            try {
                $('.btn-submit').prop('disabled', true);
            } catch(error){}

            try {
                $(elem.closest('tr')).addClass('bg-danger');
            } catch(error){}
            data_item = data_item.filter(x => x.mtnumber != material_code);
            if(!elem.value || parseFloat(elem.value) <= 0){
                $(elem.closest('tr')).find('#last-price-text').text('0.00');
                $(elem.closest('tr')).find('[name="marketlistMaterialLastPrice[]"]').val('0.00');
                $(elem.closest('tr')).find('[name="marketlistMaterialLastPriceFormatted[]"]').val('0.00');

            }

            // try {
            //     if(material_additional.toString().toUpperCase() == 'Y'){
            //         data_item_additional = data_item_additional.filter(x => x.mtnumber != material_code);
            //     }
            // } catch(error){}
            calculateGrandTotalDetail(elem);
        }
    }

    function checkLastPriceDetail(target, material_number='', material_name='', material_unit='', obj={}){
        try {
            $('.btn-approve').prop('disabled', true);
            $('.btn-reject').prop('disabled', true);
            $('.btn-submit').prop('disabled', true);
            $('.select-template').prop('disabled', true);

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
                     if(resp.hasOwnProperty('last_price')){
                        let last_price = resp.last_price;
                        let last_price_plain = resp.last_price_plain;
                        $(target).parents('tr').find('#last-price-text').text(last_price);
                        $(target).parents('tr').find('[name="marketlistMaterialLastPrice[]"]').val(last_price_plain);
                        $(target).parents('tr').find('[name="marketlistMaterialLastPriceFormatted[]"]').val(last_price);
                        try {
                            if(typeof obj == 'object'){
                                if(obj.hasOwnProperty('material_additional') && obj.material_additional == 'Y'){
                                    data_item_additional = data_item_additional.map(function(x) {
                                        if(x.mtnumber == material_number){
                                            x.mtqty = qty;
                                            x.mtnote = obj.material_note;
                                            x.mtlastprice = last_price;
                                            x.mtlastpriceplain = last_price_plain;
                                        }
                                        return x;
                                    });
                                }
                            }
                        } catch(error){}
                        calculateGrandTotalDetail(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('#last-price-text').text('0.00');
                     $(target).parents('tr').find('[name="marketlistMaterialLastPrice[]"]').val('0');
                     $(target).parents('tr').find('[name="marketlistMaterialLastPriceFormatted[]"]').val('0.00');
                     try {
                        if(typeof obj == 'object'){
                            if(obj.hasOwnProperty('material_additional') && obj.material_additional == 'Y'){
                                data_item_additional = data_item_additional.map(function(x) {
                                    if(x.mtnumber == material_number){
                                        x.mtqty = qty;
                                        x.mtnote = obj.material_note;
                                        x.mtlastprice = 0;
                                        x.mtlastpriceplain = 0;
                                    }
                                    return x;
                                });
                            }
                        }
                    } catch(error){}

                     console.log("Error in check last price detail");
                     $('.btn-approve').prop('disabled', false);
                     $('.btn-reject').prop('disabled', false);
                     $('.btn-submit').prop('disabled', false);
                     $('.select-template').prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                   },
                   complete : function(){
                    setTimeout(function(){
                        $('input[name="marketlistMaterialQty[]"]').not(target).prop('disabled', false);
                    }, 100);
                   }
                });
            } else {
                $(target).parents('tr').find('#last-price-text').text('0.00');
                $(target).parents('tr').find('[name="marketlistMaterialLastPrice[]"]').val('0');
                $(target).parents('tr').find('[name="marketlistMaterialLastPriceFormatted[]"]').val('0.00');
                $('.btn-approve').prop('disabled', false);
                $('.btn-reject').prop('disabled', false);
                $('.btn-submit').prop('disabled', false);
                $('.select-template').prop('disabled', false);
                $('input[name="marketlistMaterialQty[]"]').not(target).prop('disabled', false);
            }
        } catch(error){
            console.log("Error in last price detail", error);
            $('.btn-approve').prop('disabled', false);
            $('.btn-reject').prop('disabled', false);
            $('.btn-submit').prop('disabled', false);
            $('.select-template').prop('disabled', false);
            $('input[name="marketlistMaterialQty[]"]').not(target).prop('disabled', false);
        }
    }

    function calculateGrandTotalDetail(targetChild){
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
            console.log('error', error);
        }
        $('.btn-approve').prop('disabled', false);
        $('.btn-reject').prop('disabled', false);
        $('.btn-submit').prop('disabled', false);
        $('.select-template').prop('disabled', false);
    }

    $(document).on('submit', '#modalDetailAjax', function(e) {
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

        var form = $('#modalDetailAjax')[0];
        var url_post=$('#modalDetailAjax').attr('data-url-post');
        var loader=$('#modalDetailAjax').attr('data-loader-file');
        var form=new FormData(form);

        Swal.fire({
          title: 'PR Marketlist Request Revision',
          text: "Are you sure want to change the data of prior request ? You will only be able to change data if the request is not yet approved.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Sure!',
          showLoaderOnConfirm: true,
          preConfirm: (login) => {
            return fetch(url_post, {
              method: 'POST',
              body: form,
              headers: {
                "Accept": "application/json",
                "X-CSRF-Token": $('input[name="_token"]').val()
              }
            })
            .then((response) => response.json())
            .then((result) => {
              return result
            })
            .catch((error) => {
              return error
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
                title: "PR Marketlist Request Revision",
                text: result.value.message,
                icon: result.value.status,
                allowOutsideClick: false
            }).then((result) =>{
                $('.modal').modal('hide');
                setTimeout(function(){
                    location.reload();
                }, 600)
            });
          }
        });
        return false;
    });
</script>