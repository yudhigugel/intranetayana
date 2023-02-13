<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use DataTables;
Use Cookie;
use App\Http\Controllers\Traits\IntranetTrait;

class ReportAyana extends Controller
{
	use IntranetTrait;

	function queryHelper($sqlQuery=array(), $compare=false){
		//
	}

	function welcome(){
		return "Welcome to report ayana, if you see this means controller's fine";
	}


	function index($quartal=null, Request $request){
		$comparison = false;
		$resort_selected = null;
		$reference_year = $request->post('reference-year', false);
		$to_compare_year = $request->post('to-compare-year', false);
		$quartal_comparison = $request->post('quartal-select', false);

		if($reference_year && $to_compare_year) {
			$comparison = array('to_compare_year'=>$to_compare_year, 'reference_year'=>$reference_year);
		}

		$is_year_lookup = '';
		$year_to_find = date('Y');
		if(isset($_GET['year']) and $_GET['year']){
		  $year_to_find = $_GET['year'];
		  $is_year_lookup = '?year='.$_GET['year'];
		}

		$QUARTAL = array('Q1'=>array('JAN','FEB','MAR'), 'Q2'=>array('APR','MAY','JUN'), 'Q3'=>array('JUL','AUG','SEP'), 'Q4'=>array('OCT','NOV','DEC'));

		if($quartal){
			try {
				$QUARTAL = array(strtoupper($quartal)=>$QUARTAL[$quartal]);
			} catch(Exception $e) {
				return "Exception occured, ".$e->getMessage();
			}
		}

		if($quartal_comparison) {
			try {
				$new_q = array();
				for($i=0;$i<count($quartal_comparison);$i++) {
					$new_q[$quartal_comparison[$i]] = $QUARTAL[strtoupper($quartal_comparison[$i])];
				}
				$QUARTAL = $new_q;
			} catch(Exception $e) {
				return "Exception occured, ".$e->getMessage();
			}
		}


		if($comparison){
			$rs1 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE $resort_selected YEAR in ('$reference_year') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");
			$rs2 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE $resort_selected YEAR in ('$to_compare_year') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");


			if($rs1 and $rs2) {
			  // $sql1="SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			  //        ORDER BY RESORT_NAME, ROOM_NAME ASC";
			  $row_fetched[$comparison['reference_year']]['hotel_data'] = array();
			  $row_fetched[$comparison['reference_year']]['year'] = $comparison['reference_year'];
			  foreach($data_hotel = $rs1 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched[$comparison['reference_year']]['hotel_data']))){
			      $row_fetched[$comparison['reference_year']]['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched[$comparison['reference_year']]['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }

			  $row_fetched[$comparison['to_compare_year']]['hotel_data'] = array();
			  $row_fetched[$comparison['to_compare_year']]['year'] = $comparison['to_compare_year'];
			  foreach($data_hotel2 = $rs2 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched[$comparison['to_compare_year']]['hotel_data']))){
			      $row_fetched[$comparison['to_compare_year']]['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched[$comparison['to_compare_year']]['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }
			}
			else{
		   		return "Connection could not be established (Comparison).<br />".DB::connection()->getPdo();
			}

		// ELSE (IF NOT COMPARING DATA USE BELOW QUERY)
		} else {
			$rs2 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");
			if($rs2) {
			  // $sql1="SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			  //        ORDER BY RESORT_NAME, ROOM_NAME ASC";
			  $row_fetched['hotel_data'] = array();
			  $row_fetched['year'] = $year_to_find;

			  foreach($data_hotel = $rs2 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched['hotel_data']))){
			      $row_fetched['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }

			  // return json_encode($row_fetched);
			  // Eventually Return View
			} else{
		   	  return "Connection could not be established.<br />".DB::connection()->getPdo();
			}
		}

		return view('pages.report.revenue_report_tab', ['row_fetched'=>$row_fetched, 'QUARTAL'=>$QUARTAL, 'year_to_find'=>$year_to_find, 'year_lookup'=>$is_year_lookup, 'comparison'=>$comparison]);
	}

	function report_inline($quartal=null, Request $request){
		$comparison = false;
		$reference_year = $request->post('reference-year', false);
		$to_compare_year = $request->post('to-compare-year', false);
		$quartal_comparison = $request->post('quartal-select', false);
		if($reference_year && $to_compare_year) {
			$comparison = array('to_compare_year'=>$to_compare_year, 'reference_year'=>$reference_year);
		}

		$is_year_lookup = '';
		$year_to_find = date('Y');
		if(isset($_GET['year']) and $_GET['year']){
		  $year_to_find = $_GET['year'];
		  $is_year_lookup = '?year='.$_GET['year'];
		}

		$QUARTAL = array('Q1'=>array('JAN','FEB','MAR'), 'Q2'=>array('APR','MAY','JUN'), 'Q3'=>array('JUL','AUG','SEP'), 'Q4'=>array('OCT','NOV','DEC'));

		if($quartal){
			try {
				$QUARTAL = array(strtoupper($quartal)=>$QUARTAL[$quartal]);
			} catch(Exception $e) {
				return "Exception occured, ".$e->getMessage();
			}
		}

		if($quartal_comparison) {
			try {
				$new_q = array();
				for($i=0;$i<count($quartal_comparison);$i++) {
					$new_q[$quartal_comparison[$i]] = $QUARTAL[strtoupper($quartal_comparison[$i])];
				}
				$QUARTAL = $new_q;
			} catch(Exception $e) {
				return "Exception occured, ".$e->getMessage();
			}
		}

		if($comparison){
			$rs1 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$reference_year') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");
			$rs2 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$to_compare_year') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");

			if($rs1 and $rs2) {
			  // $sql1="SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			  //        ORDER BY RESORT_NAME, ROOM_NAME ASC";
			  $row_fetched[$comparison['reference_year']]['hotel_data'] = array();
			  $row_fetched[$comparison['reference_year']]['year'] = $comparison['reference_year'];
			  foreach($data_hotel = $rs1 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched[$comparison['reference_year']]['hotel_data']))){
			      $row_fetched[$comparison['reference_year']]['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched[$comparison['reference_year']]['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }

			  $row_fetched[$comparison['to_compare_year']]['hotel_data'] = array();
			  $row_fetched[$comparison['to_compare_year']]['year'] = $comparison['to_compare_year'];
			  foreach($data_hotel2 = $rs2 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched[$comparison['to_compare_year']]['hotel_data']))){
			      $row_fetched[$comparison['to_compare_year']]['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched[$comparison['to_compare_year']]['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }
			}
			else{
		   		return "Connection could not be established (Comparison).<br />".odbc_errors();
			}

		// ELSE (IF NOT COMPARING DATA USE BELOW QUERY)
		} else {
			$rs2 = DB::connection('operadbms')->select("SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			       ORDER BY RESORT_NAME, ROOM_NAME ASC");
			if($rs2) {
			  // $sql1="SELECT * FROM dbo.VW_REVENUE_DASHBOARD WHERE YEAR in ('$year_to_find') and ROOM_NAME NOT IN ('MIG - Migration')
			  //        ORDER BY RESORT_NAME, ROOM_NAME ASC";
			  $row_fetched['hotel_data'] = array();
			  $row_fetched['year'] = $year_to_find;

			  foreach($data_hotel = $rs2 as $data_hotel){
			    if(!in_array($data_hotel->RESORT_NAME, array_keys($row_fetched['hotel_data']))){
			      $row_fetched['hotel_data'][$data_hotel->RESORT_NAME] = array('ROOM_DETAIL' =>
			       array($data_hotel->ROOM_NAME =>
			       array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV))
			      ));
			    }
			    else {
			      $row_fetched['hotel_data'][$data_hotel->RESORT_NAME]['ROOM_DETAIL'][$data_hotel->ROOM_NAME] =
			      array('JAN' => array('OCC'=>(int)$data_hotel->JAN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JAN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JAN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JAN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JAN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JAN_TOTAL_REV),

			      'FEB' => array('OCC'=>(int)$data_hotel->FEB_OCC,
			                  'OCC_PR'=>(int)$data_hotel->FEB_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->FEB_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->FEB_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->FEB_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->FEB_TOTAL_REV),

			      'MAR' => array('OCC'=>(int)$data_hotel->MAR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAR_TOTAL_REV),

			      'APR' => array('OCC'=>(int)$data_hotel->APR_OCC,
			                  'OCC_PR'=>(int)$data_hotel->APR_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->APR_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->APR_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->APR_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->APR_TOTAL_REV),

			      'MAY' => array('OCC'=>(int)$data_hotel->MAY_OCC,
			                  'OCC_PR'=>(int)$data_hotel->MAY_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->MAY_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->MAY_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->MAY_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->MAY_TOTAL_REV),

			      'JUN' => array('OCC'=>(int)$data_hotel->JUN_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUN_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUN_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUN_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUN_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUN_TOTAL_REV),

			      'JUL' => array('OCC'=>(int)$data_hotel->JUL_OCC,
			                  'OCC_PR'=>(int)$data_hotel->JUL_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->JUL_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->JUL_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->JUL_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->JUL_TOTAL_REV),

			      'AUG' => array('OCC'=>(int)$data_hotel->AUG_OCC,
			                  'OCC_PR'=>(int)$data_hotel->AUG_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->AUG_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->AUG_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->AUG_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->AUG_TOTAL_REV),

			      'SEP' => array('OCC'=>(int)$data_hotel->SEP_OCC,
			                  'OCC_PR'=>(int)$data_hotel->SEP_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->SEP_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->SEP_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->SEP_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->SEP_TOTAL_REV),

			      'OCT' => array('OCC'=>(int)$data_hotel->OCT_OCC,
			                  'OCC_PR'=>(int)$data_hotel->OCT_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->OCT_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->OCT_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->OCT_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->OCT_TOTAL_REV),

			      'NOV' => array('OCC'=>(int)$data_hotel->NOV_OCC,
			                  'OCC_PR'=>(int)$data_hotel->NOV_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->NOV_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->NOV_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->NOV_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->NOV_TOTAL_REV),

			      'DEC' => array('OCC'=>(int)$data_hotel->DEC_OCC,
			                  'OCC_PR'=>(int)$data_hotel->DEC_OCC_PR,
			                  'ROOM_REV'=>(int)$data_hotel->DEC_ROOM_REV,
			                  'FNB_REV'=>(int)$data_hotel->DEC_FNB_REV,
			                  'OTH_REV'=>(int)$data_hotel->DEC_OTH_REV,
			                  'TOTAL_REV'=>(int)$data_hotel->DEC_TOTAL_REV));
			    }
			  }

			  // Eventually Return View
			} else{
		   	  return "Connection could not be established.<br />".DB::connection()->getPdo();
			}
		}

		return view('pages.report.revenue_report_inline', ['row_fetched'=>$row_fetched, 'QUARTAL'=>$QUARTAL, 'year_to_find'=>$year_to_find, 'year_lookup'=>$is_year_lookup, 'comparison'=>$comparison]);
	}


	function revenue_daily_fb_delonix(Request $request){

		$date_start=date('2018-03-19');
		$date_end=date('2018-03-21');
		$q_date_end=date('Y-m-d',strtotime($date_end.' + 1 day'));


		if(!empty($request->get('date_start'))){
			$date_start=$request->get('date_start');
		}

		if(!empty($request->get('date_end'))){
			$date_end=$request->get('date_end');
			$q_date_end=date('Y-m-d',strtotime($request->get('date_end').' + 1 day'));
		}



		DB::enableQueryLog();

		// $record = DB::connection('dbreport')
        //       ->table('dbo.TB_POS_DETAIL')
        //       ->where('BUSINESSDATE','>=', $date_start)
        //       ->where('BUSINESSDATE','<=', $date_end)
        //       ->orderBy('GUESTCHECKID','ASC')
        //       ->orderBy('LINENUM','ASC')
        //       ->get();

        $record= DB::connection('dbreport')->select("SELECT * FROM dbo.TB_POS_DETAIL WHERE BUSINESSDATE >= '$date_start' AND BUSINESSDATE <= '$q_date_end' ORDER BY GUESTCHECKID ASC, LINENUM ASC");







        //sortir dulu arraynya
        $tampung=array();
        foreach($record as $key => $item){
        	$tampung[$item->GUESTCHECKID][] = $item;
        }

        //tambahkan total dan proses per GUESTCHECKID
        $result=array(
        	'data'=>array()
        );
        $total_revenue_daily=$grand_total_daily=$total_tax_daily=$total_discount_daily=$total_service_daily=0;
        foreach($tampung as $key=> $item){ //first loop key guestcheckid

        	$totalrevenue=$totaltax1=$totaltax2=$totaldiscount=$totalservice=$grand_total=0;
        	foreach($item as $child){ //looping per item dari guest check id
        		$totalrevenue+=$child->ITEMREVENUE;

        		$totaltax1+=($child->TAX1TOTAL > 0)? $child->TAX1TOTAL : 0; //trap tax yang minus
        		$totaldiscount+=$child->DISCOUNT;
        		$totalservice+=$child->SERVICE_CHG;
        	}



        	if($totalrevenue+$totaldiscount == 0){ //trap jika ada discount yang mengurangi dari revenue
        		$totaltax1=0;
        	}

        	$grand_total+=($totalrevenue+$totaltax1+$totalservice) + $totaldiscount;

        	$data=array();
        	$data['GUESTCHECKID']=$key;
        	$data['DATE']=date('Y-m-d',strtotime($child->BUSINESSDATE));
        	$data['TIME']=date('H:i',strtotime($child->TRANSDATETIME));
        	$data['OUTLET']=$child->REVENUECENTERNAME;
        	$data['CATEGORY']=$child->DAYPARTNAME;
        	$data['TOTAL_REVENUE']=$totalrevenue;
        	$data['TOTAL_TAX']=$totaltax1;
        	$data['TOTAL_DISCOUNT']=$totaldiscount;
        	$data['TOTAL_SERVICE']=$totalservice;
        	$data['GRAND_TOTAL']=$grand_total;

        	$result['data'][]=$data;

        	$total_revenue_daily+=$totalrevenue;
        	$total_tax_daily+=$totaltax1;
        	$total_service_daily+=$totalservice;
        	$total_discount_daily+=$totaldiscount;
        	$grand_total_daily+=$grand_total;
        }

        $result['total_revenue_daily']=$total_revenue_daily;
        $result['total_tax_daily']=$total_tax_daily;
        $result['total_service_daily']=$total_service_daily;
        $result['total_discount_daily']=$total_discount_daily;
        $result['grand_total_daily']=$grand_total_daily;


        $data=array(
        	'date_start'=>$date_start,
        	'date_end' =>$date_end,
        	'result'=>$result
        );

		return view('pages.report.revenue_daily_fb_delonix', ['data'=>$data]);
	}

	function revenue_daily_fb_delonix_detail ($id){

		//data yang hanya mau di show
		$result = DB::connection('dbreport')
        ->select('
        	SELECT
			REVENUECENTERNAME,ORDERTYPENAME,ITEMNAME,BUSINESSDATE,GUESTCHECKID,CHECKNUM,ITEMREFNUMBER,FAMILYGROUPNAME,
			SUM(REPORTLINECOUNT) as TOTAL_QTY,
			SUM(ITEMREVENUE) AS TOTAL_REVENUE,
			SUM(TAX1TOTAL) AS TOTAL_TAX,
			SUM(DISCOUNT) AS TOTAL_DISCOUNT
			FROM TB_POS_DETAIL
			WHERE GUESTCHECKID='.$id.' AND ITEMREVENUE > 0
			GROUP BY REVENUECENTERNAME,ITEMNAME,ORDERTYPENAME,BUSINESSDATE,GUESTCHECKID,CHECKNUM,ITEMREFNUMBER,FAMILYGROUPNAME
        ');


        //data yang lengkap
        $dataset = DB::connection('dbreport')
        ->select('
        	SELECT * FROM TB_POS_DETAIL
        	WHERE GUESTCHECKID = '.$id.'
        ');



        ///mencari summary
        $subtotal=$total_tax=$total_service=$total_discount=0;
        $transdatetime="";
        foreach($dataset as $r){
        	if($transdatetime==""){$transdatetime=$r->TRANSDATETIME;} //ngisi transdatetime pertama
        	$subtotal+=$r->ITEMREVENUE;
        	$total_tax+=$r->TAX1TOTAL;
        	$total_service+=$r->SERVICE_CHG;
        	$total_discount+=$r->DISCOUNT;

        }




        //trap kalau FOC supaya ga nampilin tax
        if($subtotal + $total_discount == 0){

        	// $total_discount=$total_discount-$total_tax;
        	$total_tax = 0;
        }




        $grand_total = $subtotal + $total_tax + $total_service + $total_discount;
        // echo $subtotal."<br>";
        // echo $total_tax."<br>";
        // echo $total_service."<br>";
        // echo $total_discount."<br>";
        // die;

        $summary=array(
        	'date' => $r->BUSINESSDATE,
        	'datetime' => $transdatetime,
        	'ordertype' => $r->ORDERTYPENAME,
        	'outlet' => $r->REVENUECENTERNAME,
        	'room'=> $r->REFERENCE1,
        	'guest' =>$r->REFERENCE2,
        	'subtotal' => $subtotal,
        	'total_tax' => $total_tax,
        	'total_service' => $total_service,
        	'total_discount' => $total_discount,
        	'grand_total' => $grand_total
        );

        $data=array(
        	'result'=>$result,
        	'summary'=>$summary
        );
		return view('pages.report.revenue_daily_fb_delonix_detail', ['id'=>$id, 'data'=>$data]);
	}

	function report_room_villa_daily(Request $request, $resort='ARSB'){
		$date = date("Y-m-d");
		if($request->get('business_date')){
			try {
				$date = date('Y-m-d', strtotime($request->get('business_date')));
				if($date == date('Y-m-d', strtotime('1970-01-01')))
					$date = date('Y-m-d');
			} catch(\Exception $e){
				$date = date('Y-m-d');
			}
		}

		// Select all room and room category from master data
		$dataset = DB::connection('dbreport')
        ->select("
        	SELECT rm.[OLD RESORT], rm.ROOM AS ROOM_NO, rm.[OLD_LABEL] AS ROOM_CATEGORY, rm.[OLD_ROOM_CLASS] AS ROOM_CLASS, rm.[NEW LABEL] AS ROOM_LABEL, rm.[NEW DESCRIPTION] as ROOM_DESC, rc.[NEW LABEL SHORT_DESCRIPTION] as NEW_ROOM_CATEGORY
        	-- SELECT rm.RESORT, rm.ROOM as ROOM_NO, rm.ROOM_CATEGORY, rm.ROOM_CLASS, rc.SHORT_DESCRIPTION as ROOM_DESC
			FROM [DBREPORT].[dbo].[TBL_ROOM_KMS1] rm
			LEFT JOIN [DBREPORT].[dbo].[TBL_ROOM_CATEGORY] rc
			-- ON rm.ROOM_CATEGORY = rc.ROOM_CATEGORY
			ON rm.OLD_LABEL = rc.[OLD LABEL]
			WHERE rm.[OLD_ROOM_CLASS] = 'VIL' AND rm.[OLD RESORT] = 'ARSB'
			ORDER BY rm.ROOM ASC
        ");


        // Memperoleh data statistik villa
        $data_villa_statistic = DB::connection('dbopera')
        ->select("
        	SELECT rs.RESORT, rs.ROOM_CLASS, rm.VILLA_AVAILABLE_ROOM, rm.VILLA_AVAILABLE_ROOM * DATEPART(DAY, CAST('$date' AS datetime)) as VILLA_AVAILABLE_MTD,
			-- SUMMARY VILLA OCCUPIED
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN rs.STAY_ROOMS ELSE 0 END) AS VILLA_OCCUPIED_NOW,
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN rs.STAY_ROOMS ELSE 0 END) AS VILLA_OCCUPIED_ACTUAL_MTD,
			-- SUMMARY VILLA UPGRADE
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM-dd') = '$date' AND rs.BOOKED_ROOM_CLASS <> rs.ROOM_CLASS THEN 1 ELSE 0 END) AS VILLA_UPGRADE_NOW,
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') AND rs.BOOKED_ROOM_CLASS <> rs.ROOM_CLASS THEN 1 ELSE 0 END) AS VILLA_UPGRADE_ACTUAL_MTD,
			-- SUMMARY VILLA UPSOLD
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM-dd') = '$date' AND rs.BOOKED_ROOM_CLASS <> rs.ROOM_CLASS AND UPG_CODE = 'UPS' THEN 1 ELSE 0 END) AS VILLA_UPSOLD_NOW,
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') AND rs.BOOKED_ROOM_CLASS <> rs.ROOM_CLASS AND UPG_CODE = 'UPS' THEN 1 ELSE 0 END) AS VILLA_UPSOLD_ACTUAL_MTD,
			-- SUMMARY VILLA COMPLEMENTARY
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM-dd') = '$date' AND rs.COMPLIMENTARY_YN = 'Y' THEN 1 ELSE 0 END) AS VILLA_COMPLIMENTARY_NOW,
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') AND rs.COMPLIMENTARY_YN = 'Y' THEN 1 ELSE 0 END) AS VILLA_COMPLIMENTARY_ACTUAL_MTD,
			-- SUMMARY VILLA NON UPGRADE ORIGINAL VILLA
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM-dd') = '$date' AND rs.BOOKED_ROOM_CLASS = rs.ROOM_CLASS THEN 1 ELSE 0 END) AS VILLA_ORIGINAL_NOW,
			SUM(CASE WHEN FORMAT(rs.BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') AND rs.BOOKED_ROOM_CLASS = rs.ROOM_CLASS THEN 1 ELSE 0 END) AS VILLA_ORIGINAL_ACTUAL_MTD
			FROM dbo.TB_EXAMPLE_MAP_BALI_ROOM_STATISTIC_2021 rs
			LEFT JOIN (
			    SELECT ROOM_CLASS, COUNT(*) as VILLA_AVAILABLE_ROOM FROM dbo.TB_EXAMPLE_MAP_BALI_ROOM
			    WHERE ROOM_CLASS = 'VIL'
			    GROUP BY ROOM_CLASS
			) as rm
			ON rs.ROOM_CLASS = rm.ROOM_CLASS
			WHERE rs.ROOM_CLASS = 'VIL' AND rs.STAY_ROOMS > 0
			GROUP BY rs.RESORT, rs.ROOM_CLASS, rm.VILLA_AVAILABLE_ROOM
        ");

        // Memperoleh data revenue per ROOM NUMBER
        $data_revenue = DB::connection('dbayana-stg')
        ->select("
            EXEC dbo.filter_villa_per_room_no ?
        ", array($date));

        // Memperoleh data revenue per ROOM CATEGORY
        $data_revenue_room_category = DB::connection('dbopera')
        ->select("
        	SELECT CASE WHEN GROUPING(rs.ROOM_CLASS) = '1' THEN 'TOTAL' ELSE rs.ROOM_CLASS END as ROOM_CLASS,
			CASE WHEN GROUPING(rs.ROOM_CATEGORY) = '1' THEN 'TOTAL' ELSE rs.ROOM_CATEGORY END as ROOM_CATEGORY,
			CASE WHEN GROUPING(rc.[NEW LABEL SHORT_DESCRIPTION]) = '1' THEN 'TOTAL' ELSE rc.[NEW LABEL SHORT_DESCRIPTION] END as ROOM_DESCRIPTION,
			CASE WHEN GROUPING(rs.ROOM_CATEGORY_LABEL) = '1' THEN 'TOTAL' ELSE rs.ROOM_CATEGORY_LABEL END as ROOM_CATEGORY_LABEL,
			-- CASE WHEN GROUPING(rc.SHORT_DESCRIPTION) = '1' THEN 'TOTAL' ELSE rc.SHORT_DESCRIPTION END as ROOM_DESCRIPTION,
			-- GET OCCUPANCY NOW AND MTD
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN STAY_ROOMS ELSE 0 END) AS OCCP,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN STAY_ROOMS ELSE 0 END) AS OCCP_MTD,
			-- NOW DATE REVENUE
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN ROOM_REVENUE ELSE 0 END) AS NOW_ROOM_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN FOOD_REVENUE ELSE 0 END) AS NOW_FNB_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN OTHER_REVENUE ELSE 0 END) AS NOW_OTHER_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM-dd') = '$date' THEN TOTAL_REVENUE ELSE 0 END) AS NOW_TOTAL_REVENUE,
			-- MTD REVENUE
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN ROOM_REVENUE ELSE 0 END) AS MTD_ROOM_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN FOOD_REVENUE ELSE 0 END) AS MTD_FNB_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN OTHER_REVENUE ELSE 0 END) AS MTD_OTHER_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy-MM') = FORMAT(CAST('$date' AS datetime), 'yyyy-MM') THEN TOTAL_REVENUE ELSE 0 END) AS MTD_TOTAL_REVENUE,
			-- YTD REVENUE
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy') = FORMAT(CAST('$date' AS datetime), 'yyyy') THEN ROOM_REVENUE ELSE 0 END) AS YTD_ROOM_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy') = FORMAT(CAST('$date' AS datetime), 'yyyy') THEN FOOD_REVENUE ELSE 0 END) AS YTD_FNB_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy') = FORMAT(CAST('$date' AS datetime), 'yyyy') THEN OTHER_REVENUE ELSE 0 END) AS YTD_OTHER_REVENUE,
			SUM(CASE WHEN FORMAT(BUSINESS_DATE, 'yyyy') = FORMAT(CAST('$date' AS datetime), 'yyyy') THEN TOTAL_REVENUE ELSE 0 END) AS YTD_TOTAL_REVENUE
			FROM dbo.TB_EXAMPLE_MAP_BALI_ROOM_STATISTIC_2021 rs
			-- LEFT JOIN dbo.TB_EXAMPLE_MAP_BALI_ROOMCATEGORY rc ON rs.ROOM_CATEGORY = rc.ROOM_CATEGORY
			LEFT JOIN [DBREPORT].[dbo].[TBL_ROOM_CATEGORY] rc ON rs.ROOM_CATEGORY_LABEL = rc.[OLD LABEL]
			WHERE rs.ROOM_CLASS = 'VIL' AND rs.ROOM_NO IS NOT NULL AND rs.RESV_STATUS IN ('CHECKED IN', 'CHECKED OUT')
			-- GROUP BY GROUPING SETS((rs.ROOM_CLASS, rs.ROOM_CATEGORY, rc.SHORT_DESCRIPTION), ())
			GROUP BY GROUPING SETS((rs.ROOM_CLASS, rs.ROOM_CATEGORY, rc.[NEW LABEL SHORT_DESCRIPTION], rs.ROOM_CATEGORY_LABEL), ())
        ");

        // SET DATA MASTER ROOM AS COLLECTION
        $dataset = collect($dataset);

        // Mendapatkan semua room category dan set total
        $data_room_category = $dataset->mapWithKeys(function($data, $key){
        	return [$data->ROOM_CATEGORY => (object) array('ROOM'=>$data->NEW_ROOM_CATEGORY, 'OCC'=>0.0, 'MTD_OCC'=>0.0), "TOTAL" => (object) array('ROOM'=>'-', 'OCC'=>0.0, 'MTD_OCC'=>0.0)];
        });

        $data_revenue_room_category_map = $data_room_category->map(function($data, $key) use ($data_revenue_room_category){
        	$data->NOW_ROOM_REVENUE = 0.0;
			$data->NOW_FNB_REVENUE = 0.0;
			$data->NOW_OTHER_REVENUE = 0.0;

			$data->MTD_ROOM_REVENUE = 0.0;
			$data->MTD_FNB_REVENUE = 0.0;
			$data->MTD_OTHER_REVENUE = 0.0;

			$data->YTD_ROOM_REVENUE = 0.0;
			$data->YTD_FNB_REVENUE = 0.0;
			$data->YTD_OTHER_REVENUE = 0.0;

        	foreach ($data_revenue_room_category as $loop => $obj) {
    			if($obj->ROOM_CATEGORY_LABEL == $key){
    				$data->OCC = $obj->OCCP;
    				$data->MTD_OCC = $obj->OCCP_MTD;

    				$data->NOW_ROOM_REVENUE = $obj->NOW_ROOM_REVENUE;
        			$data->NOW_FNB_REVENUE = $obj->NOW_FNB_REVENUE;
        			$data->NOW_OTHER_REVENUE = $obj->NOW_OTHER_REVENUE;

        			$data->MTD_ROOM_REVENUE = $obj->MTD_ROOM_REVENUE;
        			$data->MTD_FNB_REVENUE = $obj->MTD_FNB_REVENUE;
        			$data->MTD_OTHER_REVENUE = $obj->MTD_OTHER_REVENUE;

        			$data->YTD_ROOM_REVENUE = $obj->YTD_ROOM_REVENUE;
        			$data->YTD_FNB_REVENUE = $obj->YTD_FNB_REVENUE;
        			$data->YTD_OTHER_REVENUE = $obj->YTD_OTHER_REVENUE;
    			}
        	}
        });

        $data_revenue_all = $dataset->map(function($data, $key) use ($data_revenue){
        	// Set default Revenue First
        	$data->NOW_ROOM_REVENUE = 0.0;
			$data->NOW_FNB_REVENUE = 0.0;
			$data->NOW_OTHER_REVENUE = 0.0;

			$data->MTD_ROOM_REVENUE = 0.0;
			$data->MTD_FNB_REVENUE = 0.0;
			$data->MTD_OTHER_REVENUE = 0.0;

			$data->YTD_ROOM_REVENUE = 0.0;
			$data->YTD_FNB_REVENUE = 0.0;
			$data->YTD_OTHER_REVENUE = 0.0;

			// Loop Revenue Data and match room number
        	foreach($data_revenue as $data_revenue_each_room) {
        		if($data->ROOM_NO == $data_revenue_each_room->ROOM_NO){
        			$data->NOW_ROOM_REVENUE = $data_revenue_each_room->NOW_ROOM_REVENUE;
        			$data->NOW_FNB_REVENUE = $data_revenue_each_room->NOW_FNB_REVENUE;
        			$data->NOW_OTHER_REVENUE = $data_revenue_each_room->NOW_OTHER_REVENUE;

        			$data->MTD_ROOM_REVENUE = $data_revenue_each_room->MTD_ROOM_REVENUE;
        			$data->MTD_FNB_REVENUE = $data_revenue_each_room->MTD_FNB_REVENUE;
        			$data->MTD_OTHER_REVENUE = $data_revenue_each_room->MTD_OTHER_REVENUE;

        			$data->YTD_ROOM_REVENUE = $data_revenue_each_room->YTD_ROOM_REVENUE;
        			$data->YTD_FNB_REVENUE = $data_revenue_each_room->YTD_FNB_REVENUE;
        			$data->YTD_OTHER_REVENUE = $data_revenue_each_room->YTD_OTHER_REVENUE;
        		}
        	}
        });

        // return $dataset;
		return view('pages.report.report_daily_room_villa', ['data_room'=>$dataset, 'date_to_lookup'=>$date, 'data_room_category'=>$data_room_category, 'data_villa_statistic'=>$data_villa_statistic]);
	}

	function report_revenue_daily(Request $request){
		try {

			$date = date('Y-m-d');			
			$date = date('Y-m-d',strtotime ( '-1 day' , strtotime ( $date ) )); 
			if($request->get('business_date')){
				try {
					$date = date('Y-m-d', strtotime($request->get('business_date')));
					if($date == date('Y-m-d', strtotime('1970-01-01')))
						$date = date('Y-m-d');
				} catch(\Exception $e){
					$date = date('Y-m-d');
				}
			}

			$date_start = date('Y-m-01', strtotime($date));
            $filter_plant = (!empty($request->get('plant')))? $request->get('plant') : 'KMS1';
            $list_plant=DB::connection('dbayana-stg')
            ->select("SELECT RESORT FROM dbo.MasterSubResort GROUP BY RESORT");

            $filter_subresort=(!empty($request->get('subresort')))? $request->get('subresort') : 'AYANARIMBA';
            if($filter_plant=="KMS1"){
                $list_subresort=DB::connection('dbayana-stg')
                ->select("SELECT SUB_RESORT, SUB_RESORT_NAME FROM dbo.MasterSubResort WHERE RESORT = '".$filter_plant."' AND SUB_RESORT <> 'AYZR' AND SUB_RESORT <> 'KMS1' ");

                $array_manual=array(
                    'SUB_RESORT'=>"AYANARIMBA",
                    'SUB_RESORT_NAME'=>"AYANA ESTATE"
                );

                $array_manual = json_decode(json_encode($array_manual), FALSE);
                array_unshift($list_subresort,$array_manual);

            }else{
                $list_subresort=DB::connection('dbayana-stg')
            	->select("SELECT RESORT, SUB_RESORT, SUB_RESORT_NAME FROM dbo.MasterSubResort WHERE RESORT = '".$filter_plant."' GROUP BY RESORT, SUB_RESORT, SUB_RESORT_NAME");
            }

            //cari nama plant
			if($filter_subresort=="AYANARIMBA"){
                $plant_name[]=(object)array('SUB_RESORT_NAME'=>"AYANA ESTATE");

            }else{
                $plant_name = DB::connection('dbayana-stg')
            	->select("SELECT SUB_RESORT_NAME FROM MasterSubResort WHERE SUB_RESORT ='$filter_subresort'");
            }

			//hardcode untuk filter sub resort AYBL
			if($filter_subresort=="AYBL"){
				$query_filter_subresort="SUB_RESORT IN ('AYBL')";
			}else if($filter_subresort=="AYANARIMBA"){
                $query_filter_subresort="SUB_RESORT IN ('AYBL','AYVL','RBBL','KMS1','RBWN','AYSG')";
            }else{
				$query_filter_subresort="SUB_RESORT = '".$filter_subresort."' ";
			}


			// BOOKING RATE NOW AND AVERAGE PER MONTH OLD
			// $booking_rate_today=DB::connection('dbayana-stg')
			// ->select("SELECT TOP 1 BUSINESS_DATE AS BUSINESS_DATE_TODAY, EXCHANGE_RATE AS EXCHANGE_RATE_TODAY
			// FROM ReservationDailyStat
			// WHERE BUSINESS_DATE = FORMAT(CAST('".$date."' AS datetime), 'yyyy-MM-dd') AND  RESORT ='".$filter_plant."' AND ".$query_filter_subresort."
			// GROUP BY EXCHANGE_RATE, BUSINESS_DATE
			// ORDER BY BUSINESS_DATE DESC");


			// $booking_rate_mtd=DB::connection("dbayana-stg")
			// ->select("SELECT
            //     ROUND(AVG(CASE WHEN FORMAT(CAST(BUSINESS_DATE as datetime), 'yyyy-MM') BETWEEN FORMAT(CAST('$date_start' as datetime), 'yyyy-MM') AND FORMAT(CAST('2021-11-23' as datetime), 'yyyy-MM')  THEN EXCHANGE_RATE END),0) AS EXCHANGE_RATE_MTD,
            //     ROUND(AVG(CASE WHEN FORMAT(CAST(BUSINESS_DATE as datetime), 'yyyy-MM') BETWEEN FORMAT(DATEADD(year, -1, CAST('$date_start' as datetime)), 'yyyy-MM') AND FORMAT(DATEADD(year, -1, CAST('$date' as datetime)), 'yyyy-MM') THEN EXCHANGE_RATE END),0) AS EXCHANGE_RATE_MTD_LAST_YEAR
            //     FROM ReservationDailyStat
            //     WHERE
            //     FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
            //     AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')  AND RESORT ='".$filter_plant."'
            //     AND ".$query_filter_subresort."
			// ");
			// $booking_rate['today']=$booking_rate_today;
			// $booking_rate['mtd']=$booking_rate_mtd;
            // END ==================================================================

            // BOOK KEEPING RATE NOW AND MTD

            $booking_rate=DB::connection('dbayana-stg')
			->select("SELECT
            SUM(BOOK_KEEPING_RATE_TODAY) AS BOOK_KEEPING_RATE_TODAY,
            AVG(BOOK_KEEPING_RATE_MTD) AS BOOK_KEEPING_RATE_MTD,
            AVG(BOOK_KEEPING_RATE_MTD_LAST_YEAR) AS BOOK_KEEPING_RATE_MTD_LAST_YEAR
            FROM(
                SELECT
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN AVG(BOOKEEPING_RATE) END AS BOOK_KEEPING_RATE_TODAY,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN AVG(BOOKEEPING_RATE) END AS BOOK_KEEPING_RATE_MTD,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN AVG(BOOKEEPING_RATE) END AS BOOK_KEEPING_RATE_MTD_LAST_YEAR
                FROM dbo.PmsBookeepingRate
                WHERE
                FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                AND RESORT='".$filter_plant."'
                GROUP BY BUSINESS_DATE
            )dt
            ");

			// ROOM AVAILABLE NOW AND MTD
			$room_available = DB::connection('dbayana-stg')
			->select("SELECT
                SUM(CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('".$date."' as date), 'yyyy-MM-dd') THEN PHYSICAL_ROOMS END) AS ROOM_AVAILABLE_TODAY,
                SUM(CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('".$date_start."' as date), 'yyyy-MM-dd') AND FORMAT(CAST('".$date."' as date), 'yyyy-MM-dd') THEN PHYSICAL_ROOMS END) AS ROOM_AVAILABLE_MTD,
                SUM(CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('".$date_start."' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('".$date."' as date)), 'yyyy-MM-dd') THEN PHYSICAL_ROOMS END) AS ROOM_AVAILABLE_MTD_LAST_YEAR
                FROM StatRoomCategory
                WHERE
                FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('".$date."' AS datetime))) AND  DATENAME (year,CAST('".$date."' AS datetime))
                AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('".$date."' AS datetime), 'MM')
                AND RESORT='".$filter_plant."' AND ".$query_filter_subresort."
			");
            // END ==================================================================


			// ROOM SOLD AND VILLA SOLD
            if($filter_plant=="KMS1" && $filter_subresort=="AYBL"){
                $query_rs_resort = "SUB_RESORT = 'AYBL'";
				$query_rs_villa = "SUB_RESORT = '-----'";            		
            }else if ($filter_plant=="KMS1" && $filter_subresort=="AYVL"){
				$query_rs_resort = "SUB_RESORT = '-----'";
				$query_rs_villa = "SUB_RESORT = 'AYVL'";
			}
			else if($filter_plant=="KMS1" && $filter_subresort=="AYANARIMBA"){
                $query_rs_resort = "SUB_RESORT IN ('AYBL','RBBL','RBWN','AYSG') ";
                $query_rs_villa = "SUB_RESORT = 'AYVL'";
            }else{
                $query_rs_resort = "SUB_RESORT = '$filter_subresort'";
                $query_rs_villa = "SUB_RESORT = '-----'";
            }

			$resort_room_sold = DB::connection('dbayana-stg')
			->select("SELECT
                SUM(RESORT_ROOMS_SOLD_TODAY) AS RESORT_ROOMS_SOLD_TODAY,
                SUM(VILLA_ROOMS_SOLD_TODAY) AS VILLA_ROOMS_SOLD_TODAY,
                SUM(RESORT_ROOMS_SOLD_MTD) AS RESORT_ROOMS_SOLD_MTD,
                SUM(VILLA_ROOMS_SOLD_MTD) AS VILLA_ROOMS_SOLD_MTD,
                SUM(RESORT_ROOMS_SOLD_MTD_LAST_YEAR) AS RESORT_ROOMS_SOLD_MTD_LAST_YEAR,
                SUM(VILLA_ROOMS_SOLD_MTD_LAST_YEAR) AS VILLA_ROOMS_SOLD_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN $query_rs_resort THEN ROUND(SUM(RESORT_ROOMS_SOLD_TODAY),0) END AS RESORT_ROOMS_SOLD_TODAY,
                    CASE WHEN $query_rs_villa THEN ROUND(SUM(RESORT_ROOMS_SOLD_TODAY),0) END AS VILLA_ROOMS_SOLD_TODAY,
                    CASE WHEN $query_rs_resort THEN ROUND(SUM(RESORT_ROOMS_SOLD_MTD),0) END AS RESORT_ROOMS_SOLD_MTD,
                    CASE WHEN $query_rs_villa THEN ROUND(SUM(RESORT_ROOMS_SOLD_MTD),0) END AS VILLA_ROOMS_SOLD_MTD,
                    CASE WHEN $query_rs_resort THEN ROUND(SUM(RESORT_ROOMS_SOLD_MTD_LAST_YEAR),0) END AS RESORT_ROOMS_SOLD_MTD_LAST_YEAR,
                    CASE WHEN $query_rs_villa THEN ROUND(SUM(RESORT_ROOMS_SOLD_MTD_LAST_YEAR),0) END AS VILLA_ROOMS_SOLD_MTD_LAST_YEAR
                    FROM (
                        SELECT BUSINESS_DATE, SUB_RESORT,
                        CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS) END AS RESORT_ROOMS_SOLD_TODAY,
                        CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS) END AS RESORT_ROOMS_SOLD_MTD,
                        CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS) END AS RESORT_ROOMS_SOLD_MTD_LAST_YEAR
                        FROM StatRoomCategory
                        WHERE
                        FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND RESORT='$filter_plant'
                        GROUP BY BUSINESS_DATE, SUB_RESORT
                    ) dt
                    GROUP BY SUB_RESORT
                ) dtm
			");

            // END ==================================================================

            // OCCUPANCY
			$occupancy = DB::connection('dbayana-stg')
			->select("SELECT
				ROUND(SUM(HOUSE_USE_ROOMS_TODAY),0) AS HOUSE_USE_ROOMS_TODAY,
				ROUND(SUM(HOUSE_USE_ROOMS_MTD),0) AS HOUSE_USE_ROOMS_MTD,
				ROUND(SUM(HOUSE_USE_ROOMS_MTD_LAST_YEAR),0) AS HOUSE_USE_ROOMS_MTD_LAST_YEAR,

				ROUND(SUM(TOTAL_ROOM_OCC_TODAY),0) AS TOTAL_ROOM_OCC_TODAY,
				ROUND(SUM(TOTAL_ROOM_OCC_MTD),0) AS TOTAL_ROOM_OCC_MTD,
				ROUND(SUM(TOTAL_ROOM_OCC_MTD_LAST_YEAR),0) AS TOTAL_ROOM_OCC_MTD_LAST_YEAR,

				ROUND(SUM(NUMBER_OF_GUEST_TODAY),0) AS NUMBER_OF_GUEST_TODAY,
				ROUND(SUM(NUMBER_OF_GUEST_MTD),0) AS NUMBER_OF_GUEST_MTD,
				ROUND(SUM(NUMBER_OF_GUEST_MTD_LAST_YEAR),0) AS NUMBER_OF_GUEST_MTD_LAST_YEAR,

				ROUND(AVG(ADR_RESORT_TODAY),0) AS ADR_RESORT_TODAY,
				ROUND(AVG(ADR_RESORT_MTD),0) AS ADR_RESORT_MTD,
				ROUND(AVG(ADR_RESORT_MTD_LAST_YEAR),0) AS ADR_RESORT_MTD_LAST_YEAR,

				ROUND(AVG(ADR_VILLA_TODAY),0) AS ADR_VILLA_TODAY,
				ROUND(AVG(ADR_VILLA_MTD),0) AS ADR_VILLA_MTD,
				ROUND(AVG(ADR_VILLA_MTD_LAST_YEAR),0) AS ADR_VILLA_MTD_LAST_YEAR,

				ROUND(SUM(CAST(ROOM_REVENUE_TODAY as BIGINT)),0) AS ROOM_REVENUE_TODAY,
				ROUND(SUM(CAST(ROOM_REVENUE_MTD as BIGINT)),0) AS ROOM_REVENUE_MTD,
				ROUND(SUM(CAST(ROOM_REVENUE_MTD_LAST_YEAR as BIGINT)),0) AS ROOM_REVENUE_MTD_LAST_YEAR,

				ROUND(SUM(CAST(VILLA_REVENUE_TODAY as BIGINT)),0) AS VILLA_REVENUE_TODAY,
				ROUND(SUM(CAST(VILLA_REVENUE_MTD as BIGINT)),0) AS VILLA_REVENUE_MTD,
				ROUND(SUM(CAST(VILLA_REVENUE_MTD_LAST_YEAR as BIGINT)),0) AS VILLA_REVENUE_MTD_LAST_YEAR
				FROM(
					SELECT
					/* HOUSE_USE_ROOMS*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(HOUSE_USE_ROOMS) END AS HOUSE_USE_ROOMS_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(HOUSE_USE_ROOMS) END AS HOUSE_USE_ROOMS_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(HOUSE_USE_ROOMS) END AS HOUSE_USE_ROOMS_MTD_LAST_YEAR,
					/* TOTAL_ROOM_OCC*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS) END AS TOTAL_ROOM_OCC_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS) END AS TOTAL_ROOM_OCC_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(OCCUPIED_ROOMS) END AS TOTAL_ROOM_OCC_MTD_LAST_YEAR,
					/* NUMBER_OF_GUEST*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_PERSONS) END AS NUMBER_OF_GUEST_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(OCCUPIED_PERSONS) END AS NUMBER_OF_GUEST_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(OCCUPIED_PERSONS) END AS NUMBER_OF_GUEST_MTD_LAST_YEAR,
					/* ADR RESORT*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_resort THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_RESORT_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_resort THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_RESORT_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') AND  $query_rs_resort THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_RESORT_MTD_LAST_YEAR,
					/* ADR VILLA*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_villa THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_VILLA_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_villa THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_VILLA_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') AND  $query_rs_villa THEN SUM(ROOM_REVENUE)/ NULLIF(SUM(OCCUPIED_ROOMS)-SUM(HOUSE_USE_ROOMS)-SUM(COMPLIMENTARY_ROOMS),0) END AS ADR_VILLA_MTD_LAST_YEAR,
					/* ROOM RESORT REVENUE*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_resort THEN SUM(ROOM_REVENUE) END AS ROOM_REVENUE_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_resort THEN SUM(ROOM_REVENUE) END AS ROOM_REVENUE_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') AND $query_rs_resort THEN SUM(ROOM_REVENUE) END AS ROOM_REVENUE_MTD_LAST_YEAR,
					/* ROOM VILLA REVENUE*/
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_villa THEN SUM(ROOM_REVENUE) END AS VILLA_REVENUE_TODAY,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') AND $query_rs_villa THEN SUM(ROOM_REVENUE) END AS VILLA_REVENUE_MTD,
					CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') AND $query_rs_villa THEN SUM(ROOM_REVENUE) END AS VILLA_REVENUE_MTD_LAST_YEAR
					FROM (
						SELECT  SUB_RESORT, BUSINESS_DATE, HOUSE_USE_ROOMS, OCCUPIED_ROOMS, OCCUPIED_PERSONS, ROOM_REVENUE, COMPLIMENTARY_ROOMS, PHYSICAL_ROOMS
						FROM StatRoomCategory
						WHERE
						FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
						AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
						AND RESORT='$filter_plant' AND $query_filter_subresort
					) data1
					GROUP BY BUSINESS_DATE, SUB_RESORT
				)source1
			");
            // END ==================================================================


            // GROUPS ROOM SOLD
			$groups_room_sold= DB::connection('dbayana-stg')
			->select("WITH GROUPS_ROOM_SOLD_TODAY AS  (
					SELECT COUNT(DISTINCT(ROOM_NO))  AS GROUPS_ROOM_SOLD_TODAY, 1 AS PEMERSATU
					FROM ReservationDailyStat
					WHERE
					FORMAT(CAST(BUSINESS_DATE AS date),'yyyy-MM-dd') = FORMAT(CAST('$date' AS date), 'yyyy-MM-dd')
					AND RESORT='$filter_plant' AND $query_filter_subresort
					AND ALLOTMENT_HEADER_ID IS NOT NULL
					AND ROOM_CATEGORY_LABEL <> 'PM'
					AND RESV_STATUS='CHECKED IN'
				),
				GROUPS_ROOM_SOLD_MTD AS  (
					SELECT COUNT(DISTINCT(ROOM_NO))  AS GROUPS_ROOM_SOLD_MTD, 1 AS PEMERSATU
					FROM ReservationDailyStat
					WHERE
					FORMAT(CAST(BUSINESS_DATE AS date),'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' AS date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' AS date), 'yyyy-MM-dd')
					AND RESORT='$filter_plant' AND $query_filter_subresort
					AND ALLOTMENT_HEADER_ID IS NOT NULL
					AND ROOM_CATEGORY_LABEL <> 'PM'
					AND RESV_STATUS='CHECKED IN'
				),
				GROUPS_ROOM_SOLD_MTD_LAST_YEAR AS  (
					SELECT COUNT(DISTINCT(ROOM_NO)) AS GROUPS_ROOM_SOLD_MTD_LAST_YEAR, 1 AS PEMERSATU
					FROM ReservationDailyStat
					WHERE
					FORMAT(CAST(BUSINESS_DATE AS date),'yyyy-MM') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' AS date)), 'yyyy-MM') AND FORMAT(DATEADD(year,-1,CAST('$date' AS date)), 'yyyy-MM')
					AND RESORT='$filter_plant' AND $query_filter_subresort
					AND ALLOTMENT_HEADER_ID IS NOT NULL
					AND ROOM_CATEGORY_LABEL <> 'PM'
					AND RESV_STATUS='CHECKED IN'
				)
				SELECT GROUPS_ROOM_SOLD_TODAY, GROUPS_ROOM_SOLD_MTD, GROUPS_ROOM_SOLD_MTD_LAST_YEAR FROM GROUPS_ROOM_SOLD_TODAY a
				JOIN GROUPS_ROOM_SOLD_MTD b ON a.PEMERSATU = b.PEMERSATU
				JOIN GROUPS_ROOM_SOLD_MTD_LAST_YEAR c ON a.PEMERSATU = c.PEMERSATU
			");
            // END ==================================================================

            // REVPAR
            $revpar= DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVPAR_TODAY) AS REVPAR_TODAY,
                AVG(REVPAR_MTD) AS REVPAR_MTD,
                AVG(REVPAR_MTD_LAST_YEAR) AS REVPAR_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(ROOM_REVENUE) / SUM(PHYSICAL_ROOMS) END AS REVPAR_TODAY,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(ROOM_REVENUE) / SUM(PHYSICAL_ROOMS) END AS REVPAR_MTD,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(ROOM_REVENUE) / SUM(PHYSICAL_ROOMS) END AS REVPAR_MTD_LAST_YEAR
                    FROM(
                        SELECT ROOM_REVENUE,PHYSICAL_ROOMS,BUSINESS_DATE
                        FROM StatRoomCategory
                        WHERE
                        FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND RESORT='$filter_plant' AND $query_filter_subresort
                    )dt
                    GROUP BY BUSINESS_DATE
                ) dt2
            ");
            // END ==================================================================

			// OUTLET REVENUE FOOD
            $outlet_revenue_food=DB::connection('dbayana-stg')
            ->select("SELECT
                TRUNCOUTLETNAME, REVENUECENTERNAME, REVENUE_SAPCOSTCENTER,
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    TRUNCOUTLETNAME, REVENUECENTERNAME,
                    RIGHT(TRX_CODE,10) AS REVENUE_SAPCOSTCENTER,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TrialBalance.TRX_DATE,TrialBalance.RESORT,TrialBalance.SUB_RESORT,TrialBalance.TRX_CODE,TrialBalance.REVENUE,
                        MasterOutlet.TRUNCOUTLETNAME, MasterOutlet.REVENUECENTERNAME
                        FROM TrialBalance
                        LEFT JOIN MasterOutlet ON RIGHT(TrialBalance.TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP='RFNB'
                        AND RIGHT(TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='FOOD'
                        )
                    )dt
                    GROUP BY TRX_DATE,TRX_CODE, TRUNCOUTLETNAME, REVENUECENTERNAME
                ) dt2
                GROUP BY REVENUE_SAPCOSTCENTER,TRUNCOUTLETNAME, REVENUECENTERNAME
                ORDER BY TRUNCOUTLETNAME
            ");
            // END ==================================================================

            // OUTLET REVENUE BEVERAGE
            $outlet_revenue_beverage=DB::connection('dbayana-stg')
            ->select("SELECT
                TRUNCOUTLETNAME, REVENUECENTERNAME, REVENUE_SAPCOSTCENTER,
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    TRUNCOUTLETNAME, REVENUECENTERNAME,
                    RIGHT(TRX_CODE,10) AS REVENUE_SAPCOSTCENTER,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TrialBalance.TRX_DATE,TrialBalance.RESORT,TrialBalance.SUB_RESORT,TrialBalance.TRX_CODE,TrialBalance.REVENUE,
                        MasterOutlet.TRUNCOUTLETNAME, MasterOutlet.REVENUECENTERNAME
                        FROM TrialBalance
                        LEFT JOIN MasterOutlet ON RIGHT(TrialBalance.TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP='RFNB'
                        AND RIGHT(TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='BEVERAGE'
                        )
                    )dt
                    GROUP BY TRX_DATE,TRX_CODE, TRUNCOUTLETNAME, REVENUECENTERNAME
                ) dt2
                GROUP BY REVENUE_SAPCOSTCENTER,TRUNCOUTLETNAME, REVENUECENTERNAME
                ORDER BY TRUNCOUTLETNAME
            ");

            // END ==================================================================

             // OUTLET REVENUE FOOD & BEVERAGE
             $outlet_revenue_food_beverage=DB::connection('dbayana-stg')
             ->select("SELECT
                 TRUNCOUTLETNAME, REVENUECENTERNAME, REVENUE_SAPCOSTCENTER,
                 SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                 SUM(REVENUE_MTD) AS REVENUE_MTD,
                 SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                 FROM(
                     SELECT
                     TRUNCOUTLETNAME, REVENUECENTERNAME,
                     RIGHT(TRX_CODE,10) AS REVENUE_SAPCOSTCENTER,
                     CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                     CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                     CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                     FROM(
                         SELECT TrialBalance.TRX_DATE,TrialBalance.RESORT,TrialBalance.SUB_RESORT,TrialBalance.TRX_CODE,TrialBalance.REVENUE,
                         MasterOutlet.TRUNCOUTLETNAME, MasterOutlet.REVENUECENTERNAME
                         FROM TrialBalance
                         LEFT JOIN MasterOutlet ON RIGHT(TrialBalance.TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                         WHERE
                         FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                         AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                         AND $query_filter_subresort
                         AND TC_GROUP='RFNB'
                         AND RIGHT(TRX_CODE,10) = MasterOutlet.SAPCOSTCENTER
                         AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='FOOD' OR GROUP_REPORT='BEVERAGE'
                         )
                     )dt
                     GROUP BY TRX_DATE,TRX_CODE, TRUNCOUTLETNAME, REVENUECENTERNAME
                 ) dt2
                 GROUP BY REVENUE_SAPCOSTCENTER,TRUNCOUTLETNAME, REVENUECENTERNAME
                 ORDER BY TRUNCOUTLETNAME
             ");

             // END ==================================================================

        	$retail = DB::connection('dbayana-stg')
        	->select("SELECT
            SUM(REVENUE_TODAY) AS REVENUE_TODAY,
            SUM(REVENUE_MTD) AS REVENUE_MTD,
            SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
            FROM(
                SELECT
                CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                    FROM TrialBalance
                    WHERE
                    FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                    AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                    AND $query_filter_subresort
                    AND TC_GROUP ='RRTL'
                )dt
                GROUP BY TRX_DATE
            )dt2
        	");

        	$recreation = DB::connection('dbayana-stg')
        	->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RREC'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
        	");

            $spa = DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RSPA'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $laundry = DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP = 'RLDY'
                    --     AND RIGHT(TRX_CODE,10) = '3110200301' AND
                    --     LEFT(TRX_CODE,7) IN (
                    --         SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='LAUNDRY'
                    --     )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $fnb_other=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND FB_OUTLET IS NOT NULL
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='OTHER'
                        )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");


            $tel_local=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2010000'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $tel_long_distance=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2020000'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $tel_idd=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2030000'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $tel_internet=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2040000'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $cn_tel=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2990000'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $cn_internet=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP ='RTEL' AND
                        LEFT(TRX_CODE,7) = '2990001'
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");


            $transportation=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP = 'ROTH'
                        AND RIGHT(TRX_CODE,10) IN (
                        SELECT SAPCOSTCENTER
                            FROM MasterOutlet MO
                            WHERE TRUNCOUTLETNAME in ('TRANSPORTATION','GOLDEN BIRD')
                            AND $query_filter_subresort
                        )
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='TRANSPORTATION'
                        )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $business_center=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP='ROTH'
                        AND RIGHT(TRX_CODE,10) IN (
                        SELECT SAPCOSTCENTER
                            FROM MasterOutlet MO
                            WHERE TRUNCOUTLETNAME ='BUSINESS CENTE'
                            AND $query_filter_subresort
                        )
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT='BUSINES CENTER'
                        )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $other_income=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND (TC_GROUP = 'ROTH' OR TC_GROUP = 'ROTP')
                        AND RIGHT(TRX_CODE,10) NOT IN (
                        SELECT SAPCOSTCENTER
                            FROM MasterOutlet MO
                            WHERE TRUNCOUTLETNAME IN ('BUSINESS CENTE','TRANSPORTATION')
                            AND $query_filter_subresort
                        )
                        AND LEFT(TRX_CODE,7) NOT IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT = 'TRANSPORTATION' OR GROUP_REPORT= 'BUSINESS CENTER'
                        )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");

            $other_spa=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(REVENUE_TODAY) AS REVENUE_TODAY,
                SUM(REVENUE_MTD) AS REVENUE_MTD,
                SUM(REVENUE_MTD_LAST_YEAR) AS REVENUE_MTD_LAST_YEAR
                FROM(
                    SELECT
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(TRX_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(REVENUE) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TRX_DATE,RESORT,SUB_RESORT,TC_GROUP,TRX_CODE,TRX_CODE_DESCRIPTION,REVENUE
                        FROM TrialBalance
                        WHERE
                        FORMAT(TRX_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, TRX_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND $query_filter_subresort
                        AND TC_GROUP = 'ROTH'
                        AND RIGHT(TRX_CODE,10) IN (
                        SELECT SAPCOSTCENTER
                            FROM MasterOutlet MO
                            WHERE TRUNCOUTLETNAME ='SPA'
                            AND $query_filter_subresort
                        )
                        AND LEFT(TRX_CODE,7) IN (
                            SELECT TRX_CODE FROM MasterGrpTrxCode WHERE GROUP_REPORT = 'TRANSPORTATION' OR GROUP_REPORT= 'BUSINESS CENTER'
                        )
                    )dt
                    GROUP BY TRX_DATE
                )dt2
            ");


            $total_mgr=DB::connection('dbayana-stg')
            ->select("SELECT
                SUM(CAST(REVENUE_TODAY as BIGINT)) AS REVENUE_TODAY,
                SUM(CAST(REVENUE_MTD as BIGINT)) AS REVENUE_MTD,
                SUM(CAST(REVENUE_MTD_LAST_YEAR as BIGINT)) AS REVENUE_MTD_LAST_YEAR
                FROM
                (
                    SELECT
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') = FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(TOTAL_REVENUE) END AS REVENUE_TODAY,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(CAST('$date_start' as date), 'yyyy-MM-dd') AND FORMAT(CAST('$date' as date), 'yyyy-MM-dd') THEN SUM(CAST(TOTAL_REVENUE as BIGINT)) END AS REVENUE_MTD,
                    CASE WHEN FORMAT(CAST(BUSINESS_DATE as date), 'yyyy-MM-dd') BETWEEN FORMAT(DATEADD(year,-1,CAST('$date_start' as date)), 'yyyy-MM-dd') AND FORMAT(DATEADD(year,-1,CAST('$date' as date)), 'yyyy-MM-dd') THEN SUM(CAST(TOTAL_REVENUE as BIGINT)) END AS REVENUE_MTD_LAST_YEAR
                    FROM(
                        SELECT TOTAL_REVENUE, BUSINESS_DATE
                        FROM ReservationDailyStat
                        WHERE
                        FORMAT(BUSINESS_DATE,'yyyy') BETWEEN DATENAME(year,DATEADD(year,-1,CAST('$date' AS datetime))) AND  DATENAME (year,CAST('$date' AS datetime))
                        AND DATEPART(MM, BUSINESS_DATE) = FORMAT(CAST('$date' AS datetime), 'MM')
                        AND RESORT='$filter_plant' AND $query_filter_subresort
                    )dt
                    GROUP BY BUSINESS_DATE
                )dt2
            ");

            // Ingat hapus ini 
            // $filter_subresort = 'PNB1';
            // $filter_plant = 'PNB1';
            // $plant_name[0] = (object)array('SUB_RESORT_NAME'=>'Ayana Cruise');
            // Sampai sini

            $pnb_revenue = [];
            // Jika filter subresort Ayana Cruise, maka gunakan prosedur
            if(strtoupper($filter_subresort) == 'PNB1'){
            	$pnb_revenue = DB::connection('dbayana-dw')
            	->select('EXEC dbo.usp_RptDrrPnbV2 ?', array($date));
            	$pnb_revenue = collect($pnb_revenue)
            	->groupBy('RptType')->toArray();
            }

		} catch (\Illuminate\Database\QueryException $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '502';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            return $result;

        } catch(\Exception $e) {
			$result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '500';
            $result['status'] = 'failed';
            $result['message'] = 'Server Internal Error';
            return $result;
		}

		return view('pages.report.report_daily_revenue_new', [
            'plant_name'=>$plant_name[0],
            'list_plant'=>$list_plant,
            'filter_plant'=>$filter_plant,
            'list_subresort'=>$list_subresort,
            'filter_subresort'=>$filter_subresort,
            'date_to_lookup'=>$date,
			'booking_rate'=>$booking_rate,
			'room_available'=>$room_available,
			'resort_room_sold'=>$resort_room_sold,
			'occupancy'=>$occupancy,
            'revpar'=>$revpar,
            'groups_room_sold'=>$groups_room_sold,
            'outlet_revenue_food'=>$outlet_revenue_food,
            'outlet_revenue_beverage'=>$outlet_revenue_beverage,
			'retail'=>$retail,
			'recreation'=>$recreation,
            'spa'=>$spa,
            'laundry'=>$laundry,
            'fnb_other'=>$fnb_other,
            'tel_local'=>$tel_local,
            'tel_long_distance'=>$tel_long_distance,
            'tel_idd'=>$tel_idd,
            'tel_internet'=>$tel_internet,
            'cn_tel'=>$cn_tel,
            'cn_internet'=>$cn_internet,
            'transportation'=>$transportation,
            'other_income'=>$other_income,
            'other_spa'=>$other_spa,
            'outlet_revenue_food_beverage'=>$outlet_revenue_food_beverage,
            'business_center'=>$business_center,
            'total_mgr'=>$total_mgr,
            'pnb_revenue'=>$pnb_revenue
		]);
	}

	function report_cancellation_daily(Request $request){
		$date = date('Y-m-d');

		$cancellation_header_room_nite = [];
		$cancellation_summary = [];
		$cancellation_daily = [];
		try {
			$cancellation_summary = DB::connection('dbayana-dw')
			->select("SET NOCOUNT ON;EXEC dbo.usp_RptSummaryCancellation_Pivoted_GetData ?, ?", array($date,'KMS1'));
			if(count($cancellation_summary) > 0) {
				$cancellation_header_room_nite = (array)$cancellation_summary[0];
			}

			// Untuk cancellation yang dibuat tanggal terkait
			$cancellation_daily = DB::connection('dbayana-dw')
			->select("EXEC dbo.usp_RptSummaryCancellation_Detail_GetData ?, ?", array($date,'KMS1'));
		} catch(\Exception $e){}

		// return view('pages.report.report_cancellation_daily', ['date_to_lookup'=>$date, 'cancellation_summary'=>$cancellation_summary, 'cancellation_header_room_nite'=>$cancellation_header_room_nite, 'cancellation_daily'=>$cancellation_daily]);
		// Untuk export Custom excel
		$xml_version = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$rawHtml = view('pages.report.kms.cancellation.template', ['date_to_lookup'=>$date, 'cancellation_summary'=>$cancellation_summary, 'cancellation_header_room_nite'=>$cancellation_header_room_nite, 'cancellation_daily'=>$cancellation_daily])->render();
		return view('pages.report.kms.cancellation.index', ['template'=>$rawHtml, 'date_to_lookup'=>$date, 'xml_version'=>$xml_version]);
	}

	function filter_report_cancellation_daily(Request $request){
		if(self::wantsJson($request)){
			$date = date('Y-m-d');
			if($request->get('business_date')){
				try {
					$date = date('Y-m-d', strtotime($request->get('business_date')));
					if($date == date('Y-m-d', strtotime('1970-01-01')))
						$date = date('Y-m-d');
				} catch(\Exception $e){
					$date = date('Y-m-d');
				}
			}
			// Untuk cancellation berdasarkan arrival
			$cancellation_header_room_nite = [];
			$cancellation_summary = [];
			$cancellation_daily = [];
			try {
				$cancellation_summary = DB::connection('dbayana-dw')
				->select("SET NOCOUNT ON;EXEC dbo.usp_RptSummaryCancellation_Pivoted_GetData ?, ?", array($date,'KMS1'));
				if(count($cancellation_summary) > 0) {
					$cancellation_header_room_nite = (array)$cancellation_summary[0];
				}

				// Untuk cancellation yang dibuat tanggal terkait
				$cancellation_daily = DB::connection('dbayana-dw')
				->select("EXEC dbo.usp_RptSummaryCancellation_Detail_GetData ?, ?", array($date,'KMS1'));
			} catch(\Exception $e){}

			// return view('pages.report.report_cancellation_daily', ['date_to_lookup'=>$date, 'cancellation_summary'=>$cancellation_summary, 'cancellation_header_room_nite'=>$cancellation_header_room_nite, 'cancellation_daily'=>$cancellation_daily]);
			$rawHtml = view('pages.report.kms.cancellation.template', ['date_to_lookup'=>$date, 'cancellation_summary'=>$cancellation_summary, 'cancellation_header_room_nite'=>$cancellation_header_room_nite, 'cancellation_daily'=>$cancellation_daily])->render();
			$date_to_lookup = date('d-M-Y', strtotime($date));
			return ['data'=>$rawHtml, 'date_to_lookup'=>$date_to_lookup];
		}
		else {
			abort(403);
		}
	}

	function list_menu_delonix(Request $request){
		$result = DB::connection('operadbms')
        ->select('
        	SELECT *
			FROM TB_EXAMPLE_MAP_POS_LIST_FOODMENU
        ');

        $data['result']=$result;


        return view('pages.report.list_menu_delonix', ['data'=>$data]);

	}

	// function revenue_daily_fb_ayana(Request $request) {
	// 	$date = date('Y-m-d');
	// 	if($request->get('business_date')){
	// 		try {
	// 			$date = date('Y-m-d', strtotime($request->get('business_date')));
	// 			if($date == date('Y-m-d', strtotime('1970-01-01')))
	// 				$date = date('Y-m-d');
	// 		} catch(\Exception $e){
	// 			$date = date('Y-m-d');
	// 		}
	// 	}

	// 	$date_start = date('Y-m-01', strtotime($date));

	// 	$data_outlet = DB::connection('dbayana-stg')
	// 	->select("
	// 		EXEC dbo.filter_date_revenue_with_mtd ?,?
	// 	", array($date, $date_start));
	// 	return view('pages.report.revenue_daily_fb_ayana', ['outlet'=>$data_outlet, 'date_to_lookup'=>$date]);
	// }
	function filter_daily_fb_ayana(Request $request){
		if(self::wantsJson($request)){
			$date = date('Y-m-d');
			if($request->get('business_date')){
				try {
					$date = date('Y-m-d', strtotime($request->get('business_date')));
					if($date == date('Y-m-d', strtotime('1970-01-01')))
						$date = date('Y-m-d');
				} catch(\Exception $e){
					$date = date('Y-m-d');
				}
			}
			
			$date_start = date('Y-m-01', strtotime($date));
			$data_outlet = DB::connection('dbayana-stg')
			->select("
				EXEC dbo.filter_date_revenue_with_mtd ?,?
			", array($date, $date_start));
			// return view('pages.report.report_cancellation_daily', ['date_to_lookup'=>$date, 'cancellation_summary'=>$cancellation_summary, 'cancellation_header_room_nite'=>$cancellation_header_room_nite, 'cancellation_daily'=>$cancellation_daily]);
			$rawHtml = view('pages.report.simphony.template', ['date_to_lookup'=>$date, 'outlet'=>$data_outlet])->render();
			return ['data'=>$rawHtml, 'date_to_lookup'=>$date];
		}
		else {
			abort(403);
		}
	} 
	
	function revenue_daily_fb_ayana(Request $request) {
		$date = date('Y-m-d');
		if($request->get('business_date')){
			try {
				$date = date('Y-m-d', strtotime($request->get('business_date')));
				if($date == date('Y-m-d', strtotime('1970-01-01')))
					$date = date('Y-m-d');
			} catch(\Exception $e){
				$date = date('Y-m-d');
			}
		}

		$date_start = date('Y-m-01', strtotime($date));
		$data_outlet = DB::connection('dbayana-stg')
		->select("
			EXEC dbo.filter_date_revenue_with_mtd ?,?
		", array($date, $date_start));
		$rawHtml = view('pages.report.simphony.template', ['date_to_lookup'=>$date, 'outlet'=>$data_outlet])->render();
		return view('pages.report.simphony.index', ['template'=>$rawHtml, 'date_to_lookup'=>$date]);
	}

	function revenue_daily_fb_ayana_outlet(Request $request) {
		$COST_CENTER_ID = $request->get('COSTCENTERID', '');
		// $date_start = in_array('date_start', array_keys($request->get('DATE', []))) ? $request->get('DATE', [])['date_start'] : date('Y-m-d');
		// $date_end = in_array('date_end', array_keys($request->get('DATE', []))) ? $request->get('DATE', [])['date_end'] : date('Y-m-d');
		$date_start = date('Y-m-d');
		$date_end = date('Y-m-d');

		if($request->get('BUSINESSDATESTART') && $request->get('BUSINESSDATEEND')){
			try {
				$date_start = date('Y-m-d', strtotime($request->get('BUSINESSDATESTART')));
				if($date_start == date('Y-m-d', strtotime('1970-01-01')))
					$date_start = date('Y-m-d');

				$date_end = date('Y-m-d', strtotime($request->get('BUSINESSDATEEND')));
				if($date_end == date('Y-m-d', strtotime('1970-01-01')))
					$date_end = date('Y-m-d');

			} catch(\Exception $e){
				$date_end = date('Y-m-d');
				$date_start = date('Y-m-d');
			}
		}

		$date_to_lookup = array(
			'date_start'=>$date_start,
			'date_end'=>$date_end
		);

		$data_outlet = DB::connection('dbayana-stg')
		->table('MasterOutlet')
		->select('TRUNCOUTLETNAME', 'SAPCOSTCENTER')
		->where(['SAPCOSTCENTER'=>$COST_CENTER_ID])
		->get()
		->first();

		$data_transaction = DB::connection('dbayana-stg')
		->select("
			EXEC dbo.filter_revenue_outlet_new ?,?,?
		", array($COST_CENTER_ID, $date_start, $date_end));


		$data_transaction = collect($data_transaction);
		// MAP DATA CHECKNUM DAN TOTAL SEBAGAI KEY DARI TRANSAKSI
		$new_data_trans = $data_transaction->mapWithKeys(function($data, $key){
			return [$data->CHECKNUM => $data];
		});
		$data_outlet_name = isset($data_outlet->TRUNCOUTLETNAME) ? $data_outlet->TRUNCOUTLETNAME : 'Unknown Outlet';
		$data_costcenter = isset($data_outlet->SAPCOSTCENTER) ? $data_outlet->SAPCOSTCENTER : 'Unknown Outlet ID';

		return view('pages.report.revenue_daily_fb_ayana_outlet', ['data_transaction'=>$new_data_trans, 'data_outlet'=>$data_outlet_name, 'data_costcenter'=>$data_costcenter, 'data'=>$date_to_lookup]);
	}

	function revenue_daily_fb_ayana_outlet_ytd(Request $request) {
		$COST_CENTER_ID = $request->get('COSTCENTERID', '');
		// $date_start = in_array('date_start', array_keys($request->get('DATE', []))) ? $request->get('DATE', [])['date_start'] : date('Y-m-d');
		// $date_end = in_array('date_end', array_keys($request->get('DATE', []))) ? $request->get('DATE', [])['date_end'] : date('Y-m-d');
		$date_start = date('Y-m-d');
		$date_end = date('Y-m-d');

		if($request->get('BUSINESSDATESTART') && $request->get('BUSINESSDATEEND')){
			try {
				$date_start = date('Y-m-d', strtotime($request->get('BUSINESSDATESTART')));
				if($date_start == date('Y-m-d', strtotime('1970-01-01')))
					$date_start = date('Y-m-d');

				$date_end = date('Y-m-d', strtotime($request->get('BUSINESSDATEEND')));
				if($date_end == date('Y-m-d', strtotime('1970-01-01')))
					$date_end = date('Y-m-d');

			} catch(\Exception $e){
				$date_end = date('Y-m-d');
				$date_start = date('Y-m-d');
			}
		}

		$date_to_lookup = array(
			'date_start'=>$date_start,
			'date_end'=>$date_end
		);

		$data_outlet = DB::connection('dbayana-stg')
		->table('MasterOutlet')
		->select('TRUNCOUTLETNAME', 'SAPCOSTCENTER')
		->where(['SAPCOSTCENTER'=>$COST_CENTER_ID])
		->get()
		->first();

		$data_transaction = DB::connection('dbayana-stg')
		->select("
			EXEC dbo.filter_revenue_outlet_with_ytd ?,?,?
		", array($COST_CENTER_ID, $date_start, $date_end));


		$data_transaction = collect($data_transaction);
		// MAP DATA CHECKNUM DAN TOTAL SEBAGAI KEY DARI TRANSAKSI
		$new_data_trans = $data_transaction->mapWithKeys(function($data, $key){
			return [$data->CHECKNUM => $data];
		});
		$data_outlet_name = isset($data_outlet->TRUNCOUTLETNAME) ? $data_outlet->TRUNCOUTLETNAME : 'Unknown Outlet';
		$data_costcenter = isset($data_outlet->SAPCOSTCENTER) ? $data_outlet->SAPCOSTCENTER : 'Unknown Outlet ID';

		return view('pages.report.revenue_daily_fb_ayana_outlet', ['data_transaction'=>$new_data_trans, 'data_outlet'=>$data_outlet_name, 'data_costcenter'=>$data_costcenter, 'data'=>$date_to_lookup, 'is_ytd'=>true]);
	}

	function revenue_daily_fb_ayana_outlet_detail(Request $request) {
		$COST_CENTER_ID = $request->get('COSTCENTERID', '');
		$CHECKNUM = $request->get('CHECKNUM', '');
		$TS_DATE = $request->get('TRANSDATE', date('Y-m-d'));

		$data_transaction = DB::connection('dbayana-stg')
		->select("
			EXEC dbo.filter_revenue_outlet_detail ?,?,?
		", array($COST_CENTER_ID, $TS_DATE, $CHECKNUM));

		$data_transaction = collect($data_transaction);
		$new_data_trans = $data_transaction->mapWithKeys(function($data, $key){
			if(strtolower($data->TRANSACTIONTYPE) == 'tender')
				return [$data->TRANSACTIONTYPE => $data];
			else
				return [$data->TRANSACTIONTYPE."#".$key => $data];
		});

		$OUTLETNAME = isset($new_data_trans['TENDER']->TRUNCOUTLETNAME) ? $new_data_trans['TENDER']->TRUNCOUTLETNAME : "UNKNOWN OUTLET";
		$TS_DATE = isset($new_data_trans['TENDER']->CLOSEBUSINESSDATE) ? $new_data_trans['TENDER']->CLOSEBUSINESSDATE : date('Y-m-d H:i:s');
		$DAYPARTNAME = isset($new_data_trans['TENDER']->DAYPARTNAME) ? $new_data_trans['TENDER']->DAYPARTNAME : "UNKNOWN";
		$ORDERTYPE = isset($new_data_trans['TENDER']->ORDERTYPENAME) ? $new_data_trans['TENDER']->ORDERTYPENAME : "UNKNOWN";

		return view('pages.report.revenue_daily_fb_ayana_outlet_detail',
		[	'trans_date'=>$TS_DATE,
			'outletname'=>$OUTLETNAME,
			'checknum'=>$CHECKNUM,
			'data_transaction'=>$new_data_trans,
			'ordertype'=>$ORDERTYPE,
			'daypartname'=>$DAYPARTNAME
		]);
	}

	function report_sales_daily(Request $request){
		$date = date('Y-m-d');
		if($request->get('transaction_date')){
			try {
				$date = date('Y-m-d', strtotime($request->get('transaction_date')));
				if($date == date('Y-m-d', strtotime('1970-01-01')))
					$date = date('Y-m-d');
			} catch(\Exception $e){
				$date = date('Y-m-d');
			}
		}

		$data_outlet = DB::connection('dbayana-dw')
		->select("SET NOCOUNT ON; EXEC dbo.usp_RptSales_GetData ?", array($date));

		return view('pages.report.report_sales_daily', ['outlet'=>$data_outlet, 'date_to_lookup'=>$date]);
	}

	function report_sales_daily_outlet(Request $request){
		$outlet = $request->get('outlet', '');

		$date_start=date('Y-m-d');
		$date_end=date('Y-m-d');
		$date_now=date('Y-m-d');

		if($request->get('date_start') && $request->get('date_end')){
			try {
				$date_start = date('Y-m-d', strtotime($request->get('date_start')));
				if($date_start == date('Y-m-d', strtotime('1970-01-01')))
					$date_start = date('Y-m-d');

				$date_end = date('Y-m-d', strtotime($request->get('date_end')));
				if($date_end == date('Y-m-d', strtotime('1970-01-01')))
					$date_end = date('Y-m-d');

			} catch(\Exception $e){
				$date_end = date('Y-m-d');
				$date_start = date('Y-m-d');
			}
		}

		$data=array(
        	'date_start'=>$date_start,
        	'date_end' =>$date_end,
			'outlet'  =>$outlet
        );

		$data_transaction = DB::connection('dbayana-dw')
		->select("
			EXEC dbo.usp_RptSalesOutlet_GetData ?, ?
		", array($date_start, $outlet));

		//return view('pages.report.revenue_daily_fb_ayana', ['outlet'=>$data_outlet, 'data'=>$data]);
		return view('pages.report.report_sales_daily_outlet', ['data_transaction'=>$data_transaction, 'data'=>$data]);
	}

	function report_sales_daily_outlet_mtd(Request $request){
		$outlet = $request->get('outlet', '');

		$date_start=date('Y-m-d');
		$date_end=date('Y-m-d');
		$date_now=date('Y-m-d');

		if($request->get('date_start') && $request->get('date_end')){
			try {
				$date_start = date('Y-m-d', strtotime($request->get('date_start')));
				if($date_start == date('Y-m-d', strtotime('1970-01-01')))
					$date_start = date('Y-m-d');

				$date_end = date('Y-m-d', strtotime($request->get('date_end')));
				if($date_end == date('Y-m-d', strtotime('1970-01-01')))
					$date_end = date('Y-m-d');

			} catch(\Exception $e){
				$date_end = date('Y-m-d');
				$date_start = date('Y-m-d');
			}
		}

		$data=array(
        	'date_start'=>$date_start,
        	'date_end' =>$date_end,
			'outlet'  =>$outlet
        );

		$data_transaction = DB::connection('dbayana-dw')
		->select("
			EXEC dbo.usp_RptSalesOutletMTD_GetData ?, ?
		", array($date_start, $outlet));

		//return view('pages.report.revenue_daily_fb_ayana', ['outlet'=>$data_outlet, 'data'=>$data]);
		return view('pages.report.report_sales_daily_outlet', ['data_transaction'=>$data_transaction, 'data'=>$data]);
	}

	function get_sales_daily(Request $request){
		try{
            $row_fetched = array();
            $query = DB::connection('operadbms')
            ->table('dbo.TB_REPORT_SALES')
            ->select('ReceiptNumber','Tenant','EventType','TransactionDate','GrossSales','Discount','Refunds','NetSales','Gratuity','Tax','TotalCollected','PaymentMethod')
            ->orderBy('ReceiptNumber', 'ASC')
            ->skip(0)
            ->take(15)
            ->get();
            // dd($query);
            foreach($data_sales = $query as $data_sales) {
                $row_fetched[] = array(
				'RECEIPTNUMBER'=>"<a style='cursor:pointer;text-decoration:none;' href='/report/sales_daily_detail?receiptnumber=".$data_sales->ReceiptNumber."&eventtype=".$data_sales->EventType."'>".$data_sales->ReceiptNumber."</a>",
				'TENANT'=>strtoupper($data_sales->Tenant),
				'EVENTTYPE'=>strtoupper($data_sales->EventType),
				'TRANSACTIONDATE'=>date('d M Y', strtotime($data_sales->TransactionDate)),
				'GROSSSALES'=>$data_sales->GrossSales,
				'DISCOUNT'=>$data_sales->Discount,
				'REFUNDS'=>$data_sales->Refunds,
				'NETSALES'=>$data_sales->NetSales,
				'GRATUITY'=>$data_sales->Gratuity,
				'TAX'=>$data_sales->Tax,
				'TOTALCOLLECTED'=>$data_sales->TotalCollected,
				'PAYMENTMETHOD'=>$data_sales->PaymentMethod
				);

            }

            return DataTables::of($row_fetched)
            ->rawColumns(['RECEIPTNUMBER'])
            ->make(true);

        } catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
	}

	function report_sales_daily_detail(Request $request){

		try{
			$ReceiptNumber = $request->get('ReceiptNumber', '');
			$TransactionDate = $request->get('TransactionDate', '');
			$EventType = $request->get('EventType', '');

			$data_transaction = DB::connection('dbayana-dw')
			->select("
				EXEC dbo.usp_RptSalesOutletDetail_GetData ?,?,?
			", array($ReceiptNumber, $TransactionDate, $EventType));


			$ReceiptNumber = isset($data_transaction[0]->ReceiptNumber) ? $data_transaction[0]->ReceiptNumber : "UNKNOWN";
			$TransactionDate = isset($data_transaction[0]->TransactionDate) ? $data_transaction[0]->TransactionDate : "UNKNOWN";
			$SalesType = isset($data_transaction[0]->SalesType) ? $data_transaction[0]->SalesType : "UNKNOWN";
			$Tenant = isset($data_transaction[0]->Tenant) ? $data_transaction[0]->Tenant : "UNKNOWN";

			return view('pages.report.report_sales_daily_detail',
			[
				'ReceiptNumber'=>$ReceiptNumber,
				'TransactionDate'=>$TransactionDate,
				'SalesType'=>$SalesType,
				'Tenant'=>$Tenant,
				'data_transaction'=>$data_transaction
			]);

        } catch(\Exception $e){
            echo $e->getMessage();
        }
	}

    function ajax_getSubResort(Request $request){
        $plant = $request->input('plant');
        try{

            if($plant=="KMS1"){
                $data=DB::connection('dbayana-stg')
            ->select("SELECT SUB_RESORT, SUB_RESORT_NAME FROM dbo.MasterSubResort WHERE RESORT = '".$plant."' AND SUB_RESORT <> 'AYZR' AND SUB_RESORT <> 'AYVL' AND SUB_RESORT <> 'KMS1' ");

            $array_manual=array(
                'SUB_RESORT'=>"AYANARIMBA",
                'SUB_RESORT_NAME'=>"AYANA & RIMBA BALI"
            );

            $array_manual = json_decode(json_encode($array_manual), FALSE);
            array_unshift($data,$array_manual);

            }else{
                $data=DB::connection('dbayana-stg')
                ->select("SELECT SUB_RESORT, SUB_RESORT_NAME FROM dbo.MasterSubResort WHERE RESORT = '".$plant."'");
            }

            $success=true;
            $code = 200;
            $msg = 'Query success';
        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }
        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'data'=>$data, 200));
    }

    function report_menu_total(Request $request){
    	$filtered = [];
    	$plant = NULL;
    	$date_start = date('Y-m-01');
    	$date_end = date('Y-m-d');
    	$data_menu = [];

    	if($request->get('plant') && $request->get('daterange')){
			$plant = $request->get('plant');
			$date = explode('-', $request->get('daterange'));
			$date_start = isset($date[0]) ? date('Y-m-d', strtotime(trim($date[0]))) : date('Y-m-01');
			$date_end = isset($date[1]) ? date('Y-m-d', strtotime(trim($date[1]))) : date('Y-m-d');
			$filtered = [
				'date_start'=>date('m/d/Y', strtotime($date_start)),
				'date_end'=>date('m/d/Y', strtotime($date_end)),
				'plant'=>$plant
			];
		}

		if($request->ajax()){
			$limit = (string)$request->get('length') ? $request->get('length') : 0;
            $start = (string)$request->get('start') ? $request->get('start') : 0;

            $additional_query = '';
            
           	if($request->get('CONSIGNMENT_FLAG') && $request->get('REVCTRSUBRESORT')){
           		$subresort = $request->get('REVCTRSUBRESORT');
            	$consignment_type = $request->get('CONSIGNMENT_FLAG');
            	$additional_query .= "WHERE REVCTRSUBRESORT = '$subresort' AND CONSIGNMENT_FLAG = '$consignment_type'"; 
            }
            else if($request->get('REVCTRSUBRESORT')){
            	$subresort = $request->get('REVCTRSUBRESORT');
            	$additional_query .= "WHERE REVCTRSUBRESORT = '$subresort'"; 
            }
            else if($request->get('CONSIGNMENT_FLAG')){
            	$consignment_type = $request->get('CONSIGNMENT_FLAG');
            	$additional_query .= "WHERE CONSIGNMENT_FLAG = '$consignment_type'"; 
            }

			if($additional_query){
				$additional_query = stripslashes($additional_query);
				$data_menu_total = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE"
				);

				$data_menu_total_filtered = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData ".$additional_query." 
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE"
				);

				$data_menu = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData ".$additional_query." 
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE
				    WHERE ROW_NUM >= (CASE WHEN ISNULL(CASE $start
						    WHEN 0 THEN NULL
						    ELSE $start
							END, '') = '' THEN 0 ELSE $start END)
				    AND ROW_NUM <= (CASE WHEN ISNULL(CASE $limit
						    WHEN 0 THEN NULL
						    ELSE $limit
							END, '') = '' THEN 1000000000000 ELSE $start + $limit END)"
				);
			} else {
				$data_menu_total = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE"
				);

				$data_menu_total_filtered = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE"
				);

				$data_menu = DB::connection('dbayana-dw')
				->select(
					// EXEC dbo.usp_RptMenuTotalByRevenueCenter ?,?,?,?,?
					"WITH SourceData AS (
				        SELECT FORMAT(BUSINESSDATE, 'yyyy-MM-dd') AS BUSINESSDATE, RESORT AS REVCTRRESORT, SUBRESORT AS REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, SALESCOUNT, ISNULL(CONSIGNMENT_FLAG, '-') AS CONSIGNMENT_FLAG
				        FROM AyanaStg.dbo.MenuDailyTotal
				        WHERE BUSINESSDATE BETWEEN '$date_start' AND '$date_end'
				        AND RESORT = (CASE WHEN ISNULL(CASE '$plant'
						    WHEN '' THEN NULL
						    ELSE '$plant'
							END 
						, '') = '' THEN RESORT ELSE '$plant' END)
				    ),
				    SumMenu AS (
				        SELECT BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME
				        ,SAPMATERIALCODE, MENUITEMNAME, CAST(SUM(SALESCOUNT) AS INT) AS SALESCOUNT, CONSIGNMENT_FLAG  
				        FROM SourceData
				        GROUP BY BUSINESSDATE, REVCTRRESORT, REVCTRSUBRESORT, REVENUECENTERNAME, SAPMATERIALCODE,
				        MENUITEMNAME, CONSIGNMENT_FLAG
				    )
				    SELECT * FROM (
				        SELECT ROW_NUMBER() OVER (
				            ORDER BY REVCTRRESORT
				        ) ROW_NUM ,* FROM SumMenu
				    ) DATASOURCE
				    WHERE ROW_NUM >= (CASE WHEN ISNULL(CASE $start
						    WHEN 0 THEN NULL
						    ELSE $start
							END, '') = '' THEN 0 ELSE $start END)
				    AND ROW_NUM <= (CASE WHEN ISNULL(CASE $limit
						    WHEN 0 THEN NULL
						    ELSE $limit
							END, '') = '' THEN 1000000000000 ELSE $start + $limit END)"
				);
			}
			// dd($data_menu_total, $data_menu_total_filtered, $data_menu);

			$recordsTotal = count($data_menu_total);
			$recordsFiltered = count($data_menu_total_filtered);
			$result = $data_menu;

			return response()->json([
	            "draw" => intval($request->get('draw')),  
	            "recordsTotal" => intval($recordsTotal),  
	            "recordsFiltered" => intval($recordsFiltered),
	            "data" => $result,
	            "data_all"=>$data_menu_total
	        ]);
		}

		$plant_list = DB::connection('dbintranet')
		->table('dbo.INT_BUSINESS_PLANT')
		->where('PLANT_TYPE', 'HOTEL')
		->select('SAP_PLANT_ID')->get()
		->pluck('SAP_PLANT_ID')->toArray();
		// dd($data_menu);
		// $rawHtml = view('pages.report.simphony.template', ['date_to_lookup'=>$date, 'outlet'=>$data_outlet])->render();
		// return view('pages.report.simphony.index', ['template'=>$rawHtml, 'date_to_lookup'=>$date]);
		return view('pages.report.menu_total.index', 
		[
			'data_menu'=>$data_menu, 
			'data_plant'=>$plant_list, 
			'filtered'=>$filtered, 
			'date_start'=>$date_start,
			'date_end'=>$date_end
		]);
		
    }

}
