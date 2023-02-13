<form method="POST" id="formApproveModal" enctype="multipart/form-data" data-url-post="{{url('finance/add-material-master/approval/save-with-form-data')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
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
                <input type="text" class="form-control" id="insert_date" readonly value="{{ date('d F Y H:i',strtotime($data['data_form']->INSERT_DATE))}}"/>
            </div>
            <div class="col-md-6">
                <label>Form Number</label>
                <input type="text"  class="form-control" id="form_number" name="form_number" value="{{$data['data_form']->UID}}" readonly/>
                <input type="hidden"  id="idJoin" value="{{$data['data_form']->UID}}#{{$data['data_form']->APPROVAL_LEVEL}}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Requestor Information</h3>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Name</label>
                <input type="text" name="Requestor_Name" class="form-control" id="Requestor_Name" value="{{ strtoupper($data['data_json']->Requestor_Name) }}" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Company</label>
                <input type="text" name="Requestor_Company" class="form-control" id="Requestor_Company" value="{{ strtoupper($data['data_json']->Requestor_Company) }}" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Employee ID</label>
                <input type="text" name="Requestor_Employee_ID" class="form-control" id="Requestor_Employee_ID" value="{{ strtoupper($data['data_json']->Requestor_Employee_ID) }}" readonly/>
            </div>
            <div class="col-md-6">
                <label>Requestor Territory</label>
                <input type="text" name="Requestor_Territory" id="Requestor_Territory" class="form-control" value="{{ strtoupper($data['data_json']->Requestor_Territory) }}" readonly />
                <input type="hidden" name="Requestor_Territory_ID" id="Requestor_Territory_ID" value="{{$data['data_json']->Requestor_Territory_ID}}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Cost Center ID</label>
                <input type="text"name="Requestor_Cost_Center_ID" class="form-control" id="Requestor_Cost_Center_ID" value="{{ strtoupper($data['data_json']->Requestor_Cost_Center_ID) }}" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Derpatment</label>
                <input type="text" name="Requestor_Department" class="form-control" id="Requestor_Department" value="{{ strtoupper($data['data_json']->Requestor_Department) }}" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Requestor Plant ID</label>
                <input type="text" value="{{ strtoupper(@$data['data_json']->Requestor_Plant_ID) }}" name="Requestor_Plant_ID" class="form-control" readonly />
            </div>
            <div class="col-md-6">
                <label>Requestor Division</label>
                <input type="text" value="{{ strtoupper(@$data['data_json']->Requestor_Division) }}" name="Requestor_Division" class="form-control" readonly />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                @if(isset($data['data_json']->pos_type))
                <div class="mb-2">
                    <label>Request POS Status <span class="red">*</span></label>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="toggle-switch">
                            <input @if(isset($data['data_json']->pos_type) && $data['data_json']->pos_type == 'POS') checked @endif type="radio" disabled>
                            <span class="toggle-slider round"></span>
                        </label>
                        <small class="ml-2">POS</small>
                    </div>
                    <div class="col-6">
                        <label class="toggle-switch">
                            <input @if(isset($data['data_json']->pos_type) && $data['data_json']->pos_type == 'NONPOS') checked @endif type="radio" disabled>
                            <span class="toggle-slider round"></span>
                        </label>
                        <small class="ml-2">Non POS</small>
                    </div>
                    <input type="hidden" name="pos_type" value="{{ isset($data['data_json']->pos_type) ? $data['data_json']->pos_type : '' }}">
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <label>Requestor Job Position</label>
                <input type="text" value="{{ strtoupper(@$data['data_json']->Requestor_Job_Title) }}" name="Requestor_Job_Title" class="form-control" readonly />
            </div>
        </div>
    </div>
    @if($data['is_cross_plant'])
    @php
        $custom_plant=(isset($data['data_custom_request'][0]->PLANT))? $data['data_custom_request'][0]->PLANT : NULL;
    @endphp
    <div class="form-group">
        <div class="alert alert-warning" role="alert">
            <p style="font-size:14px;"><i class="fa fa-warning"></i> This request has a different Plant destination. This request is for Plant <span style="font-size:16px;font-weight:bold;">{{$custom_plant}}</span></p>

        </div>
    </div>
    @endif

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Material Information  </h3>
        <div class="row mb-3">
            @php

            if(isset($data['data_json']->Is_Recipe) && strtoupper($data['data_json']->Is_Recipe == 'Y') && $data['data_form']->APPROVAL_LEVEL==1) {
            //Jika Approval Purchasing
                $readonly="readonly";
                $required="";
                $placeholder="";
            } else if($data['data_form']->APPROVAL_LEVEL==1){
                $readonly="";
                $required="";
                $placeholder="";
            } else{
            //Jika selain purchasing
                $readonly="readonly";
                $required="";
                $placeholder="";
            }
            @endphp
            <div class="col-md-6">
                <label>Material Name <span class="red">*</span></label>
                <input type="text"  class="form-control" name="material_name" id="material_name" value="{{ strtoupper($data['data_json']->material_name) }}" required {{$readonly}} />
            </div>
            <div class="col-md-6">
                <label>Material Unit <span class="red">*</span></label>
                <input type="text" class="form-control" name="material_unit" id="material_unit" value="{{ strtoupper($data['data_json']->material_unit) }}" required {{$readonly}}/>
            </div>
        </div>
        @if(isset($data['data_json']->request_notes))
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Request Notes</label>
                <textarea maxlength="40" class="form-control" style="text-transform: uppercase" rows="2" name="request_notes" placeholder="insert request notes here.." readonly>{{ isset($data['data_json']->request_notes) ? strtoupper($data['data_json']->request_notes) : '' }}</textarea>
            </div>
        </div>
        @endif

        @if(isset($data['data_json']->Is_Recipe) && strtoupper($data['data_json']->Is_Recipe == 'Y'))
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Material Type Request <span class="red">*</span></label>
                <div class="row py-2">
                    <input type="hidden" name="material_type_request" value="{{ isset($data['data_json']->material_type_request) ? $data['data_json']->material_type_request : '' }}">
                    @if(isset($data['material_type_recipe']))
                        @php
                            $mtr_type_request = isset($data['data_json']->material_type_request) ? $data['data_json']->material_type_request : '';
                        @endphp
                        @foreach($data['material_type_recipe'] as $mtr)
                        @php
                            $mtr_type = isset($mtr['MATERIAL_TYPE']) ? $mtr['MATERIAL_TYPE'] : '0';
                        @endphp
                        <div class="col-6">
                            <label class="toggle-switch">
                                <input @if($mtr_type_request == $mtr_type) checked @endif disabled type="radio" id="mtype-{{ isset($mtr['MATERIAL_TYPE']) ? $mtr['MATERIAL_TYPE'] : rand(0,10) }}">
                                <span class="toggle-slider round"></span>
                            </label>
                            <small class="ml-2">{{ isset($mtr['MATERIAL_TYPE_DESC']) ? $mtr['MATERIAL_TYPE_DESC'] : 'Unknown' }}</small>
                        </div>
                        @endforeach
                    @else 
                    <div class="col-12">
                        <h6>No material type data available</h6>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label style="position: relative;">
                    Material Group Request <span class="red mr-3">*</span>
                    <div style="position: absolute;top: -7px;right: -25px;">
                        <small class="text-muted loading-mat-group" hidden><img style="width: 25px" src="{{ asset('image/loader.gif') }}"></small>
                    </div>
                </label>
                <div class="mb-1">
                    @php
                        $mtr_grp_request = isset($data['data_json']->material_group_request) ? $data['data_json']->material_group_request : '';
                    @endphp
                    <input type="hidden" name="material_group_request" value="{{ $mtr_grp_request }}">
                    <select class="form-control select2" id="material_group_request_select" style="width: 100%" disabled>
                        <option value="" selected disabled>{{ $mtr_grp_request }}</option>
                    </select>
                </div>
                {{--<small class="text-muted">Material group will dynamically change based on material type selection</small>--}}
            </div>
        </div>
        @endif


        <div class="row mb-3">
            @php
                if(isset($data['data_json']->Is_Recipe) && strtoupper($data['data_json']->Is_Recipe == 'Y')) {
                    if($data['data_form']->APPROVAL_LEVEL==1){
                    //Jika Approval Accounting
                        $readonly="readonly";
                        $required="";
                        $placeholder="";
                    }else if ($data['data_form']->APPROVAL_LEVEL==2){
                    // Jika Approval IT
                        $readonly="";
                        $required="required";
                        $placeholder="Insert material number..";
                    }else{
                        $readonly="readonly";
                        $required="";
                        $placeholder="";
                    }
                } else {
                    if($data['data_form']->APPROVAL_LEVEL==2){
                    //Jika Approval Accounting
                        $readonly="readonly";
                        $required="";
                        $placeholder="";
                    }else if ($data['data_form']->APPROVAL_LEVEL==3){
                    // Jika Approval IT
                        $readonly="";
                        $required="required";
                        $placeholder="Insert material number..";
                    }else{
                        $readonly="readonly";
                        $required="";
                        $placeholder="";
                    }
                }
            @endphp
            <div class="col-md-12">
                <label>Material Number @if($required=="required") <span class="red">*</span>  @endif</label>
                <input type="text" class="form-control" name="material_number" id="material_number" value="{{ strtoupper($data['data_json']->material_number) }}" placeholder="{{$placeholder}}" {{$readonly}} {{$required}}/>
                <small class="form-text text-muted">Filled by Master Data IT</small>
            </div>
        </div>
        @php
        // Start validasi untuk material group dkk
        if(isset($data['data_json']->Is_Recipe) && strtoupper($data['data_json']->Is_Recipe == 'Y')) {
            if($data['data_form']->APPROVAL_LEVEL==1){
            //Jika Approval Accounting
                $readonly="";
                $required="required";
                $placeholder="Insert material number..";
            }else if ($data['data_form']->APPROVAL_LEVEL==2){
            // Jika Approval IT
                $readonly="disabled";
                $required="";
                $placeholder="";
            }else{
            // jika purchasing
                $readonly="disabled";
                $required="";
                $placeholder="";
            }
        } else {
            if($data['data_form']->APPROVAL_LEVEL==2){
            //Jika Approval Accounting
                $readonly="";
                $required="required";
                $placeholder="Insert material number..";
            }else if ($data['data_form']->APPROVAL_LEVEL==3){
            // Jika Approval IT
                $readonly="disabled";
                $required="";
                $placeholder="";
            }else{
            // jika purchasing
                $readonly="disabled";
                $required="";
                $placeholder="";
            }
        }
        @endphp
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Type <span class="red">*</span></label>
                @php
                    $existing_material_type=array();
                if(!empty($data['data_json']->material_type)){
                    $existing_material_type = array_values($data['data_json']->material_type);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_type[]" id="material_type" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @foreach ($data['material_type'] as $material_type)
                        <option value="{{$material_type['MATERIAL_TYPE']}}-{{$material_type['MATERIAL_TYPE_DESC']}}" @if(in_array($material_type['MATERIAL_TYPE'].'-'.$material_type['MATERIAL_TYPE_DESC'] , $existing_material_type)) selected @endif>
                            {{$material_type['MATERIAL_TYPE']}} - {{$material_type['MATERIAL_TYPE_DESC']}}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Filled by Accounting</small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Group <span class="red">*</span></label>
                @php
                    $existing_material_group=array();
                if(!empty($data['data_json']->material_group)){
                    $existing_material_group = array_values($data['data_json']->material_group);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_group[]" id="material_group" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @if($readonly=='disabled')
                    @foreach ($data['material_group'] as $material_group)
                        <option value="{{$material_group['MATKL']}}-{{$material_group['WGBEZ']}}"  @if (in_array($material_group['MATKL'].'-'.$material_group['WGBEZ'], $existing_material_group)) selected @endif>{{$material_group['MATKL']}} - {{$material_group['WGBEZ']}}</option>
                    @endforeach
                    @endif
                  </select>
                <small class="form-text text-muted">Filled by Accounting
                    <span class="pl-2 text-primary fetch-matgroup-text" hidden>Fetching data <i class="fa fa-spinner fa-spin"></i></span>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Valuation Class <span class="red">*</span></label>
                @php
                $existing_material_valuation=array();
                if(!empty($data['data_json']->material_valuation)){
                    $existing_material_valuation = array_values($data['data_json']->material_valuation);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_valuation[]" id="material_valuation" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @if($readonly=='disabled')
                    @foreach ($data['material_valuation'] as $material_valuation)
                        <option value="{{$material_valuation['BKLAS']}}-{{$material_valuation['BKBEZ']}}" @if (in_array($material_valuation['BKLAS'].'-'.$material_valuation['BKBEZ'], $existing_material_valuation)) selected @endif>{{$material_valuation['BKLAS']}} - {{$material_valuation['BKBEZ']}}</option>
                    @endforeach
                    @endif
                  </select>
                <small class="form-text text-muted">Filled by Accounting
                    <span class="pl-2 text-primary fetch-valuation-text" hidden>Fetching data <i class="fa fa-spinner fa-spin"></i></span>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Image </label>
                @php
                    $url_asset=url('upload/material').'/';
                    if(!empty($data['data_json']->material_image)){
                        $mat_img="<a href='".$url_asset.$data['data_json']->material_image."' target='_blank'>".$data['data_json']->material_image."</a>";
                    }else{
                        $mat_img="<span class='text-muted'>No Attachment Available</span>";
                    }
                @endphp
                <p id="material_image"><?php echo $mat_img;?> </p>
            </div>
        </div>
        <div class="row mb-3" style="display: none;">
            <div class="col-md-12">
                <label>Quotation File <span class="red">*</span></label>
                @php

                if(!empty($data['data_json']->quotation)){
                    $quot="<a href='".$url_asset.$data['data_json']->quotation."' target='_blank'>".$data['data_json']->quotation."</a>";
                }else{
                    $quot="<span class='text-muted'>No Attachment Available</span>";
                }
            @endphp
                <p id="quotation"><?php echo $quot;?></p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Approve / Reject Reason </label>
                <textarea name="reason" id="reason" cols="30" rows="5" class="form-control"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                {{-- <input type="submit" id="submitApproveModal" value="APPROVE REQUEST" class="btn btn-success text-white"> --}}
                <button type="button" id="approveDetailModal" onClick="actionFormModal('Approve','APPROVED')" class="btn btn-success text-white">APPROVE REQUEST</button>
                <button type="button" id="rejectDetailModal" onClick="actionFormModal('Reject', 'REJECTED')" class="btn btn-danger">REJECT REQUEST</button>
            </div>
        </div>
    </div>
</form>
<script>
     $("#modalApprove #bodyModalApprove #material_type").select2({
        placeholder: "Select an option",
        allowClear: true,
        dropdownParent: $("#formApproveModal")
        // multiple:true
    }).on('select2:select', function(e){
        $('.fetch-valuation-text').prop('hidden', false);
        $('.fetch-matgroup-text').prop('hidden', false);

        $('#bodyModalApprove #material_group').select2('destroy').html('<option value="" selected disabled></option>');
        $('#bodyModalApprove #material_group').select2({
            placeholder: "Select an option",
            allowClear: true,
            dropdownParent: $("#formApproveModal")
        });

        $('#bodyModalApprove #material_valuation').select2('destroy').html('<option value="" selected disabled></option>');
        $('#bodyModalApprove #material_valuation').select2({
            placeholder: "Select an option",
            allowClear: true,
            dropdownParent: $("#formApproveModal")
        });

        var data = e.params.data.id || null;
        var req_plant = $('input[name="Requestor_Plant_ID"]').val() || '';
        $.ajax({
            url : '/finance/add-material-master/modal-approve-detail',
            type : 'GET',
            contentType : 'application/json',
            dataType : 'json',
            data : {'material_type': data, 'plant_requestor': req_plant, 'refer':'mtrl_approve'},
            success : function(response){
              var newOptionMatGroup = [];
              var newOptionValClass = [];

              if(response.hasOwnProperty('MAT_GROUP') && response.MAT_GROUP && response.MAT_GROUP.length){
                $.each(response.MAT_GROUP, function(index, data){
                  newOptionMatGroup[index] = new Option(`${data.MATKL} - ${data.WGBEZ}`, `${data.MATKL}-${data.WGBEZ}`, false, false);
                });
              }

              if(response.hasOwnProperty('VAL_CLASS') && response.VAL_CLASS && response.VAL_CLASS.length){
                $.each(response.VAL_CLASS, function(index, data){
                  newOptionValClass[index] = new Option(`${data.BKLAS} - ${data.BKBEZ}`, `${data.BKLAS}-${data.BKBEZ}`, false, false);
                });
              }

              setTimeout(function(){
                $('#bodyModalApprove #material_group').append(newOptionMatGroup).trigger('change');
                $('#bodyModalApprove #material_valuation').append(newOptionValClass).trigger('change');
              },100)

            },
            error : function(xhr){
              Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
              console.log("EXCEPTION OCCURED IN MODAL APPROVE DETAIL")
            },
            complete : function(){
              $('.fetch-valuation-text').prop('hidden', true);
              $('.fetch-matgroup-text').prop('hidden', true);
            }
        });


    });

    $("#modalApprove #bodyModalApprove #material_group").select2({
        placeholder: "Select an option",
        allowClear: true,
        dropdownParent: $("#formApproveModal")
        // multiple:true
    });
    $("#modalApprove #bodyModalApprove #material_valuation").select2({
        placeholder: "Select an option",
        allowClear: true,
        dropdownParent: $("#formApproveModal")
        // multiple:true
    });
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

    function actionFormModal(type, type2){

        var url_post="{{url('finance/add-material-master/approval/save-with-form-data')}}";
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
                        url: "/finance/add-material-master/approval/submitApprovalForm",
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
