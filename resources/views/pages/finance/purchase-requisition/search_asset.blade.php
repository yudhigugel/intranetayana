@extends('layouts.default')

@section('title', 'Search Asset')
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
      <li aria-current="page" class="breadcrumb-item active"><span>Search Asset</span></li></ol>
  </nav>
  <div class="row flex-grow">
    <div class="col-sm-12 stretch-card">
        <div class="card">
            <div class="card-body px-0 pb-0 border-bottom">
                <div class="px-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title ml-2">Search Asset</h4>
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow: auto;">
                <br />
                <table class="table table-bordered datatable" id="search_result" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;">No</th>
                            <th style="min-width:90px;">Asset Name</th>
                            <th style="min-width:90px;">Asset Number</th>
                            <th style="min-width:90px;">Asset Type</th>
                            <th style="min-width:90px;">Asset Type Number</th>
                            <th style="min-width:90px;">Month Budget</th>
                            <th style="min-width:90px;">Year Budget</th>
                            <th style="min-width:90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0;

                        @endphp
                        @foreach ($data['list_asset'] as $asset)
                        @php $i++; @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$asset['TXT50']}}</td>
                                <td>{{$asset['ANLN1']}}</td>
                                <td>{{$asset['TXK50']}}</td>
                                <td>{{$asset['ANLKL']}}</td>
                                <td>{{$asset['AMOUNT_TXT']}}</td>
                                <td>{{$asset['AMOUNT_TXT_YEAR']}}</td>
                                <td>
                                    <input type="button" class="btn btn-sm green btn-primary" value="Choose" name="btn1" onclick="SelectPlant('{{$asset['ANLN1']}}','{{$data['id_row']}}','{{$asset['FIPEX']}}','{{$asset['TEXT1']}}','{{$asset['AMOUNT_TXT']}}','{{$asset['AMOUNT_TXT_YEAR']}}','{{$asset['FUNDS_CURR']}}','{{$asset['TXT50']}}')">
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

    function SelectPlant(assetNumber,idRow,materialFipex,cmmtItemText,amountTxt,amountYearTxt,fundsCurr,assetName){

    if(idRow == 1){
        idRow = "";
    }

    window.opener.document.getElementById("assetNo"+idRow).value = assetNumber;
    window.opener.document.getElementById("assetDesc"+idRow).value = assetName;
    // window.opener.document.getElementById("assetNo"+idRow).setAttribute("readonly","readonly");
    window.opener.document.getElementById("cmmtItem"+idRow).value = materialFipex;
    window.opener.document.getElementById("cmmtItemText"+idRow).value = cmmtItemText;
    window.opener.document.getElementById("fundsCurr"+idRow).value = fundsCurr;
    window.opener.document.getElementById("amountTxt"+idRow).value = "";
    window.opener.document.getElementById("amountTxt"+idRow).value = amountTxt;
    window.opener.document.getElementById("amountYearTxt"+idRow).value = "";
    window.opener.document.getElementById("amountYearTxt"+idRow).value = amountYearTxt;

    window.close();
    }


</script>
@endsection
