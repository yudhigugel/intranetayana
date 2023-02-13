<?php

namespace App\Http\Controllers\SAP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF;
Use Cookie;
use DataTables;

class PurchaseOrder extends Controller{
    function index(Request $request) {
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;

        $data['po'] = DB::connection('dbintranet')
        ->select("EXEC dbo.list_po_sample_by_date ?,?", array($date_start, $date_end));
        // dd($data['po']);
        return view('pages.sap.purchase-order.index', ['data'=>$data]);
    }

    function po_detail(Request $request, $no_po=null) {
        if(!$no_po)
            return view('pages.exception.blank_page');

        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;
        $data['po'] = DB::connection('dbintranet')
        ->select("EXEC dbo.filter_po_sample_doc_number ?", array($no_po));
        return view('pages.sap.purchase-order.po_detail', ['data'=>$data]);
    }

    function po_detail_json(Request $request, $no_po=null) {
        if($request->ajax()){
            $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
            $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
            $data['date_start'] = $date_start;
            $data['date_end'] = $date_end;
            $data['po'] = DB::connection('dbintranet')
            ->select("EXEC dbo.filter_po_sample_doc_number ?", array($no_po));
            return view('pages.sap.purchase-order.po_detail_new', ['data'=>$data])->render();
        }
        else {
            return view('pages.exception.blank_page');
        }
    }
}
