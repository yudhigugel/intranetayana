@extends('layouts.default')

@section('title', 'Search Customer')
@section('custom_source_css')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
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
      <li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
      <li class="breadcrumb-item"><span>Account Receivable Aging Report</span></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Search Customer</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Search Customer</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <br />
                <table class="table table-bordered datatable" id="search_result" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">No</th>
                            <th style="min-width:90px;">Company Code</th>
                            <th style="min-width:90px;">Customer Number</th>
                            <th style="min-width:90px;">Customer Name</th>
                            <th style="min-width:90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0;

                        @endphp
                        @foreach ($data['list_vendor'] as $vendor)
                        @php $i++; @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$vendor[0]['COMPANY']}}</td>
                                <td>{{$vendor[0]['CUSTOMER_CODE']}}</td>
                                <td>{{$vendor[0]['CUSTOMER_NAME']}}</td>
                                <td>
                                    <input type="button" class="btn btn-sm green btn-primary " value="Choose" name="btn1" onclick='SelectCustomer("{{$vendor[0]['CUSTOMER_CODE']}}","{{htmlspecialchars($vendor[0]['CUSTOMER_NAME'])}}")'>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

@endsection
@section('scripts')

<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#search_result").DataTable();
    });
    function SelectCustomer(vendorID,vendorName){
        window.opener.formRequest.customer_number.value=vendorID;
        window.opener.formRequest.customer_search.value=vendorID;
        window.close();
    }
</script>
@endsection
