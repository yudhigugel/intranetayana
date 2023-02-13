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
                @if(!empty($data['data_header']))
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
                    {{--<a target="_blank" href="{{ url('finance/purchase-order/detail/'.$data['nomor_po'])}}" class="btn btn-warning" style="display: table;margin:0 auto;margin-top:20px;"><i class="fa fa-warning"></i> This PR has converted into Purchase Order with number <b>{{$data['nomor_po']}}</b></a>--}}
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
                                        <th class="thead-apri" colspan="2">Material</th>
                                        <th class="thead-apri">Material Desc.</th>
                                        <th class="thead-apri">Material Purch. Group</th>
                                        <th class="thead-apri">SLOC</th>
                                        <th class="thead-apri">Qty</th>
                                        <th class="thead-apri">Unit</th>
                                        <th class="thead-apri">Delivery Date</th>
                                        <th class="thead-apri">Last Purchase Price</th>
                                        {{-- <th class="thead-apri">Amount</th> --}}
                                        <th class="thead-apri">Cost Center</th>
                                        <th class="thead-apri" colspan="2">Asset Number</th>
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
                                                    <span>{{@$data['data_json']->preqItem[$i]}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>-</span>
                                                </td>
                                                <td colspan="2">
                                                    <span>{{@$data['data_json']->materials[$i]}}</span>
                                                </td>
                                                <td class="text-left">
                                                    <span>{{@$data['data_json']->materialDesc[$i]}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{@$data['data_json']->materialPurchGroup[$i]}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_json']->item_sloc[$i]) ? $data['data_json']->item_sloc[$i] : '-' }}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->quantity[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->unit[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->delivDate[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->cAmitBapx[$i]}}</span>
                                                </td>
                                                {{-- <td>
                                                    <span>{{@$data['data_json']->totalAmounx[$i]}}</span>
                                                </td> --}}
                                                <td>
                                                    <span>{{ @$data['data_json']->costCenter[$i]}}</span>
                                                </td>
                                                <td colspan="2">
                                                    <span>{{@$data['data_json']->assetNo[$i]}}</span>
                                                </td>
                                                {{--<td>
                                                    <span>{{ @$data['data_json']->orderNo[$i]}}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{@$data['data_json']->trackingNo[$i]}}</span>
                                                </td>
                                                {{--<td>
                                                    <span>{{@$data['data_json']->cmmtItem[$i]}}</span>
                                                </td>--}}
                                                <td>
                                                    <span>{{@$data['data_json']->materialPurpose[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->additionalInfo[$i]}}</span>
                                                </td>
                                                {{--
                                                <td>
                                                    <span>{{@$data['data_json']->fundsCtr[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->fundsCurr[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->amountTxt[$i]}}</span>
                                                </td>
                                                <td>
                                                    <span>{{@$data['data_json']->amountYearTxt[$i]}}</span>
                                                </td>
                                                --}}
                                            </tr>
                                        <?php } ?>
                                    @elseif ($data['source_item_pr']=="rfc" && isset($data['data_form']['GI_ITEMS'][0]))
                                        <?php for($i=0;$i<count($data['data_form']['GI_ITEMS']);$i++){ ?>
                                            <tr id="rowToClone">
                                                <td>
                                                    <span>{{ @$data['data_form']['GI_ITEMS'][$i]['PREQ_ITEM'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span>{{ isset($data['data_form']['GI_ITEMS'][$i]['PO']) ? $data['data_form']['GI_ITEMS'][$i]['PO'] : '-' }}</span>
                                                </td>
                                                <td colspan="2">
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
                                                <td colspan="2">
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
                                    @elseif ($data['source_item_pr']=="bapi" && isset($data['data_form_alt']['REQUISITION_ITEMS'][0]))
                                    <?php for($i=0;$i<count($data['data_form_alt']['REQUISITION_ITEMS']);$i++){ ?>
                                        <tr id="rowToClone">
                                            <td>
                                                <span>{{ @$data['data_form_alt']['REQUISITION_ITEMS'][$i]['PREQ_ITEM'] }}</span>
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
                    <div class="form-group">
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
                                    <iframe src="{{$at}}" frameborder="0" style="width:100%;height:400px;overflow:auto;" id="iframe-attachment"></iframe>
                                @else
                                    <a target="_blank" href="{{asset('upload/purchase_requisition/'.$data['data_header']->ATTACHMENT)}}">{{$data['data_header']->ATTACHMENT}}</a>
                                @endif

                            @endif
                        </div>


                        @if ($data['allow_modify_attachment'] && $data['data_form']['intranet'][0]->STATUS_APPROVAL!=='CANCELED')
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
                    <div class="form-group">
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
                </form>
            </div>

        </div>

    </div>
</div>


