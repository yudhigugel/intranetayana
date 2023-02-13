<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;

class ContractController extends Controller{
  public function index(Request $request){



  	$list_resort=DB::connection('operadbms')->select('SELECT RESORT FROM TB_EXAMPLE_MAP_RATE_HEADER GROUP BY RESORT');
    $list_package=DB::connection('operadbms')->select('SELECT RATE_CODE, DESCRIPTION FROM TB_EXAMPLE_MAP_RATE_HEADER GROUP BY RATE_CODE, DESCRIPTION');

    $data=array(
      'list_resort'=>$list_resort,
      'list_package'=>$list_package
    );

    return view('pages.generate_contract', ['data' =>$data]);
  }

  public function ajax_resort(Request $request){

  	$resort=$request->input('resort',NULL);
  	//======
  	// pecah array menjadi string per resort query
  	$resort_sql='';
  	foreach($resort as $r){
  		$resort_sql.=",'".$r."'";
  	}
  	$resort_sql=substr($resort_sql,1);
  	//======

  	$sql=DB::connection('operadbms')->select('SELECT * FROM TB_EXAMPLE_MAP_RATE_HEADER WHERE RESORT IN ('.$resort_sql.')');

  	$result="";
  	foreach($sql as $row){
  		$result.="<option value='".$row->RATE_CODE."'>".$row->RESORT." - ".$row->DESCRIPTION."</option>";
  	}

  	return $result;

  }

  public function process(Request $request){

  	$post=array();


  	$package=$request->input('package');
  	$package=explode(',', $package);

  	//======
  	// pecah array menjadi string per package query
  	$package_sql='';
  	foreach($package as $p){
  		$package_sql.=",'".$p."'";
  	}
  	$package_sql=substr($package_sql,1);
  	//======

  	//start cari room rate
  	$contract_date_start=$request->input('contract_date_start');
  	$contract_date_end=$request->input('contract_date_end');
  	$currency='IDR';

  	// $q_room_rate="
  	// 			SELECT
  	// 			rd.SHORT_DESCRIPTION, rd.AMOUNT_1, rd.AMOUNT_2
  	// 			FROM TB_EXAMPLE_MAP_RATE_DETAIL rd
  	// 			INNER JOIN TB_EXAMPLE_MAP_RATE_HEADER rh ON rd.RATE_CODE = rh.RATE_CODE
			// 	WHERE (
			// 	    FORMAT(rd.BEGIN_DATE,'yyyy-MM-dd') >= FORMAT(CAST ('".$contract_date_start."' AS DATETIME),'yyyy-MM-dd')
			// 	    OR
			// 	    (
			// 	        FORMAT(rd.BEGIN_DATE,'yyyy-MM-dd') < FORMAT(CAST ('".$contract_date_start."' AS DATETIME),'yyyy-MM-dd')
			// 	        AND
			// 	        FORMAT(rd.END_DATE,'yyyy-MM-dd') <= FORMAT(CAST ('".$contract_date_end."' AS DATETIME),'yyyy-MM-dd')
			// 	    )
			// 	)
			// 	AND (
			// 	    FORMAT(rd.END_DATE,'yyyy-MM-dd') <= FORMAT(CAST ('".$contract_date_end."' AS DATETIME),'yyyy-MM-dd')
			// 	    OR
			// 	    (
			// 	        FORMAT(rd.BEGIN_DATE,'yyyy-MM-dd') > FORMAT(CAST ('".$contract_date_start."' AS DATETIME),'yyyy-MM-dd')
			// 	        AND
			// 	        FORMAT(rd.END_DATE,'yyyy-MM-dd') >= FORMAT(CAST ('".$contract_date_end."' AS DATETIME),'yyyy-MM-dd')
			// 	    )
			// 	)

			// 	AND rd.RATE_CODE IN(".$package_sql.")
			// 	AND rh.CURRENCY_CODE ='".$currency."'

			// 	";

    $q_room_rate="
          SELECT
           rd.RESORT, rd.SHORT_DESCRIPTION, rd.AMOUNT_1, rd.AMOUNT_2
          FROM TB_EXAMPLE_MAP_RATE_DETAIL rd
          INNER JOIN TB_EXAMPLE_MAP_RATE_HEADER rh ON rd.RATE_CODE = rh.RATE_CODE
          WHERE rd.RATE_CODE IN(".$package_sql.")
          AND rh.CURRENCY_CODE ='".$currency."'

    ";



  	$room_rate=DB::connection('operadbms')->select($q_room_rate);
  	// $room_rate=(array)$room_rate;
    $room_rate=json_decode(json_encode($room_rate), true);
    $room_rate_sorted=array();
    foreach($room_rate as $rr){
      $room_rate_sorted[$rr['RESORT']][]=$rr;
    }

    $fixed_roomrate=array();
    foreach($room_rate_sorted as $rrs => $value){

      //loop isinya
      $child=array();
      foreach($value as $val){
        $val['AMOUNT_1']=number_format((int)$val['AMOUNT_1']);
        $val['AMOUNT_2']=number_format((int)$val['AMOUNT_2']);
        $child[]=$val;
      }

      $masuk=array(
        'RESORT'=>$rrs,
        'LIST'=>$child
      );
      $fixed_roomrate[]=$masuk;
    }

    $post['country_market']="Japan";

  	$post['contract_year']=$request->input('contract_year');
  	$post['contract_title']=$request->input('contract_title');
  	$post['contract_number']=$request->input('contract_number');
  	$post['booking_code']=$request->input('booking_code');
  	$post['contract_date_start']=$contract_date_start;
  	$post['contract_date_end']=$contract_date_end;

  	//miscellaneous
  	$post['room_minimum']=$request->input('room_minimum');
  	$post['festive_season_terms']=$request->input('festive_season_terms');
  	$post['rate_particulars']=$request->input('rate_particulars');


  	$post['travelagent_name']=$request->input('agent_name');
  	$post['travelagent_contactName']=$request->input('agent_contact_name');
  	$post['travelagent_contactTitle']=$request->input('agent_contact_title');
  	$post['travelagent_email']=$request->input('agent_contact_email');

  	$post['resort_name']="AYANA Resort and Spa, Bali";
  	$post['resort_contactName']="Noriko Takano";
  	$post['resort_contactTitle']="Senior Sales Manager";
  	$post['resort_email']="apriadi_kurniawan@biznetnetworks.com";


  	$post['sales_contactName']="Riri Nurdianto Syam";
  	$post['sales_contactTitle']="Sales Compliance";
  	$post['sales_email']="mahendra_permana@biznetnetworks.com";

  	$post['eam_contactName']="Michi Sonoda";
  	$post['eam_contactTitle']="Executive Assistant Manager Sales and Marketing";
  	$post['eam_email']="roy_putra@biznetnetworks.com";

    $post['room_rate']=$fixed_roomrate;
  	// $post=json_encode($post);


  	// $response = Http::post('https://www.webmerge.me/merge/715427/umz6j6', $post);
    $response = Http::post('https://www.webmerge.me/merge/742811/89w9wz', $post);
    if($response){
        return response()->json(array('success' => true, 'msg' => 'sukses', 'code' => 200, 200));
    }else{
		return response()->json(array('success' => false, 'msg' => $response, 'code' => 400, 400));
	}


  }


}




