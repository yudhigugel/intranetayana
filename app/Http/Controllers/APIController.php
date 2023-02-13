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

class APIController extends Controller{
    public function menu_rockbar(Request $request){

        $list_resort=DB::connection('operadbms')->select('
        SELECT * FROM TB_EXAMPLE_ROCKBAR_MENU
        ');

        $result=$list_resort;
        // echo json_encode($result);
        return response($result, 200);

    }


    public function menu_rockbar_section($section,Request $request){
        switch($section){
            case 'weekly1':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='weekly_special'
                ORDER BY A.SORT
                OFFSET 0 ROWS
                FETCH NEXT 2 ROWS ONLY
                ");
            break;
            case 'weekly2':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='weekly_special'
                ORDER BY A.SORT
                OFFSET 2 ROWS
                FETCH NEXT 2 ROWS ONLY
                ");
            break;
            case 'sweets_iceCream':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='sweets_iceCream'
                ORDER BY A.SORT
                ");
            break;
            case 'sweets_sorbet':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='sweets_sorbet'
                ORDER BY A.SORT
                ");
            break;
            case 'sweets_pisangGoreng':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='sweets_pisangGoreng'
                ORDER BY A.SORT
                ");
            break;
            case 'juices':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='juices'
                ORDER BY A.SORT
                ");
            break;
            case 'mains':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='mains'
                ORDER BY A.SORT
                ");
            break;
            case 'kebab_perPortion':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='kebab_perPortion'
                ORDER BY A.SORT
                ");
            break;
            case 'kebab_forTwo':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='kebab_forTwo'
                ORDER BY A.SORT
                ");
            break;
            case 'small_bites':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='small_bites'
                ORDER BY A.SORT
                ");
            break;
            case 'starters':
                $sql=DB::connection('operadbms')->select("
                SELECT * FROM
                TB_WEB_MENU_ROCKBAR A INNER JOIN TB_POS_MASTER_MENU_ITEM_PRICE B ON A.SAP_MATERIAL_CODE = B.SAP_MATERIAL_CODE
                WHERE A.CATEGORY ='starters'
                ORDER BY A.SORT
                ");
            break;

            default:
                $sql=NULL;
            break;
        }

        if(!empty($sql)){
            $data['message']="success";
            $data['data']=$sql;
            $statuscode=200;
        }else{
            $data['message']="error";
            $data['data']=$sql;
            $statuscode=400;
        }

        $result=json_encode($data);

        return response($result, $statuscode);

    }

    public function menu_liuli(Request $request){


    }

    public function menu_liuli_category($category){
        // $category=$request->get('category');

        $data=DB::connection('operadbms')
                ->select("SELECT * FROM TB_EXAMPLE_LIULI_MENU_PARSING a INNER JOIN TB_EXAMPLE_LIULI_MENU b ON a.SKU = b.SKU
                INNER JOIN TB_EXAMPLE_LIULI_MENU_PARSING_CATEGORY c ON a.CATEGORY_SLUG = c.SLUG WHERE a.CATEGORY_SLUG = '".$category."'
                ");
        return response($data,200);


    }





}




