@extends('layouts.default')

@section('title', 'VCC Parsing')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/gijgo-master/dist/modular/css/timepicker.css')}}" />
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
{{-- <link rel="stylesheet" href="/css/sweetalert.min.css"> --}}
@endsection
@section('styles')
<style>
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #a7afb7 !important;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color:  #a7afb7;;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color:  #a7afb7;;
}

.red{
    color:red !Important;
}

#section-3, #section-4, #section-5{
    display: none;
}

.caption-field{
    font-size:14px;
    text-align: left;
}

.detail-tb tr td{
    text-align: left;
}


</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Forms</a></li>
      <li class="breadcrumb-item"><a href="#">VCC Parsing</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>All Data</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">All Data VCC Parsed</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Fetch Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker2" name="request_date_from" id="request_date_from" value="{{ $data['request_date_from'] }}">
                                        <span class="input-group-addon input-group-append border-left"><span class="mdi mdi-calendar input-group-text"></span></span>
                                        <input type="text" class="form-control datepicker2" name="request_date_to" id="request_date_to" value="{{ $data['request_date_to'] }}">
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
                                        <option value="processed" {{ ($data['status']=="processed")? 'selected' : '' }} >Processed</option>
                                        <option value="unprocessed" {{ ($data['status']=="unprocessed")? 'selected' : '' }}>Unprocessed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('vcc-parsing/data')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th style="min-width:90px;">Rsv. ID</th>
                            <th style="min-width:90px;">Fetch from Zapier</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">OTA Name</th>
                            <th style="min-width:90px;">Property</th>
                            <th style="min-width:90px;">Guest</th>
                            <th style="min-width:90px;">Amount</th>
                            <th style="min-width:90px;">Valid Start</th>
                            <th style="min-width:90px;">Valid End </th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>

</div>



<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailLabel">Detail VCC</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalDetail">

			</div>
		</div>
	</div>
</div>

@endsection
@section('scripts')


<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
{{-- <script type="text/javascript" src="/js/vendor/sweetalert.min.js"></script> --}}
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript">
    var objFile = {};var objJsonDetail = {}; var objRow = {};


    $(document).ready( function () {
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




        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            "dom": '<"dt-buttons"Bfl>rtp',
            "ajax": {
                "type" : "POST",
                "url" : "/vcc-parsing/data/getData",
                "dataSrc": "data",
                "data" : {
                    "employee_id":updateBy,
                    "filter":"",
                    "value":"",
                    "status":status,
                    "insert_date_from":request_date_from,
                    "insert_date_to":request_date_to
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
            "autoWidth": true,
            'info':true,
            "fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "columns": [
                {
                    "data": "id"
                },
                {
                    "data": "reservationid",
                    "render": function (id, type, full, meta){
                        var button ='<a href="#" data-toggle="modal" data-target="#modalDetail" onclick="getDetail(\''+full.id+'\')" >'+id+'</a>';
                        return button;
                    }
                },
                {
                    "data": "date_fetched_from_mail", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return moment(id).format("DD/MM/YYYY - HH:mm");
                        }
                },
                {
                    "data": "is_processed",
                    "render": function (id, type, full, meta){
                        if(id =='0'){
                            return 'Unprocessed';
                        }else{
                            return 'Processed';
                        }
                    }
                },
                {
                    "data": "ota_name"
                },
                {
                    "data": "property_name"
                },
                {
                    "data": "guestname"
                },
                {
                    "data": "vccamount", className: 'text-right',
                    "render": function (id, type, full, meta)
                        {
                            var cur = full.invoicecurrency;
                            return cur+' '+id;

                        }
                },
                {
                    "data": "vccdatestart", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return moment(id).format("DD/MM/YYYY");
                        }
                },
                {
                    "data": "vccdateend", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return moment(id).format("DD/MM/YYYY");
                        }
                },

            ],
            "buttons": [
                // 'colvis',
                'copyHtml5',
                'csvHtml5',
                'excelHtml5',
                'print'
            ],
            "order": [[ 0, "desc" ]],
        });


        table.on( 'order.dt search.dt', function () {
            let i = 1;

            table.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                this.data(i++);
            } );
        } ).draw();



        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
            if ( typeof( onoff ) == 'undefined' ) {
                onoff = true;
            }
            this.oApi._fnProcessingDisplay( oSettings, onoff );
        };
    });

    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }


    $(".select2").select2({
    });

    function getDetail(id){

        $('#modalDetail #bodyModalDetail').html('');

        $.get("{{url('vcc-parsing/data/modal-detail')}}", { id : id}, function( data ) {
            $('#modalDetail #bodyModalDetail').html(data);
        });


    }










</script>
@endsection
