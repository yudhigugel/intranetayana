<form method="POST" id="formApproveModal" enctype="multipart/form-data" data-url-post="{{url('finance/add-business-partner/approval/save-with-form-data')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
    {{ csrf_field() }}
    <!-- Keperluan Untuk Submit Ke Controller -->
    <input type="hidden" name="employee_id" id="employee_id" value="">
    <input type="hidden" name="status_approval" id="status_approval" value="" />
    <input type="hidden" name="type_form" id="modal_type_form" value="">
    <input type="hidden" name="approval_level_previous" id="approval_level_previous" value="{{$data['data_form']->APPROVAL_LEVEL}}}">
    <input type="hidden" name="aksi" id="aksi" value="approve">

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Form Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Created Date</label>
                <input type="text" value="{{date('d F Y')}}" class="form-control" readonly/>
            </div>
            <div class="col-md-6">
                <label>Form Number</label>
                <input type="text" value="{{$data['data_form']->UID}}" name="form_number" class="form-control" readonly/>
                <input type="hidden"  id="idJoin" value="{{$data['data_form']->UID}}#{{$data['data_form']->APPROVAL_LEVEL}}">
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
                <input type="text" value="{{$data['data_json']->Requestor_Cost_Center_ID}}" name="Requestor_Cost_Center_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Department</label>
                <input type="text" value="{{$data['data_json']->Requestor_Department}}" name="Requestor_Department" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Plant ID</label>
                <input type="text" value="{{@$data['data_json']->Requestor_Plant_ID}}" name="Requestor_Plant_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Division</label>
                <input type="text" value="{{@$data['data_json']->Requestor_Division}}" name="Requestor_Division" class="form-control" readonly />
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
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Data Information  </h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Company Code <span class="red">*</span></label>
                <input type="text"  class="form-control" name="company_code" required value="{{$data['data_json']->company_code}}" readonly/>
            </div>
            <div class="col-md-6">
                <label>Request Reason <span class="red">*</span></label>
                <input type="text" class="form-control" name="request_reason" required placeholder="insert reason for adding new business partner.." value="{{$data['data_json']->request_reason}}" readonly/>
            </div>
        </div>
        {{-- <div class="row mb-3">
            <div class="col-md-12">
                <label>Vendor Number <span class="red">*</span></label>
                <input type="text" class="form-control"  name="vendor_number" placeholder="insert vendor number.." required value="{{$data['data_json']->vendor_number}}" />
                <small class="form-text text-muted">Filled by Master Data IT</small>
            </div>
        </div> --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Business Partner Number <span class="red">*</span></label>
                <input type="text" class="form-control" name="bp_number" placeholder="insert business partner number.." required value="{{$data['data_json']->bp_number}}"/>
                <small class="form-text text-muted">Filled by Master Data IT</small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Vendor Type <span class="red">*</span></label>
                <div class="form-check">
                    <label class="form-check-label">
                    <input  type="radio" class="form-check-input vendor_type" name="vendor_type" value="PT" @if($data['data_json']->vendor_type=="PT") checked @else disabled @endif >PT
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="CV" @if($data['data_json']->vendor_type=="CV") checked @else disabled @endif>CV
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="Perorangan" @if($data['data_json']->vendor_type=="Perorangan") checked @else disabled @endif>Perorangan
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input vendor_type" name="vendor_type" value="Other" @if($data['data_json']->vendor_type=="Other") checked @else disabled @endif>Other
                    <i class="input-helper"></i></label>
                    <input type="text" name="vendor_type_other" id="vendor_type_other"  class="form-control col-md-3"  @if($data['data_json']->vendor_type!=="Other") style="display:none;" @endif  placeholder="insert other vendor type.." value="{{$data['data_json']->vendor_type_other}}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> General Information  </h3>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Vendor Name <span class="red">*</span></label>
                <input type="text"  class="form-control" name="vendor_name" required  placeholder="insert vendor name.."  value="{{$data['data_json']->vendor_name}}" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Vendor Address <span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_address" required placeholder="insert vendor full address.."  value="{{$data['data_json']->vendor_address}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Building <span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_building" required placeholder="insert vendor building.."  value="{{$data['data_json']->vendor_building}}" readonly/>
            </div>
            <div class="col-md-6">
                <label>Province<span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_province" required placeholder="insert vendor province.."  value="{{$data['data_json']->vendor_province}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>City<span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_city" required placeholder="insert vendor city"  value="{{$data['data_json']->vendor_city}}" readonly/>
            </div>
            <div class="col-md-6">
                <label>District<span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_district" required placeholder="insert vendor district.."  value="{{$data['data_json']->vendor_district}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            {{-- <div class="col-md-6">
                <label>Subdistrict<span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_subdistrict" required placeholder="insert vendor subdistrict.."  value="{{$data['data_json']->vendor_subdistrict}}" readonly/>
            </div> --}}
            <div class="col-md-6">
                <label>Postal Code<span class="red">*</span></label>
                <input type="text" class="form-control" name="vendor_postcode" required placeholder="insert postal code.. "  value="{{$data['data_json']->vendor_postcode}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Phone Number <span class="red">*</span></label>
                <input type="text"  class="form-control" name="vendor_phone" required  placeholder="insert vendor phone.."  value="{{$data['data_json']->vendor_phone}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Company Email <span class="red">*</span></label>
                <input type="email"  class="form-control" name="company_email" required  placeholder="insert vendor company email.."  value="{{$data['data_json']->company_email}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label style="float:none;display:block;clear:both;">PIC Name<span class="red">*</span></label>
                <div class="col-md-3 float-left">
                    <select name="pic_title" class="form-control" required disabled>
                        <option value="Mr" @if($data['data_json']->pic_title=="Mr") selected @endif >Mr.</option>
                        <option value="Mrs" @if($data['data_json']->pic_title=="Mrs") selected @endif>Mrs.</option>
                        <option value="Ms" @if($data['data_json']->pic_title=="Ms") selected @endif>Ms.</option>
                    </select>
                </div>
                <div class="col-md-9 float-left">
                    <input type="text"  class="form-control" name="pic_name" required  placeholder="insert vendor PIC full name.."  value="{{$data['data_json']->pic_name}}" readonly/>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>PIC Phone Number <span class="red">*</span></label>
                <input type="text"  class="form-control" name="pic_phone" required  placeholder="insert vendor PIC phone.."  value="{{$data['data_json']->pic_phone}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>PIC Email <span class="red">*</span></label>
                <input type="email"  class="form-control" name="pic_email" required  placeholder="insert vendor PIC email.."  value="{{$data['data_json']->pic_email}}" readonly/>
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
                    <input  type="radio" class="form-check-input" name="npwp" value="Yes" @if($data['data_json']->npwp=="Yes") checked @else disabled @endif >Yes
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="npwp" value="No" @if($data['data_json']->npwp=="No") checked @else disabled @endif>No
                    <i class="input-helper"></i></label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>PKP <span class="red">*</span></label>
                <div class="form-check">
                    <label class="form-check-label">
                    <input  type="radio" class="form-check-input" name="pkp" value="Yes" @if($data['data_json']->pkp=="Yes") checked @else disabled @endif>Yes
                    <i class="input-helper"></i></label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                    <input  type="radio" class="form-check-input" name="pkp" value="No" @if($data['data_json']->pkp=="No") checked @else disabled @endif>No
                    <i class="input-helper"></i></label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>NPWP Number <span class="red">*</span></label>
                <input type="text"  class="form-control" name="npwp_number" required  placeholder="insert vendor NPWP number.."  value="{{$data['data_json']->npwp_number}}" readonly/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Bank Information  </h3>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Bank Name <span class="red">*</span></label>
                <input type="text"  class="form-control" name="bank_name" required  placeholder="insert vendor bank name.."  value="{{$data['data_json']->bank_name}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Bank Account Number <span class="red">*</span></label>
                <input type="text"  class="form-control" name="bank_account_number" required  placeholder="insert vendor bank account number.." value="{{$data['data_json']->bank_account_number}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Swift Code Number</label>
                <input type="text"  class="form-control" name="swift_code" required  placeholder="insert vendor bank swift code number.." value="{{$data['data_json']->swift_code}}" readonly/>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Account Holder Name</label>
                <input type="text"  class="form-control" name="account_holder_name" required  placeholder="insert vendor account holder name.." value="{{$data['data_json']->account_holder_name}}" readonly/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Attachment </h3>
        <table class="table table-bordered datatable fileList" id="fileList" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th style="min-width:90px;">Form No</th>
                    <th style="min-width:90px;">File Type</th>
                    <th style="min-width:90px;">File Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['data_file'] as $file )
                    <tr>
                        <td style="min-width:90px;">{{$file->UID}}</td>
                        <td style="min-width:90px;">{{$file->FIELD_TYPE}}</td>
                        <td style="min-width:90px;"><a href="{{url('upload/business_partner/'.$file->FILE_NAME)}}" target="_blank">{{$file->FILE_NAME}}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Approval / Reject Reason</label>
            <textarea name="reason" id="reason" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            {{-- <input type="submit" id="submitApproveModal" value="APPROVE REQUEST" class="btn btn-success text-white"> --}}
            <button type="button" id="approveDetailModal"  onClick="actionFormModal('Approve','APPROVED')" class="btn btn-success text-white">APPROVE REQUEST</button>
            <button type="button" id="rejectDetailModal"  onClick="actionFormModal('Reject', 'REJECTED')" class="btn btn-danger">REJECT REQUEST</button>
        </div>
    </div>
</form>
<script>

    $("#formApproveModal").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url_post=$("#formApproveModal").attr('data-url-post');
        var loader=$("#formApproveModal").attr('data-loader-file');
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

            if (data.code==200) {
                if (data.message) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    }).then((result) =>{
                    location.reload();
                    });

                }
            } else {

                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
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


    $(document).ready( function () {
        $('#fileList').DataTable();
    } );

    function actionFormModal(type, type2){

        var url_post="{{url('finance/add-business-partner/approval/save-with-form-data')}}";
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

                if(type2=="APPROVED"){
                    var cekForm = $('#formApproveModal')[0].checkValidity(); //cek apakah semua field yang required sudah diisi
                    if(cekForm){
                        $('#formApproveModal').submit(); // submit form, lari ke function form on submit di atas
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please complete the form to approve',
                        });
                    }

                }else{
                    jQuery.ajax({
                        type:"POST",
                        url: "/finance/add-business-partner/approval/submitApprovalForm",
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

            }
        })



    }


</script>
