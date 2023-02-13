<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui"/>
    
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="bali/ayana_template/css/custom.css">

    <title>{{ isset($title) ? $title : 'Print Purchase Requisition Market List' }}</title>

    <style type="text/css">
        @page {
            /*margin: 2rem 2rem 8rem 2rem;*/
            margin: 2rem 2rem 5rem 2rem;
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
            page-break-after: avoid;
        }
        .float-left {
            float: left;
        }
        .float-right {
            float: right;
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
        .checklist-wrapper{
            text-align: center;
        }
        footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 70px; margin-left: 15px; margin-right: 15px }
        footer a{ color: #000 !important }       
    </style>

</head>
<body>
<footer>
    <div class="mb-2">
        <p style="margin:0;font-size:12px;font-weight:normal;color: #999">AYANA INTRANET</p>
        <p style="margin:0;font-size:12px;font-weight:normal;color: #999">PURCHASE REQUISITION MARKETLIST</p>
    </div>
</footer>


<div class="invoice invoice-box mb-3" style="border: none !important">
    @if(isset($data['ITEM_DETAILS']) && count($data['ITEM_DETAILS']) > 0)
        @foreach($data['ITEM_DETAILS'] as $no_marketlist => $value)
        <div class="row">
            <div class="px-1" style="margin-left: -2rem;margin-right: -2rem">
                <div class="title-wrapper mb-4">
                    <div class="px-2 text-center">
                        <h5 style="color:#00004E;line-height: 1.5">Purchase Requisition Marketlist <br> Request No. {{ $no_marketlist }}</h5>
                    </div>
                </div>
                <div class="header-wrapper mb-3">
                    <div class="px-2" style="position: relative;">
                        <div class="p-1 text-left mb-2 mt-2" style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;margin-left: -.5rem;margin-right: -.5rem; position: relative;">
                            <div class="float-left" style="padding: 5px 0">
                                Form Information
                            </div>
                            <div class="float-right" style="padding: 5px 0">
                                SAP PR Number : {{ isset($value->JSON_ENCODE->PR_NUMBER) ? $value->JSON_ENCODE->PR_NUMBER : '-' }}
                            </div>
                            <div style="clear: both; content:' ';float: none;display: block; "></div>
                        </div>
                        <div>
                            <table style="width: 100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr class="heading">
                                        <td>Requestor Name</td>
                                        <td>Cost Center</td>
                                        <td>Document Type</td>
                                    </tr>
                                    <tr class="item-center">
                                        <td>{{ isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : 'Unknown' }}</td>
                                        <td>{{ isset($value->COST_CENTER_NAME) ? $value->COST_CENTER_NAME : 'Unknown' }}</td>
                                        <td>{{ isset($value->DOC_TYPE_DESC) ? $value->DOC_TYPE_DESC : 'Unknown' }}</td>
                                    </tr>
                                    <tr class="heading">
                                        <td>Delivery Date</td>
                                        <td>Ship To SLOC</td>
                                        <td>Grand Total</td>
                                    </tr>
                                    <tr class="item-center">
                                        <td>{{ isset($value->JSON_ENCODE->Delivery_Date) ? $value->JSON_ENCODE->Delivery_Date : '-' }}</td>
                                        <td>{{ isset($value->SHIP_TO_SLOC) ? $value->SHIP_TO_SLOC : '-' }}</td>
                                        <td>{{ isset($value->JSON_ENCODE->grandTotal) ? number_format(str_replace(',','', $value->JSON_ENCODE->grandTotal), 0) : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="item-wrapper">
                    <div class="px-2 mb-2 mt-2" style="position: relative;">
                        <div class="p-1 text-left" style="border-bottom:#ececec 1px solid;color:#00004E;font-weight: bold;margin-left: -.5rem;margin-right: -.5rem;">
                            <div style="padding-bottom: 5px">
                                Item Information
                            </div>
                        </div>
                    </div>
                    <div class="px-2">
                        <table style="width: 100%" class="table-bordered" style="margin-top: -5px;">
                            <thead>
                                <tr class="heading">
                                    <td class="text-center" style="width: 8%"><input type="checkbox" style="margin-left: 1.35em"></td>
                                    <td class="text-center" style="width: 6%">NO.</td>
                                    <td class="text-center" style="width: 41%">MATERIAL NAME</td>
                                    <td class="text-center" style="width: 10%">QTY</td>
                                    <td class="text-center" style="width: 10%">UoM</td>
                                    <td class="text-center" style="width: 25%">SUBTOTAL</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($value->JSON_ENCODE->marketlistItemOrder) && count($value->JSON_ENCODE->marketlistItemOrder) > 0)
                                    @foreach($value->JSON_ENCODE->marketlistItemOrder as $order_key => $ml_item)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" style="margin-left: 1.35em">
                                        </td>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-left">
                                            <span class="d-block" style="margin-left: .5em">
                                                {{ isset($value->JSON_ENCODE->marketlistMaterialName[$order_key]) ? $value->JSON_ENCODE->marketlistMaterialName[$order_key] : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{ isset($value->JSON_ENCODE->marketlistMaterialQty[$order_key]) ? number_format($value->JSON_ENCODE->marketlistMaterialQty[$order_key], 1) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ isset($value->JSON_ENCODE->marketlistMaterialUnit[$order_key]) ? $value->JSON_ENCODE->marketlistMaterialUnit[$order_key] : '-' }}
                                        </td>
                                        <td class="text-right">
                                            {{ isset($value->JSON_ENCODE->marketlistMaterialLastPrice[$order_key]) ? number_format($value->JSON_ENCODE->marketlistMaterialLastPrice[$order_key]) : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="row">
            <div class="px-1 text-center">
                <h5 style="margin-bottom: -8px">No Data Available</h5>
                <div>
                    <small style="font-size: 9.5px" class="text-muted">Please check the data and try again</small>
                </div>
            </div>
        </div>
    @endif
</div><script type="text/javascript">print();</script></body></html>