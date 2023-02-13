@extends('layouts.default')

@section('title', 'SAP Barcode Inventory')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
@endsection

{{-- @section('default_body_class',"sidebar-icon-only") --}}

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

  .text-bigger{
      font-size:17px !important;
      color:#000 !important;
  }

  .table-isi-hover{
    background:#ffff99 !important;
  }

.hover-barcode{
    background: #ffff99 !important;
    -webkit-transform:scale(1.5);
    -moz-transform:scale(1.5);
    -ms-transform:scale(1.5);
    -o-transform:scale(1.5);
    transform:scale(1.5);

    /* IE8+ */
   -ms-filter: "progid:DXImageTransform.Microsoft.Matrix(M11=1.5, M12=0, M21=0, M22=1.5, SizingMethod='auto expand')";

   /* IE6 and 7 */
   filter: progid:DXImageTransform.Microsoft.Matrix(
            M11=1.5,
            M12=0,
            M21=0,
            M22=1.5,
            SizingMethod='auto expand');

   margin-left: -64px\9;
   margin-top: -9px\9;
}

.buremin{
    background:rgba(0, 0, 0, 0.9) !important;
}

span.h{
    background:yellow;
    font-weight: bold;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Barcode Inventory</span></li></ol>
  </nav>

 <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                  <div class="px-0 mb-5 col-md-12 float-left">
                      <h1 class="text-center">SAP Barcode Inventory</h1>
                      <h3 class="text-center mt-2">Input your criteria below and find the barcode</h3>
                  </div>
                  <div class="col-md-12 float-left">
                    <form method="GET" action="{{ url('sap/barcode_inventory')}}">
                        <div class="form-group col-lg-3 col-md-6 col-xs-12 col-sm-12 float-left mb-lg-0">
                            <label class="text-bigger">Company</label>
                            <select name="company" id="" class="select2 form-control" onchange="return FilterCompany(this);">
                                @foreach ($data['list_company'] as $list_company)
                                    <option value="{{$list_company->COMPANY_CODE}}"  {{ (!empty($filter_company) && $filter_company == $list_company->COMPANY_CODE) ? 'selected' : ''}}>{{$list_company->COMPANY_CODE}} - {{$list_company->COMPANY_NAME}}</option>
                                @endforeach
                                </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-xs-12 col-sm-12  float-left mb-lg-0">
                            <label class="text-bigger">Plant</label>
                            <select name="plant" id="cmbPlant" class="select2 form-control">
                                <option value="">ALL PLANT</option>
                            @foreach ($data['list_plant'] as $list_plant)
                                <option value="{{$list_plant->SAP_PLANT_ID}}"  {{ (!empty($filter_plant) && $filter_plant == $list_plant->SAP_PLANT_ID) ? 'selected' : ''}}>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-xs-12 col-sm-12  float-left mb-lg-0">
                            <label class="text-bigger">Material Group</label>
                            <select name="matgroup" id="" class="select2 form-control" >
                                <option value="">ALL MATERIAL GROUP</option>
                            @foreach ($data['list_matgroup'] as $list_matgroup)
                                <option value="{{$list_matgroup->MATERIAL_GROUP}}"  {{ (!empty($filter_matgroup) && $filter_matgroup == $list_matgroup->MATERIAL_GROUP) ? 'selected' : ''}}>{{$list_matgroup->MATERIAL_GROUP}} - {{$list_matgroup->MATERIAL_GROUP_DESC}}</option>
                            @endforeach
                            </select>

                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-xs-12 col-sm-12  float-left mb-lg-0">
                            <label class="text-bigger">Material Name</label>
                            <input type="text" name="keyword" id="inpKeyword" value="{{ $keyword }}" placeholder="Input... " class="form-control">
                        </div>
                        <div class="form-group col-md-12 float-left" style="padding-top:10px;">
                            <button type="submit" class="btn btn-primary btn-block" id="btnFilter"><i class="fa fa-search"></i> SEARCH</button>
                        </div>
                    </form>
                  </div>
                </div>

                @if (!empty($keyword) || !empty($filter_company) && !empty($list_material))


                <hr class="my-0">

                <div class="card-body main-wrapper">
                    @if(empty($keyword))
                    <p style="font-size:20px;" class="text-center text-black mt-10" > Showing barcode inventory with the criteria above <span class="h">{{$keyword}}</span></p>
                    @else
                        <p style="font-size:20px;" class="text-center text-black mt-10" > Showing barcode inventory with keyword <span class="h">{{$keyword}}</span></p>
                    @endif
                    <p class="text-center" style="font-size:16px;"> click on each row to reveal the barcode </p>
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table class="table table-bordered table-striped" id="inventory"  style="cursor: pointer">
                            <thead>
                                <tr>
                                    <th class="bg-secondary text-white"	style="width:10%;">MATERIAL CODE</th>
                                    <th class="bg-secondary text-white"	style="width:40%;"	>MATERIAL NAME</th>
                                    <th class="bg-secondary text-white"	style="width:50%;">BARCODE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=0; @endphp
                                @if (!empty($list_material) && count($list_material)>0)
                                    @php $i++; @endphp
                                    @foreach ($list_material as $material)
                                        {{-- @php $mat_desc = strval(preg_replace("/\w*?$keyword\w*/i", "<b>$0</b>", $material['MAKTX'])); @endphp --}}
                                        @php $mat_desc = highlight_keywords($keyword, $material['MAKTX']); @endphp
                                    <tr style="height:80px !important;" >

                                        <td class="text-center text-bigger font-weight-bold">{{ltrim($material['MATNR'],'000000000') }}</td>
                                        <td class="text-left text-bigger font-weight-bold">{!!$mat_desc !!}</td>
                                        <td>
                                            <div style="display:table;margin:0 auto;" class="the-barcode buremin">
                                            {!! DNS1D::getBarcodeHTML(ltrim($material['MATNR'],'000000000'), 'C93', 2, 35, 'black', false); !!}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else


                                @endif
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

  <script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
    <script>
        (function($){
            $.fn.focusTextToEnd = function(){
                this.focus();
                var $thisVal = this.val();
                this.val('').val($thisVal);
                return this;
            }
        }(jQuery));
    $(document).ready(function() {
        $('#inventory').dataTable( {
        // "pageLength": 100,
        "aaSorting": [],
        // "scrollY": "2000px",
        // "scrollCollapse": true,
        "paging": false
        } );
        // $("body").addClass('sidebar-icon-only');
        $("#inventory > tbody > tr").click(function(){
            $("#inventory > tbody > tr").removeClass("table-isi-hover");
            $("#inventory > tbody > tr").find('.the-barcode').removeClass("hover-barcode");
            $("#inventory > tbody > tr").find('.the-barcode').removeClass("buremin");


            $(this).toggleClass("table-isi-hover");
            $(this).find('.the-barcode').toggleClass("hover-barcode");

            $("#inventory > tbody > tr").find('.the-barcode').not('.hover-barcode').addClass("buremin");
        });



        $(".select2").select2({
        });
        setTimeout(function() {
            $("#inpKeyword").focusTextToEnd();
    }, 1000);

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
                // buat option default
                let opt = document.createElement('option');
                opt.value = '';
                opt.innerHTML = 'ALL PLANT';
                select.appendChild(opt);

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
