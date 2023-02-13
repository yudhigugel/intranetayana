<form id="modalDetailAjax">
    {{ csrf_field() }}
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Form Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Created Date</label>
                <input type="text" value="{{ isset($data['data_json']->Request_Date) ? date('d F Y', strtotime($data['data_json']->Request_Date)) : date('d F Y') }}" class="form-control" readonly/>
                <input type="hidden" name="Request_Date" id="Request_Date" value="{{ isset($data['data_json']->Request_Date) ? $data['data_json']->Request_Date : date('Y-m-d') }}">
            </div>
            <div class="col-md-6">
                <label>Form Number</label>
                <input type="text" name="Form_Number" value="{{ isset($data['data_form']->UID) ? $data['data_form']->UID : '' }}" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Purpose / Notes <span class="red">*</span></label>
                <input type="text" class="form-control" value="{{ isset($data['data_json']->purpose) ? $data['data_json']->purpose : '' }}" name="purpose" required placeholder="insert your purpose / notes on requesting cash advance"/>
            </div>

        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Requestor Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Name</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Name) ? $data['data_json']->Requestor_Name : 'Unknown Name'  }}" name="Requestor_Name" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Plant Name</label>
                <input type="text" value="{{ isset($data['data_form']->SAP_PLANT_NAME) ? $data['data_form']->SAP_PLANT_NAME : 'Unknown Plant' }}" name="Requestor_Company" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Employee ID</label>
                <input type="text" value="{{ isset($data['data_form']->REQUESTOR_ID) ? $data['data_form']->REQUESTOR_ID : 'Unknown ID' }}" name="Requestor_Employee_ID" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Territory</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Territory) ? $data['data_json']->Requestor_Territory : 'Unknown Territory' }}" name="Requestor_Territory" class="form-control" readonly />
                <input type="hidden" name="Requestor_Territory_ID" value="{{ isset($data['data_json']->Requestor_Territory_ID) ? $data['data_json']->Requestor_Territory_ID : '' }}" id="Requestor_Territory_ID">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Cost Center ID</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Cost_Center_ID) ? $data['data_json']->Requestor_Cost_Center_ID : 'Unknown Cost Center ID' }}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Department</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Department) ? $data['data_json']->Requestor_Department : 'Unknown Department' }}" name="Requestor_Department" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Plant ID</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Plant_ID) ? $data['data_json']->Requestor_Plant_ID : 'Unknown Plant ID' }}" name="Requestor_Plant_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Division</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Division) ? $data['data_json']->Requestor_Division : 'Unknown Division' }}" name="Requestor_Division" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Job Position</label>
                <input type="text" value="{{ isset($data['data_json']->Requestor_Job_Title) ? $data['data_json']->Requestor_Job_Title : 'Unknown Job Title' }}" name="Requestor_Job_Title" class="form-control" readonly />
            </div>
            <div class="col-md-6 required-amount" @if(!isset($data['data_json']->Requested_PR_Number) || empty($data['data_json']->Requested_PR_Number)) hidden @endif>
                <label><span class="text-danger">*</span> Request's total amount >= 1 Million requires PR Number</label>
                <input type="text" value="{{ isset($data['data_json']->Requested_PR_Number) ? $data['data_json']->Requested_PR_Number : '' }}" id="required-amount" name="Requested_PR_Number" placeholder="Input PR Number Here" class="form-control"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
        <div class="row portlet-body table-both-scroll mb-3">
            <table class="table table-bordered smallfont" id="detailReqForm" style="overflow:scroll;">
                <thead>
                    <tr>
                        <th style="width:400px;">Description</th>
                        <th style="width:100px;" class="thead-apri">Quantity</th>
                        <th style="width:150px;" class="thead-apri">Price</th>
                        <th class="thead-apri">Amount</th>
                        <th class="thead-apri">Currency</th>
                        <!-- <th class="thead-apri"></th>
                        <th class="thead-apri"></th> -->
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['data_json']->tableRow) && ((int)$data['data_json']->tableRow - 1))
                        @php
                            $tableRow = (int)$data['data_json']->tableRow - 1;
                        @endphp
                        @for($i=0;$i<$tableRow;$i++)
                        <tr id="rowToClone">
                            <td>
                                <textarea name="description[]" required readonly rows="1" id="description" class="form-control td-apri">{{ isset($data['data_json']->description[$i]) ? $data['data_json']->description[$i] : '' }}</textarea>
                            </td>
                            <td>
                                <input required type="text" readonly value="{{ isset($data['data_json']->quantity[$i]) ? $data['data_json']->quantity[$i] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="quantity[]" id="quantity" onkeyup="CalculateTotal(this, 'detailReqForm');">
                            </td>
                            <td>
                                <input type="text" readonly value="{{ isset($data['data_json']->harga_parse[$i]) ? $data['data_json']->harga_parse[$i] : ''  }}" class="form-control td-apri" name="harga_parse[]" id="harga_parse" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" placeholder="0.00" value="0">
                            </td>
                            <td>
                                <input type="text" readonly value="{{ isset($data['data_json']->amount_parse[$i]) ? $data['data_json']->amount_parse[$i] : ''  }}" class="form-control td-apri" style="min-width:200px;" name="amount_parse[]" id="amount_parse" readonly="">
                            </td>
                            <td>
                                <!-- <select name="currency[]" readonly id="currency" class="form-control" style="min-width:100px;">
                                    <option value="IDR" @if(isset($data['data_json']->currency[$i]) && $data['data_json']->currency[$i] == 'IDR') selected @endif>IDR</option>
                                    <option value="USD" @if(isset($data['data_json']->currency[$i]) && $data['data_json']->currency[$i] == 'USD') selected @endif>USD</option>
                                </select> -->
                                <input required type="text" readonly value="{{ isset($data['data_json']->currency[0]) ? $data['data_json']->currency[0] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="currency[]" id="currency">
                            </td>
                            <!-- <td>
                                <div class="btn-group" style="min-width:140px">
                                    <button type="button" class="btn btn-success btn-addRow" data-tableID='detailReqForm' onclick="addRow('detailReqForm')">+</button>
                                    <button type="button" class="btn btn-danger btn-deleteBaris" data-tableID='detailReqForm' onclick="deleteBaris('detailReqForm')">-</button>
                                    {{-- <button type="button" class="btn btn-warning" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">Copy</button> --}}
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="harga[]" id="harga" value="{{ isset($data['data_json']->harga[$i]) ? $data['data_json']->harga[$i] : ''  }}">
                            </td>
                            <td>
                                <input type="hidden" name="amount[]" id="amount" value="{{ isset($data['data_json']->amount[$i]) ? $data['data_json']->amount[$i] : ''  }}">
                            </td> -->
                        </tr>

                        @endfor
                    @else
                    <tr id="rowToClone">
                        <td>
                            <textarea name="description[]" required rows="1" id="description" class="form-control td-apri"></textarea>
                            {{-- <input type="text" class="form-control td-apri" style="min-width:200px;" name="materialDesc[]" id="materialDesc"    > --}}
                        </td>
                        <td>
                            <input required type="text" value="1" class="form-control td-apri" style="min-width:75px;" name="quantity[]" id="quantity" onkeyup="CalculateTotal(this, 'detailReqForm');">
                        </td>
                        <td>
                            <input type="text" class="form-control td-apri" name="harga_parse[]" id="harga_parse" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" placeholder="0.00" value="0">
                        </td>
                        <td>
                            <input type="text" class="form-control td-apri" style="min-width:200px;" name="amount_parse[]" id="amount_parse" readonly="">
                        </td>
                        <td>
                            <select name="currency[]" id="currency" class="form-control" style="min-width:100px;">
                                <option value="IDR" selected>IDR</option>
                                <option value="USD">USD</option>
                            </select>

                        </td>
                        <!-- <td>
                            <div class="btn-group" style="min-width:140px">
                                <button type="button" class="btn btn-success btn-addRow" data-tableID='detailReqForm' onclick="addRow('detailReqForm')">+</button>
                                <button type="button" class="btn btn-danger btn-deleteBaris" data-tableID='detailReqForm' onclick="deleteBaris('detailReqForm')">-</button>
                                {{-- <button type="button" class="btn btn-warning" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">Copy</button> --}}
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="harga[]" id="harga">
                        </td>
                        <td>
                            <input type="hidden" name="amount[]" id="amount">
                        </td> -->
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row mb-3">
            <div class="col-12 text-center">
                <!-- <div class="form-group">
                    <label class="col-md-4 control-label">Grand Total</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="{{ isset($data['data_json']->grandTotal) ? $data['data_json']->grandTotal : ''  }}" placeholder="0.00" readonly="" name="grandTotal" id="grandTotal">
                    </div>
                </div> -->

                <div class="form-group mt-3">
                    <label class="control-label">Grand Total</label>
                    <div class="col-12">
                        <h2><strong>{{ isset($data['data_json']->grandTotal) ? number_format($data['data_json']->grandTotal) : 0 }}</strong></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Disable dlu untuk editnya --}}

    {{--
    @if(isset($data['data_form']->APPROVAL_LEVEL) && $data['data_form']->APPROVAL_LEVEL == 0 && isset($data['action']) && strtolower($data['action']) == 'view')
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="hidden" id="tableRow" name="tableRow" readonly="">
                <button type="submit" class="form-control btn btn-danger text-white">Change Request Data</button>
            </div>
        </div>
    </div>
    @endif
    --}}
</form>
<script>
</script>
