<?php
namespace App\Http\Controllers\Traits;
use App\Models\HumanResource\CustomAccess;
use App\Models\HumanResource\MasterMenu as DataMenu;

trait IntranetTrait {
	public static function customPlantAccess() {
		$data_to_return = [];
		$now_path = "/".\Request::path();
        $employee_id= \Request::session()->get('user_id');
        $check_menu = DataMenu::where('PATH', $now_path)->get()->first();
        $menu_id = isset($check_menu->SEQ_ID) ? $check_menu->SEQ_ID : 0;

		$custom_plant = CustomAccess::where(['EMPLOYEE_ID'=>$employee_id, 'MENU_ID'=>$menu_id])->get()
		->pluck('PLANT_ID', 'PLANT_ID')->filter()->values()->all();
		return $custom_plant;
	}

    public static function wantsJson($request) {
    	$acceptable = $request->getAcceptableContentTypes();
    	return isset($acceptable[0]) && $acceptable[0] == 'application/json';
	}

	public static function isOwningCompany($plantCode) {
		$length = strlen($plantCode);
	    if( !$length ) {
	        return false;
	    }
	    return strpos($plantCode, '0') !== false;
	}

	public static function startsWith($string, $startString) {
		return preg_match('#^' . $startString . '#', $string) === 1; 
	}

	public static function unit_measurement(){
		return ['PCS', 'EA'];
	}

	public static function generalUnitMeasurement(){
		return array(
			[
				'INDEX'=>1,
				'IN_UOM'=>'ML',
				'EX_UOM'=>'ML',
				'UOM_DESC'=>'Milliliter'
			],
			[
				'INDEX'=>2,
				'IN_UOM'=>'BT',
				'EX_UOM'=>'BT',
				'UOM_DESC'=>'Bottle'
			],
			[
				'INDEX'=>3,
				'IN_UOM'=>'G',
				'EX_UOM'=>'G',
				'UOM_DESC'=>'Gram'
			],
			[
				'INDEX'=>4,
				'IN_UOM'=>'PAC',
				'EX_UOM'=>'PAC',
				'UOM_DESC'=>'Pack'
			],
			[
				'INDEX'=>5,
				'IN_UOM'=>'PC',
				'EX_UOM'=>'PC',
				'UOM_DESC'=>'Piece'
			],
			[
				'INDEX'=>6,
				'IN_UOM'=>'KG',
				'EX_UOM'=>'KG',
				'UOM_DESC'=>'Kilogram'
			],
			[
				'INDEX'=>7,
				'IN_UOM'=>'EA',
				'EX_UOM'=>'EA',
				'UOM_DESC'=>'Each'
			],
			[
				'INDEX'=>8,
				'IN_UOM'=>'L',
				'EX_UOM'=>'L',
				'UOM_DESC'=>'Liter'
			],
			[
				'INDEX'=>9,
				'IN_UOM'=>'CAN',
				'EX_UOM'=>'CAN',
				'UOM_DESC'=>'Canister'
			]

		);
	}
}