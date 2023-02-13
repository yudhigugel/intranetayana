@extends('layouts.default')

@section('title', 'Marketlist Template - Edit')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">

<style type="text/css">
.dt-buttons .dataTables_length{
  float:left;
}
.dt-buttons .dataTables_filter{
  float:right;
}
.table,
.dataTables_wrapper{
  position: relative;
}
.dataTables_info{
  position: absolute;
  bottom: 1em;
}
.add-material-btn{
  position: absolute;
  right: 0;
  top: 0;
}
@media (min-width: 576px){
  .modal-dialog {
    max-width: 900px;
  }
}
.modal .select2-container {
  text-align: left !important;
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
#reqForm_wrapper .dataTable .btn {
  padding: 0.5rem 0.25rem !important;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Forms</a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Requisition Market List</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>Edit Template</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-8 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>

    <div class="card">
      <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
        @if(session('message') && isset(session('message')['type']))
        <div class="alert alert-fill-{{ session('message')['type'] }} alert-dismissable p-3 mb-3" role="alert">
          @if(session('message')['type'] == 'success')
          <i class="mdi mdi-check"></i>
          @else
          <i class="mdi mdi-alert-circle"></i>
          @endif

          {{ session('message')['msg'] }}
          <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        <h2 class="card-title"><i class="mdi mdi-plus"></i> Update Template</h2>
        <form method="POST" action="{{ route('marketlisttemplate.update', [isset($data['plant_selected']) ? $data['plant_selected'] : '', isset($data['template_name']) ? $data['template_name'] : '']) }}" id="form-marketlist" data-loader-file="{{ url('/image/gif/cube.gif') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <label class="m-0">Plant</label>
                <div class="mb-2">
                  <small class="text-muted">
                  * Please select plant first to get material
                  </small>
                </div>
                <select readonly class="select2 form-control" name="plant" id="plant-select" required style="width: 100%">
                  <option value="" selected default>--- Choose Plant ---</option>
                  @if(isset($data['plant_user']) && count($data['plant_user']))
                    @if(count($data['plant_user']) == 1)
                      @foreach($data['plant_user'] as $key_plant => $val)
                      <option @if(isset($data['plant_selected']) && $data['plant_selected'] == $key_plant) selected default @endif value="{{ $key_plant }}">{{ $val }}</option>
                      @endforeach
                    @else
                      @foreach($data['plant_user'] as $key_plant => $val)
                      <option @if(isset($data['plant_selected']) && $data['plant_selected'] == $key_plant) selected default @endif value="{{ $key_plant }}">{{ $val }}</option>
                      @endforeach
                    @endif
                  @endif
                </select>
                @error('plant')
                <small class="text-danger mb-2 mt-1 d-block">
                  <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                </small>
                @enderror
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row mb-3">
              <div class="col-12">
                <label>Template Name</label>
                <input maxlength="25" type="text" name="template_name" id="template_name" required class="form-control" placeholder="Input Template Name (Max Length 25 Characters)" value="{{ isset($data['template_name']) ? $data['template_name'] : '' }}">
                <input type="hidden" name="old_template_name" id="old_template_name" value="{{ isset($data['template_name']) ? $data['template_name'] : '' }}">
                @error('template_name')
                <small class="text-danger mb-2 mt-1 d-block">
                  <i class="mdi mdi-alert-circle"></i>&nbsp;&nbsp;{{ $message }}
                </small>
                @enderror
                <div class="mt-2 template-duplicate-text" hidden>
                  <small class="text-primary">Checking any duplicates template name&nbsp; <i class="fa fa-spin fa-spinner"></i></small>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group mb-5">
            <div class="row mb-3">
              <div class="col-12">
                <div class="row align-items-end">
                  <div class="col-7 text-left">
                    <label>Select Material</label>
                  </div>
                  <div class="col-5 text-right mb-2">
                    <button type="button" data-toggle="modal" data-target="#modalMaterial" class="btn btn-primary text-white btn-sm"><i class="fa fa-list"></i>&nbsp; Edit Items</button>
                  </div>
                </div>
                <div class="portlet-body table-both-scroll mb-4">
                  <table class="table table-bordered" id="detailForm" style="width: 100%">
                    <thead>
                      <th style="width: 8%">Items</th>
                      <th style="width: 10%" class="thead-apri">Material Number</th>
                      <th style="width: 26%" class="thead-apri">Material Name</th>
                      <th style="width: 8%" class="thead-apri">Unit</th>
                      <th style="width: 10%" class="thead-apri">Purchasing Group</th>
                    </thead>
                    <tbody>
                        @if(isset($data['material']) && count($data['material']) > 0)
                          @foreach($data['material'] as $material)
                            <tr>
                              <td class="text-center">{{ $loop->iteration }}
                                <input type="hidden" name="marketlistItemOrder[]" value="{{ $loop->iteration }}">
                              </td>
                              <td class="text-center">{{ isset($material->SAPMATERIALCODE) ? $material->SAPMATERIALCODE : '-' }}
                                <input type="hidden" name="marketlistMaterialNumber[]" value="{{ isset($material->SAPMATERIALCODE) ? $material->SAPMATERIALCODE : '' }}">
                              </td>
                              <td class="text-left">{{ isset($material->MATERIALNAME) ? $material->MATERIALNAME : '-' }}
                                <input type="hidden" name="marketlistMaterialName[]" value="{{ isset($material->MATERIALNAME) ? $material->MATERIALNAME : '' }}">
                              </td>
                              <td class="text-center">{{ isset($material->UOM) ? $material->UOM : '-' }}
                                  <input type="hidden" name="marketlistMaterialUnit[]" value="{{ isset($material->UOM) ? $material->UOM : '' }}">
                              </td>
                              <td class="text-center">{{ isset($material->PURCH_GROUP) ? $material->PURCH_GROUP : '-' }}
                                  <input type="hidden" name="marketlistMaterialPurchGroup[]" value="{{ isset($material->PURCH_GROUP) ? $material->PURCH_GROUP : '' }}">
                              </td>
                            </tr>
                          @endforeach
                        @endif
                    </tbody>                                
                  </table> 
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-group text-left d-flex">
              <div class="pr-2">
                <button type="submit" class="btn btn-primary btn-submit" disabled><i class="mdi mdi-check"></i> Submit</button>
              </div>
              <div class="pr-2">
                <button type="reset" class="btn btn-danger btn-reset" disabled><i class="mdi mdi-history"></i> Reset</button>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalMaterial" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modalMaterialLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="modalHistoryLabel">Material List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
          </button>
      </div>
      <div class="modal-body" id="bodyModalMaterial">
        <div class="form-group item-container">
          {{--<h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>--}}
          <div class="row portlet-body table-both-scroll mb-3" style="margin-bottom: 15rem !important;">
            <form class="form-horizontal" method="get" id='form-add-material' action='' style="width: 100%">
              <div class="text-center p-4 table-item-loading">
                <h6><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;Loading Items ...</h6>
              </div>
              <table class="table table-bordered smallfont table-request" id="reqForm" style="width: 100%">
                  <thead>
                    <tr>
                      <th style="width: 8%">Item</th>
                      <th style="width: 20%">Material</th>
                      <th style="width: 15%">UoM</th>
                      <th style="width: 25%">Purch. Group</th>
                      <th style="width: 10%">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="tbody-edit-items">
                    {{--@if(isset($data['material']) && count($data['material']) > 0)
                      @foreach($data['material'] as $material)
                        <tr class="rowToClone">
                          <td style="max-width: 1px">
                            <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="{{ $loop->iteration }}" readonly="">
                          </td>
                          <td style="max-width: 1px">
                            <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials-{{ $loop->iteration }}" style="width: 100%">
                                <option value="{{ isset($material->SAPMATERIALCODE) ? $material->SAPMATERIALCODE : '' }}" default selected>{{ isset($material->MATERIALNAME) ? $material->MATERIALNAME : '' }}</option>
                            </select>
                            <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-{{ $loop->iteration }}">
                          </td>
                          <td style="max-width: 1px">
                            <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-{{ $loop->iteration }}" value="{{ isset($material->UOM) ? $material->UOM : '' }}" placeholder="Automatically filled">
                          </td>
                          <td style="max-width: 1px">
                            <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-{{ $loop->iteration }}" value="{{ isset($material->PURCH_GROUP) ? $material->PURCH_GROUP : '' }}" placeholder="Automatically filled">
                          </td>
                          <td>
                            <div class="btn-group" style="min-width:80px">
                                <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                                <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @endif--}}
                  </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-finish btn-success text-white btn-block"><i class="fa fa-check"></i>&nbsp;&nbsp;Finish</button>
      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>
<script src="//bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.9/features/searchHighlight/dataTables.searchHighlight.min.js"></script>

<script type="text/javascript">
  var data_item = [];
  var table_item;
  var status_checked = false;
  var temporary_delete_item = [];
  var temporary_add_item = [];
  var global_purchasing_group = null;

  $(document).ready( function () {
     $('#modalMaterial').on('show.bs.modal', function(){
        if (!$.fn.DataTable.isDataTable('#reqForm')) {
          try {
            if(data_item.length > 0){
              $('.table-item-loading').prop('hidden', false);
              var prepared_element = '';
              var loop = 1;
              $.each(data_item, function(index, element){
                prepared_element += `<tr class="rowToClone">
                  <td style="max-width: 1px">
                    <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${loop}" readonly="">
                  </td>
                  <td style="max-width: 1px">
                    <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials-${loop}" style="width: 100%">
                        <option value="${element.mtnumber}" default selected>${element.mtname}</option>
                    </select>
                    <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-${loop}">
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${loop}" value="${element.mtunit}" placeholder="Automatically filled">
                  </td>
                  <td style="max-width: 1px">
                    <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-${loop}" value="${element.purgroup}" placeholder="Automatically filled">
                  </td>
                  <td>
                    <div class="btn-group" style="min-width:80px">
                        <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                        <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                    </div>
                  </td>
                </tr>`;
                loop++;
              });

              setTimeout(function(){
                $('.table-item-loading').prop('hidden', true);
                $('#tbody-edit-items').html(prepared_element);
                var table_item = $('#reqForm').DataTable({
                  paging: false,
                  dom: '<"row align-items-end" <"col-sm-12 col-md-8"<"wrapper-item-text"> l><"col-sm-12 col-md-4"f>>r<"table-container-h table-wrapper"t>ip',
                  // pageLength: 10,
                  info: false,
                  scrollX: false,
                  lengthChange: false,
                  "columnDefs": [
                    { "searchable": false, "targets": [0, 2, 4] },
                    { "width": '8%', 'targets': [0]},
                    { "width": '30%', 'targets': [1]},
                    { "width": '11.5%', 'targets': [2]},
                    { "width": '11.5%', 'targets': [3]},
                    { "width": '10%', 'targets': [4]}
                  ],
                  initComplete: function(){
                    $('<h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>').appendTo('.wrapper-item-text');
                  }
                });

                $(".select-decorated-material").select2({
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    templateResult: function(data) {
                        if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                            return data.text;
                        }
                        return data.html;
                    },
                    templateSelection: function(data) {
                        if(!data.id) {
                            return data.text;
                        } else {
                            return data.text;
                        }
                    },
                    dropdownParent: $('#bodyModalMaterial'),
                    allowClear: false,
                    placeholder: "Search Material ...",
                    ajax: {
                       url: "/finance/purchase-requisition-marketlist/master-template/add",
                       type: "GET",
                       dataType: 'json',
                       delay: 600,
                       data: function (params) { 
                        plant = $('#plant-select').val();
                        return {
                          searchTerm: params.term, // search term
                          type: 'material',
                          plant: plant,
                        };
                       },
                       processResults: function (response) {
                         $('.btn-finish').prop('disabled', false);
                         $('.btn-add').prop('disabled', false);
                         $('.btn-del').prop('disabled', false);

                         return {
                            results: response
                         };
                       },
                       cache: false,
                       transport: function (params, success, failure) {
                         var plant = $('#plant-select').val();
                         if(plant){
                             var $request = $.ajax(params);
                             $request.then(success);
                             $request.fail(failure);

                             $('.btn-finish').prop('disabled', true);
                             $('.btn-add').prop('disabled', true);
                             $('.btn-del').prop('disabled', true);
                             return $request;
                         } else {
                            Swal.fire('Plant selection', 'Please select plant first to search materials available within it. Material would not be available if no plant specified or selected', 'warning');
                            return false;
                         }
                       }
                    },
                    minimumInputLength: 3
                }).on('select2:select', function(e){
                    var value = e.params.data.id || 0;
                    var text = e.params.data.text || 'Unknown';
                    var unit = e.params.data.unit || 'Unknown';
                    var purgroup = e.params.data.purchasing_group || 'Unknown';
                    var rowIndex = $(e.target).parents('tr')[0].rowIndex;
                    var rowIndexCustom = $(e.target).parents('tr')[0].rowIndex+Math.floor((Math.random() * 100) + 1).toString();

                    try {
                      var duplicate = data_item.filter(function(val, index){
                        return val.mtnumber == value
                      });
                      if(duplicate.length > 0){
                        Swal.fire('Duplicate Material', `This material ${text} has been added from the template, please make sure that you only choose or select material that is not available on the template otherwise it will be ignored`, 'error');
                        return false;
                      } else {
                        if(!purgroup || purgroup == 'Unknown'){
                          try {
                            $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                            $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                            if($(`#reqForm > tbody > tr`).length > 1){
                              try {
                                $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                                $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                              } catch(error){}
                            }

                            data_item.splice((rowIndex - 1), 1);
                            if(data_item.length < 1)
                              global_purchasing_group = null
                          } catch(error){}
                          Swal.fire('Purchasing Group Material', 'No Purchasing group found within the material, please check the data and try to reloading the page again', 'error');
                          return false;
                        } else if(global_purchasing_group && global_purchasing_group !== purgroup){
                          try {
                            $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                            $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                            $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                            if($(`#reqForm > tbody > tr`).length > 1){
                              try {
                                $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                                $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                              } catch(error){}
                            }

                            data_item.splice((rowIndex - 1), 1);
                            if(data_item.length < 1)
                              global_purchasing_group = null
                            // console.log(data_item, global_purchasing_group);
                          } catch(error){}
                          Swal.fire('Invalid Purchasing Group', 'Please make sure material you select contains the same purchasing group as others', 'error');
                          return false;
                        } else {
                          if(data_item[(rowIndex - 1)] == undefined)
                            data_item.push({'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup});
                          else
                            data_item[(rowIndex - 1)] = {'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup};

                          try {
                            var index = `<td style="max-width: 1px">
                            <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${rowIndex}" readonly="">
                            </td>`;
                            var material = `<td style="max-width: 1px">
                            <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials${rowIndexCustom}" style="width: 100%">
                                  <option value="${value}" default selected>${text}</option>
                              </select>
                              <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-${rowIndexCustom}">
                            </td>`;
                            var measurement = `<td style="max-width: 1px">
                            <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${rowIndexCustom}" value="${unit}" placeholder="Automatically filled">
                            </td>`;
                            var pur_group = `<td style="max-width: 1px">
                            <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-${rowIndexCustom}" value="${purgroup}" placeholder="Automatically filled">
                            </td>`;
                            var button = `<td>
                              <div class="btn-group" style="min-width:80px">
                                <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                                <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                              </div>
                            </td>`;
                            
                            table_item.row((rowIndex - 1)).data([index, material, measurement, pur_group, button]).draw();
                            enableSelect2(`materials${rowIndexCustom}`);
                          } catch(error){
                            console.log(error);
                          }

                          $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
                        }
                      }
                    } catch(error){}
                }).on('select2:unselecting', function(e){
                    $(e.target).parents('tr').find('#rsvMeasurement').val('');
                    $(this).data('state', 'unselected');
                }).on("select2:open", function(e) {                   
                    try {
                      if ($(this).data('state') === 'unselected') {
                          $(this).removeData('state'); 
                          var self = $(this).parent().find('.select2')[0];
                          setTimeout(function() {
                              $(self).select2('close');
                          }, 0);
                      }
                    } catch(error){}   
                });
              }, 1000)
            }
            else {
              $('.table-item-loading').prop('hidden', true);
              table_item = $('#reqForm').DataTable({
                paging: false,
                dom: '<"row align-items-end" <"col-sm-12 col-md-8"<"wrapper-item-text"> l><"col-sm-12 col-md-4"f>>r<"table-container-h table-wrapper"t>ip',
                // pageLength: 10,
                info: false,
                scrollX: false,
                lengthChange: false,
                autoWidth: false,
                "columnDefs": [
                  { "searchable": false, "targets": [0, 2, 4] },
                  { "width": '8%', 'targets': [0]},
                  { "width": '30%', 'targets': [1]},
                  { "width": '11.5%', 'targets': [2]},
                  { "width": '11.5%', 'targets': [3]},
                  { "width": '10%', 'targets': [4]}
                ],
                initComplete: function(){
                  $('<h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>').appendTo('.wrapper-item-text');
                }
              });
            }
          } catch(error){}
        }
     });

     $('#modalMaterial').on('hide.bs.modal', function(){
      try {
        $('.table-item-loading').prop('hidden', false);
        $('#reqForm').DataTable().destroy();
        temporary_delete_item = [];
        temporary_add_item = [];

        if(data_item.length < 1){
          $('#detailForm > tbody').html(`<tr><td colspan="5" class="text-center">No Material Selected</td></tr>`);
          $('#tbody-edit-items').html(
            `<tr class="rowToClone">
                <td style="max-width: 1px">
                  <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="1" readonly="">
                </td>
                <td style="max-width: 1px">
                  <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                      <option value="" default selected>---- Choose Material ----</option>
                  </select>
                  <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                </td>
                <td style="max-width: 1px">
                  <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
                </td>
                <td style="max-width: 1px">
                  <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
                </td>
                <td>
                  <div class="btn-group" style="min-width:80px">
                      <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                      <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                  </div>
                </td>
            </tr>`
          );
          enableSelect2('materials');
        } else {
          $('#tbody-edit-items').empty();
        }
      } catch(error){}
     });

     $('#detailForm').DataTable({
       pageLength: 10,
       dom: '<"row align-items-end" <"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>r<"table-container-h table-wrapper"t>ip',
       lengthChange: true,
       paging: true,
       scrollX: false,
       "columnDefs": [
          { "searchable": false, "targets": [0, 3] },
          { "width": '8%', 'targets': [0]},
          { "width": '11.5%', 'targets': [1]},
          { "width": '28%', 'targets': [2]},
          { "width": '8%', 'targets': [3]},
          { "width": '15%', 'targets': [4]}
       ],
       initComplete: function(settings, json){
          var data_table = this.api().rows().data();
          var table_obj = this.api();
          // Disable button while initializing data
          $('.btn').prop('disabled', true);
          data_table.each(function (data, index) {
            var value = $('[name="marketlistMaterialNumber[]"]', table_obj.row(index).node()).val();
            var text = $('[name="marketlistMaterialName[]"]', table_obj.row(index).node()).val();
            var unit = $('[name="marketlistMaterialUnit[]"]', table_obj.row(index).node()).val();
            var purgroup = $('[name="marketlistMaterialPurchGroup[]"]', table_obj.row(index).node()).val();
            data_item.push({'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup});
          });
          $('.btn').prop('disabled', false);
          // Enable button after init data
        }
     });

     $('.select2').select2({
      placeholder: "Select an option",
      allowClear: false
     });

     $('.btn-submit').prop('disabled', false)
     $('.btn-reset').prop('disabled', false)

     var select_material_obj = $(".select-decorated-material").select2({
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                return data.text;
            }
            return data.html;
        },
        templateSelection: function(data) {
            if(!data.id) {
                return data.text;
            } else {
                return data.text;
            }
        },
        dropdownParent: $('#bodyModalMaterial'),
        allowClear: false,
        placeholder: "Search Material ...",
        ajax: {
           url: "/finance/purchase-requisition-marketlist/master-template/add",
           type: "GET",
           dataType: 'json',
           delay: 600,
           data: function (params) { 
            plant = $('#plant-select').val();
            return {
              searchTerm: params.term, // search term
              type: 'material',
              plant: plant,
            };
           },
           processResults: function (response) {
             $('.btn-finish').prop('disabled', false);
             $('.btn-add').prop('disabled', false);
             $('.btn-del').prop('disabled', false);

             return {
                results: response
             };
           },
           cache: false,
           transport: function (params, success, failure) {
             var plant = $('#plant-select').val();
             if(plant){
                 var $request = $.ajax(params);
                 $request.then(success);
                 $request.fail(failure);

                 $('.btn-finish').prop('disabled', true);
                 $('.btn-add').prop('disabled', true);
                 $('.btn-del').prop('disabled', true);
                 return $request;
             } else {
                Swal.fire('Plant selection', 'Please select plant first to search materials available within it. Material would not be available if no plant specified or selected', 'warning');
                return false;
             }
           }
        },
        minimumInputLength: 3
     }).on('select2:select', function(e){
        var value = e.params.data.id || 0;
        var text = e.params.data.text || 'Unknown';
        var unit = e.params.data.unit || 'Unknown';
        var purgroup = e.params.data.purchasing_group || 'Unknown';
        var rowIndex = $(e.target).parents('tr')[0].rowIndex;
        var rowIndexCustom = $(e.target).parents('tr')[0].rowIndex+Math.floor((Math.random() * 100) + 1).toString();

        try {
          var duplicate = data_item.filter(function(val, index){
            return val.mtnumber == value
          });

          if(duplicate.length > 0){
            Swal.fire('Duplicate Material', `This material ${text} has been added from the template, please make sure that you only choose or select material that is not available on the template otherwise it will be ignored`, 'error');
            return false;
          } else {
            if(!purgroup || purgroup == 'Unknown'){
              try {
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                if($(`#reqForm > tbody > tr`).length > 1){
                  try {
                    $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                    $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                  } catch(error){}
                }

                data_item.splice((rowIndex - 1), 1);
                if(data_item.length < 1)
                  global_purchasing_group = null
              } catch(error){}
              Swal.fire('Purchasing Group Material', 'No Purchasing group found within the material, please check the data and try to reloading the page again', 'error');
              return false;
            } else if(global_purchasing_group && global_purchasing_group !== purgroup){
              try {
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                if($(`#reqForm > tbody > tr`).length > 1){
                  try {
                    $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                    $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                  } catch(error){}
                }

                data_item.splice((rowIndex - 1), 1);
                if(data_item.length < 1)
                  global_purchasing_group = null
              } catch(error){}
              Swal.fire('Invalid Purchasing Group', 'Please make sure material you select contains the same purchasing group as others', 'error');
              return false;
            } else {
              global_purchasing_group = purgroup;
              if(data_item[(rowIndex - 1)] == undefined)
                data_item.push({'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup});
              else
                data_item[(rowIndex - 1)] = {'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup};

              try {
                var index = `<td style="max-width: 1px">
                <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${rowIndex}" readonly="">
                </td>`;
                var material = `<td style="max-width: 1px">
                <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials${rowIndexCustom}" style="width: 100%">
                      <option value="${value}" default selected>${text}</option>
                  </select>
                  <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-${rowIndexCustom}">
                </td>`;
                var measurement = `<td style="max-width: 1px">
                <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${rowIndexCustom}" value="${unit}" placeholder="Automatically filled">
                </td>`;
                var pur_group = `<td style="max-width: 1px">
                <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-${rowIndexCustom}" value="${purgroup}" placeholder="Automatically filled">
                </td>`;
                var button = `<td>
                  <div class="btn-group" style="min-width:80px">
                    <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                    <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                  </div>
                </td>`;
                table_item.row((rowIndex - 1)).data([index, material, measurement, pur_group, button]).draw();
                enableSelect2(`materials${rowIndexCustom}`);
              } catch(error){
                console.log(error);
              }

              $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
            }
          }
        } catch(error){}
    }).on('select2:unselecting', function(e){
        $(e.target).parents('tr').find('#rsvMeasurement').val('');
        $(this).data('state', 'unselected');
    }).on("select2:open", function(e) {                   
        try {
          if ($(this).data('state') === 'unselected') {
              $(this).removeData('state'); 
              var self = $(this).parent().find('.select2')[0];
              setTimeout(function() {
                  $(self).select2('close');
              }, 0);
          }
        } catch(error){}   
    });

  // END READY FUNCTION
  });

  function enableSelect2(target={}){
    $(`#${target}`).select2({
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                return data.text;
            }
            return data.html;
        },
        templateSelection: function(data) {
            if(!data.id) {
                return data.text;
            } else {
                return data.text;
            }
        },
        dropdownParent: $('#bodyModalMaterial'),
        allowClear: false,
        placeholder: "Search Material ...",
        ajax: {
           url: "/finance/purchase-requisition-marketlist/master-template/add",
           type: "GET",
           dataType: 'json',
           delay: 600,
           data: function (params) { 
            plant = $('#plant-select').val();
            return {
              searchTerm: params.term, // search term
              type: 'material',
              plant: plant,
            };
           },
           processResults: function (response) {
             $('.btn-finish').prop('disabled', false);
             $('.btn-add').prop('disabled', false);
             $('.btn-del').prop('disabled', false);

             return {
                results: response
             };
           },
           cache: false,
           transport: function (params, success, failure) {
             var plant = $('#plant-select').val();
             if(plant){
                 var $request = $.ajax(params);
                 $request.then(success);
                 $request.fail(failure);

                 $('.btn-finish').prop('disabled', true);
                 $('.btn-add').prop('disabled', true);
                 $('.btn-del').prop('disabled', true);
                 return $request;
             } else {
                Swal.fire('Plant selection', 'Please select plant first to search materials available within it. Material would not be available if no plant specified or selected', 'warning');
                return false;
             }
           }
        },
        minimumInputLength: 3
     }).on('select2:select', function(e){
        var value = e.params.data.id || 0;
        var text = e.params.data.text || 'Unknown';
        var unit = e.params.data.unit || 'Unknown';
        var purgroup = e.params.data.purchasing_group || 'Unknown';
        var rowIndex = $(e.target).parents('tr')[0].rowIndex;
        var rowIndexCustom = $(e.target).parents('tr')[0].rowIndex+Math.floor((Math.random() * 100) + 1).toString();

        try {
          var duplicate = data_item.filter(function(val, index){
            return val.mtnumber == value
          });

          if(duplicate.length > 0){
            Swal.fire('Duplicate Material', `This material ${text} has been added from the template, please make sure that you only choose or select material that is not available on the template otherwise it will be ignored`, 'error');
            return false;
          } else {
            if(!purgroup || purgroup == 'Unknown'){
              try {
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                if($(`#reqForm > tbody > tr`).length > 1){
                  try {
                    $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                    $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                  } catch(error){}
                }

                data_item.splice((rowIndex - 1), 1);
                if(data_item.length < 1)
                  global_purchasing_group = null;
              } catch(error){}
              Swal.fire('Purchasing Group Material', 'No Purchasing group found within the material, please check the data and try to reloading the page again', 'error');
              return false;
            } else if(global_purchasing_group && global_purchasing_group !== purgroup){
              try {
                $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                if($(`#reqForm > tbody > tr`).length > 1){
                  try {
                    $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                    $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                  } catch(error){}
                }

                data_item.splice((rowIndex - 1), 1);
                if(data_item.length < 1)
                  global_purchasing_group = null
              } catch(error){}
              Swal.fire('Invalid Purchasing Group', 'Please make sure material you select contains the same purchasing group as others', 'error');
              return false;
            } else {
              global_purchasing_group = purgroup;
              if(data_item[(rowIndex - 1)] == undefined)
                data_item.push({'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup});
              else
                data_item[(rowIndex - 1)] = {'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup};

              try {
                var index = `<input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${rowIndex}" readonly="">`;
                var material = `<select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials${rowIndexCustom}" style="width: 100%">
                      <option value="${value}" default selected>${text}</option>
                  </select>
                  <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-${rowIndexCustom}">`;
                var measurement = `<input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${rowIndexCustom}" value="${unit}" placeholder="Automatically filled">`;
                var pur_group = `<input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-${rowIndexCustom}" value="${purgroup}" placeholder="Automatically filled">`;
                var button = `<div class="btn-group" style="min-width:80px">
                  <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                  <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                </div>`;
                
                table_item.row((rowIndex - 1)).data([index, material, measurement, pur_group, button]).draw();
                enableSelect2(`materials${rowIndexCustom}`);
              } catch(error){
                console.log(error);
              }

              $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
            }
          }
        } catch(error){}
        // console.log(data_item);
    }).on('select2:unselecting', function(e){
        $(e.target).parents('tr').find('#rsvMeasurement').val('');
        $(this).data('state', 'unselected');
    }).on("select2:open", function(e) {                   
        try {
          if ($(this).data('state') === 'unselected') {
              $(this).removeData('state'); 
              var self = $(this).parent().find('.select2')[0];
              setTimeout(function() {
                  $(self).select2('close');
              }, 0);
          }
        } catch(error){}   
    });
  }

  function cloneRow(tableID, isModal=false) {
    const inpObj = document.getElementById("form-add-material");
    if (!inpObj.checkValidity()) {
      Swal.fire('Material Selection', 'Please select material first before adding new row', 'warning');
      return false;
    }

    var table = document.getElementById(tableID);
    var dropdownParent = {dropdownParent:$('#bodyModalMaterial')};
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    row.classList.add('rowToClone');
    var colCount = table.rows[1].cells.length;
    if(rowCount<10000){
      for(var i=0; i<colCount; i++) {
        var newcell = row.insertCell(i);
        newcell.style.maxWidth = '1px';
        newcell.style.position = 'relative';

        if(i == 1 || i == 2 || i == 3 || i == 4){
            try {
                var html = '';
                if(i == 1){
                  html += `<td>
                      <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials" style="width: 100%">
                          <option value="" default selected>---- Choose Material ----</option>
                      </select>
                      <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc">
                  </td>`;
                } else if(i == 2){
                   html += `<td style="max-width: 1px">
                      <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement" placeholder="Automatically filled">
                      </td>`;
                } else if(i == 3){
                  html += `<td style="max-width: 1px">
                        <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup" placeholder="Automatically filled">
                      </td>`;
                } else if(i == 4){
                  html += `<td style="max-width: 1px">
                        <div class="btn-group" style="min-width:80px">
                          <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                          <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                        </div>
                      </td>`;
                }
                newcell.innerHTML = html;
            } catch(error){
                console.log(`Error in column 1 / 2 / 3 ${error}`);
            }
        }
        else {
            newcell.innerHTML = table.rows[1].cells[i].innerHTML;
        }

        try {
            // Set unique id based on row
            if(newcell.childNodes.length == 1){
              if(newcell.childNodes[0].id !== undefined && newcell.childNodes[0].id){
                newcell.childNodes[0].id = newcell.childNodes[0].id+rowCount+Math.floor((Math.random() * 100) + 1).toString();
              }
            }
            else {
              if(newcell.childNodes[1].id !== undefined && newcell.childNodes[1].id){
                newcell.childNodes[1].id = newcell.childNodes[1].id+rowCount+Math.floor((Math.random() * 100) + 1).toString();
              }
            }
        } catch(error){}

        try {
          if(newcell.childNodes.length == 1 && newcell.childNodes[0].name == 'rsvItem[]' || newcell.childNodes.length > 1 && newcell.childNodes[1].name == 'rsvItem[]'){
              let newVal = (rowCount - 1) + 1;
              if(newcell.childNodes.length == 1)
                newcell.childNodes[0].value = newVal;
              else
                newcell.childNodes[1].value = newVal;
          }

          else if(newcell.childNodes.length == 1 && newcell.childNodes[0].name == 'rsvMaterials[]' || newcell.childNodes.length > 1 && newcell.childNodes[1].name == 'rsvMaterials[]'){
            $(`#${newcell.childNodes[1].id}`).select2({
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if(data.hasOwnProperty('text') && data.hasOwnProperty('loading')){
                        return data.text;
                    }
                    return data.html;
                },
                templateSelection: function(data) {
                    if(!data.id) {
                        return data.text;
                    } else {
                        return data.text;
                    }
                },
                ...dropdownParent,
                allowClear: false,
                placeholder: "Search Material ...",
                ajax: {
                url: "/finance/purchase-requisition-marketlist/master-template/add",
                type: "GET",
                dataType: 'json',
                delay: 600,
                data: function (params) { 
                plant = $('#plant-select').val();
                  return {
                    searchTerm: params.term, // search term
                    type: 'material',
                    plant: plant,
                  };
                },
                processResults: function (response) {
                   $('.btn-finish').prop('disabled', false);
                   $('.btn-add').prop('disabled', false);
                   $('.btn-del').prop('disabled', false);

                   return {
                      results: response
                   };
                },
                cache: false,
                transport: function (params, success, failure) {
                   var plant = $('#plant-select').val();
                   if(plant){
                       var $request = $.ajax(params);
                       $request.then(success);
                       $request.fail(failure);

                       $('.btn-finish').prop('disabled', true);
                       $('.btn-add').prop('disabled', true);
                       $('.btn-del').prop('disabled', true);
                       return $request;
                   } else {
                      Swal.fire('Plant selection', 'Please select plant first to search materials available within it. Material would not be available if no plant specified or selected', 'warning');
                      return false;
                   }
                }
              },
              minimumInputLength: 3
            }).on('select2:select', function(e){
                var value = e.params.data.id || 0;
                var text = e.params.data.text || 'Unknown';
                var unit = e.params.data.unit || 'Unknown';
                var purgroup = e.params.data.purchasing_group || 'Unknown';
                var rowIndex = $(e.target).parents('tr')[0].rowIndex;
                var rowIndexCustom = $(e.target).parents('tr')[0].rowIndex+Math.floor((Math.random() * 100) + 1).toString();

                try {
                  var duplicate = data_item.filter(function(val, index){
                    return val.mtnumber == value
                  });

                  if(duplicate.length > 0){
                    $(e.target).val('').trigger('change');
                    $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                    Swal.fire('Duplicate Material', `This material ${text} has been added from the template, please make sure that you only choose or select material that is not available on the template otherwise it will be ignored`, 'error');
                    return false;
                  } else {
                    if(!purgroup || purgroup == 'Unknown'){
                      try {
                        $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                        $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                        $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                        if($(`#reqForm > tbody > tr`).length > 1){
                          try {
                            $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                            $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                          } catch(error){}
                        }

                        data_item.splice((rowIndex - 1), 1);
                        if(data_item.length < 1)
                          global_purchasing_group = null
                      } catch(error){}
                      Swal.fire('Purchasing Group Material', 'No Purchasing group found within the material, please check the data and try to reloading the page again', 'error');
                      return false;
                    } else if(global_purchasing_group && global_purchasing_group !== purgroup){
                      try {
                        $(e.target).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
                        $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val('');
                        $(e.target).parents('tr').find('[name="rsvPurchGroup[]"]').val('');

                        if($(`#reqForm > tbody > tr`).length > 1){
                          try {
                            $('#reqForm').DataTable().row((rowIndex - 1)).remove();
                            $(`#reqForm > tbody > tr`)[rowIndex - 1].remove();
                          } catch(error){}
                        }

                        data_item.splice((rowIndex - 1), 1);
                        if(data_item.length < 1)
                          global_purchasing_group = null
                      } catch(error){}
                      Swal.fire('Invalid Purchasing Group', 'Please make sure material you select contains the same purchasing group as others', 'error');
                      return false;
                    } else {
                      global_purchasing_group = purgroup
                      if(data_item[(rowIndex - 1)] == undefined)
                        data_item.push({'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup});
                      else
                        data_item[(rowIndex - 1)] = {'mtnumber': value, 'mtname': text, 'mtunit': unit, 'purgroup': purgroup};

                      try {
                        var index = `<td style="max-width: 1px">
                        <input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${rowIndex}" readonly="">
                        </td>`;
                        var material = `<td style="max-width: 1px">
                        <select required class="form-control select2 select-decorated-material" name="rsvMaterials[]" id="materials${rowIndexCustom}" style="width: 100%">
                              <option value="${value}" default selected>${text}</option>
                          </select>
                          <input type="hidden" name="rsvMaterialsDesc[]" value="" id="rsvMaterialsDesc-${rowIndexCustom}">
                        </td>`;
                        var measurement = `<td style="max-width: 1px">
                        <input type="text" readonly class="form-control text-center" name="rsvMeasurement[]" id="rsvMeasurement-${rowIndexCustom}" value="${unit}" placeholder="Automatically filled">
                        </td>`;
                        var pur_group = `<td style="max-width: 1px">
                        <input type="text" readonly class="form-control text-center" name="rsvPurchGroup[]" id="rsvPurchGroup-${rowIndexCustom}" value="${purgroup}" placeholder="Automatically filled">
                        </td>`;
                        var button = `<td>
                          <div class="btn-group" style="min-width:80px">
                            <button type="button" class="btn btn-danger btn-sm px-1 btn-del" onclick="deleteBaris('reqForm', this)">-</button>
                            <button type="button" class="btn btn-primary btn-sm px-1 btn-add" id="cloneButton" name="cloneButton" onclick="cloneRow('reqForm')">+</button>
                          </div>
                        </td>`;
                        
                        $('#reqForm').DataTable().row.add([index, material, measurement, pur_group, button]).draw();                      
                        enableSelect2(`materials${rowIndexCustom}`);
                      } catch(error){
                        console.log(error);
                      }

                      $(e.target).parents('tr').find('[name="rsvMeasurement[]"]').val(unit);
                    }
                  }
                } catch(error){
                  console.log(error)
                }
                // console.log(data_item);

            }).on('select2:unselecting', function(e){
                $(e.target).parents('tr').find('#rsvMeasurement').val('');
                $(this).data('state', 'unselected');
            }).on("select2:open", function(e) {                   
                try {
                  if ($(this).data('state') === 'unselected') {
                      $(this).removeData('state'); 
                      var self = $(this).parent().find('.select2')[0];
                      setTimeout(function() {
                          $(self).select2('close');
                      }, 0);
                  }
                } catch(error){}   
            });
          }
        } catch(error){ console.log(`There's error on column ${i}`, error) }
      }
    }

    try {
        document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
    } catch(error){}
  }

  function setItemOrderAdditional(item_table=0, tableID){
    if(item_table > 0){
      try {
          $('.btn-finish').prop('disabled', true);
          var iter = 0;
          for(var loop=1;loop<=item_table;loop++){
              try{
                  var item_replace = `<input type="text" class="form-control text-center" name="rsvItem[]" id="rsvItem" value="${loop}" readonly="">`;
                  $(`#${tableID}`).DataTable().row(iter).data()[0] = item_replace;
                  var new_data = $(`#${tableID}`).DataTable().row(iter).data();
                  // $(`#${tableID}`).find('input[name="rsvItem[]"]').eq(iter).parents('td').html(item_replace);
                  // $(`#${tableID}`).find('[name="rsvMaterials[]"]').eq(iter).select2('destroy').parent()[0].childNodes[1].id = `materials${loop}`;
                  // $(`#${tableID}`).find('[name="rsvMeasurement[]"]').eq(iter).parent()[0].childNodes[1].id = `rsvMeasurement-${loop}`;
                  // $(`#${tableID}`).find('[name="rsvPurchGroup[]"]').eq(iter).parent()[0].childNodes[1].id = `rsvPurchGroup-${loop}`;
                  // enableSelect2(`materials${loop}`);
                  $(`#${tableID}`).DataTable().row(iter).data(new_data).draw();
              } catch(error){
                  console.log(error);
              }
              iter++;
          }
          $('.btn-finish').prop('disabled', false);
      } catch(error){}
    }
  }

  function deleteBaris(tableID, objRow=null) {
    var table = document.getElementById(tableID);
    // var rowCount = $(`#${tableID} > tbody > tr`).length;
    var rowCount = $(table).DataTable().rows().count();
    try {
      // var rowIndex = $(objRow).parents('tr')[0].rowIndex;
      var rowIndex = $(table).DataTable().row($(objRow).parents('tr')[0]).index() + 1;
    } catch(error){
      var rowIndex = 0;
    }

    data_item.splice((rowIndex - 1), 1);
    if(data_item.length < 1){
      global_purchasing_group = null;
    }

    if(rowCount == 1){
      $(objRow).parents('tr').find('[name="rsvMaterials[]"]').val('').trigger('change');
      $(objRow).parents('tr').find('[name="rsvMeasurement[]"]').val('');
      $(objRow).parents('tr').find('[name="rsvPurchGroup[]"]').val('');
      return false;
    }

    try {
      $(table).DataTable().row((rowIndex - 1)).remove().draw();
      // $(`#${tableID} > tbody > tr`)[rowIndex - 1].remove();
    } catch(error){}

    try {
      setItemOrderAdditional($(table).DataTable().rows().count(), tableID);
    } catch(error){}

    try {
        document.getElementById('tableRow').value = document.getElementById(tableID).rows.length;
    } catch(error){}

    try {
        calculateGrandTotal(table);
    } catch(error){}
  }

  $(document).on('click', '.btn-finish', function(){
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
            temporary_delete_item.map(function(val, index){
              data_item.splice(val, 1); 
            });
            if(data_item.length){
              $('#modalMaterial').modal('hide');
              setTimeout(function(){
                var index = 0;
                for(var loop=1;loop <= data_item.length;loop++){
                    var item_order = loop;

                    table_data += `<tr>
                      <td class="text-center">${item_order}
                          <input type="hidden" name="marketlistItemOrder[]" value="${item_order}">
                      </td>
                      <td class="text-center">${data_item[index].mtnumber}
                        <input type="hidden" name="marketlistMaterialNumber[]" value="${data_item[index].mtnumber}">
                      </td>
                      <td class="text-left">${data_item[index].mtname}
                        <input type="hidden" name="marketlistMaterialName[]" value="${data_item[index].mtname}">
                      </td>
                      <td class="text-center">${data_item[index].mtunit}
                          <input type="hidden" name="marketlistMaterialUnit[]" value="${data_item[index].mtunit}">
                      </td>
                      <td class="text-center">${data_item[index].purgroup}
                        <input type="hidden" name="marketlistMaterialPurchGroup[]" value="${data_item[index].purgroup}">
                      </td>
                    </tr>`;
                    index++;
                }
                if(table_data){
                  try {
                    $('#detailForm').DataTable().destroy();
                  } catch(error){};
                  $('#detailForm > tbody').html(table_data);
                  $('#detailForm').DataTable({
                     pageLength: 10,
                     dom: '<"row align-items-end" <"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>r<"table-container-h table-wrapper"t>ip',
                     lengthChange: true,
                     paging: true,
                     scrollX: false,
                     "columnDefs": [
                        { "searchable": false, "targets": [0, 3] },
                        { "width": '8%', 'targets': [0]},
                        { "width": '11.5%', 'targets': [1]},
                        { "width": '28%', 'targets': [2]},
                        { "width": '8%', 'targets': [3]},
                        { "width": '15%', 'targets': [4]}
                     ],
                  });
                }
              }, 500);
            } else {
                Swal.fire('Template Item Selection', 'No data has been selected, please select at least 1 material data', 'warning');
            }
        } catch(error){
            Swal.fire('Assign Item Error', 'Something went wrong while adding data to the template item list, please try again in a moment', 'error');
            console.log(error.message)
        }
      }
    });
  });

  $('.btn-submit').on('click', function(e){
    try {
      var form_valid = document.getElementById('form-marketlist').checkValidity();
      if(!form_valid){
        Swal.fire('Edit Existing Template', 'Please make sure all data required is filled or selected', 'warning');
        return false;
      }
    } catch(error){}

    if(data_item.length < 1){
      Swal.fire('Template Item Selection', 'Cannot submit request, no data was selected. Please select at least 1 material data to be added in the template', 'warning');
      return false;
    } else {
      e.preventDefault();
      $('#form-marketlist .btn').prop('disabled', true);
      $('.template-duplicate-text').prop('hidden', false);

      var plant = $('#plant-select').val();
      var template_name = $('#template_name').val();
      var old_template_name = $('#old_template_name').val();

      $.ajax({
        url : "/finance/purchase-requisition-marketlist/master-template/edit",
        type: "GET",
        dataType: 'json',
        data: {'type': 'duplicate-template', 'plant': plant, 'template_name': template_name, 'old_template_name': old_template_name},
        success : function(response){
          if(response.hasOwnProperty('template_found') && response.template_found > 0){
            Swal.fire({
              title: "Duplicate Template Name",
              text: 'Template name is already exist, please use another name or if you want to change or modify the items within its template you can use edit feature instead',
              icon: "error",
              showConfirmButton: true
            });
          } else if(response.hasOwnProperty('template_found') && response.template_found == 0) {
            setTimeout(function(){
              var loader=$("#form-marketlist").attr('data-loader-file');
              var params = $('#detailForm').DataTable().rows().nodes();
              var item_order = $('[name="marketlistItemOrder[]"]', params).serializeArray();
              var mtnumber = $('[name="marketlistMaterialNumber[]"]', params).serializeArray();
              var mtname = $('[name="marketlistMaterialName[]"]', params).serializeArray();
              var mtunit = $('[name="marketlistMaterialUnit[]"]', params).serializeArray();
              var purgroup = $('[name="marketlistMaterialPurchGroup[]"]', params).serializeArray();
              var form = JSON.stringify({'plant': plant, 'template_name': template_name, 'old_template_name': old_template_name, 'marketlistItemOrder': item_order, 'marketlistMaterialName': mtname, 'marketlistMaterialNumber': mtnumber, 'marketlistMaterialUnit': mtunit, 'marketlistMaterialPurchGroup': purgroup})

              $.ajax({
                  type: "POST",
                  url: "/finance/purchase-requisition-marketlist/master-template/edit",
                  data: form,
                  contentType: "application/json",
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
                    if (data.success) {
                      if (data.msg) {
                        Swal.fire({ 
                        icon: 'success',
                        title: 'Success!',
                        text: data.msg,
                        }).then((result) =>{
                            window.location.href="/finance/purchase-requisition-marketlist/master-template";
                        });
                      }
                    } else {
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                      });
                    }
                  },
                  error: function(err) {
                    Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text : err && err.responseJSON && err.responseJSON.msg
                    });

                  }
              });
              return false;
              // $('#form-marketlist').submit();
            }, 500)
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var error = "Something went wrong while trying to read the data, please try again in a moment";
          Swal.fire({
              title: "Oops..",
              text: error,
              icon: "error",
              showConfirmButton: true
          });
        },
        complete: function(){
          $('#form-marketlist .btn').prop('disabled', false);
          $('.template-duplicate-text').prop('hidden', true);
        }
      });
      return false;
    }
  });

</script>
@endsection

