@extends('layouts.default')

@section('title', 'Request Entertainment Form')
@section('custom_source_css')

@endsection
@section('styles')
<style>
/* #modalRequest{
padding:0px !important;
}

#modalRequest .modal-lg{
    max-width: 100% !important;
}
#modalRequest .modal-dialog{
    margin-top:0px !important;
}

#modalRequest .modal-body{
    padding:0px !important;
}

.modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 100%;
  border-radius: 0;
} */


/* CSS Zoho Creator */
.zcform_Request_Form .first-column .form-label{
    width: 200px !important;
}
form.label-left {
    width:100% !important;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Entertainment Form</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Report</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Report Request Form Entertainment</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="get" action="" name="form_merge_list" id="form_merge_list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Request Date</label>
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
                                        <option value="Requested" {{ ($data['status']=="Requested")? 'selected' : '' }} >Requested</option>
                                        <option value="Approved" {{ ($data['status']=="Approved")? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ ($data['status']=="Rejected")? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{url('finance/entertainmentForm/reports')}}" class="btn btn-danger" id="resetList">Reset</a>
                            <button type="submit" class="btn btn-primary" id="submitChange">Search</button>
                        </div>
                    </div>
                </form>
                <br />
                <table class="table table-bordered datatable requestList" id="requestList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Form No</th>
                            <th style="min-width:90px;">Requestor Name</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Request Date</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="min-width:90px;">Plant Name</th>
                            <th style="min-width:90px;">Department</th>
                            <th style="min-width:90px;">Reason</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFileLabel">@if(env('APP_ENV')=='production') File List @else Request Detail @endif </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
            <div class="modal-body" id="bodyModalFile">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalHistory" tabindex="-1" role="dialog" aria-labelledby="modalHistoryLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalHistoryLabel">History List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="bodyModalHistory">
                <table class="table table-bordered datatable requestHistoryList" id="requestHistoryList" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">Level Approval</th>
                            <th style="min-width:90px;">Level Desc</th>
                            <th style="min-width:90px;">Approval Name</th>
                            <th style="min-width:90px;">Status</th>
                            <th style="min-width:90px;">Approval Date</th>
                            <th style="min-width:90px;">Reason</th>
                        </tr>
                    </thead>
                    <tbody id="historyListDT">
                    </tbody>
                </table>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="updateBy" id="updateBy" class="form-control" value="{{$data['employee_id']}}">
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('/atlant/html/js/plugins/bootstrap/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

  });
  var objFile = {};var objJsonDetail = {};
    $('#modalRequest').on('shown.bs.modal', function (e) {
    // do something...
    $("#bodymodalRequest").html("<iframe class='embed-responsive-item' style='width:100%;height:450px;' scrolling='yes' src='https://creatorapp.zohopublic.com/david_djokopramono_midplaza/request-entertainment/form-embed/Request_Form/SShtrR27zZR5bMyqgHgdSXBAyvWBwOr9sTUue7PTw1Qw87Wg6Epeaujsh3xXm6rmSDaakx10tNBdHGYCKegerKYy2XObwdYXf9CO?Requestor_Employee_ID={{$data['employee_id']}}&Requestor_Company={{$data['plant_name']}}&Plant={{$data['plant']}}&Date_Req={{date('d-M-Y')}}&Requestor_Name={{$data['employee_name']}}&Division={{$data['division']}}&Requestor_Department={{$data['department']}}'></iframe>");
    })

    $(document).ready( function () {
        $(".datepicker2").datepicker();
        var request_date_from =  $('#request_date_from').val();
        var request_date_to =  $('#request_date_to').val();
        var status =  $('#status').val();
        var updateBy =  $('#updateBy').val();
        var table = $('#requestList').DataTable({
            "responsive": true,
            "dom": '<"dt-buttons"Bfli>rtp',
            "ajax": {
                "type" : "POST",
                "url" : "/finance/entertainmentForm/request/getData",
                "dataSrc": "data",
                "data" : {
                    "employee_id":updateBy,
                    "filter":"",
                    "value":"",
                    "request_type":"REPORT",
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
            "autoWidth": false,
            'info':true,
            "fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "columns": [
                { "data": "UID",
                "render": function (id, type, full, meta)
                    {
                        objJsonDetail[id] = full.JSON_ENCODE.replace(/&quot;/g,'"');

                        return '<a href="#" data-toggle="modal" data-target="#modalFile" onclick="getFormDetail(\''+id+'\')" >'+id+'</a>';
                    },   className: 'text-left'},
                { "data": "REQUESTOR_NAME", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        var data = JSON.parse(full.JSON_ENCODE.replace(/&quot;/g,'"'));
                        return data.Requestor_Name;

                    }
                },
                { "data": "STATUS_APPROVAL", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        if(id == null || id == "null"){ id = "REQUESTED"; }
                        if(id == "REQUESTED" || id=="APPROVED"){ id="WAITING FOR APPROVAL"}
                        return '<a href="#" data-toggle="modal" data-target="#modalHistory" onclick="getHistoryDetail(\''+full.UID+'\')" >'+id+'</a>';
                    }
                },
                { "data": "INSERT_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                        {
                            return moment(id).format("DD/MM/YYYY - HH:mm");
                        }
                },
                { "data": "LAST_APPROVAL_NAME", className: 'text-left'},
                { "data": "LAST_APPROVAL_DATE", className: 'text-left',
                    "render": function (id, type, full, meta)
                    {
                        if(id == null){
                            return '';
                        }else{
                            return moment(id).format("DD/MM/YYYY - HH:mm");
                        }

                    }
                },
               { "data": "SAP_PLANT_NAME", className: 'text-left'},
               { "data": "DEPARTMENT_NAME", className: 'text-left'},
               { "data": "REASON", className: 'text-left'},
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

        $.fn.dataTableExt.oApi.fnProcessingIndicator = function ( oSettings, onoff ) {
          if ( typeof( onoff ) == 'undefined' ) {
          onoff = true;
          }
          this.oApi._fnProcessingDisplay( oSettings, onoff );
        };
   });
   function getFormDetail(id){
        $('#requestFileList').DataTable().clear().destroy();
        $('#fileListDT').html("");
        $('#modalFile #bodyModalFile').html('');

        $.get("{{url('finance/entertainmentForm/modal-detail')}}", { id : id}, function( data ) {
            $('#modalFile #bodyModalFile').html(data);
        });

        $('#requestFileList').DataTable();

    }

    function getHistoryDetail(id){
        $('#requestHistoryList').DataTable().clear().destroy();
        $('#historyListDT').html("");
        var tr = "";

        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: '/finance/entertainmentForm/request/getHistoryApproval',
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
                $('#requestHistoryList').DataTable({
                    "order": [[ 0, "asc" ]]
                });
            }
        });
    }

    function changeNull(id){
        if(id == null || id == ""){
            id = "";
        }
        return id;
    }

</script>
@endsection
