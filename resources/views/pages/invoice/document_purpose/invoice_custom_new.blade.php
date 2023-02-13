<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui"/>
    
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/css/custom.css">

    <title>{{ isset($title) ? $title : 'Invoice Payment' }}</title>

    <style type="text/css">
        @page {
            margin: 2rem 2rem 8rem 2rem;
            font-family: 'Helvetica';
        }
        body {
            margin: 0px;
            font-family: 'Helvetica';
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice {
            margin-left: 15px;
            margin-right: 15px;
        }
        /*.invoice table {
            margin: 15px;
        }*/        
        .information {
            /*background-color: #60A7A6;*/
            /*color: #FFF;*/
        }
        .information .logo img {
            margin: 5px !important;
        }
        .information table {
            padding: 10px;
            margin-bottom: 10px;
        }
        .td-25{
            width: 25%;
            position: relative;
        }
        .td-30{
            width: 33.33333333%;
            position: relative;
            vertical-align: middle;
        }
        .td-30-fix{
            width: 30%;
            position: relative;
        }
        .td-70-fix{
            width: 70%;
            position: relative;
        }
        .td-50{
            width: 50%;
            position: relative;
        }
        .td-100{
            width: 100%;
            position: relative;
        }
        footer { position: fixed; bottom: -70px; left: 0px; right: 0px; height: 70px; margin-left: 15px; margin-right: 15px }
        footer a{ color: #000 !important }       
    </style>

</head>
<body>
<footer>
    <div class="mb-2">
        @if(isset($plant_info) && $plant_info)
            <p style="margin:0;font-size:20px;font-weight:bold;">{{ isset($plant_info->SAP_PLANT_NAME) && !empty($plant_info->SAP_PLANT_NAME) ? strtoupper($plant_info->SAP_PLANT_NAME) : 'UNKNOWN' }}</p>
            <p style="margin:0;font-size:12px;">{{ isset($plant_info->ADDRESS) && !empty($plant_info->ADDRESS) ? ucwords(strtolower($plant_info->ADDRESS)) : '-' }}</p>
            <p style="font-size:12px;margin-bottom: 0">P {{ isset($plant_info->PHONE) ? '+'.$plant_info->PHONE : '+62-361-8468468' }} | E {{ isset($plant_info->EMAIL) ? strtolower($plant_info->EMAIL) : '-' }} | <a target="_blank" href="https://{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}">{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}</a></p>
        @else
            <p style="margin:0;font-size:20px;font-weight:bold;">PT. {{ isset($data['invoice']->BUTXT) && !empty($data['invoice']->BUTXT) ? strtoupper($data['invoice']->BUTXT) : 'KARANG MAS SEJAHTERA' }}</p>
            <p style="margin:0;font-size:12px;">Karang Mas Estate. Jl Karang Mas Sejahtera. Kab Badung, Bali 80364 – Indonesia.</p>
            <p style="font-size:12px;margin-bottom: 0">P +62-361-8468468 | E finance@ayanaresort.com | <a target="_blank" href="https://www.ayana.com">www.ayana.com</a></p>
        @endif
    </div>

    {{--@switch(strtolower(isset($data['invoice']->BUKRS) ? $data['invoice']->BUKRS : 'KMS'))
      @case('kms')
        <div class="bg-info" style="width:100%;height:20px;">
        </div>
        @break
      @case('njp')
        <div style="background:#c2a459;width:100%;height:20px;">
        </div>
        @break
      @case('pad')
        <div style="background:#c2a459;width:100%;height:20px;">
        </div>
        @break
      @case('pnb')
        <div class="bg-info" style="width:100%;height:20px;">
        </div>
        @break
      @case('ppc')
        <div class="bg-info" style="width:100%;height:20px;">
        </div>
        @break
      @case('wkk')
        <div style="background:#c2a459;width:100%;height:20px;">
        </div>
        @break
    @endswitch--}}
    <div class="bg-info" style="width:100%;height:20px;">
    </div>
</footer>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" class="td-30">
                <h4>ACCOUNT <br> <span class="d-inline-block">STATEMENT</h4>
            </td>
            <td align="center" class="td-30">
                <div class="d-flex justify-content-center align-items-center align-self-center">
                    <div style="margin-left: 3.2rem; margin-top: 0.5rem">
                        {!! $barcode !!}
                    </div>
                    <div style="position: relative; top: 3rem">
                        <h6>{{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '-'}}</h6>
                    </div>
                </div>
            </td>
            <td align="right" class="td-30 text-right">
                <div class="logo">
                    @switch(strtolower(isset($data['invoice']->BUKRS) ? $data['invoice']->BUKRS : 'kms'))
                      @case('kms')
                        {{--<img src="bali/ayana_template/img/ayana-bali-logo-color.png" style="width:45px; margin:0 5px;">
                        <img src="bali/ayana_template/img/rimba-bali-logo-color.png" style="width:45px; margin:0 5px;">
                        <img src="bali/ayana_template/img/thevillas-bali-logo-color.png" style="width:45px; margin:0 5px;">--}}
                        <img src="bali/ayana_template/img/ayana-estate.png" style="width:100px; margin:0 5px;">
                        @break
                      @case('njp')
                        <img src="bali/ayana_template/img/logo_ayana_residences.png" style="width:60px; margin:0 5px;">
                        @break
                      @case('pad')
                        @if(isset($data['invoice']->PRCTR))
                            @if(strtoupper($data['invoice']->PRCTR) == 'PAD1')
                                <img src="bali/ayana_template/img/ayana-midplaza.png" style="width:70px; margin:0 5px;">
                            @elseif(strtoupper($data['invoice']->PRCTR) == 'PAD2')
                                <img src="bali/ayana_template/img/plaza-residences.png" style="width:75px; margin:0 5px;">
                            @endif
                        @else
                            <img src="bali/ayana_template/img/ayana-midplaza.png" style="width:45px; margin:0 5px;">
                            <img src="bali/ayana_template/img/plaza-residences.png" style="width:50px; margin:0 5px;">
                        @endif

                        @break
                      @case('pnb')
                        <img src="bali/ayana_template/img/ayana-cruise.png" style="width:60px; margin:0 5px;">
                        @break
                      @case('ppc')
                        <img src="bali/ayana_template/img/ayana-komodo.png" style="width:100px; margin:0 5px;">
                        @break
                      @case('wkk')
                        @if(isset($data['invoice']->PRCTR))
                            @if(strtoupper($data['invoice']->PRCTR) == 'WKK1')
                                <img src="bali/ayana_template/img/delonix-hotel.png" style="width:80px; margin:0 5px;">
                            @elseif(strtoupper($data['invoice']->PRCTR) == 'WKK2')
                                <img src="bali/ayana_template/img/delonix-apartment.png" style="width:70px; margin:0 5px;">
                            @endif
                        @else
                            <img src="bali/ayana_template/img/delonix-hotel.png" style="width:45px; margin:0 5px;">
                            <img src="bali/ayana_template/img/delonix-apartment.png" style="width:50px; margin:0 5px;">
                        @endif
                        @break
                    @endswitch
                </div>
            </td>
        </tr>

    </table>
</div>

<div class="invoice mb-1">
    <div class="row">
        <div class="px-2">
            <div class="p-1 text-left" style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;">
                Document Information
            </div>
        </div>
    </div>
</div>
<div class="invoice invoice-box mb-3" style="border: none !important; padding: 10px">
    <div class="row">
        <div class="px-1">
        <table style="width: 100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr class="heading">
                    <td>Account Statement No</td>
                    <td>Account Statement Date</td>
                    <td>Account Statement Period</td>
                    <td>Due Date</td>
                </tr>
                <tr class="item-center">
                    <td>{{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '-'}}</td>
                    @if(isset($data['invoice']->PERIOD) && $data['invoice']->PERIOD)
                        @php
                            $new_period = [];
                            $caano_period = explode(' - ',$data['invoice']->PERIOD);
                            foreach($caano_period as $period){
                                if(!in_array(date('d M Y', strtotime($period)), $new_period))
                                    $new_period[] = date('d M Y', strtotime($period));
                            }
                            $caano_period = implode('-', $new_period);
                        @endphp
                    <td>{{ isset($new_period[1]) ? $new_period[1] : '-' }}</td>
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
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="invoice mb-2 mt-2">
    <div class="row">
        <div class="px-2">
            <div class="p-1 text-left" style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;">
                Customer Info
            </div>
        </div>
    </div>
</div>
<div class="invoice mb-3">
    <table style="width: 100%">
        <tbody>
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
                TELP : {{!empty($data['invoice']->TEL_NUMBER) ? $data['invoice']->TEL_NUMBER : '-'}}<br>
                MOBILE : {{!empty($data['invoice']->MOBILE) ? $data['invoice']->MOBILE : '-'}}<br/>
                EMAIL : {{!empty($data['invoice']->SMTP_ADDR) ? strtolower($data['invoice']->SMTP_ADDR) : '-'}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="invoice mb-1 mt-2">
    <div class="row">
        <div class="px-2">
            <div class="p-1 text-left" style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;">
                Invoice List
            </div>
        </div>
    </div>
</div>
<div class="invoice invoice-box mb-3" style="border: none !important; padding: 10px">
    <div class="row">
        <div class="px-1">
        <table style="width: 100%" class="table-bordered" style="margin-top: -5px">
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
                    <td class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>{{number_format($total_gross)}}</b></td>
                </tr>
            @else
                <tr class="item">
                    <td colspan="7" class="text-center">No Data</td>
                </tr>
            @endif
        </table>
        </div>
    </div>
</div>
</body>
</html>