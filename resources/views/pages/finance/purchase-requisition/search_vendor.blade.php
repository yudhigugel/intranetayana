@extends('layouts.default')

@section('title', 'Search Vendor')
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
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Search Vendor</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Search Vendor</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <br />
                <table class="table table-bordered datatable" id="search_result" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">No</th>
                            <th style="min-width:90px;">Company Name</th>
                            <th style="min-width:90px;">City</th>
                            <th style="min-width:90px;">Number</th>
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
                                <td>{{$vendor['NAME1']}}</td>
                                <td>{{$vendor['CITY1']}}</td>
                                <td>{{$vendor['LIFNR']}}</td>
                                <td>
                                    <input type="button" class="btn btn-sm green btn-primary " value="Choose" name="btn1" onclick='SelectVendor("{{$vendor['LIFNR']}}","{{htmlspecialchars($vendor['NAME1'])}}","{{$vendor['TEL_NUMBER']}}","{{htmlspecialchars($vendor['STREET'])}}","{{$vendor['TEL_NUMBER']}}","","{{$vendor['SMTP_ADDR']}}","{{$vendor['FAX_NUMBER']}}")'>
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
    function SelectVendor(vendorID,vendorName,mobileVendor,addressVendor,phoneVendor,contactPersonVendor,emailVendor,faxVendor){
        window.opener.formRequest.vendor_search.value=vendorName;
        window.opener.formRequest.vendor_name.value=vendorName;
        window.opener.formRequest.vendor_code.value=vendorID;
        window.opener.formRequest.vendor_mobile.value=mobileVendor;
        window.opener.formRequest.vendor_address.value=addressVendor;
        window.opener.formRequest.vendor_phone.value=phoneVendor;
        window.opener.formRequest.vendor_cp.value=contactPersonVendor;
        window.opener.formRequest.vendor_email.value=emailVendor;
        window.opener.formRequest.vendor_fax.value=faxVendor;
        window.close();
    }
</script>
@endsection
