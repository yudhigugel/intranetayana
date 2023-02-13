@php
    $editable = 'disabled';
    if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
        $editable = 'required';
@endphp

<form id="modalDetailAjax" method="POST" enctype="multipart/form-data" data-url-post="{{url('finance/add-reservation/request/update')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
    @if(isset($data['data_json']->RESERVATION_NO_SAP))
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <a class="btn btn-sm btn-secondary text-white px-3" href="javascript:void(0)"><h5 style="margin: 0; font-weight: normal;">SAP Reservation No. {{ ltrim($data['data_json']->RESERVATION_NO_SAP, "0") }}</h5></a>
                </div>
            </div>
        </div>
    </div>
    @endif
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
            <div class="row mb-3">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <label>Requestor Job Position</label>
                    <input type="text" value="{{ isset($data['data_json']->Requestor_Job_Title) ? $data['data_json']->Requestor_Job_Title : '' }}" name="Requestor_Job_Title" class="form-control" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Request For Date</label>
                <input type="text" name="CreatedDate" @if(isset($data['action']) && strtolower($data['action']) == 'approve') readonly @else {{ $editable }} @endif value="{{ date('d F Y', strtotime(isset($data['data_json']->CreatedDate) ? $data['data_json']->CreatedDate : 'd F Y' )) }}" class="form-control datepicker-detail"/>
            </div>
            <div class="col-md-6">
                <label>Form Number</label>
                <input type="text" name="FormNumber" value="{{ isset($data['data_form']->UID) ? $data['data_form']->UID : 'UNKNOWN' }}" class="form-control" readonly/>
                <input type="hidden" name="type_form" id="type_form" value="{{$data['data_form']->TYPE_FORM}}">
                <input type="hidden" name="idJoin" id="idJoin" value="{{$data['data_form']->UID}}#{{$data['data_form']->APPROVAL_LEVEL}}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Data Information</h3>
        <div class="row mb-3">
            {{--<div class="col-md-1">
                <label>Check Date</label>
                <label class="toggle-switch mt-2">
                    <input type="checkbox" id="check-date" name="CheckDate" value="Y" @if(isset($data['data_json']->CheckDate) && strtoupper($data['data_json']->CheckDate) == 'Y') checked @endif disabled>
                    <span class="toggle-slider round"></span>
                </label>
            </div>--}}
            <input type="hidden" id="check-date" name="CheckDate" value="{{ isset($data['data_json']->CheckDate) ? $data['data_json']->CheckDate : 'Y' }}">

            <div class="col-md-4">
                <label>Movement Type <span class="red">*</span></label>
                <select required class="select2 select-decorated-detail form-control" disabled name="MovementType" id="movementTypeShow" style="width: 100%; padding: 0.8rem 1.375rem">
                    <option value="" selected>---- Choose Data ----</option>
                    @if(isset($data['movement_type']) && count($data['movement_type']) > 0)
                        @foreach($data['movement_type'] as $key_mv => $val_mv)
                            <option value="{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE : '' }}" @if(isset($data['data_json']->MovementType) && $val_mv->MV_TYPE == $data['data_json']->MovementType) selected @endif>{{ isset($val_mv->MV_TYPE) ? $val_mv->MV_TYPE. ' - ' : '' }} {{ isset($val_mv->MV_DESCRIPTION) ? "(".$val_mv->MV_DESCRIPTION.")" : '' }}</option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" name="MovementType" value="{{ isset($data['data_json']->MovementType) ? $data['data_json']->MovementType : '' }}">
            </div>
            <div class="col-md-4">
                <label>Request Note <span class="red">*</span></label>
                <input type="text" @if(isset($data['action']) && strtolower($data['action']) == 'approve') readonly @else {{ $editable }} @endif class="form-control" style="padding: 0.78rem 1.375rem" name="RequestNote" value="{{ isset($data['data_json']->RequestNote) ? $data['data_json']->RequestNote : '' }}" placeholder="Insert reservation notes here.."/>
            </div>
            <div class="col-md-4">
                <label>Recipient Name <span class="red">*</span></label>
                <input type="text" readonly class="form-control" style="padding: 0.78rem 1.375rem" name="Recipient" value="{{ isset($data['data_json']->Recipient) ? $data['data_json']->Recipient : '' }}" placeholder="Insert goods recipient name.."/>
            </div>
        </div>
    </div>

    @if(isset($data['data_json']->isPlantToPlantReceive) && strtoupper($data['data_json']->isPlantToPlantReceive) == 'Y')
        <div class="form-group" id="rsvPlantSlocContainer">
            <div class="row">
                <div class="col-md-2">
                    <label>Receiving Plant <span class="red">*</span></label>
                    <div style="position: relative;">
                        <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') readonly @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvReceivingPlant" id="receiving_plant_new" style="width: 100%">
                            <option value="" selected>---- Choose Plant ----</option>
                            @if(isset($data['plant_list']) && count($data['plant_list']) > 0)
                                @foreach($data['plant_list'] as $key => $val)
                                    <option @if(isset($data['data_json']->rsvReceivingPlant) && $data['data_json']->rsvReceivingPlant == $val->SAP_PLANT_ID) selected @endif value="{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : '' }}" >{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : 'Unknown Plant' }}</option>
                                @endforeach
                            @endif      
                        </select>
                        <div class="spinner-receiving-plant" style="position: absolute; top: 13px; right: 30px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                    </div>
                </div>
                <div class="col-md-10">
                    <label>Receiving SLOC <span class="red">*</span></label>
                    <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') readonly @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvReceivingPlantSLOC" id="receiving_plant_sloc_new" style="width: 100%">
                        <option value="">---- Choose Sloc ----</option>
                        @if(isset($data['s_loc_receiving']) && count($data['s_loc_receiving']) > 0)
                            @foreach($data['s_loc_receiving'] as $key => $val)
                                <option @if(isset($data['data_json']->rsvReceivingPlantSLOC) && trim($data['data_json']->rsvReceivingPlantSLOC) == $val['STORAGE_LOCATION']) selected @endif value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'] : '' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STORAGE LOCATION' }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-muted">Note : Please select receiving plant first to show available store location</small>
                </div>
                <input type="hidden" name="isPlantToPlantReceive" value="Y">
                <input type="hidden" name="rsvReceivingSLOCDesc" value="{{ isset($data['data_json']->rsvReceivingSLOCDesc) ? $data['data_json']->rsvReceivingSLOCDesc : '' }}" id="receiving_sloc_desc">
            </div>
        </div>

        <div class="form-group item-container-with-plant">
            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
            @if(isset($data['status_approval']) && $data['status_approval'] == false)
                <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important; overflow: auto;">
            @else
                <div class="row portlet-body table-both-scroll mb-3" style="overflow: auto;">
            @endif
                <table class="table table-bordered smallfont table-request" id="reqFormWithPlantDetail">
                    <thead>
                        <tr>
                            <th style="width: 7%">Item</th>
                            <th style="width: 14%">Plant</th>
                            <th style="width: 20%">SLOC</th>
                            <th style="width: 30%">Material</th>
                            <th style="width: 8%">Quantity</th>
                            <th style="width: 11%">Last Purchase Price</th>
                            <th style="width: 8%">UoM</th>
                            @if(isset($data['status_approval']) && $data['status_approval'] == false)
                            <th style="width: 2%">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data['data_json']->rsvItem) && count($data['data_json']->rsvItem) > 0)
                            @foreach($data['data_json']->rsvItem as $index => $item)
                            <tr class="rowToClone">
                                <td style="max-width: 1px">
                                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem{{ $loop->iteration }}" value="{{ $item }}" readonly="">
                                </td>
                                <td style="max-width: 1px">
                                   <div style="position: relative;">
                                       <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvOriginPlant[]" id="plantNew{{ $loop->iteration }}" style="width: 100%">
                                            <option value="" selected>---- Choose Plant ----</option>
                                            @if(isset($data['plant_list']) && count($data['plant_list']) > 0)
                                                @foreach($data['plant_list'] as $key => $val)
                                                    <option @if(isset($data['data_json']->rsvOriginPlant[$index]) && $data['data_json']->rsvOriginPlant[$index] == $val->SAP_PLANT_ID) selected @endif value="{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : '' }}" >{{ isset($val->SAP_PLANT_ID) ? $val->SAP_PLANT_ID : 'Unknown Plant' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="spinner-receiving-plant-item" style="position: absolute; top: 13px; right: 30px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                    </div>
                                </td>
                                <td style="max-width: 1px">
                                   <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvSLOC[]" id="sloc-detail-{{ $loop->iteration }}" style="width: 100%">
                                        <option value="" selected>---- Choose Sloc ----</option>
                                        @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                            @foreach($data['s_loc'] as $key => $val)
                                                <option @if(isset($data['data_json']->rsvSLOC[$index]) && $data['data_json']->rsvSLOC[$index] == $val['STORAGE_LOCATION']) selected @endif value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'] : '' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STORAGE LOCATION' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td style="max-width: 1px">
                                    <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif class="form-control select2 select-decorated-material-detail" name="rsvMaterials[]" id="materials-detail-{{ $loop->iteration }}" style="width: 100%">
                                        <option value="{{ isset($data['data_json']->rsvMaterials[$index]) ? $data['data_json']->rsvMaterials[$index] : 
                                            (isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : '') }}" default selected>{{ isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : 
                                            (isset($data['data_json']->rsvMaterials[$index]) ? $data['data_json']->rsvMaterials[$index] : '') }}</option>
                                    </select>
                                    <input type="hidden" name="rsvMaterialsDesc[]" value="{{ isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : '' }}" id="rsvMaterialsDescNew">
                                </td>
                                <td style="max-width: 1px; position: relative;">
                                    <input @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" value="{{ isset($data['data_json']->rsvQuantity[$index]) ? $data['data_json']->rsvQuantity[$index] : 1 }}">
                                    <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                </td>
                                <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" id="rsvLastPrice" placeholder="Automatically filled" value="{{ isset($data['data_json']->rsvLastPrice[$index]) ? $data['data_json']->rsvLastPrice[$index] : '' }}">
                                </td>
                                <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled" value="{{ isset($data['data_json']->rsvMeasurement[$index]) ? $data['data_json']->rsvMeasurement[$index] : '' }}">
                                </td>
                                @if(isset($data['status_approval']) && $data['status_approval'] == false)
                                    <td>
                                        <div class="btn-group" style="min-width:80px">
                                            @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) == 'approve')
                                            <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqFormWithPlantDetail', this)">-</button>
                                            @else
                                            <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqFormWithPlantDetail', this)">-</button>
                                            <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRowWithPlant('reqFormWithPlantDetail')">+</button>
                                            @endif
                                            
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td class="text-center" colspan="7">No Data Available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    @else
        @if(isset($data['data_json']->rsvReceivingSLOC) && $data['data_json']->rsvReceivingSLOC)
            <div class="form-group" id="rsvSlocContainer">
                <div class="row">
                    <div class="col-md-12">
                        <label>Receiving SLOC <span class="red">*</span></label>
                        @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
                            <select {{ $editable }} class="form-control select2 select-decorated-detail" name="rsvReceivingSLOC" id="receiving_sloc_detail" style="width: 100%">
                                <option value="" selected>---- Choose Sloc ----</option>
                                @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                    @foreach($data['s_loc'] as $key => $val)
                                        <option @if($data['data_json']->rsvReceivingSLOC == $val['STORAGE_LOCATION']. ' -  '.$val['STORAGE_LOCATION_DESC']) selected @endif value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @else
                        <input type="text" class="form-control" readonly name="rsvReceivingSLOC" value="{{ isset($data['data_json']->rsvReceivingSLOC) ? $data['data_json']->rsvReceivingSLOC : '' }}">
                        @endif
                    </div>
                </div>
            </div>
        @elseif(isset($data['data_json']->rsvCostCenterExpense) && $data['data_json']->rsvCostCenterExpense)
            <div class="form-group" id="rsvCostCenterContainer">
                <div class="row">
                    <div class="col-md-12">
                        <label>Cost Center <span class="red">*</span></label>
                        @if(isset($data['custom_cost_center']) && count($data['custom_cost_center']) > 0)
                        <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') readonly @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvCostCenterExpense" id="cost_center_expense" style="width: 100%">
                            <option value="" selected>---- Choose Cost Center ----</option>
                            @if(isset($data['custom_cost_center']) && count($data['custom_cost_center']) > 0)
                                @foreach($data['custom_cost_center'] as $key_cc => $val_cc)
                                    <option @if(isset($data['data_json']->rsvCostCenterExpense) && $data['data_json']->rsvCostCenterExpense == $val_cc->SAP_COSTCENTER_ID.' - '.$val_cc->SAP_COST_CENTER_DESCRIPTION) selected @endif value="{{ isset($val_cc->SAP_COSTCENTER_ID) ? $val_cc->SAP_COSTCENTER_ID.' - ' : '' }}{{ isset($val_cc->SAP_COST_CENTER_DESCRIPTION) ? $val_cc->SAP_COST_CENTER_DESCRIPTION : ''}}">{{ isset($val_cc->SAP_COSTCENTER_ID) ? $val_cc->SAP_COSTCENTER_ID.' - ' : '' }}{{ isset($val_cc->SAP_COST_CENTER_DESCRIPTION) ? $val_cc->SAP_COST_CENTER_DESCRIPTION : ''}}</option>
                                @endforeach
                            @endif
                        </select>
                        @else
                        <input readonly type="text" value="{{ isset($data['data_json']->rsvCostCenterExpense) ? $data['data_json']->rsvCostCenterExpense : '' }}" id="cost_center_expense_new" name="rsvCostCenterExpense" class="form-control" />
                        @endif
                        
                        @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) != 'approve')
                            <small class="text-muted">Note : This cost center will be used as an expense destination</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group">
            <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
            @if(isset($data['status_approval']) && $data['status_approval'] == false)
                <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important; overflow: auto;">
            @else
                <div class="row portlet-body table-both-scroll mb-3" style="overflow: auto;">
            @endif
                <table class="table table-bordered smallfont" id="reqFormDetail">
                    <thead>
                        <tr>
                            <th style="width: 8%">Item</th>
                            <th style="width: 20%">SLOC</th>
                            <th style="width: 29%">Material</th>
                            <th style="width: 10%">Quantity</th>
                            <th style="width: 15%">Last Purchase Price</th>
                            <th style="width: 10%">UoM</th>
                            @if(isset($data['status_approval']) && $data['status_approval'] == false)
                            <th style="width: 2%">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data['data_json']->rsvItem) && count($data['data_json']->rsvItem) > 0)
                            @foreach($data['data_json']->rsvItem as $index => $item)
                            <tr class="rowToClone">
                                <td style="max-width: 1px">
                                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="{{ $item }}" readonly>
                                </td>
                                <td style="max-width: 1px">
                                   <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif class="form-control select2 select-decorated-detail" name="rsvSLOC[]" id="sloc-detail-{{ $loop->iteration }}" style="width: 100%">
                                        <option value="" selected>---- Choose Sloc ----</option>
                                        @if(isset($data['s_loc']) && count($data['s_loc']) > 0)
                                            @foreach($data['s_loc'] as $key => $val)
                                                <option @if(isset($data['data_json']->rsvSLOC[$index]) && $data['data_json']->rsvSLOC[$index] == $val['STORAGE_LOCATION']) selected @endif value="{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'] : '' }}">{{ isset($val['STORAGE_LOCATION']) ? $val['STORAGE_LOCATION'].' - ' : '' }} {{ isset($val['STORAGE_LOCATION_DESC']) ? $val['STORAGE_LOCATION_DESC'] : 'UNKNOWN STIRANGE LOCATION' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td style="max-width: 1px">
                                    <select @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif class="form-control select2 select-decorated-material-detail" name="rsvMaterials[]" id="materials-detail-{{ $loop->iteration }}" style="width: 100%">
                                        <option value="{{ isset($data['data_json']->rsvMaterials[$index]) ? $data['data_json']->rsvMaterials[$index] : 
                                            (isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : '') }}" default selected>{{ isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : 
                                            (isset($data['data_json']->rsvMaterials[$index]) ? $data['data_json']->rsvMaterials[$index] : '') }}</option>
                                    </select>
                                    <input type="hidden" name="rsvMaterialsDesc[]" value="{{ isset($data['data_json']->rsvMaterialsDesc[$index]) ? $data['data_json']->rsvMaterialsDesc[$index] : '' }}" id="rsvMaterialsDescNew">
                                </td>
                                <td style="max-width: 1px; position: relative;">
                                    <input @if(isset($data['action']) && strtolower($data['action']) == 'approve') required @else {{ $editable }} @endif type="text" oninput="qtyInput(this)" class="form-control text-center" name="rsvQuantity[]" id="rsvQty" value="{{ isset($data['data_json']->rsvQuantity[$index]) ? $data['data_json']->rsvQuantity[$index] : 1 }}">
                                    <div class="spinner-qty" style="position: absolute; top: 18px; right: 12px" hidden><i class="fa fa-spin fa-spinner"></i></div>
                                </td>
                                <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvLastPrice[]" id="rsvLastPrice" placeholder="Automatically filled" value="{{ isset($data['data_json']->rsvLastPrice[$index]) ? $data['data_json']->rsvLastPrice[$index] : '' }}">
                                </td>
                                <td style="max-width: 1px">
                                    <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled" value="{{ isset($data['data_json']->rsvMeasurement[$index]) ? $data['data_json']->rsvMeasurement[$index] : '' }}">
                                </td>
                                @if(isset($data['status_approval']) && $data['status_approval'] == false)
                                    <td>
                                        <div class="btn-group" style="min-width:80px">
                                            @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && strtolower($data['action']) == 'approve')
                                            <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqFormDetail', this)">Remove</button>
                                            @else
                                            <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqFormDetail', this)">-</button>
                                            <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqFormDetail', true)">+</button>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                                
                            </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td class="text-center" colspan="6">No Data Available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="form-group mb-5 text-center">
        <label class="control-label">Grand Total</label>
        <div class="col-12">
            <h2><strong>IDR <span id="grand_total">{{ isset($data['data_json']->rsvGrandTotal) ? number_format($data['data_json']->rsvGrandTotal, 2) : 0 }}</span></strong></h2>
            <input type="hidden" name="rsvGrandTotal" id="grand_total_value" value="{{ isset($data['data_json']->rsvGrandTotal) ? $data['data_json']->rsvGrandTotal : 0 }}">
        </div>
    </div>

    @if(isset($data['status_approval']) && $data['status_approval'] == false && isset($data['action']) && $data['action'] != 'approve')
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
            <label>Reason</label>
            <textarea name="reason" id="reason" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" id="approveDetailModal" onclick="actionFormModal('Approve','APPROVED')" class="btn btn-success text-white">APPROVE REQUEST</button>
            <button type="button" id="rejectDetailModal" onclick="actionFormModal('Reject', 'REJECTED')" class="btn btn-danger">REJECT REQUEST</button>
        </div>
    </div>
    @endif
</form>


<script type="text/javascript">
    function actionFormModal(type, type2){
        if(!$('#modalDetailAjax')[0].checkValidity()){
            Swal.fire('Reservation Approval', 'Please make sure all data required is filled or selected', 'warning');
            return false;
        }

        var loader="{{ url('/image/gif/cube.gif') }}"
        var idJoin = $("#idJoin").val();
        var updateBy =  $('#updateBy').val();
        var inputValue = $("#reason").val();
        var form = $('#modalDetailAjax')[0];
        var form=new FormData(form);

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
                // const params = {
                //     "form_id": idJoin, //Pisahkan dengan ;
                //     "employe_id": updateBy, //emp id yg melakukan approve
                //     "status_approval": type2, //APPROVE or REJECT
                //     "type_form": $('#type_form').val(), //type_form
                //     "reason":inputValue,
                // };
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
                return fetch( '/finance/add-reservation/approval/submitApprovalForm', options )
                .then(response => response.json())
                .then((res)=>{
                    var json = res;
                    if(json.hasOwnProperty('exception') && json.exception){
                        throw new Error(json.message);
                    } else {
                        return json;
                    }                        
                }).then(json => {
                    return {'status':'success', 'msg': "Total Data: " + json.Total_Data +" Total Failed: " + json.Total_Failed +" Total Success: " + json.Total_Success}
                })
                .catch(error => {
                    return {'status':'error', 'msg': error.message};
                });
              },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                swal({
                    title: type+" :",
                    text: result.value.msg,
                    type: result.value.status,
                }, function() {
                    if(result.value.status !== 'error')
                        location.reload();
                });
            }
        });
    }

    $(document).on('submit', '#modalDetailAjax', function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        try {
            var zero_value = false;
            $('input[name="rsvQuantity[]"]', $('#modalDetailAjax')[0]).each(function(index, elem){
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

        try {
            var mv_type = $('input[name="MovementType"]', this).length > 0 ?  $('input[name="MovementType"]', this).val() : '';
            if(mv_type == '311'){
                if(data_is_not_available_in_sloc.length > 0){
                    Swal.fire('Material Selection SLOC', 'Some ot the materials might be not available or have not been extended to the destination SLOC, please check the warning sign (if any) within each material and solve the issues.', 'error');
                    // console.log(data_is_not_available_in_sloc);
                    return false;
                }
            }
        } catch(error){
            console.log(error)
        }

        var form = $('#modalDetailAjax')[0];
        var url_post=$('#modalDetailAjax').attr('data-url-post');
        var loader=$('#modalDetailAjax').attr('data-loader-file');
        var form=new FormData(form);

        Swal.fire({
          title: 'Reservation Request Revision',
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
                title: "Reservation Request Revision",
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