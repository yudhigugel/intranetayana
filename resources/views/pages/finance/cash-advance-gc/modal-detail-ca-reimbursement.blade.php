@php
    $is_parked = false;
    $string_keterangan='Post Cash Advance to SAP';
    if(isset($data['park_fbcj'][0]->CURRENT_CA_BUSINESS_TRANSACTION)){
        $string_keterangan='Post Cash Advance to SAP';
        $is_parked=true;
    }

    $is_posted=($data['park_fbcj'][0]->STATUS_POSTING == 'YES') ? true : false;
    $is_posted_date=$data['park_fbcj'][0]->POSTING_DATE;
    if($is_posted){ $is_parked=false;}

@endphp
<form id="modalDetailAjax" data-loader-file="/image/gif/cube.gif">
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
                <input type="hidden" name="ca_number" id="ca_number_park" value="{{ isset($data['data_form']->UID) ? $data['data_form']->UID : '' }}">
                <input type="hidden" name="plant" id="plant_park" value="{{ isset($data['data_form']->PLANT_ID) ? $data['data_form']->PLANT_ID : '' }}">
                <input type="hidden" name="company_code" id="company_code" value="{{ isset($data['company_code']) ? $data['company_code'] : '' }}">
                <input type="hidden" name="currency" id="currency_park" value="{{ isset($data['data_json']->currency[0]) ? $data['data_json']->currency[0] : '' }}">
                <input type="hidden" name="grand_total" id="grand_total_park" value="{{ isset($data['data_json']->final_adjustment) ? $data['data_json']->final_adjustment : $data['data_json']->grandTotal }}">
                <input type="hidden" name="current_ca_number" id="current_ca_number" value="{{ isset($data['park_fbcj'][0]->CURRENT_CA_NUMBER) ? $data['park_fbcj'][0]->CURRENT_CA_NUMBER : '' }}">
                <input type="hidden" name="current_ca_business_transaction" id="current_ca_business_transaction" value="{{ isset($data['park_fbcj'][0]->CURRENT_CA_BUSINESS_TRANSACTION) ? $data['park_fbcj'][0]->CURRENT_CA_BUSINESS_TRANSACTION : '' }}">
                <input type="hidden" name="current_ca_cash_journal" id="current_ca_cash_journal" value="{{ isset($data['park_fbcj'][0]->CURRENT_CA_CASH_JOURNAL) ? $data['park_fbcj'][0]->CURRENT_CA_CASH_JOURNAL : '' }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Purpose / Notes <span class="red">*</span></label>
                <input type="text" readonly class="form-control" id="purpose_park" value="{{ isset($data['data_json']->purpose) ? $data['data_json']->purpose : '' }}" name="purpose" required placeholder="insert your purpose / notes on requesting cash advance"/>
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
        <div class="mb-3" style="overflow-x:auto;">
            <table class="table table-bordered " id="detailReqForm" >
                <thead>
                    <tr>
                        <th style="width:400px;">Description</th>
                        <th style="width:100px;" class="thead-apri">Quantity</th>
                        <th style="width:150px;" class="thead-apri">Price</th>
                        <th class="" >Amount</th>
                        <th class="" >Currency</th>
                        <th class=""  style="background:#f5f5f5">&nbsp;</th>
                        <th class="" >Adjustment <br/>Qty</th>
                        <th class=""  >Adjustment <br/>Price</th>
                        <th class="" >Adjustment <br/>Tax</th>
                        <th class=""  >Adjustment <br/>Amount</th>
                        <th class="" >Over</th>
                        <th class="" >Under</th>
                        <th class="" >Remarks</th>
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
                                <textarea style="width:200px;" name="description[]" required readonly rows="1" id="description<?php echo '-'.$i;?>" class="form-control td-apri">{{ isset($data['data_json']->description[$i]) ? $data['data_json']->description[$i] : '' }}</textarea>
                            </td>
                            <td>
                                <input required type="text" readonly value="{{ isset($data['data_json']->quantity[$i]) ? $data['data_json']->quantity[$i] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="quantity[]" id="quantity<?php echo '-'.$i;?>">
                            </td>
                            <td>
                                <input type="text" readonly style="min-width:150px;" readonly value="{{ isset($data['data_json']->harga_parse[$i]) ? $data['data_json']->harga_parse[$i] : ''  }}" class="form-control td-apri" name="harga_parse[]" id="harga_parse<?php echo '-'.$i;?>">
                                <input type="hidden" name="harga[]" value="{{ isset($data['data_json']->harga[$i]) ? $data['data_json']->harga[$i] : ''  }}" id="harga<?php echo '-'.$i;?>">
                            </td>
                            <td>
                                <input type="text" readonly value="{{ isset($data['data_json']->amount_parse[$i]) ? $data['data_json']->amount_parse[$i] : ''  }}" class="form-control td-apri" style="min-width:200px;" name="amount_parse[]" id="amount_parse<?php echo '-'.$i;?>" readonly="">

                                <input type="hidden" name="amount[]" id="amount-<?php echo $i;?>" value="{{ isset($data['data_json']->amount[$i]) ? $data['data_json']->amount[$i] : ''  }}">
                            </td>
                            <td>
                                <input required readonly type="text" readonly value="{{ isset($data['data_json']->currency[0]) ? $data['data_json']->currency[0] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="currency[]" id="currency<?php echo '-'.$i;?>">
                            </td>
                            <td style="background:#f5f5f5">
                                &nbsp;
                            </td>
                            {{-- ADJUSTMENT STARTS HERE --}}
                            <td>
                                {{-- ADJUSTMENT QTY --}}
                                <input required readonly type="text" value="{{ isset($data['data_json']->quantity_adjustment[$i]) ? $data['data_json']->quantity_adjustment[$i] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="quantity_adjustment[]" id="quantity_adjustment<?php echo '-'.$i;?>" onkeyup="CalculateTotal(this);">
                            </td>
                            <td>
                                {{-- ADJUSTMENT PRICE --}}
                                <input type="text" readonly style="min-width:150px;" value="{{ isset($data['data_json']->harga_parse_adjustment[$i]) ? $data['data_json']->harga_parse_adjustment[$i] : ''  }}" class="form-control td-apri" name="harga_parse_adjustment[]" id="harga_parse_adjustment<?php echo '-'.$i;?>" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" placeholder="0.00">
                                <input type="hidden" name="harga_adjustment[]" value="{{ isset($data['data_json']->harga_adjustment[$i]) ? $data['data_json']->harga_adjustment[$i] : ''  }}" id="harga_parse_adjustment<?php echo '-'.$i;?>_value">
                            </td>
                            <td>
                                {{-- ADJUSTMENT TAX --}}
                                <input required readonly style="min-width:150px;" type="text" value="{{ isset($data['data_json']->tax_parse_adjustment[$i]) ? $data['data_json']->tax_parse_adjustment[$i] : ''  }}" class="form-control td-apri" style="min-width:75px;" name="tax_parse_adjustment[]" id="tax_parse_adjustment<?php echo '-'.$i;?>" maxlength="12" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode==0 || event.charCode==46 || event.charCode==17 || event.charCode==86 || event.charCode==67" onkeyup="keyUpCurrency(this.value, this.id); CalculateTotal(this);" onfocusout="checkAngkaNol(this.value, this.id);" placeholder="0.00" >
                                <input type="hidden" name="tax_adjustment[]" id="tax_parse_adjustment<?php echo '-'.$i;?>_value" value="{{ isset($data['data_json']->tax_adjustment[$i]) ? $data['data_json']->tax_adjustment[$i] : ''  }}">
                            </td>
                            <td>
                                {{-- ADJUSTMENT AMOUNT --}}
                                <input required style="min-width:150px;" type="text" readonly value="{{ isset($data['data_json']->amount_parse_adjustment[$i]) ? $data['data_json']->amount_parse_adjustment[$i] : 1 }}" class="form-control td-apri" style="min-width:75px;" name="amount_parse_adjustment[]" id="amount_parse_adjustment<?php echo '-'.$i;?>">
                                <input type="hidden" name="amount_parse_adjustment_value[]" value="{{ isset($data['data_json']->amount_parse_adjustment_value[$i]) ? $data['data_json']->amount_parse_adjustment_value[$i] : ''  }}" id="amount_parse_adjustment<?php echo '-'.$i;?>_value">
                            </td>
                            <td>
                                {{-- ADJUSTMENT OVER --}}
                                <input type="text" readonly value="{{ isset($data['data_json']->adjustment_over_parse[$i]) ? $data['data_json']->adjustment_over_parse[$i] : 1 }}" class="form-control td-apri" style="min-width:150px;" name="adjustment_over_parse[]" id="adjustment_over<?php echo '-'.$i;?>" >
                                <input type="hidden" name="adjustment_over[]" id="adjustment_over<?php echo '-'.$i;?>_value" value="{{ isset($data['data_json']->adjustment_over[$i]) ? $data['data_json']->adjustment_over[$i] : 0 }}">
                            </td>
                            <td>
                                {{-- ADJUSTMENT UNDER --}}
                                <input type="text" readonly value="{{ isset($data['data_json']->adjustment_under_parse[$i]) ? $data['data_json']->adjustment_under_parse[$i] : 1 }}"  class="form-control td-apri" style="min-width:150px;" name="adjustment_under_parse[]" id="adjustment_under<?php echo '-'.$i;?>" >
                                <input type="hidden" name="adjustment_under[]" id="adjustment_under<?php echo '-'.$i;?>_value" value="{{ isset($data['data_json']->adjustment_under[$i]) ? $data['data_json']->adjustment_under[$i] : 0 }}">
                            </td>

                            <td>
                                <input readonly type="text" name="remarks_adjustment[]" id="remarks_adjustment<?php echo '-'.$i;?>" class="form-control" style="width:200px;" value="{{ isset($data['data_json']->remarks_adjustment[$i]) ? $data['data_json']->remarks_adjustment[$i] : '' }}">
                            </td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4 offset-4 text-center">

            <div class="form-group mt-3 col-4 col-xs-12 float-left ">
                <label class="control-label">Total Amount</label>
                <div class="col-12">
                    <h2><strong>{{ isset($data['data_json']->grandTotal) ? number_format($data['data_json']->grandTotal) : 0 }}</strong></h2>
                </div>
            </div>
            @if (isset($data['data_json']->final_adjustment))
            <div class="form-group mt-3 col-4 col-xs-12  float-left ">
                <label class="control-label">Adjusted</label>
                <div class="col-12">
                    <i class="fa fa-arrow-right"></i>
                </div>
            </div>
            <div class="form-group mt-3 col-4 col-xs-12 float-left  ">
                <label class="control-label">Final Total Amount</label>
                <div class="col-12">
                    <h2><strong>{{ isset($data['data_json']->final_adjustment) ? number_format($data['data_json']->final_adjustment) : 0 }}</strong></h2>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if ($data['readonly']==false)
        @if(isset($data['data_form']->STATUS_APPROVAL) && strtolower($data['data_form']->STATUS_APPROVAL) == 'finished')
            @if ($is_parked)
                <div class="alert alert-warning" role="alert" id="parked_alert" >
                    <h4 class="alert-heading">Information!</h4>
                    <p>This Cash Advance is already parked with document number : <b>{{ $data['park_fbcj'][0]->CURRENT_CA_NUMBER }}</b></p>
                </div>
            @elseif($is_posted)
                <div class="alert alert-success" role="alert" id="parked_alert" >
                    <h4 class="alert-heading">Information!</h4>
                    <p>This Cash Advance is already posted at {{ date('d F Y',strtotime($is_posted_date))}}</b></p>
                    <p>This Cash Advance Document Number is <b>{{ $data['park_fbcj'][0]->CURRENT_CA_NUMBER }}</b></p>
                </div>
            @endif


            <div class="form-group">
                <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">{{ $string_keterangan }}</h3>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Cash Journal</label>
                        <select class="form-control select2 select2-ca" id="cajo_number" required disabled name="cajo_number">
                            <option value="" selected disabled></option>
                        </select>
                        <div class="pt-1">
                            <small class="text-muted loading-cajo-select" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Finding available Cash Journal ...</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Business Transaction Type</label>
                        <select class="form-control select2 select2-ca" id="business_trans" required disabled name="business_trans">
                            <option value="" selected disabled></option>
                        </select>
                        <div class="pt-1">
                            <small class="text-muted loading-butrans-select" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}">&nbsp; Finding available Business Transaction ...</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Cash Advance Category</label>
                        @php
                            $current_status_category=isset($data['park_fbcj'][0]->STATUS_CATEGORY) ? $data['park_fbcj'][0]->STATUS_CATEGORY : NULL ;
                        @endphp
                        <select class="form-control select2 select2-ca" id="ca_category" required name="ca_category" disabled>
                            <option value="" disabled></option>
                            <option value="PO" {{ ($current_status_category == 'PO') ? 'selected' : ''}}>PO</option>
                            <option value="NON_PO"  {{ ($current_status_category == 'NON_PO') ? 'selected' : ''}}>Non PO</option>
                            <option value="FORM_TRIP"  {{ ($current_status_category == 'FORM_TRIP') ? 'selected' : ''}}>Form Trip</option>
                        </select>
                    </div>
                </div>
            </div>
            @if (!$is_posted)
                <div class="">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="form-control btn btn-success text-white">Save and Post to SAP</button>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
</form>
<input type="hidden" id="is_parked" value="{{ ($is_parked) ? 1 : 0 }}">
<script>
    $(function(){
        // Initialize select2 for all Class
        $('.select2').select2({
          placeholder: "Select an option",
          allowClear: true
        });

        $('.loading-cajo-select').prop('hidden', false);
        $('.loading-butrans-select').prop('hidden', false);
        setTimeout(function(){
            var company =  $('#company_code').val();
            var currency = $('#currency_park').val();
            var current_value = $("#current_ca_cash_journal").val();
            $.ajax({
                url : '/finance/cash-advance/fetch-cash-journal',
                type : 'GET',
                data : {'company_code': company, 'currency' : currency},
                success : function(response){
                  var newOption = [];
                  if(response.hasOwnProperty('data') && response.data){
                    $.each(response.data, function(index, data){
                      newOption[index] = new Option(`${data.CURRENCY} - ${data.CAJO_NUMBER} - ${data.CAJO_NAME}`, data.CAJO_NUMBER, false, false);
                    });
                  }

                  setTimeout(function(){
                    $('.loading-cajo-select').prop('hidden', true);
                    $('#cajo_number').append(newOption).trigger('change');

                    if(current_value){
                        $("#cajo_number").val(current_value);
                    }

                  },400)

                },
                error : function(xhr){
                  $('.loading-cajo-select').prop('hidden', true);
                  Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
                  console.log("EXCEPTION OCCURED IN CAJO FETCH DATA");
                }
            });

            $.ajax({
                url : '/finance/cash-advance/fetch-business-transaction',
                type : 'GET',
                data : {'company_code': company},
                success : function(response){
                  var newOption = [];
                  var current_value = $("#current_ca_business_transaction").val();
                  if(response.hasOwnProperty('data') && response.data){
                    $.each(response.data, function(index, data){
                      newOption[index] = new Option(`${data.GL_ACCOUNT} - ${data.LONG_TEXT}`, data.TRANSACT_NUMBER, false, false);

                    });
                  }

                  setTimeout(function(){
                    $('.loading-butrans-select').prop('hidden', true);
                    $('#business_trans').append(newOption).trigger('change');
                    if(current_value){
                        $("#business_trans").val(current_value);
                    }
                  },400)

                },
                error : function(xhr){
                  $('.loading-butrans-select').prop('hidden', true);
                  Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
                  console.log("EXCEPTION OCCURED IN BUSINESS TRANS FETCH DATA");
                }
            });
        }, 300);

        $('form#modalDetailAjax').submit(function(e){
            e.preventDefault();
            var form = this;
            var is_parked = $("#is_parked").val(); // flag kalau CA sudah di park, maka nanti ini mentrigger delete existing CA

            $data = {
                'company_code' : $('#company_code').val(),
                'plant' : $('#plant_park').val(),
                'currency' : $('#currency_park').val(),
                'cajo_number' : $('#cajo_number').val(),
                'business_trans' : $('#business_trans').val(),
                'purpose' : $('#purpose_park').val(),
                'ca_number' : $('#ca_number_park').val(),
                'grand_total' : $('#grand_total_park').val(),
                'ca_category' : $("#ca_category").val(),
                'is_parked' : is_parked
            }

            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Post to SAP'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    title: "Sending data...",
                    text: "Please wait!",
                    imageUrl: form.getAttribute("data-loader-file"),
                    imageWidth: 140,
                    imageHeight: 140,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                setTimeout(function(){
                    $.ajax({
                        url : '/finance/cash-advance/post-cash-advance',
                        type : 'GET',
                        data : {...$data},
                        success : function(response){
                        if(response.hasOwnProperty('status') && response.status == 'success'){
                            Swal.fire({
                            icon: 'success',
                            title: 'Cash Advance Posted to SAP',
                            text: response.message,
                            }).then((result) =>{
                                location.reload();
                            });
                        } else {
                            Swal.fire('Oops..', response.message, 'error');
                        }
                        },
                        error : function(xhr){
                        Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
                        console.log("EXCEPTION OCCURED IN SENDING POSTED DATA TO SAP");
                        }
                    });
                }, 500);
                return false;
                }
            });


        })
    })
</script>
