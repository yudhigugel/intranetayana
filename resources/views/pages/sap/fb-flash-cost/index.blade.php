@extends('layouts.default')

@section('title', 'SAP FB Flash Cost Report')
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

</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>FB Flash Cost Report</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
            <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body pb-0 bg-white" id="header">
                    <div class="px-0 mb-5 col-md-12 float-left">
                        <h1 class="text-center">FB Flash Cost Report</h1>
                        <h3 class="text-center mt-2">Input your criteria below and view the summary</h3>
                    </div>
                    <div class="col-md-12 float-left">
                        <form method="GET" action="{{ url('sap/report_ar_aging') }}" id='formRequest'>
                            
                        </form>
                    </div>
                </div>
                <hr class="my-0">
            </div>
        </div>
</div>

<div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
        </div>
        <div class="card">
            <div class="card-body pb-0 bg-white" id="header">
                
            </div>
            <hr class="my-0">
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
                    "width" : "5%"
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
            "order": [[ 1, "desc" ]],

        // }).ajax.reload();
        });
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
