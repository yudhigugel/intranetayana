<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui"/>
    <title>AYANA INVOICE</title>
    
    <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/custom.css">
    <link rel="stylesheet" type="text/css" href="/bali/ayana_template/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/bali/ayana_template/vendor/fontawesome/css/all.css">
    <style>
       #overlay{
            width:100%;
            height: 100%;
            background:url('/bali/ayana_template/img/pattern.png');
            z-index: 9999;
            position: absolute;
            transition: opacity 1s;
            overflow: hidden;
       }
       .btn-ayana{
           background: #aa9157;
           color:#fff;
           text-align: center;
           display: block;
       }

       #content{
            transition: opacity 1s;
       }
       .bill-text-info{
            border-color: #6c757d61 !important;
       }
       .thecontent{
            width: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
       }

       .come-title{
            text-align: center;font-size:25px;text-transform:uppercase;color:#505152;font-weight: bold;
       }

       @media only screen and (max-width: 600px) {
            .come-title{
                font-size:25px;
            }
       }

       @media only screen and (min-width: 768px) and (max-width:1023px) {
         #section1{
             display: none;
         }
         #section2{
             display: none;
         }
       }
    </style>

    <script type="text/javascript" src="/bali/ayana_template/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bali/ayana_template/vendor/fontawesome/js/all.js"></script>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
    <div class="container invoice-box" style="max-width: 825px">
        <table cellpadding="0" cellspacing="0">
            <tr>
            <td colspan="9">
                <table>
                    <tbody>
                        <tr>
                            <td class="badge bg-secondary text-center d-block py-2">
                                <span style="font-size: 12px;text-transform: uppercase;" class="text-white">Billing Statement</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            </tr>
            <tr>
            <td colspan="9">
                <div class="row px-3 justify-content-center align-items-center">
                    <div class="col-6">
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Payment Date</span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">{{ date("d M Y") }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Bill No.
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{ isset($data['invoice']->CAANO) && !empty($data['invoice']->CAANO) ? $data['invoice']->CAANO : '-'}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Due Date
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{ isset($data['invoice']->DUEDATE) && !empty($data['invoice']->DUEDATE) ? date('d M Y',strtotime($data['invoice']->DUEDATE)) : '-'}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">ATTN.
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{ isset($data['invoice']->ATTN) && !empty($data['invoice']->ATTN) ? $data['invoice']->ATTN : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Cust. Name
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{ isset($data['invoice']->NAME_FIRST) && !empty($data['invoice']->NAME_FIRST) ? $data['invoice']->NAME_FIRST : ''}} {{ isset($data['invoice']->NAME_LAST) && !empty($data['invoice']->NAME_LAST) ? $data['invoice']->NAME_LAST : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Cust. Address
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{$data['invoice']->STREET}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Cust. City
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{$data['invoice']->CITY1}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-4">
                                    <span class="text-muted" style="font-size: 12px; font-weight: bold; vertical-align: middle;">Origin Country
                                    </span>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <!-- <input type="text" class="form-control"> -->
                                        <div class="border border-secondary bill-text-info badge d-block p-2 text-left">
                                            <span class="d-block px-4">
                                                {{ 'Indonesia' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            </tr>
            <tr>
            <td colspan="9">
                <table>
                    <tbody>
                        <tr>
                            <td class="badge bg-secondary text-center d-block py-2">
                                <span style="font-size: 12px; text-transform: uppercase;" class="text-white">Billing Items Detail</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            </tr>
            <tr>
                <td colspan="8">
                <table class="table-bordered">
                    <tr class="heading">
                    <td class="text-center" style="width:10%;">Order No.</td>
                    <td class="text-center" style="width:8%;">Type</td>
                    <td class="text-center" style="width:20%;">Type Description</td>
                    <td class="text-center" style="width:12%;">Net Amount</td>
                    <td class="text-center" style="width:12%;">Tax</td>
                    <td class="text-center" style="width:12%;">Total Amount</td>
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
                                    <td class="text-center">{{$item->CONDTYPE}}</td>
                                    <td class="text-left">{{$item->XCONDTYPEM}}</td>
                                    <td class="text-right">{{number_format($item->NETAMOUNT)}}</td>
                                    <td class="text-right">{{number_format($item->TAXAMOUNT)}}</td>
                                    <td class="text-right">{{number_format($item->GROSSAMOUNT)}}</td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach

                        <tr class="total">
                        <td colspan="3" class="text-right" style="font-size:12px;border-top: 2px solid #eee;font-weight: normal;"><b>TOTAL IN IDR : </b></td>
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
        <div class="px-1">
            <hr>
        </div>
        <table cellpadding="0" cellspacing="0">
            <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            @if(isset($plant_info) && $plant_info)
                                <p style="margin:0;font-size:20px;font-weight:bold;">{{ isset($plant_info->SAP_PLANT_NAME) && !empty($plant_info->SAP_PLANT_NAME) ? strtoupper($plant_info->SAP_PLANT_NAME) : 'UNKNOWN' }}</p>
                                <p style="margin:0;font-size:12px;">{{ isset($plant_info->ADDRESS) && !empty($plant_info->ADDRESS) ? ucwords(strtolower($plant_info->ADDRESS)) : '-' }}</p>
                                <p style="font-size:12px;margin-bottom: 0">P {{ isset($plant_info->PHONE) ? '+'.$plant_info->PHONE : '+62-361-8468468' }} | E {{ isset($plant_info->EMAIL) ? strtolower($plant_info->EMAIL) : '-' }} | <a target="_blank" href="{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}">{{ isset($plant_info->WEB) ? strtolower($plant_info->WEB) : '-' }}</a></p>
                            @else
                                <p style="margin:0;font-size:20px;font-weight:bold;">PT. {{ isset($data['invoice']->BUTXT) && !empty($data['invoice']->BUTXT) ? strtoupper($data['invoice']->BUTXT) : 'KARANG MAS SEJAHTERA' }}</p>
                                <p style="margin:0;font-size:12px;">Karang Mas Estate. Jl Karang Mas Sejahtera. Kab Badung, Bali 80364 – Indonesia.</p>
                                <p style="font-size:12px;margin-bottom: 0">P +62-361-8468468 | E finance@ayanaresort.com | <a target="_blank" href="https://www.ayana.com">www.ayana.com</a></p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
        </table>
    </div>
    </div>
</body>
</html>
