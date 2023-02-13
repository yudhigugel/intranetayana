@extends('layouts.default')

@section('title', 'Real Estate Invoice')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/custom.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}">

<!-- <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/bootstrap.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="/bali/ayana_template/vendor/fontawesome/css/all.css"> -->
<script type="text/javascript" src="/bali/ayana_template/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bali/ayana_template/vendor/fontawesome/js/all.js"></script>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">SAP</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sap.invoice.list') }}">Invoice</a></li>
    <li aria-current="page" class="breadcrumb-item active"><span>Invoice Detail</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="overlay">
      <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card">
        <!-- <div class="pt-5 pb-1 text-center">
          <h3><i class="fa fa-check-circle text-success"></i>&nbsp; THIS TRANSACTION HAS BEEN SETTLED</h3>
        </div> -->
        @if(isset($status_payment))
          {!! $status_payment !!}
        @endif
        <div class="text-center pt-2">
            <button data-href="{{url('folio/invoice_payment/data/download')}}?invoice_no={{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '0' }}" class="btn-download btn btn-sm btn-secondary badge text-white px-4">Download Statement</button>
        </div>
        <div id="content" class="pt-4">
            <div class="invoice-box mb-3">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                    <td colspan="9">
                        <table>
                            <tr>
                                <td style="width:33.333%">
                                    <p style="font-size:1.5rem;font-weight: bold;">ACCOUNT <br> <span class="d-inline-block my-2">STATEMENT</span></p>
                                </td>
                                <td style="text-align:center;width:33.333%">
                                    <p style="font-size:1.5rem;font-weight: bold;">{{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '-'}}</p>
                                </td>
                                <td class="title" style="width:33.333%">
                                    <img src="/bali/ayana_template/img/ayana-komodo.png" style="width:100px;float:right;margin-top:0">
                                </td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="9">
                        <table>
                            <tr>
                                <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Document Information</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="9">
                        <table>
                            <tr class="heading">
                                <td>Account Statement No</td>
                                <td>Account Statement Date</td>
                                <td>Account Statement Period</td>
                                <td>Due Date</td>
                            </tr>
                            <tr class="item-center">
                                <td>{{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '-'}}</td>
                                @if(isset($data['invoice']->PERIOD) && $data['invoice']->PERIOD && $data['invoice']->PERIOD != '00-00-0000 - 00-00-0000')
                                    @php
                                        $new_period = [];
                                        $caano_period = explode(' - ',$data['invoice']->PERIOD);
                                        foreach($caano_period as $period){
                                            if(!in_array(date('d M Y', strtotime($period)), $new_period))
                                                $new_period[] = date('d M Y', strtotime($period));
                                        }
                                        $caano_period = implode('-', $new_period);
                                    @endphp
                                <td>{{ isset($new_period[0]) ? $new_period[0] : '-' }}</td>
                                <td>{{ $caano_period }}</td>
                                @else
                                <td>-</td>
                                <td>-</td>
                                @endif
                                <td>{{ isset($data['invoice']->DUEDATE) && !empty($data['invoice']->DUEDATE) ? date('d M Y',strtotime($data['invoice']->DUEDATE)) : '-'}}</td>
                            </tr>
                            <tr class="heading">
                                <td>SAP Customer No</td>
                                <td>Account Executive</td>
                                <td>Currency</td>
                                <td>Total Amount</td>
                            </tr>
                            <tr class="item-center">
                                <td>{{$data['invoice']->KUNNR}}</td>
                                <td>-</td>
                                <td>{{$data['invoice']->WAERS}}</td>
                                <td>{{number_format($data['invoice']->WRBTR)}}</td>
                            </tr>
                            <tr class="heading">
                                <td>Company Code</td>
                                <td></td>
                                <td>BCA Virtual Account No</td>
                                <td>CIMB Niaga Virtual Account No</td>
                            </tr>
                            <tr class="item-center">
                                <td>{{$data['invoice']->PRCTR}}</td>
                                <td></td>
                                <td>{{$data['invoice']->VABCA ? $data['invoice']->VABCA : '-'}}</td>
                                <td>{{$data['invoice']->VANIAGA ? $data['invoice']->VANIAGA : '-'}}</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="9">
                        <table style="margin-top:10px;">
                            <tr>
                                <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Customer Info</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr class="information">
                    <td colspan="9">
                        <table id="customer-info">
                            <tr>
                                <td style="width: 60%;">
                                {{ isset($data['invoice']->NAME_FIRST) && !empty($data['invoice']->NAME_FIRST) ? $data['invoice']->NAME_FIRST : ''}}
                                {{ isset($data['invoice']->NAME_LAST) && !empty($data['invoice']->NAME_LAST) ? $data['invoice']->NAME_LAST : '' }}<br>
                                {{$data['invoice']->STREET}}  <br>
                                {{$data['invoice']->CITY1}} <br>
                                INDONESIA
                                </td>
                                <td>
                                ATTN : {{ isset($data['invoice']->ATTN) && !empty($data['invoice']->ATTN) ? $data['invoice']->ATTN : '-' }}<br>
                                <!-- CUST : <br> -->
                                TEL : {{!empty($data['invoice']->TEL_NUMBER) ? $data['invoice']->TEL_NUMBER : '-'}}<br>
                                MOBILE : {{!empty($data['invoice']->MOBILE) ? $data['invoice']->MOBILE : '-'}}<br/>
                                EMAIL : {{!empty($data['invoice']->SMTP_ADDR) ? strtolower($data['invoice']->SMTP_ADDR) : '-'}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="9">
                        <table>
                            <tr>
                                <td style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;text-align: left;">Invoice List</td>
                                <!-- <td class="text-right"><div style="padding: 0 5px"><span class="badge badge-sm bg-secondary text-white">Order Number : 
                                {{ isset($data['invoice']->ITEM_DETAILS->INVNO) && !empty($data['invoice']->ITEM_DETAILS->INVNO) ? $data['invoice']->ITEM_DETAILS->INVNO : '-' }}</span></div></td> -->
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                        <table class="table-bordered" style="margin-top: -5px">
                            <tr class="heading">
                                <td class="text-center" style="width:10%;">INVOICE NO.</td>
                                <td class="text-center" style="width:20%;">TRANSACTION DETAIL</td>
                                <td class="text-center" style="width:12%;">SUBTOTAL</td>
                                <td class="text-center" style="width:12%;">VAT</td>
                                <td class="text-center" style="width:12%;">TOTAL AMOUNT</td>
                            </tr>
                            @if(isset($data['invoice']->ITEM_DETAILS) && !empty($data['invoice']->ITEM_DETAILS))
                                @php
                                    $total_gross=$total_net=$total_tax=$subtotal=0;
                                @endphp
                                @foreach($data['invoice']->ITEM_DETAILS as $item_header)
                                    @php
                                        $order_id = $item_header->INVNO;
                                    @endphp
                                    @if(isset($item_header->DETAILS) && !empty($item_header->DETAILS))
                                        @foreach($item_header->DETAILS as $item)
                                        @php
                                            $total_gross+=$item->GROSSAMOUNT;
                                            $total_net+=$item->NETAMOUNT;
                                            $total_tax+=$item->TAXAMOUNT;
                                        @endphp
                                        <tr class="item">
                                            <td class="text-center">{{$order_id}}</td>
                                            <td class="text-left">{{$item->XCONDTYPEM}}</td>
                                            <td class="text-right">{{number_format($item->NETAMOUNT)}}</td>
                                            <td class="text-right">{{number_format($item->TAXAMOUNT)}}</td>
                                            <td class="text-right">{{number_format($item->GROSSAMOUNT)}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                            <tr class="total">
                                <td colspan="2" class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>GRAND TOTAL IN IDR : </b></td>
                                <td class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>{{number_format($total_net)}}</b></td>
                                <td class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>{{number_format($total_tax)}}</b></td>
                                <td class=" text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>{{number_format($total_gross)}}</b></td>
                            </tr>
                        @else
                            <tr class="item">
                                <td colspan="7" class="text-center">No Data</td>
                            </tr>
                        @endif
                        </table>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="margin:80px 0px 0px 0px;">
                    <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    @if(isset($plant_info) && $plant_info)
                                        <p style="margin:0;font-size:20px;font-weight:bold;">{{ isset($plant_info->SAP_PLANT_NAME) && !empty($plant_info->SAP_PLANT_NAME) ? strtoupper($plant_info->SAP_PLANT_NAME) : 'UNKNOWN' }}</p>
                                        <p style="margin:0;font-size:12px;">{{ isset($plant_info->ADDRESS) && !empty($plant_info->ADDRESS) ? ucwords(strtolower($plant_info->ADDRESS)) : '-' }}</p>
                                        <p style="margin-top:-3px;font-size:12px;">P {{ isset($plant_info->PHONE) ? '+'.$plant_info->PHONE : '+62-361-8468468' }} | E {{ isset($plant_info->EMAIL) ? strtolower($plant_info->EMAIL) : '-' }} | <a target="_blank" href="https://{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}">{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}</a></p>
                                    @else
                                        <p style="margin:0;font-size:20px;font-weight:bold;">PT. {{ isset($data['invoice']->BUTXT) && !empty($data['invoice']->BUTXT) ? strtoupper($data['invoice']->BUTXT) : 'PRIMA PRATAMA CITRA' }}</p>
                                        <p style="margin:0;font-size:12px;">Labuan Bajo, Flores, Nusa Tenggara Timur 86554 – Indonesia.</p>
                                        <p style="margin-top:-3px;font-size:12px;">P +62-385-2441000 | E info@ayanakomodo.com | <a target="_blank" href="https://ayanakomodo.com">www.ayanakomodo.com</a></p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                <div class="bg-info" style="width:100%;height:20px;">
                </div>
            </div>
        </div>
        <!-- Extra space at bottom -->
        <div class="pt-5 pb-1 text-center">
        </div>
        
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/js/jquery.toast.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready( function () {
      $('#table-folio-reference').DataTable();

      $('.btn-download').click(function(e){
            e.preventDefault();
            $_url = $(this).data('href') ?? '-';
            $(this).prop('disabled', true);
            try{ 
                $.toast({
                  text : "<i class='fa fa-spin fa-spinner'></i> &nbsp;Downloading statement...",
                  hideAfter : false,
                  textAlign : 'left',
                  showHideTransition : 'slide',
                  position : 'bottom-right'  
                })
            } catch(error){}

            $.ajax({
                url : $_url,
                cache: false,
                contentType: false,
                processData: false,
                type : 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response, status, xhr) {

                    var filename = "";                   
                    var disposition = xhr.getResponseHeader('Content-Disposition');

                     if (disposition) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    } 
                    var linkelem = document.createElement('a');
                    try {
                        var blob = new Blob([response], { type: 'application/octet-stream' });                        

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) { 
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");

                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
                                    window.location = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.target = "_blank";
                                    a.click();
                                }
                            } else {
                                window.location = downloadUrl;
                            }
                        }   

                    } catch (ex) {
                        console.log(ex);
                    }
                    $.toast().reset('all'); 
                },
                error : function(xhr){
                    console.log(xhr);
                    $.toast().reset('all');
                    setTimeout(function(){
                        $.toast({
                          text : "Something went wrong, please try again",
                          hideAfter : 3000,
                          textAlign : 'left',
                          showHideTransition : 'slide',
                          position : 'bottom-right'  
                        })
                    }, 500)
                },
                complete : function(){
                    $('.btn-download').prop('disabled', false);
                }
            })
            // End AJAX
        });
   });
</script>
@endsection

