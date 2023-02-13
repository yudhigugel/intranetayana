@extends('layouts.default')

@section('title', 'SAP Inventory Stock')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
@endsection

@section('styles')
<style type="text/css">
  .overlay {
    display: none;
    justify-content: center;
    align-items: flex-start;
    position: absolute;
    z-index: 2;
    opacity: 0;
    background: rgba(255, 255, 255, 0.86);
    transition: opacity 200ms ease-in-out;
    margin: -15px 0 0 0;
    top: 15px;
    left: 0;
    width:100%;
    height: 100%;
  }
  .overlay.in {
    opacity: 1;
    display: flex;
  }
  .fl-scrolls {
    bottom:0;
    height:35px;
    overflow:auto;
    position:fixed;
  }
  .fl-scrolls div {
    height:1px;
    overflow:hidden;
  }
  .fl-scrolls div:before {
    content:""; /* fixes #6 */
  }
  .fl-scrolls-hidden {
    bottom:9999px;
  }
  .sticky {
    position: fixed;
    top: 45px;
    z-index: 99;
    box-shadow: 0px 3px 7px -5px #878787;
  }
  .sticky + .main-wrapper {
    padding-top: auto;
  }
  #header{
    transform: all .7s ease-in-out;
  }

#content-table{

  }

  #content-table tr th{
    text-align: center;
    border: 1px solid #ddd;
    font-size:12px !important;
  }

  #content-table tr td{
    text-align: left;
    border: 1px solid #ddd;
    font-size:11px !important;
  }

  .dataTables_wrapper .dataTable thead th{
    border-bottom-width:thin;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Inventory</span></li></ol>
  </nav>

 <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                  <div class="px-0 mb-3 col-md-4 float-left">
                      <h1>SAP Inventory Stock</h1>
                  </div>
                  <div class="col-md-12 float-left">
                    <form method="GET" action="{{ url('sap/inventory')}}">
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Company</label>
                                <select name="company" id="" class="select2 form-control" onchange="return FilterCompany(this);">
                                @foreach ($data['list_company'] as $list_company)
                                    <option value="{{$list_company->COMPANY_CODE}}"  {{ (!empty($filter_company) && $filter_company == $list_company->COMPANY_CODE) ? 'selected' : ''}}>{{$list_company->COMPANY_CODE}} - {{$list_company->COMPANY_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Plant</label>
                                <select name="plant" id="cmbPlant" class="select2 form-control">
                                @foreach ($data['list_plant'] as $list_plant)
                                    <option value="{{$list_plant->SAP_PLANT_ID}}"  {{ (!empty($filter_plant) && $filter_plant == $list_plant->SAP_PLANT_ID) ? 'selected' : ''}}>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Material Group</label>
                                <select name="matgroup" id="" class="select2 form-control" >
                                    <option value="">ALL MATERIAL GROUP</option>
                                @foreach ($data['list_matgroup'] as $list_matgroup)
                                    <option value="{{$list_matgroup['MATKL']}}"  {{ (!empty($list_matgroup) && $list_matgroup == $list_matgroup['MATKL']) ? 'selected' : ''}}>{{$list_matgroup['MATKL']}} - {{$list_matgroup['WGBEZ']}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 float-left">
                                <label for="">Apply</label>
                                <button type="submit" class="btn btn-primary form-control" id="btnFilter">Filter</button>
                            </div>
                        </form>
                  </div>
                </div>





                <hr class="my-0">
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-bordered table-striped" id="inventory" style="white-space: nowrap">
                            <thead>
                                <tr>
                                    <th class="bg-secondary text-white"	style="width: 4%" rowspan="2">NO</th>
                                    <th class="bg-secondary text-white"	 rowspan="2">MATERIAL</th>
                                    <th class="bg-secondary text-white"	 rowspan="2">DESCRIPTION</th>
                                    <th class="bg-secondary text-white"	 rowspan="2">MAT GROUP</th>
                                    <th class="bg-secondary text-white"	 rowspan="2">UOM</th>
                                    <th class="bg-secondary text-white"	 rowspan="2">GRAND TOTAL</th>
                                    @foreach ($plant_sloc as $key_plant => $val_plant)

                                        <th class="bg-secondary text-white" colspan="{{$val_plant['total_sloc']}}">{{ $key_plant}}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($plant_sloc as $key_sloc => $val_sloc)
                                        @php unset($val_sloc['total_sloc']); @endphp
                                        @foreach ($val_sloc as $key_child => $val_child)
                                            @foreach ($val_child as $val_grandchild)
                                                <th class="bg-secondary text-white">{{ $val_grandchild['code']}}<br>{{$val_grandchild['name']}}</th>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                              </tr>
                            </thead>
                            <tbody>
                                @php $i=0; @endphp
                                @foreach ($inventory as $material => $value)
                                    @php $i++; @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{ ltrim($material,'000000000') }}</td>
                                        <td class="text-left">{{$value['MATERIAL_DESC']}}</td>
                                        <td>{{$value['MATERIAL_GROUP']}}</td>
                                        <td>{{$value['UOM']}}</td>
                                        <td>{{$value['GRAND_TOTAL']}}</td>
                                        @foreach ($plant_sloc as $key_sloc => $val_sloc)
                                            @php unset($val_sloc['total_sloc']); @endphp
                                            @foreach ($val_sloc as $key_child => $val_child)
                                                @foreach ($val_child as $val_grandchild)
                                                    @php
                                                        $jumlah_item = (!empty($value['PLANT'][$key_sloc][$val_grandchild['code']]['TOTAL'])) ? $value['PLANT'][$key_sloc][$val_grandchild['code']]['TOTAL'] : 0;
                                                    @endphp
                                                    <td>{{ $jumlah_item }}</td>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tr>

                                @endforeach
                            </tbody>
                          </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  <script type="text/javascript" src="/js/vendor/moment.min.js"></script>
  <script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
  <script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>

  <script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
    <script>
    $(document).ready(function() {
        $(".select2").select2({
        });
        $('#inventory').dataTable( {
        "pageLength": 100
        } );
        $('#daterange').daterangepicker(null, function(start, end, label) {
            const date_start = moment(start).format('YYYY-MM-DD');
            const date_end = moment(end).format('YYYY-MM-DD');
            window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
            // console.log(start.toISOString(), end.toISOString(), label);
        });
    });

    function FilterCompany(e){

        var company = $(e).find('option:selected').val();
        $.ajax({
            url: "{{ url('sap/inventory/ajaxFilterCompany')}}",
            type: "post",
            data: {company : company} ,
            beforeSend : function (act){
                $("#cmbPlant").prop("disabled", true);
                $("#btnFilter").prop("disabled", true);
            },
            success: function (response) {
                $('#cmbPlant').empty().trigger("change");
                var select = document.getElementById('cmbPlant');
                console.log(select);
                let plant = response.data;
                plant.forEach(element => {
                    var opt = document.createElement('option');
                    opt.value = element.SAP_PLANT_ID;
                    opt.innerHTML = element.SAP_PLANT_ID+' '+element.SAP_PLANT_NAME;
                    select.appendChild(opt);
                });

                $("#cmbPlant").prop("disabled", false);
                $("#btnFilter").prop("disabled", false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            }
        });
    }

    </script>
@endsection
