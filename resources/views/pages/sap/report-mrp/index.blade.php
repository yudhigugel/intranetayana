@extends('layouts.default')

@section('title', 'SAP MRP report')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.dataTables.min.css">
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

  .table{
      color:#000 !important;
      cursor:pointer;
  }

  .table-isi-hover td{
    background:#ffff99 !important;
  }
/*
  table#inventory.dataTable tbody tr:hover td {
  background-color: #ffa !important;
}
table#inventory.dataTable thead:hover td {
  background-color: #ffa !important;
} */
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>MRP Report</span></li></ol>
  </nav>

 <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                    <div class="px-0 mb-5 col-md-12 float-left">
                        <h1 class="text-center">SAP MRP Report</h1>
                        <h3 class="text-center mt-2">Input your criteria below and find the materials</h3>
                    </div>
                  <div class="col-md-12 float-left">
                    <form method="GET" action="{{ url('sap/report_mrp')}}">
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Company</label>
                                <select name="company" id="cmbCompany" class="select2 form-control" onchange="return FilterCompany(this);">
                                @foreach ($data['list_company'] as $list_company)
                                    <option value="{{$list_company->COMPANY_CODE}}"  {{ (!empty($filter_company) && $filter_company == $list_company->COMPANY_CODE) ? 'selected' : ''}}>{{$list_company->COMPANY_CODE}} - {{$list_company->COMPANY_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Plant</label>
                                <select name="plant" id="cmbPlant" class="select2 form-control" onchange="return FilterPlant(this);" required>
                                @foreach ($data['list_plant'] as $list_plant)
                                    <option value="{{$list_plant->SAP_PLANT_ID}}"  {{ (!empty($filter_plant) && $filter_plant == $list_plant->SAP_PLANT_ID) ? 'selected' : ''}}>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Filter by Sloc</label>
                                <select name="sloc[]" id="cmbSloc" class="select2-sloc form-control"  multiple="multiple">
                                    {{-- <option value="" >ALL SLOC</option> --}}
                                    @foreach ($data['list_sloc'] as $list_sloc)
                                        <option value="{{$list_sloc['STORAGE_LOCATION']}}"  {{ (!empty($filter_sloc) && in_array($list_sloc['STORAGE_LOCATION'],$filter_sloc)) ? 'selected' : ''}}>{{$list_sloc['STORAGE_LOCATION']}} - {{$list_sloc['STORAGE_LOCATION_DESC']}}</option>
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

                @if (!$harus_pilih_dulu)
                    <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                        <div class="table-wrapper">
                        <div class="table-container-h table-responsive">
                            <table class="table table-bordered table-striped" id="inventory" style="white-space: nowrap">
                                <thead>
                                    <tr>
                                        <th class="bg-secondary text-white"	 rowspan="2">MATERIAL</th>
                                        <th class="bg-secondary text-white"	 rowspan="2">DESCRIPTION</th>
                                        <th class="bg-secondary text-white"	 rowspan="2">UOM</th>
                                        @foreach ($plant_sloc as $key_sloc => $val_sloc)
                                        @php unset($val_sloc['total_sloc']); @endphp
                                            @foreach ($val_sloc as $key_child => $val_child)
                                                @foreach ($val_child as $val_grandchild)
                                                    <th  class="bg-secondary text-white" colspan="4">{{ $val_grandchild['code']}}<br>{{$val_grandchild['name']}}</th>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    <tr>
                                    @foreach ($plant_sloc as $key_sloc => $val_sloc)
                                    @php unset($val_sloc['total_sloc']); @endphp
                                        @foreach ($val_sloc as $key_child => $val_child)
                                            @foreach ($val_child as $val_grandchild)
                                                <th class="pembatas">LEAD TIME</th>
                                                <th>SAFETY STOCK</th>
                                                <th>MAX STOCK</th>
                                                <th>AVG DAILY USAGE</th>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result as $material)
                                    <tr>
                                        <td>{{ltrim($material['MATERIAL'],"000000000")}}</td>
                                        <td class="text-left">{{$material['DESCRIPTION']}}</td>
                                        <td>{{$material['UOM']}}</td>
                                        @foreach ($plant_sloc as $key_sloc => $val_sloc)
                                        @php unset($val_sloc['total_sloc']); @endphp
                                            @foreach ($val_sloc as $key_child => $val_child)
                                                @foreach ($val_child as $val_grandchild)
                                                    @php
                                                        $lead_time = $safety_stock = $max_stock = $daily_usage = 0;
                                                        if(!empty(($material['SLOC'][$val_grandchild['code']]))){
                                                        @$lead_time = (float)$material['SLOC'][$val_grandchild['code']][0]['LEAD_TIME'];
                                                        @$safety_stock = (float)$material['SLOC'][$val_grandchild['code']][0]['SAFETY_STOCK'];
                                                        @$max_stock = (float)$material['SLOC'][$val_grandchild['code']][0]['MAX_STOCK'];
                                                        @$daily_usage = (float)$material['SLOC'][$val_grandchild['code']][0]['AVG_DAILY_USAGE'];
                                                        }
                                                    @endphp
                                                    <td class="pembatas">{{ $lead_time }}</td>
                                                    <td>{{ $safety_stock }}</td>
                                                    <td>{{ $max_stock }}</td>
                                                    <td>{{ $daily_usage }}</td>
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
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  <script type="text/javascript" src="/js/vendor/moment.min.js"></script>
  <script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
  <script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
  <script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
  <script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>

  <script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
    <script>
    $(document).ready(function() {
        $(".select2").select2({});
        $(".select2-sloc").select2({
            placeholder: "ALL SLOC",
        });
        $('#inventory').dataTable( {
            "aaSorting": [],
            "scrollY": "400px",
            "stateSave": true,
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "autoWidth": false,
            "scrollCollapse": true,
            "fixedColumns": {
                left: 3
            }
        } );
        $('#daterange').daterangepicker(null, function(start, end, label) {
            const date_start = moment(start).format('YYYY-MM-DD');
            const date_end = moment(end).format('YYYY-MM-DD');
            window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
            // console.log(start.toISOString(), end.toISOString(), label);
        });


    });

    $("#inventory > tbody > tr").click(function(){
            $("#inventory > tbody > tr ").removeClass("table-isi-hover");
            $(this).toggleClass("table-isi-hover");
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
                $('#cmbPlant').append($('<option>', {
                    value: '',
                    text: 'SELECT PLANT'
                }));
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

    function FilterPlant(e){
        var company = $("#cmbCompany").find('option:selected').val();
        var plant = $(e).find('option:selected').val();
        console.log(company);
        console.log(plant);
        if(company && plant){
            $.ajax({
                url: "{{ url('sap/inventory/ajaxFilterPlant')}}",
                type: "post",
                data: {company: company, plant : plant} ,
                beforeSend : function (act){
                    $("#cmbPlant").prop("disabled", true);
                    $("#cmbSloc").prop("disabled", true);
                    $("#btnFilter").prop("disabled", true);
                },
                success: function (response) {
                    console.log(response);
                    $('#cmbSloc').empty().trigger("change");
                    var select = document.getElementById('cmbSloc');
                    // $('#cmbSloc').append($('<option>', {
                    //     value: '',
                    //     text: 'ALL SLOC'
                    // }));
                    let plant = response.data;
                    plant.forEach(element => {
                        var opt = document.createElement('option');
                        opt.value = element.STORAGE_LOCATION;
                        opt.innerHTML = element.STORAGE_LOCATION+' '+element.STORAGE_LOCATION_DESC;
                        select.appendChild(opt);
                    });
                    $("#cmbPlant").prop("disabled", false);
                    $("#cmbSloc").prop("disabled", false);
                    $("#btnFilter").prop("disabled", false);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                }
            });
        }
    }

    </script>
@endsection
