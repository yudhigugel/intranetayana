@extends('layouts.default')

@section('title', 'Search Material')
@section('custom_source_css')
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
@endsection
@section('styles')
<style>

</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Purchase Requisition</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Search Material</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Search Material</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <br />
                <table class="table table-bordered datatable" id="search_result" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">No</th>
                            <th style="min-width:90px;">Material Number</th>
                            <th style="min-width:90px;">Material Description</th>
                            <th style="min-width:90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0;

                        @endphp
                        @foreach ($data['list_material'] as $material)
                        @php $i++; @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$material['MATNR']}}</td>
                                <td>{{$material['MAKTX']}}</td>
                                <td>
                                    <input type="button" class="btn btn-sm green btn-primary " value="Choose" name="btn1" onclick='SelectMaterial("{{$material['MATNR']}}","{{htmlspecialchars($material['MAKTX'])}}","{{$material['MEINS']}}","{{$material['FIPEX']}}","","{{$material['FUNDS_CURR']}}","{{$material['AMOUNT_TXT']}}","{{$data['id_row']}}","{{$material['AMOUNT_TXT_YEAR']}}","{{$material['TEXT1']}}","{{$material['VERPR_TXT']}}")'>
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


function SelectMaterial(materialNumber,materialDesc,materialUnit,materialFipex,cAmitBapi,fundsCurr,amountTxt,idRow,amountYearTxt,cmmtItemText,prPrice){
	if(idRow == 1){
		idRow = "";
	}

	// cAmitBapi = cAmitBapi.replace(/\./g, "");
	// cAmitBapi = cAmitBapi.replace(/,/g, ".");
    var cAmitBapi = prPrice.replace('.', '');
    cAmitBapi = parseInt(cAmitBapi.replace(/[^0-9+\-Ee.]/g, ''));

	window.opener.document.getElementById("materials"+idRow).value = materialNumber;
	window.opener.document.getElementById("materialDesc"+idRow).value = materialDesc;
	window.opener.document.getElementById("unit"+idRow).value = materialUnit;
	window.opener.document.getElementById("cmmtItem"+idRow).value = materialFipex;
	window.opener.document.getElementById("cmmtItemText"+idRow).value = cmmtItemText;
	window.opener.document.getElementById("cAmitBapi"+idRow).value = cAmitBapi;
	window.opener.document.getElementById("fundsCurr"+idRow).value = fundsCurr;
	window.opener.document.getElementById("amountTxt"+idRow).value = amountTxt;
	window.opener.document.getElementById("amountYearTxt"+idRow).value = amountYearTxt;
	window.close();
}
</script>
@endsection
