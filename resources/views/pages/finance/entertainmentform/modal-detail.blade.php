<form id="popup-detail">
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Created Date</label>
                <input type="text" value="{{date('d F Y - H:i',strtotime($data['data_form']->INSERT_DATE))}}" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Request Number</label>
                <input type="text" value="{{$data['data_form']->UID}}" class="form-control" readonly/>
                <input type="hidden" name="type_form" id="type_form" value="{{$data['data_form']->TYPE_FORM}}">
                <input type="hidden" name="idJoin" id="idJoin" value="{{$data['data_form']->UID}}#{{$data['data_form']->APPROVAL_LEVEL}}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Requestor Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Name</label>
                <input type="text" value="{{$data['data_json']->Requestor_Name}}" name="Requestor_Name" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Company</label>
                <input type="text" value="{{$data['data_json']->Requestor_Company}}" name="Requestor_Company" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Employee ID</label>
                <input type="text" value="{{$data['data_json']->Requestor_Employee_ID}}" name="Requestor_Employee_ID" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Territory</label>
                <input type="text" value="{{$data['data_json']->Requestor_Territory}}" name="Requestor_Territory" class="form-control" readonly />
                <input type="hidden" name="Requestor_Territory_ID" value="{{$data['data_json']->Requestor_Territory_ID}}" id="Requestor_Territory_ID">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Cost Center ID</label>
                <input type="text" value="{{@$data['data_json']->Requestor_Cost_Center_ID}}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Department</label>
                <input type="text" value="{{@$data['data_json']->Requestor_Department}}" name="Requestor_Department" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">

            </div>
            <div class="col-md-6">
                <label>Requestor Job Position</label>
                <input type="text" value="{{@$data['data_json']->Requestor_Job_Title}}" name="Requestor_Job_Title" class="form-control" readonly />
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Client Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Company / Affiliation <span class="red">*</span></label>
                <input type="text"  class="form-control" name="Company_Affiliation" required placeholder="insert company/affiliation.." value="{{$data['data_json']->Company_Affiliation}}"  readonly/>
            </div>
            <div class="col-md-6">
                <label>Entertainment Reason <span class="red">*</span></label>
                <input type="text" class="form-control" name="Entertainment_Reason" required placeholder="insert reason.." value="{{$data['data_json']->Entertainment_Reason}}"  readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Name <span class="red">*</span></label>
                <input type="text" class="form-control" name="Name" required id="Name" placeholder="insert name.." value="{{$data['data_json']->Name}}" readonly />
            </div>
            <div class="col-md-6">
                <label>Potential <span class="red">*</span></label>
                <input type="text"  class="form-control" name="Potential" required placeholder="insert potential.." value="{{$data['data_json']->Potential}}" readonly />
            </div>
        </div>
    </div>

    <div class="form-group" id="detail-section-3">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Entertainment Type</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Entertainment Type <span class="red">*</span></label>
                <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input entertainment_type" value="In House"  {{  ($data['data_json']->Entertainment_Type=="In House")? 'checked' : 'disabled' }}>
                      In House
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input entertainment_type" value="In Group"  {{  ($data['data_json']->Entertainment_Type=="In Group")? 'checked' : 'disabled' }}>
                      In Group
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input entertainment_type" value="Outside"  {{  ($data['data_json']->Entertainment_Type=="Outside")? 'checked' : 'disabled' }}>
                      Outside
                    <i class="input-helper"></i></label>
                </div>
            </div>
            <div class="col-md-6">
                <label>Entertainment Options <span class="red">*</span></label>
                <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="Entertainment_Options" value="F&B" checked>
                      F&B
                    <i class="input-helper"></i></label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" id="detail-section-4">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Details</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Entertainment Date <span class="red">*</span></label>
                <input type="text" name="Entertainment_Date" id="datepicker" class="form-control" required value="{{$data['data_json']->Entertainment_Date}}" readonly >
            </div>
            <div class="col-md-6">
                <label>Entertainment Time <span class="red">*</span></label>
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" id="timepicker" name="Entertainment_Time" onkeydown="event.preventDefault()" required value="{{$data['data_json']->Entertainment_Time}}"  readonly>
                    <div class="input-group-addon">
                          <span class="glyphicon glyphicon-time"></span>
                    </div>
                  </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Location <span class="red">*</span></label><span id="spinnerLocation" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                <input type="text" name="Location" id="cmbLocation" class="form-control" value="{{@$data['data_json']->Location_desc}}" readonly>
                <input type="text" name="Location_Alternative" id="Location_Alternative" class="form-control" value="{{@$data['data_json']->Location_Alternative}}" readonly>
            </div>
            <div class="col-md-6">
                <label>Type <span class="red">*</span></label>
                <input type="text" name="Type" id="Type" class="form-control" value="{{@$data['data_json']->Type}}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label id="labelSBU">SBU <span class="red">*</span></label><span id="spinnerSBU" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                <input type="text" name="SBU" id="cmbSBU" class="form-control" value="{{@$data['data_json']->SBU_desc}}" readonly>

                <label id="labelOutlet_Alternative" style="display:none;">Outlet <span class="red">*</span></label>
                <input type="text" name="Outlet_Alternative" id="Outlet_Alternative" class="form-control" value="{{@$data['data_json']->Outlet_Alternative}}" readonly>
            </div>
            <div class="col-md-6">
                <label>No. of Guests (Incl. Requestor) <span class="red">*</span></label>
                <input type="number"  class="form-control" name="No_Guest" required placeholder="insert number of guest.." value="{{$data['data_json']->No_Guest}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label id="labelOutlet">Outlet <span class="red">*</span></label><span id="spinnerOutlet" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>
                <input type="text" name="Outlet" id="cmbOutlet" class="form-control" value="{{@$data['data_json']->Outlet_desc}}" readonly>
            </div>
            <div class="col-md-6">
                <label id="labelLimit_Estimated">Limit / Estimated Amount (IDR) <span class="red">*</span></label>
                <input type="text"  class="form-control" id="Limit_Estimated"  name="Limit_Estimated" required placeholder="insert number of amount.." value="{{number_format($data['data_json']->Limit_Estimated)}}" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Benefit Entitlement <span class="red">*</span></label>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="Benefit_Entitlement[]" @php if(in_array("Food",$data['data_json']->Benefit_Entitlement)){ echo "checked";}  @endphp  value="Food" disabled>
                    Food
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input"  name="Benefit_Entitlement[]" value="Alcohol Beverage" @php if(in_array("Alcohol Beverage",$data['data_json']->Benefit_Entitlement)){ echo "checked";}  @endphp disabled>
                    Alcohol Beverage
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input"  name="Benefit_Entitlement[]" value="Non Alcohol Beverage" @php if(in_array("Non Alcohol Beverage",$data['data_json']->Benefit_Entitlement)){ echo "checked";}  @endphp disabled>
                    Non Alcohol Beverage
                    <i class="input-helper"></i></label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" id="detail-section-5">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Remarks</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Remarks</label>
                <textarea name="Remarks" class="form-control" id="" cols="30" rows="10" readonly>{{$data['data_json']->Remarks}} </textarea>
            </div>
        </div>
    </div>
    @if($data['action']=="approve")

    <div class="row mb-3">
        <div class="col-md-12">
            <label>Reason</label>
            <textarea name="reason" id="reason" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" id="approveDetailModal"  onClick="actionFormModal('Approve','APPROVED')" class="btn btn-success text-white">APPROVE REQUEST</button>
            <button type="button" id="rejectDetailModal"  onClick="actionFormModal('Reject', 'REJECTED')" class="btn btn-danger">REJECT REQUEST</button>
        </div>
    </div>
    @endif
</form>

<script>
    $(document).ready(function(){
        var entertainment_type=$(".entertainment_type:checked").val();

        console.log(entertainment_type);
        if(entertainment_type=="In House"){

            $("#popup-detail #labelLimit_Estimated").hide();
            $("#popup-detail #Limit_Estimated").hide();
        }

        if(entertainment_type=="Outside"){
            $("#popup-detail #cmbLocation").hide();
            $("#popup-detail #cmbSBU").hide();
            $("#popup-detail #cmbOutlet").hide();
            $("#popup-detail #labelOutlet").hide();
            $("#popup-detail #labelSBU").hide();


            $("#popup-detail #labelOutlet_Alternative").show();
            $("#popup-detail #Outlet_Alternative").show();
            $("#popup-detail #Location_Alternative").show();
        }else{
            $("#popup-detail #cmbLocation").show();
            $("#popup-detail #cmbSBU").show();
            $("#popup-detail #cmbOutlet").show();
            $("#popup-detail #labelOutlet").show();
            $("#popup-detail #labelSBU").show();

            $("#popup-detail #labelOutlet_Alternative").hide();
            $("#popup-detail #Outlet_Alternative").hide();
            $("#popup-detail    #Location_Alternative").hide();
        }
    })

    function actionFormModal(type, type2){

        var url_post="{{url('finance/entertainmentForm/approval/save-with-form-data')}}";
        var loader="{{ url('/image/gif/cube.gif') }}"

        var idJoin = $("#idJoin").val();
        var updateBy =  $('#updateBy').val();
        var inputValue = $("#reason").val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, '+type
            }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    type:"POST",
                    url: "/finance/entertainmentForm/approval/submitApprovalForm",
                    data:
                    {
                        "form_id": idJoin, //Pisahkan dengan ;
                        "employe_id": updateBy, //emp id yg melakukan approve
                        "status_approval": type2, //APPROVE or REJECT
                        "type_form": $('#type_form').val(), //type_form
                        "reason":inputValue
                    },
                    success: function(data) {
                            swal({
                                title: type+": ",
                                text: "Form  "+type2+" Successfully",
                                type: "success",
                                closeOnConfirm: false
                                }, function () {
                                    swal.close();
                                    location.reload();
                            });
                    }
                });
            }
        })



        }
</script>
