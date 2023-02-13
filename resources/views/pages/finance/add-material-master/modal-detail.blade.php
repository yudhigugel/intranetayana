<form id="modalDetailAjax">
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
                <input type="text" name="Requestor_Territory" id="Requestor_Territory" class="form-control" value="{{$data['data_json']->Requestor_Territory}}" readonly />
                <input type="hidden" name="Requestor_Territory_ID" id="Requestor_Territory_ID" value="{{ strtoupper($data['data_json']->Requestor_Territory_ID) }}">
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
            <div class="col-md-6">
                <label>Material Name <span class="red">*</span></label>
                <input type="text"  class="form-control" name="material_name" id="material_name" value="{{ strtoupper($data['data_json']->material_name) }}" required readonly />
            </div>
            <div class="col-md-6">
                <label>Material Unit <span class="red">*</span></label>
                <input type="text" class="form-control" name="material_unit" id="material_unit" value="{{ strtoupper($data['data_json']->material_unit) }}" required readonly/>
            </div>
        </div>
        @if(isset($data['data_json']->request_notes))
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Request Notes</label>
                <textarea readonly maxlength="40" class="form-control" style="text-transform: uppercase" rows="2" name="request_notes" placeholder="insert request notes here..">{{ isset($data['data_json']->request_notes) ? strtoupper($data['data_json']->request_notes) : '' }}</textarea>
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

                $readonly="readonly";
                $required="";
                $placeholder="";

            @endphp
            <div class="col-md-12">
                <label>Material Number </label>
                <input type="text" class="form-control" name="material_number" id="material_number" value="{{ strtoupper($data['data_json']->material_number) }}" placeholder="{{$placeholder}}" {{$readonly}} {{$required}}/>
                <small class="form-text text-muted">Filled by Master Data IT</small>
            </div>
        </div>
        @php

            $readonly="disabled";
            $required="";
            $placeholder="";

        @endphp
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Type</label>
                @php
                    $existing_material_type=array();
                if(!empty($data['data_json']->material_type)){
                    $existing_material_type = array_values($data['data_json']->material_type);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_type[]" id="material_type" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @foreach ($data['material_type'] as $material_type)
                        <option value="{{$material_type['MATERIAL_TYPE']}}-{{$material_type['MATERIAL_TYPE_DESC']}}" @if (in_array($material_type['MATERIAL_TYPE'].'-'.$material_type['MATERIAL_TYPE_DESC'] , $existing_material_type)) selected @endif>
                            {{$material_type['MATERIAL_TYPE']}} - {{$material_type['MATERIAL_TYPE_DESC']}}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Filled by Accounting</small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Group </label>
                @php
                    $existing_material_group=array();
                if(!empty($data['data_json']->material_group)){
                    $existing_material_group = array_values($data['data_json']->material_group);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_group[]" id="material_group" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @foreach ($data['material_group'] as $material_group)
                        <option value="{{$material_group['MATKL']}}-{{$material_group['WGBEZ']}}"  @if (in_array($material_group['MATKL'].'-'.$material_group['WGBEZ'], $existing_material_group)) selected @endif>{{$material_group['MATKL']}} - {{$material_group['WGBEZ']}}</option>
                    @endforeach
                  </select>
                <small class="form-text text-muted">Filled by Accounting</small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Material Valuation Class </label>
                @php
                $existing_material_valuation=array();
                if(!empty($data['data_json']->material_valuation)){
                    $existing_material_valuation = array_values($data['data_json']->material_valuation);
                }
                @endphp
                <select class="form-control select-2-modal" name="material_valuation[]" id="material_valuation" {{$required}} {{$readonly}}>
                    <option value="" selected></option>
                    @foreach ($data['material_valuation'] as $material_valuation)
                        <option value="{{$material_valuation['BKLAS']}}-{{$material_valuation['BKBEZ']}}" @if (in_array($material_valuation['BKLAS'].'-'.$material_valuation['BKBEZ'], $existing_material_valuation)) selected @endif>{{$material_valuation['BKLAS']}} - {{$material_valuation['BKBEZ']}}</option>
                    @endforeach
                  </select>
                <small class="form-text text-muted">Filled by Accounting</small>
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
        <div class="row mb-3" style="display:none;">
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
    </div>
</form>
<script>
     $("#modalDetailAjax #material_type").select2({
        placeholder: "To be filled",
        // multiple:true
    });
    $("#modalDetailAjax #material_group").select2({
        placeholder: "To be filled",
        // multiple:true
    });
    $("#modalDetailAjax #material_valuation").select2({
        placeholder: "To be filled",
        // multiple:true
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
        })



    }

</script>
