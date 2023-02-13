@extends('layouts.default')

@section('title', 'Report Purchase Requisition Market List')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/template/css/card-form-step/main.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
{{-- <link rel="stylesheet" href="/css/sweetalert.min.css"> --}}
@endsection
@section('styles')
<style>
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #a7afb7 !important;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color:  #a7afb7;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #a7afb7;
}

.red{
    color:red !Important;
}

.dataTables_wrapper{
    position: relative;
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

.table{
    color:#000 !important;
}
td.purpose {
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
}

select[readonly].select2-hidden-accessible + .select2-container {
    pointer-events: none;
    touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
    background: #e9ecef;
    box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
    display: none;
}

.modal-obstruct:nth-of-type(odd) {
    z-index: 1054 !important;
}
.modal-backdrop.show:nth-of-type(even) {
    z-index: 1052 !important;
}
#table-marketlist_info{
    text-align: left;
}
.relative-td {
    position: relative;
}
.select-plain {
    padding: 0 !important;
    border: none !important;
    outline: none !important;
    font-size: 0.75rem !important;
    color: #6c7293 !important;
    max-width: 100px !important;
    text-align: left !important;
    margin: 0 -5px !important;
}
.modal .dataTables_info{
    float: left;
}
.modal .dataTables_paginate{
    float: right;
}
#requestList_wrapper {
    position: relative;
}
.dataTables_wrapper .dataTables_processing {
    top: 5% !important;
    left: 42% !important;
}
.remove-click {
    pointer-events: none;
    cursor: default;
    text-decoration: none;
    color: black;
}
.wrapper-table{
    position: relative;
}
.wrapper-table:after {
    clear: both;
    float: none;
    content: " ";
    display: block;
}
.grand-total-request{
    max-width: 350px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.dataTables_info{
    float: left;
}
.table-container-h {
    overflow: auto;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition Market List</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Report</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Report List</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <!-- <label class="col-sm-2 col-form-label">Request Date</label> -->
                                <div class="col-sm-2">
                                    <select class="form-control select-plain" name="filter_type" id="filter_type">
                                      <option value="REQUEST_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'REQUEST_DATE') selected default @endif>Request Date</option>
                                      <option value="DELIVERY_DATE" @if(isset($data['filter_date_type']) && $data['filter_date_type'] == 'DELIVERY_DATE') selected default @endif>Delivery Date</option>
                                    </select>
                                    <div class="mt-1">
                                        <small class="text-muted">* Click to change filter</small>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="date_from" id="date_from" value="{{ $data['request_date_from'] }}" placeholder="Date From...">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="date_to" id="date_to" value="{{ $data['request_date_to'] }}" placeholder="Date To...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="status" name="status">
                                        <option value="">All</option>
                                        <option value="Waiting" {{ ($data['status']=="Waiting")? 'selected' : '' }}>Waiting for Approval</option>
                                        <option value="Finished" {{ ($data['status']=="Finished")? 'selected' : '' }}>Finished</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/purchase-requisition-marketlist/report')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap; width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 10% !important">PR Number</th>
                            {{--<th style="width: 8% !important">PR Detail</th>--}}
                            <th style="width: 9% !important">Form Number</th>
                            <th style="width: 8% !important">Status PR</th>
                            <th style="width: 12% !important">Last Approver PR</th>
                            {{--<th >Reason</th>--}}
                            <th style="width: 10% !important">Req. Date</th>
                            <th style="width: 8% !important">Deliv. Date</th>

                            {{--<th>Purpose</th>--}}
                            {{--<th>Tracking No.</th>--}}
                            {{--<th>Tracking No. Desc</th>--}}
                            <th style="width: 12% !important">Cost Center</th>
                            <th style="width: 8% !important">SLOC</th>
                            <th style="width: 8% !important">Grand Total</th>
                            <th style="width: 8% !important">PO Number</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="modal-detail-content">
			<div class="modal-header">
				<div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalFileLabel">Purchase Requisition Market List Detail </h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFile">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPO" tabindex="-1" role="dialog" aria-labelledby="modalPOLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <h5 class="modal-title mr-3" id="modalPOLabel">Purchase Order Detail</h5>
                    <div class="overlay loader-modal">
                      <img width="10%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="bodymodalPO">
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">
<input type="hidden" name="deptid" id="deptid" class="form-control" value="{{$data['department_id']}}">
<input type="hidden" name="midjobid" id="midjobid" class="form-control" value="{{$data['midjob_id']}}">
<input type="hidden" name="costcenter" id="costcenter" class="form-control" value="{{$data['costcenter']}}">
<input type="hidden" name="type_form" id="type_form" class="form-control" value="{{$data['form_code']}}">
@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/ellipsis.js"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/js/card-form-step/main.js"></script>
<script src="/template/js/card-form-step/plugins.js"></script>
<script type="text/javascript">

    // Change scrollbar to existing modal
    $(document).on('hidden.bs.modal', '.modal',
    () => $('.modal:visible').length && $(document.body).addClass('modal-open'));

    var is_error_template = false;
    var data_item = [];
    var template_selected_code = '';
    var template_selected_code_temp = '';
    var is_hide_back_btn = false;
    var is_detail_popup = false;
    $('#marketlist-template').on('show.bs.modal', function(){
        if(is_detail_popup){
            var template_code_detail = $('#Template_Code_Detail').val() || '';
            if(template_code_detail){
                template_selected_code = template_code_detail;
                template_selected_code_temp = template_code_detail;
            }
        }

        try {
            $('#template-content-load').html('');
            $('#spinner-template').prop('hidden', false);
            $('#spinner-template-failed').prop('hidden', true);
        } catch(error){}
        if(template_selected_code){
            $('#spinner-template').prop('hidden', true);
            var element_to_add = `<h6 style="line-height: 1.5">You have choosen ${template_selected_code} as your template before <br> Now preparing items &nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i></h6>`;
            $(element_to_add).appendTo('#template-content-load');
            is_hide_back_btn = true;
            setTimeout(function(){
                $('.btn-next').prop('disabled', false);
                $('.btn-next').trigger('click');
            }, 3000);
        }
        else {
            is_hide_back_btn = false;
            var emp_id = $('input[name="Requestor_Employee_ID"]').val() || 0;
            $.ajax({
                url: '/finance/purchase-requisition-marketlist/request',
                method: 'GET',
                data: {'type':'recipe-template', 'employee_id': emp_id},
                dataType: 'json',
                success : function(response){
                    if(response.hasOwnProperty('data') && response.data.length > 0){
                        is_error_template = false;
                        var element_to_add = '';
                        var col = 'col-4 col-md-4';
                        for(var loop=0;loop<response.data.length;loop++){
                            element_to_add += `<div class="switch-field ${col}">
                                <input type="radio" id="radio-${loop+1}" name="template-selection" value="${response.data[loop]}"/>
                                <label for="radio-${loop+1}">${response.data[loop]}</label>
                            </div>`;
                        }
                        $(element_to_add).appendTo('#template-content-load');
                    }
                },
                error: function(xhr){
                    $('#spinner-template-failed').prop('hidden', false);
                    is_error_template = true;
                },
                complete: function(){
                    $('#spinner-template').prop('hidden', true);
                    setTimeout(function(){
                        if(!is_error_template)
                            $('.btn-next').prop('disabled', false);
                    }, 500)
                }
            })
        }
    });

    $(document).on('click', '.btn-next', function(){
        data_item = [];
        if(is_hide_back_btn)
            $('.btn-back').prop('hidden', true);
        else 
            $('.btn-back').prop('hidden', false);

        var check_element = document.getElementsByClassName('toRight').length;
        if(check_element > 0){
            $('.btn-finish').prop('disabled', true);
            try {
                if(template_selected_code){
                    var template_select = template_selected_code;
                } else {
                    var template_select = document.querySelector('input[name="template-selection"]:checked').value;
                    template_selected_code_temp = template_select;
                }
            } catch(error){
                var template_select = '';
            }

            $.ajax({
                url: '/finance/purchase-requisition-marketlist/request',
                method: 'GET',
                data: {'type':'recipe-template-item', 'template': template_select},
                dataType: 'json',
                success : function(response){
                    $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    setTimeout(function(){
                        $('#table-wrapper').prop('hidden', false);
                        try{
                          var table = $('#table-marketlist').DataTable({
                            "dom":'<"abs-search row align-items-end mb-2 mt-4" <"button-export-wrapper col-12" <"row align-items-end" <"col-8 btn-show-all text-left"B> <"col-4"fl> >>> <"wrapper-table"rtip >',
                            "paging": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "buttons": [],
                            "data": response.data,
                            "order": [[ 0, "" ]],
                            "columns": [
                               { "data": "MATERIALNAME",
                                 className: 'text-left',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="materialItemTemplate[]" value="'+id+'"><input type="hidden" name="materialItemCode[]" value="'+full.SAPMATERIALCODE+'">';
                                 },
                                 width : "30%"
                               },
                               { "data": "UOM",
                                 className: 'text-center',
                                 render: function(id, type, full, meta){
                                    return id+'<input type="hidden" name="unitItemTemplate[]" value="'+id+'">';
                                 },
                                 width : "7%",
                               },
                               { "data": null, 
                                 className : 'relative-td',
                                 "defaultContent": "<input type='text' value='0' oninput='qtyInput(this)' class='form-control' name='qtyItemTemplate[]' id='qtyItemTemplate' placeholder='Input Qty...'/><div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>",
                                 width : "15%",
                                 orderable: false,
                                 sortable: false
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' readonly class='form-control' name='costItemTemplate[]' id='costItemTemplate' placeholder='Autofill...' value='0.00' />",
                                 width : "15%",
                                 orderable: false,
                                 sortable: false
                               },
                               { "data": null, 
                                 "defaultContent": "<input type='text' class='form-control' oninput='noteInput(this)' name='noteItemTemplate[]' id='noteItemTemplate' placeholder='Add Note...'/>",
                                 width : "28%",
                                 orderable: false,
                                 sortable: false
                               },
                            ],
                            // drawCallback : function(settings){
                            //     if(settings.json){
                            //         console.log(data_item, settings.json);
                            //         data_item.filter(function(obj, index){
                            //             var sm = settings.json.filter(x => x.SAPMATERIALCODE == obj.mtnumber);
                            //             console.log(sm);
                            //         });
                            //     }
                                    
                            // },
                            rowCallback: function (row, data) {
                                var input = document.getElementsByName('marketlistMaterialNumber[]');
                                if(input.length > 0 && template_selected_code){
                                    var material_code_selected = data.SAPMATERIALCODE;
                                    var input = document.getElementsByName('marketlistMaterialNumber[]');
                                    for (var i = 0; i < input.length; i++) {
                                        var a = input[i];
                                        if(a.value == material_code_selected){
                                            $(row).addClass('bg-success');
                                            var material_number = document.getElementsByName('marketlistMaterialNumber[]')[i].value,
                                            material_name = document.getElementsByName('marketlistMaterialName[]')[i].value,
                                            material_unit = document.getElementsByName('marketlistMaterialUnit[]')[i].value,
                                            last_price = document.getElementsByName('marketlistMaterialLastPriceFormatted[]')[i].value,
                                            last_price_plain = document.getElementsByName('marketlistMaterialLastPrice[]')[i].value,
                                            notes = document.getElementsByName('marketlistMaterialNote[]')[i].value,
                                            qty = document.getElementsByName('marketlistMaterialQty[]')[i].value;

                                            try {
                                                $(row).find('[name="qtyItemTemplate[]"]').val(qty);
                                                $(row).find('[name="costItemTemplate[]"]').val(last_price);
                                                $(row).find('[name="noteItemTemplate[]"]').val(notes);
                                            } catch(error){
                                                console.log(error);
                                            }
                                        }
                                    }
                                }
                            },
                            initComplete : function(settings, json){
                                var data_table = this.api().rows().data();
                                var input = document.getElementsByName('marketlistMaterialNumber[]');
                                if(input.length > 0 && template_selected_code){
                                    for (var i = 0; i < input.length; i++) {
                                        data_table.each(function (data, index) {
                                            var material_code_selected = data.SAPMATERIALCODE;
                                            var a = input[i];
                                            if(a.value == material_code_selected){
                                                var material_number = document.getElementsByName('marketlistMaterialNumber[]')[i].value,
                                                material_name = document.getElementsByName('marketlistMaterialName[]')[i].value,
                                                material_unit = document.getElementsByName('marketlistMaterialUnit[]')[i].value,
                                                last_price = document.getElementsByName('marketlistMaterialLastPriceFormatted[]')[i].value,
                                                last_price_plain = document.getElementsByName('marketlistMaterialLastPrice[]')[i].value,
                                                notes = document.getElementsByName('marketlistMaterialNote[]')[i].value,
                                                qty = document.getElementsByName('marketlistMaterialQty[]')[i].value;

                                                var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice':last_price, 'mtlastpriceplain': last_price_plain, 'mtnote': notes, 'mtqty': qty};
                                                // data_item = data_item.filter(x => x.mtnumber != material_number);
                                                data_item.push(obj_to_add);
                                            }
                                        });
                                    }
                                }

                                setTimeout(function(){
                                    $('.btn-finish').prop('disabled', false);
                                }, 500);
                                $(`<div style="cursor: pointer" class="mb-2">
                                      <div>
                                        <h6 style="margin: 0">
                                          <a class="text-primary btn-show-hide-material"><span class="icon-show-hide" data-status="hidden"><i class="fa fa-eye"></i></span>&nbsp;&nbsp;<span id="show-hide-text">Show</span> All Materials</a>
                                        </h6>
                                      </div>
                                      <small class="text-muted">* This toggle will also show inactive materials</small>
                                    </div>`).appendTo('.btn-show-all');
                            }
                          });

                          $(document).on('click','.btn-show-hide-material', function(){
                            var status_now = $('.icon-show-hide', this).attr('data-status')
                            if(status_now){
                                var selected_template = $('#Template_Code_Detail').val() || '';
                                if(!selected_template)
                                    selected_template = template_select;

                                if(status_now == 'hidden'){
                                    $('.btn-finish').prop('disabled', true);
                                    $('.btn-show-hide-material').addClass('remove-click');
                                    var element_to_add = (`<div class="wrapper-loading" style="position:absolute;top:0;left:0;width:100%;height:100%;"><h6 style="position: relative; top: -3em">Loading data... &nbsp;<i class="fa fa-spin fa-spinner"></i></h6></div>`);
                                    $(element_to_add).appendTo('.wrapper-table');
                                    setTimeout(function(){
                                        table.ajax.url(`?type=recipe-template-item&show_inactive=1&template=${selected_template}`).load(function(data){
                                            $('.btn-show-hide-material').removeClass('text-primary');
                                            $('.btn-show-hide-material').addClass('text-success');
                                            $('#show-hide-text').text('Showing');
                                            $('.icon-show-hide').attr('data-status', 'showed')
                                            $('.icon-show-hide').html('<i class="fa fa-check"></i>');
                                            $('.wrapper-loading').remove();
                                            $('.btn-finish').prop('disabled', false);
                                            $('.btn-show-hide-material').removeClass('remove-click');
                                            // console.log(Object.values($('#table-marketlist').DataTable().rows().data()), data_item);
                                        });
                                    }, 500);
                                } 
                                // else if(status_now == 'showed'){
                                //     $('.btn-finish').prop('disabled', true);
                                //     $('.btn-show-hide-material').addClass('remove-click');
                                //     var element_to_add = (`<div class="wrapper-loading" style="position:absolute;top:0;left:0;width:100%;height:100%;"><h6 style="position: relative; top: -3em">Loading data... &nbsp;<i class="fa fa-spin fa-spinner"></i></h6></div>`);
                                //     $(element_to_add).appendTo('.wrapper-table');
                                //     setTimeout(function(){
                                //         table.ajax.url(`?type=recipe-template-item&template=${selected_template}`).load(function(data){
                                //             $('#show-hide-text').text('Show');
                                //             $('.icon-show-hide').attr('data-status', 'hidden')
                                //             $('.icon-show-hide').html('<i class="fa fa-eye"></i>');
                                //             $('.wrapper-loading').remove();
                                //             $('.btn-finish').prop('disabled', false);
                                //             $('.btn-show-hide-material').removeClass('remove-click');
                                //             // console.log(Object.values($('#table-marketlist').DataTable().rows().data()), data_item);
                                //         });
                                //     }, 500)
                                // }
                            }
                          })

                        } catch(error){
                            Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                            console.log(error.message)
                        }
                    }, 500);
                },
                error: function(xhr){
                    // $('#marketlist-template > .modal-dialog').addClass('modal-lg');
                    $('#marketlist-template').modal('hide');
                    setTimeout(function(){
                        Swal.fire('Fetch Error', 'Error while retrieving data from the server, please try again in a moment', 'error');
                    }, 500);
                },
                complete: function(){
                    $('#spinner-template-item').prop('hidden', true);
                    // $('.btn-finish').prop('disabled', false);
                }
            });
        }

    });

    function clear_form_elements(class_name) {
        jQuery("."+class_name).find(':input').each(function() {
            switch(this.type) {
                case 'password':
                case 'text':
                case 'textarea':
                case 'file':
                case 'select-one':
                case 'select-multiple':
                case 'date':
                case 'number':
                case 'tel':
                case 'email':
                    jQuery(this).val('');
                    break;
                case 'checkbox':
                case 'radio':
                    this.checked = false;
                    break;
            }
        });
    }

    $("#toggleVendor").change(function(){
        if(this.checked){
            $(".formVendor").show();
            $("#vendor_reason").attr('required','');
            $("#vendor_currency").attr('required','');
            $("#vendor_search").attr('required','');
            $('#vendor_currency').select2('destroy').select2();
        }else{
            $(".formVendor").hide();
            $("#vendor_reason").removeAttr('required');
            $("#vendor_currency").removeAttr('required');
            $("#vendor_search").removeAttr('required');
            clear_form_elements('formVendor');
        }

    });
    var objFile = {};var objJsonDetail = {}; var objRow = {};

    $('#modalRequest').on('show.bs.modal', function (e) {
        $("#modalRequest .select2").select2({
            dropdownParent: $('#modal-request-content'),
            placeholder: 'Select Data'
        });
    });

    /*****************************************************************************/
    /*****************************************************************************/
    /* FUNCTION DARI INTRANET BIZNET */
    /*****************************************************************************/
    /*****************************************************************************/
    $(document).ready( function () {
        $('#delivDate').datepicker({
            autoclose: true
        });

        $('.datepicker3').datepicker({
            dateFormat: 'dd MM yy',
            minDate: 0,
        });

        $(".datepicker2").datepicker();
        var min_date="{{date('Y-m-d',strtotime('today  -30 days'))}}";
        var values = min_date.split('-');
        var parsed_min_date = new Date(values[0], values[1]-1, values[2]);

        $('#datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            showWeek: true,
            changeYear: true,
            showButtonPanel: true,
            minDate : parsed_min_date
        });
        $('#datepicker').prop('disabled', false);

        var request_date_from =  $('#date_from').val();
        var request_date_to =  $('#date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var deptid =  $('#deptid').val();
        var midjobid= $("#midjobid").val();
        var costcenter= $("#costcenter").val();
        var data_cc = [];
        var data_sloc = [];
        var data_filter_menu = {};
        var table = $('#requestList').DataTable({
            "pageLength": 50,
            "lengthChange": false,
            "searching": false,
            "responsive": true,
            // "dom": '<"dt-buttons"Bfl>rtip',
            "dom":'<"abs-search row align-items-end mb-2" <"button-export-wrapper col-6"Bfl>> <"table-wrapper table-container-h"rt> ip',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/purchase-requisition-marketlist/report/getData",
                "dataSrc": function ( json ) {
                    data_cc = [...new Set(json.data.map(item => `${item.COSTCENTERID} - ${item.COSTCENTER}`))];
                    data_sloc = [...new Set(json.data.map(item => `${item.SLOC_ID} - ${item.SLOC}`))];
                    //Make your callback here.
                    if(json.hasOwnProperty('data') && json.data)
                       return json.data;
                    else
                       return [];
                },
                "data" : function(d){
                    d.employeeId = updateBy;
                    d.deptId = deptid;
                    d.midjobId = midjobid;
                    d.costcenter = costcenter;
                    d.filter = "";
                    d.value = "";
                    d.status = status;
                    d.date_from = request_date_from;
                    d.date_to = request_date_to;
                    d.filter_type = $('#filter_type').val();
                    d.REQUESTOR_NAME = $('.filter-select-1').val();
                    d.SHIP_TO_COST_CENTER = $('.filter-select-costcenter').val();
                    d.SHIP_TO_SLOC = $('.filter-select-sloc').val();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
                    Swal.fire({
                        title: "Oops..",
                        text: error,
                        icon: "error",
                        showConfirmButton: true
                    });
                }
            },
            "language": {
                "processing": ""
            },
            "paging": true,
            "autoWidth": false,
            'info':true,
            "fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "scrollX": false,
            "columns": [
                { "data": "UID",
                    "render": function (id, type, full, meta)
                    {
                        if(id)
                            return id;
                        else
                            return '-';
                    },
                    className : 'text-center',
                    width: '10%'
                },
                { "data": "FORM_NUMBER",
                    "render": function (id, type, full, meta)
                    {
                        if(id){
                            return '<a href="#" class="text-primary" onclick="getFormDetail(\''+id+'\')" data-toggle="modal" data-target="#modalFile">'+id+'</i></a>';
                        } else 
                            return '-';
                    },
                    width: '9%'
                },
                { "data": "STATUS_APPROVAL",
                    "render" : function (id){
                        if(id=="APPROVED" || id=="REQUESTED" || id==""){
                            id="WAITING FOR APPROVAL";
                            return '<a href="javascript:void(0);" style="text-decoration: none; font-weight:bold;"">WAITING</a>';
                        }else if(id=="FINISHED"){
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:green;font-weight:bold;">FINISHED</a>';
                        }else{
                            return '<a href="javascript:void(0);" style="text-decoration: none; color:red;font-weight:bold;">'+id+'</a>';
                        }
                    },
                    width: '8%'
                },
                { "data": "LAST_APPROVAL_NAME",
                  width: '12%'
                },
                { "data": "REQ_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY - HH:mm");
                    },
                    width: '10%'
                },
                { "data": "DELIVERY_DATE" ,
                    "render": function (id, type, full, meta)
                    {
                        return moment(id).format("DD/MM/YYYY");
                    },
                    width: '8%',
                    className: 'text-center'
                },
                // { "data": "PURPOSE" },
                { "data": "COSTCENTER",
                  width: '12%'
                },
                {
                    "data" : "SLOC",
                    "render": function (id, type, full, meta){
                        if(id){
                            return id;
                        }
                        else {
                            return '-';
                        }
                    },
                    width: '8%'
                },
                { "data": "GRANDTOTAL",
                  "render": function (id, type, full, meta)
                    {
                        return number_format(id, 0, '.', ',');
                    },
                  width: '8%',
                  className: 'text-right'

                },
                {
                    "data" : "PO_NUMBER",
                    "render": function (id, type, full, meta){
                        if(id){
                            return '<a href="#" class="text-primary" onclick="getDetailPO(\''+id+'\')" data-toggle="modal" data-target="#modalPO" >'+id+'</a>';
                        }
                        else {
                            return '-';
                        }
                    },
                    className: 'text-center',
                    width: '8%'
                }
            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 1, "desc" ]],
            "columnDefs": [{
              targets: 5,
              // render: $.fn.dataTable.render.ellipsis( 40 ),
              className: "text-left purpose",
              width: "25%"
            }],
            drawCallback : function(settings){
                if(Object.keys(data_filter_menu).length > 0){
                    try {
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                            i : 0;
                        };
          
                        // Total over all pages USD
                        var total_all = 0;
                        $(settings.json.data).each( function (index, value) {
                            if(value.STATUS_APPROVAL.toString().toUpperCase() !== 'REJECTED'){
                                total_all += intVal(value.GRANDTOTAL);
                            }
                        });

                        // Update footer
                        total_all = number_format(total_all, 0, 0 , ',');
                        console.log(total_all);
                        $('.grand-total-request').html(total_all);
                    } catch(error){}
                }
            },
            initComplete: function(settings, json) {
                var table_obj = this.api();
                try {
                    $(`<div class="pb-2 mb-2">
                        <h6 class="text-muted m-0">All Requests Grand Total</h6>
                        <small class="text-danger">* This total excludes rejected requests</small>
                       </div>
                       <div>
                        <h2 class="grand-total-request">0</h2>
                       </div>`).appendTo('.button-export-wrapper');

                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                        i : 0;
                    };
      
                    // Total over all pages USD
                    var total_all = 0;
                    table_obj.column( 8 )
                    .data()
                    .each( function (value, index) {
                        if(table_obj.row(index).data().STATUS_APPROVAL.toString().toUpperCase() !== 'REJECTED'){
                            total_all += intVal(value);
                        }
                    });

                    // Update footer
                    total_all = number_format(total_all, 0, 0 , ',');
                    $('.grand-total-request').html(total_all);
                } catch(error){}

                var requestor = [];
                if(typeof json.data.length != 'undefined' && json.data.length > 0){
                    requestor = [...new Set(json.data.map(element => ({'ALIAS':element.ALIAS,'NAME':element.NAME})).filter(x => x !== null))];
                    requestor = [...new Map(requestor.map((item) => [item["NAME"], item]).filter(x => x[0] !== null && x[0] !== '-')).values()];
                }
                var select = $(`<div class="content-filter col-2 mb-2"><label>Filter By Requestor Name</label><div><select style="width: 100%" class="form-control select2-requestor filter-select-1 mr-3" data-filter="0"><option value="">Pilih Data Disini</option></select></div></div>`)
                .appendTo( $('.abs-search') )
                .on('select2:select', function (e) {
                    var value = e.params.data.id;
                    var val = $.fn.dataTable.util.escapeRegex(
                        value
                    );
                    if(val){
                      data_filter_menu['REQUESTOR_NAME'] = val
                    }
                    const qs = Object.keys(data_filter_menu)
                    .map(key => `${key}=${data_filter_menu[key]}`)
                    .join('&');
                    if(qs)
                      table_obj.ajax.reload();

                }).on("select2:unselecting", function(e) {
                    try {
                      delete data_filter_menu['REQUESTOR_NAME'];
                      const qs = Object.keys(data_filter_menu)
                      .map(key => `${key}=${data_filter_menu[key]}`)
                      .join('&');
                      table_obj.ajax.reload();
                    } catch(error){}
                    $(this).data('state', 'unselected');
                }).on("select2:open", function(e) {
                    try {
                      if ($(this).data('state') === 'unselected') {
                          $(this).removeData('state'); 

                          var self = $(this).find('.select2-requestor')[0];
                          setTimeout(function() {
                              $(self).select2('close');
                          }, 0);
                      }
                    } catch(error){}   
                });

                $(requestor).each( function ( d, j ) {
                    $(`.filter-select-1`).append( '<option value="'+j.ALIAS+'">'+j.NAME+'</option>' )
                });

                $('.select2-requestor').select2({
                  placeholder: 'Choose Data',
                  allowClear: true
                });

                var text_order = 0;
                this.api().columns([6, 7]).every( function (i) {
                    var column = this;
                    var text = [{'name':'Cost Center', 'filter_name':'costcenter'}, {'name':'SLOC', 'filter_name':'sloc'}];
                    var paramName = ['SHIP_TO_COST_CENTER', 'SHIP_TO_SLOC'];
                    var col_length = 'col-2';
                    var select = $(`<div class="content-filter mb-2 ${col_length}"><label>Filter By ${text[text_order].name}</label><div><select style="width: 100%" class="form-control select2-filter-marketlist filter-select-${text[text_order].filter_name} mr-3" data-filter="${text_order}"><option value="">Choose Data</option></select></div></div>`)
                        .appendTo( $('.abs-search') )
                        .on('select2:select', function (e) {
                            var value = e.params.data.id;
                            var target = $(e.target).data('filter');
                            var val = $.fn.dataTable.util.escapeRegex(
                                value
                            );
                            if(val){
                              data_filter_menu[paramName[target]] = val
                            }

                            const qs = Object.keys(data_filter_menu)
                            .map(key => `${key}=${data_filter_menu[key]}`)
                            .join('&');
                            if(qs)
                              table_obj.ajax.url(`report/getData?${qs}`).load();

                        }).on("select2:unselecting", function(e) {
                            var target = $(e.target).data('filter');
                            try {
                              delete data_filter_menu[paramName[target]]
                              const qs = Object.keys(data_filter_menu)
                              .map(key => `${key}=${data_filter_menu[key]}`)
                              .join('&');
                              if(qs)
                                table_obj.ajax.url(`report/getData?${qs}`).load();
                              else
                                table_obj.ajax.url(`report/getData`).load();

                            } catch(error){
                              console.log(error);
                            }
                            $(this).data('state', 'unselected');
                        }).on("select2:open", function(e) {
                            try {
                              if ($(this).data('state') === 'unselected') {
                                  $(this).removeData('state'); 

                                  var self = $(this).find('.select2-filter-marketlist')[0];
                                  setTimeout(function() {
                                      $(self).select2('close');
                                  }, 0);
                              }
                            } catch(error){}   
                        });
                    if(i == 6){
                      $(data_cc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }
                    else if(i == 7){
                      $(data_sloc).each( function ( d, j ) {
                        var split_value = j.split('-')[0].trim() == 'undefined' ? '-' : j.split('-')[0].trim();
                        $(`.filter-select-${text[text_order].filter_name}`).append( '<option value="'+split_value+'">'+j+'</option>');
                      });
                    }

                    text_order++;
                });
                $('.select2-filter-marketlist').select2({
                  placeholder: 'Choose data',
                  allowClear: true
                });
            }
        });

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };
    });
    function getFormDetail(id){
        var segment='view';
        $('#modalFile #bodyModalFile').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition-marketlist/modal-detail')}}", { id : id, action : segment, 'marketlist_type': 'report'}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
            $(".loader-modal").hide();
            $("#modalFile .select2").select2({
                dropdownParent: $('#modal-detail-content'),
                placeholder: 'Select Data'
            });
            is_detail_popup = true;
        });

    }

    $('#modalFile').on('hide.bs.modal', function(){
        is_detail_popup = false;
    })

    function getDetailPO(id){
        var segment='request';
        $('#modalPO #bodymodalPO').html('');
        $(".loader-modal").show();

        $.get("{{url('finance/purchase-requisition/modal-detail-po')}}", { id : id, segment : segment}, function( data ) {
            $('#modalPO #bodymodalPO').html(data);
            $(".loader-modal").hide();
        });

    }

    function getHistoryDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/purchase-requisition/getHistoryApproval',
            dataSrc: "data",
            data: {
                "form_number":id
            },
            success: function(responseSIA){
                if(responseSIA.code == "200"){
                    if(responseSIA.data.length > 0)
                    {
                        listSIA = responseSIA.data;
                        for (i = 0; i < listSIA.length; i++) {
                            if(changeNull(listSIA[i].APPROVAL_DATE) == ""){
                                var APPROVAL_DATE = "";
                            }
                            else{
                                var APPROVAL_DATE = moment(listSIA[i].APPROVAL_DATE).format("DD/MM/YYYY - HH:mm");
                            }
                            tr += '<tr>';
                            tr += '<td>'+changeNull(listSIA[i].LEVEL_APPROVAL)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].TYPE_DESC)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].EMPLOYEE_NAME)+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].STATUS_APPROVAL)+'</td>';
                            tr += '<td>'+APPROVAL_DATE+'</td>';
                            tr += '<td>'+changeNull(listSIA[i].REASON)+'</td>';
                            tr += '</tr>';
                        }
                    }
                }else{
                    swal("Error API!", responseSIA.message, type);
                }
                $('#historyListDT').html(tr);
                $('#requestHistoryList').DataTable();
            }
        });
    }
    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }

    $("#formRequest").submit(function(e) {
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

        var form = this;
        var url_post=$(this).attr('data-url-post');
        var loader=$(this).attr('data-loader-file');
        var form = new FormData(form);

        $.ajax({
            type: "POST",
            url: url_post,
            data: form,
            // cache:false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            Swal.fire({
                title: "Loading...",
                text: "Please wait!",
                imageUrl: loader,
                allowOutsideClick: false,
                imageSize: '150x150',
                showConfirmButton: false
            });
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || "Success insert data",
                }).then((result) =>{
                    setTimeout(function(){
                        window.location.href="/finance/purchase-requisition-marketlist/request";
                    }, 500);
                });
            },
            error : function(error){
                let message = 'Something went wrong, ' + error.responseJSON.message || 'Something went wrong when trying to save the document, make sure to fill all the data required, check your connection and try again';
                Swal.fire('Save PR Marketlist Form', message, 'error');
            },
        });
        return false;
    });

    var delayInAjaxCall = (function(){
          var timer = 0;
          return function(callback, milliseconds){
          clearTimeout (timer);
          timer = setTimeout(callback, milliseconds);
       };
    })();

    function qtyInput(elem){
        elem.value = elem.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '',
        material_name = item.hasOwnProperty('MATERIALNAME') ? item.MATERIALNAME : '',
        material_uom = item.hasOwnProperty('UOM') ? item.UOM : '';

        if(parseFloat(elem.value) > 0){
            try {
                $(elem.closest('tr')).addClass('bg-success');
            } catch(error){}
            delayInAjaxCall(function(){
                checkLastPrice(elem, material_code, material_name, material_uom);
            }, 1000);
        } else {
            try {
                $('.btn-finish').prop('disabled', true);
            } catch(error){}

            try {
                $(elem.closest('tr')).removeClass('bg-success');
            } catch(error){}
            data_item = data_item.filter(x => x.mtnumber != material_code);

            try {
                setTimeout(function(){
                    $('.btn-finish').prop('disabled', false);
                }, 500);
            } catch(error){}
        }
    }

    function setNote(material_code='', notes=''){
        data_item = data_item.map(function(x) {
            try {
                if(x.mtnumber == material_code){
                    x.mtnote = notes;
                }
            } catch(error){}
            return x;
        });
    }

    function noteInput(elem){
        var item = $('#table-marketlist').DataTable().row( elem.closest('tr') ).data() || {};
        var material_code = item.hasOwnProperty('SAPMATERIALCODE') ? item.SAPMATERIALCODE : '';
        var notes = $(elem.closest('tr')).find('[name="noteItemTemplate[]"]').val();
        delayInAjaxCall(function(){
            setNote(material_code, notes);
        }, 300);

    }

    function checkLastPrice(target, material_number='', material_name='', material_unit='', obj={}){
        // console.log(target, target.value, typeof target.value);
        try {
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', true);
            $('.btn-finish').prop('disabled', true);
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
                        try {
                            var notes = $(target).parents('tr').find('[name="noteItemTemplate[]"]').val();
                            var obj_to_add = {'mtnumber': material_number, 'mtname': material_name, 'mtunit': material_unit, 'mtlastprice': resp.last_price, 'mtlastpriceplain': resp.last_price_plain, 'mtnote': notes, 'mtqty': qty};
                            data_item = data_item.filter(x => x.mtnumber != material_number);
                            data_item.push(obj_to_add);
                        } catch(error){}

                        let last_price = resp.last_price;
                        $(target).parents('tr').find('[name="costItemTemplate[]"]').val(last_price);
                        calculateGrandTotal(target);
                        $(target).parent().find('.spinner-qty').prop('hidden', true);
                     }
                   }, 
                   error : function(xhr){
                     $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                     console.log("Error in check last price");
                     $('.btn-finish').prop('disabled', false);
                     // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                     $(target).parent().find('.spinner-qty').prop('hidden', true);
                   },
                   complete : function(){
                    setTimeout(function(){
                        // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                        $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
                    }, 100);
                   }
                });
            } else {
                $(target).parents('tr').find('[name="costItemTemplate[]"]').val('0.00');
                // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
                $('.btn-finish').prop('disabled', false);
                $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);
            }
        } catch(error){
            // Swal.fire('Find Last Price', 'Something when wrong when trying to get the last price from SAP', 'error');
            console.log("Error in last price", error);
            // $('input[name="idItemTemplate[]"]').not(obj).prop('disabled', false);
            $('.btn-finish').prop('disabled', false);
            $('input[name="qtyItemTemplate[]"]').not(target).prop('disabled', false);

        }
    }

    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    function calculateGrandTotal(targetChild){
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
        $('.btn-finish').prop('disabled', false);
        $('.btn-back').prop('disabled', false);
    }

    $('.btn-finish').on('click', function(){
        Swal.fire({
          title: 'Are you sure?',
          text: "Please make sure that you've added material based on your need and then confirm by tapping Yes",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          allowOutsideClick: false
        }).then((result) => {
          if (result.isConfirmed) {
            try{
                var table_data = '';
                if(data_item.length){
                    var grandTotal = 0;
                    $('#marketlist-template').modal('hide');
                    setTimeout(function(){
                        var index = 0;
                        for(var loop=1;loop <= data_item.length;loop++){
                            if(loop < 10){
                                var item_order = "000"+(loop * 10);
                            }
                            else if(loop < 100 && loop > 9){
                                var item_order = "00"+(loop * 10);
                            }
                            else if(loop < 1000 && loop > 90){
                                var item_order = "0"+(loop * 10);
                            }
                            else if(loop < 10000 && loop > 900){
                                var item_order = "0"+(loop * 10);
                            }


                            if(is_detail_popup){
                                var qty_element = `<input type="text" class="form-control" oninput='qtyInputDetail(this)' name="marketlistMaterialQty[]" value="${data_item[index].mtqty}">
                                    <div class='spinner-qty' style='position: absolute; top: 18px; right: 12px' hidden><i class='fa fa-spin fa-spinner'></i></div>`
                                var notes = `<input type="text" class="form-control" name="marketlistMaterialNote[]" value="${data_item[index].mtnote}">`
                            } else {
                                var qty_element = `${data_item[index].mtqty}<input type="hidden" name="marketlistMaterialQty[]" value="${data_item[index].mtqty}">`;
                                var notes = `${data_item[index].mtnote}<input type="hidden" name="marketlistMaterialNote[]" value="${data_item[index].mtnote}">`;

                            }

                            if(is_detail_popup)
                                var tableID = 'reqFormDetail';
                            else 
                                var tableID = 'reqForm';

                            table_data += `<tr>
                                    <td class="text-center">${item_order}
                                        <input type="hidden" name="marketlistItemOrder[]" value="${item_order}">
                                    </td>
                                    <td class="text-left">${data_item[index].mtname}
                                        <input type="hidden" name="marketlistMaterialNumber[]" value="${data_item[index].mtnumber}">
                                        <input type="hidden" name="marketlistMaterialName[]" value="${data_item[index].mtname}">
                                    </td>
                                    <td class="text-center">${data_item[index].mtunit}
                                        <input type="hidden" name="marketlistMaterialUnit[]" value="${data_item[index].mtunit}">
                                    </td>
                                    <td class="text-right">
                                        ${qty_element}
                                    </td>
                                    <td class="text-left">
                                        ${notes}
                                    </td>
                                    <td class="text-right"><span id="last-price-text">${data_item[index].mtlastprice}</span>
                                        <input type="hidden" name="marketlistMaterialLastPrice[]" value="${data_item[index].mtlastpriceplain}">
                                        <input type="hidden" name="marketlistMaterialLastPriceFormatted[]" value="${data_item[index].mtlastprice}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm px-3 btn-del" onclick="deleteBaris('${tableID}', this)">Remove</button>
                                    </td>
                                </tr>`;
                            grandTotal += parseFloat(data_item[index].mtlastpriceplain);
                            index++;
                        }
                        if(table_data){
                            template_selected_code = template_selected_code_temp;

                            if(is_detail_popup){
                                $('#reqFormDetail > tbody').html(table_data);
                                var grandTotalFormat =  number_format(grandTotal, 2, '.', ',');
                                $('#reqFormDetail').parents('form').find('#grandTotal').val(grandTotalFormat);
                                $('#Template_Code_Detail').val(template_selected_code);
                            } else {
                                $('#reqForm > tbody').html(table_data);
                                var grandTotalFormat =  number_format(grandTotal, 2, '.', ',');
                                $('#reqForm').parents('form').find('#grandTotal').val(grandTotalFormat);
                                $('#Template_Code').val(template_selected_code);
                            }

                        }
                    }, 500);

                } else {
                    Swal.fire('Template Item Selection', 'No data has been selected, please select at least 1 material data', 'warning');
                }
            } catch(error){
                Swal.fire('Assign Item Error', 'Something went wrong while adding data to the request item list, please try again in a moment', 'error');
                console.log(error.message)
            }
          }
        });
    });

    function setItemOrderPR(data_item=0, tableID){
        if(data_item > 0){
            try {
                $('.btn-submit').prop('disabled', true);
                var iter = 0;
                for(var loop=1;loop<=data_item;loop++){
                    if(loop < 10){
                        var item_order = "000"+(loop * 10);
                    }
                    else if(loop < 100 && loop > 9){
                        var item_order = "00"+(loop * 10);
                    }
                    else if(loop < 1000 && loop > 90){
                        var item_order = "0"+(loop * 10);
                    }
                    else if(loop < 10000 && loop > 900){
                        var item_order = "0"+(loop * 10);
                    }

                    try{
                        var item_replace = `${item_order}<input type="hidden" name="marketlistItemOrder[]" value="${item_order}">`
                        $(`#${tableID}`).find('input[name="marketlistItemOrder[]"]').eq(iter).parent().html(item_replace);
                    } catch(error){
                        console.log(error);
                    }
                    iter++;
                }
                $('.btn-submit').prop('disabled', false);
            } catch(error){}
        }
    }

    function deleteBaris(tableID, objRow=null) {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        try {
            var rowIndex = $(objRow).parents('tr')[0].rowIndex;
        } catch(error){
            var rowIndex = 0;
        }

        table.deleteRow(rowIndex);

        try {
            // document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
            setItemOrderPR((document.getElementById(tableID).rows.length - 1), tableID);
        } catch(error){
            console.log(error); 
        }

        try {
            calculateGrandTotal(table);
        } catch(error){}

        try {
            if((table.rows.length - 1) == 0 ){
                var no_data = `<tr>
                    <td colspan="8" class="text-center">No Material Selected</td>
                </tr>`;
                $(`#${tableID} > tbody`).html(no_data);
                template_selected_code = '';
                template_selected_code_temp = '';
                is_hide_back_btn = false;

                // Delete template code if no items
                try {
                    $('#Template_Code').val(template_selected_code);
                } catch(error){}

                try {
                    $('#Template_Code_Detail').val(template_selected_code);
                } catch(error){}
            }
        } catch(error){}

    }

</script>
@endsection
