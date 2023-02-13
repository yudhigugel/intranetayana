@extends('layouts.default')

@section('title', 'SAP Account Receivable Aging Report')
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
  .btn-success:focus, .btn-success.focus {
    background-color: #0ddbb9 !important;
   }
  .toolbar{
      display:flex;
      float:left;
  }

</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Account Payable Aging Report</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
            <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                    <div class="px-0 mb-5 col-md-12 float-left">
                        <h1 class="text-center">Account Payable Aging Report</h1>
                        <h3 class="text-center mt-2">Input your criteria below and view the summary</h3>
                    </div>
                    <div class="col-md-12 float-left">
                        <form method="GET" action="{{ url('sap/report_ap_aging') }}" id='formRequest'>
                            <div class="form-group col-md-4 float-left">
                                <label for="">Plant</label>
                                {{--<select name="company" id="cmbCompany" class="select2 form-control">
                                @foreach ($data['list_company'] as $list_company)
                                    <option value="{{$list_company->COMPANY_CODE}}"  {{ (!empty($filter_company) && $filter_company == $list_company->COMPANY_CODE) ? 'selected' : ''}}>{{$list_company->COMPANY_CODE}} - {{$list_company->COMPANY_NAME}}</option>
                                @endforeach
                                </select>--}}

                                <select name="plant" id="cmbCompany" class="select2 form-control">
                                @foreach ($data['list_plant'] as $list_plant)
                                    <option value="{{$list_plant->COMPANY_CODE}}-{{$list_plant->SAP_PLANT_ID}}"  {{ (isset($data_plant) && !empty($data_plant) && trim($data_plant->SAP_PLANT_ID) == trim($list_plant->SAP_PLANT_ID)) ? 'selected' : ''}}>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Customer Number</label>
                                <div class="input-group">
                                    <input type="text" id="customer_search" name="customer_number" class="form-control" required style="text-transform:uppercase" oninput="disableButton('customer_search','sCustomer');" placeholder="Search Customer Number" onkeypress="return (event.charCode !=13)" value="{{$filter_customer}}">
                                    <input type="hidden" id="customer_number">
                                    <span class="input-group-btn">
                                        <button class="btn blue btn-primary" type="button" onclick="SearchCustomer();" id="sCustomer"><i class="fa fa-search
                                        "></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-3 float-left">
                                <label for="">Currency</label>
                                <select name="currency" id="cmbCurrency" class="select2 form-control">
                                @foreach ($data['currency'] as $list_currency)
                                    <option value="{{$list_currency}}"  {{ (!empty($filter_currency) && $filter_currency == $list_currency) ? 'selected' : ''}}>{{$list_currency}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 float-left">
                                <label for="">Apply</label>
                                <button type="submit" class="btn btn-success text-white form-control" id="btnFilter">SEARCH</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="my-0">
            </div>
        </div>
</div>
@if(!empty($data_customer))
<div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
        </div>
        <div class="card">
            <div class="card-body pb-0 bg-white" id="header">
                <div class="px-0 col-md-12 float-left">
                    <h2 class="">Customer Info</h2>
                </div>
                <div class="col-md-12 float-left" style="padding-bottom:20px;">
                    <div class="col-md-6 float-left">
                        <table class="table-customer text-left">
                            <tr><td>SAP Customer No</td><td>:</td><td>{{ $data_customer[0]['CUSTOMER_CODE'] }}</td></tr>
                            <tr><td>Customer Name</td><td>:</td><td>{{ $data_customer[0]['CUSTOMER_NAME'] }}</td></tr>
                            <tr><td>Address</td><td>:</td><td>{{ $data_customer[0]['STREET_1'] }}</td></tr>
                            <tr><td>City</td><td>:</td><td>{{ $data_customer[0]['CITY'] }}</td></tr>
                            <tr><td>Country</td><td>:</td><td>{{ $data_customer[0]['COUNTRY_DESC'] }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6 float-left">
                        <table class="text-left">
                            <tr><td>ATTN</td><td>:</td><td>-</td></tr>
                            <tr><td>Phone</td><td>:</td><td>{{ empty($data_customer[0]['TEL_NUMBER']) ? '-' : $data_customer[0]['CUSTOMER_NAME'] }}</td></tr>
                            <tr><td>Email</td><td>:</td><td>{{ empty($data_customer[0]['EMAIL']) ? '-' : $data_customer[0]['EMAIL'] }}</td></tr>
                            <tr><td>Tax ID/NPWP</td><td>:</td><td>{{ empty($data_customer[0]['NPWP']) ? '-' : $data_customer[0]['NPWP']}}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <hr class="my-0">
        </div>
    </div>
</div>
@endif


<div class="row flex-grow">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                @if(count($data_aging) > 0)
                {{--<h2>{{$data_company->COMPANY_CODE}} - {{$data_company->COMPANY_NAME}}</h2>--}}
                <h2>{{ isset($data_plant->SAP_PLANT_ID) ? $data_plant->SAP_PLANT_ID : 'Unknown Plant ID' }} - 
                    {{ isset($data_plant->SAP_PLANT_NAME) ? $data_plant->SAP_PLANT_NAME : 'Unknown Plant Name' }}
                </h2>
                <table class="table table-border table-striped" id="content-table">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white text-center">Account Statement Date</th>
                            <th class="bg-secondary text-white text-center">Account Statement No</th>
                            <th class="bg-secondary text-white text-center">Payment Terms</th>
                            <th class="bg-secondary text-white text-center">Due Date</th>
                            <th class="bg-secondary text-white text-center">Amount</th>
                            <th class="bg-secondary text-white text-center">0-30 Days</th>
                            <th class="bg-secondary text-white text-center">31-60 Days</th>
                            <th class="bg-secondary text-white text-center">61-90 Days</th>
                            <th class="bg-secondary text-white text-center">>91 Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $grand_total=0;
                        $grand_total_0_30 = $grand_total_31_60 = $grand_total_61_90 = $grand_total_91 = 0;
                        @endphp
                        @foreach ($data_aging as $ar)
                        @php
                            @$grand_total+=$ar['TOTAL_AMOUNT'];
                            @$grand_total_0_30+=$ar['TOTAL_0_30'];
                            @$grand_total_31_60+=$ar['TOTAL_31_60'];
                            @$grand_total_61_90+=$ar['TOTAL_61_90'];
                            @$grand_total_91+=$ar['TOTAL_91'];
                        @endphp
                            <tr>
                                <td>
                                    <span style="display: none;">
                                        {{ !empty($ar['TRANSACTION_DATE']) && $ar['TRANSACTION_DATE']!=='00000000' ? strtotime($ar['TRANSACTION_DATE']) : 0 }}
                                    </span>
                                    {{ !empty($ar['TRANSACTION_DATE']) && $ar['TRANSACTION_DATE']!=='00000000' ? date('d M Y',strtotime($ar['TRANSACTION_DATE'])) : '-' }}
                                </td>
                                <td>{{ !empty($ar['ACCOUNTING_DOCUMENT_NUMBER']) ? $ar['ACCOUNTING_DOCUMENT_NUMBER'] : '-' }}</td>
                                <td>{{ !empty($ar['TERMS_OF_PAYMENT']) ? $ar['TERMS_OF_PAYMENT'] : '-' }}</td>
                                <td>
                                    <span style="display: none;">
                                        {{ !empty($ar['NET_DUE_DATE']) && $ar['NET_DUE_DATE']!=='00000000' ? strtotime($ar['NET_DUE_DATE']) : 0 }}
                                    </span>
                                    {{ !empty($ar['NET_DUE_DATE']) && $ar['NET_DUE_DATE']!=='00000000' ? date('d M Y',strtotime($ar['NET_DUE_DATE'])) : '-' }}
                                </td>
                                <td class="text-right">{{ !empty($ar['TOTAL_AMOUNT']) ? number_format($ar['TOTAL_AMOUNT']) : '-' }}</td>
                                <td class="text-right">{{ !empty($ar['TOTAL_0_30']) ? number_format($ar['TOTAL_0_30']) : '-' }}</td>
                                <td class="text-right">{{ !empty($ar['TOTAL_31_60']) ? number_format($ar['TOTAL_31_60']) : '-' }}</td>
                                <td class="text-right">{{ !empty($ar['TOTAL_61_90']) ? number_format($ar['TOTAL_61_90']) : '-' }}</td>
                                <td class="text-right">{{ !empty($ar['TOTAL_91']) ? number_format($ar['TOTAL_91']) : '-' }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="bg-secondary text-white text-center" colspan="4">TOTAL AMOUNT</th>
                            <th class="bg-secondary text-white text-right" >{{ number_format($grand_total)}}</th>
                            <th class="bg-secondary text-white text-right">{{ number_format($grand_total_0_30)}}</th>
                            <th class="bg-secondary text-white text-right">{{ number_format($grand_total_31_60)}}</th>
                            <th class="bg-secondary text-white text-right">{{ number_format($grand_total_61_90)}}</th>
                            <th class="bg-secondary text-white text-right">{{ number_format($grand_total_91)}}</th>
                        </tr>

                    </tfoot>
                </table>
                @else
                    @if (!empty($filter_company) && !empty($filter_customer))
                        <h2 class="text-center"> Sorry, no data available </h2>
                    @endif

                @endif

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
  <script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
  <script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
  <script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>

  <script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
    <script>
    $(document).ready(function() {
        $(".select2").select2({});
        $(".select2-vendor").select2({
            placeholder: "ALL VENDOR",
        });
        var tables =  $('#content-table').DataTable({
            "dom": '<"toolbar">frtip',
            "fixedHeader": {
                header: true,
                footer:true
            },
            "aaSorting": [],
            "searching":true,
            "paging":   false,
            "autoWidth":false,
            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
            "columns": [
                {
                    "width": "15%",
                },
                {
                    "width": "15%",
                },
                {
                    "width": "5%",
                },
                {
                    "width" : "10%"
                },
                {
                    "width" : "15%"
                },
                {
                    "width" : "10%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "10%"
                }
            ],
            "pagingType":"full_numbers",
            "language": {
                "zeroRecords": "Sorry, your query doesn't match any data",
                "processing": ''
            },
            "order": [[ 0, "asc" ]],

        // }).ajax.reload();
        });

        $("div.toolbar").html('<b>CURRENCY : {{ $filter_currency }}</b>');





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

    function SearchCustomer(){
        var keywordParameter = document.getElementById('customer_search').value;
        var company = document.getElementById('cmbCompany').value;
        if(keywordParameter.length > 2){
            var vUrl= window.location.href.split(/[?#]/)[0]+'/search-customer?keywordParameter='+keywordParameter+'&company='+company;
            doPopUpWindow = window.open(vUrl,"cari","width=900,height=500,resizable=yes,scrollbars=yes,left=80,top=80");
        }
        else{
            if(keywordParameter.length > 0  && keywordParameter.length <= 3){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Vendor Name length must be greater than 3 characters',
                });
            }
            else{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please input customer name!',
                });
            }
        }
    }

    function disableButton(idTag,buttonId){
        var x = document.getElementById(idTag).value;

        if(x.length > 2){
            document.getElementById(buttonId).removeAttribute("disabled");
        }
        else{
            document.getElementById(buttonId).setAttribute("disabled","disabled");
        }
    }

    </script>
@endsection
