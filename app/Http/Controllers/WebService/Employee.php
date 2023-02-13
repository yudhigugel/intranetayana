<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class Employee extends Controller
{
    protected $api_key;
    protected $api_secret;

    public function __construct()
    {
        $this->api_key = '';
        $this->api_secret = '';
        $this->path = '';
    }

    public function initRequest($request, $path, $date, $header){
        $client = new \GuzzleHttp\Client();
        // $header = ['headers' => ['Authorization' => $this->hmacHandler($request, parse_url($path)), 'Date'=>$date]];
        $res = $client->get($path, $header);
        $data_employee = json_decode($res->getBody()->getContents(), true);
        return $data_employee;
    }

    public function insertData($data_insert){
        // DB::connection('dbintranet')->beginTransaction();
        // try {
        //     $data = DB::connection('dbintranet')
        //     ->table('INT_EMPLOYEE_TALENTA')
        //     ->insert($data_insert);

        //     DB::commit();
        //     return true;
        // } catch (\Exception $e) {
        //     dd($e->getMessage);
        //     DB::rollback();
        //     return false;
        //     // something went wrong
        // }
        try {
            DB::connection('dbintranet')->transaction(function() use ($data_insert) {
                $data = DB::connection('dbintranet')
                ->table('INT_EMPLOYEE_TALENTA')
                ->insert($data_insert);
            }); 
            return true;
        } catch(\Exception $e){
            Log::error('TRANSACTION FAILED |'.$e->getMessage());
            return false;
        }
        
    }

    protected function hmacHandler($request, $api_path) {
        $key = $this->api_key;
        $req_method = 'GET';
        if(isset($api_path['query']) && $api_path['query'])
        	$api_path['path'] = $api_path['path'].'?'.$api_path['query'];

        $path_query = 'GET'. ' ' .$api_path['path']. ' HTTP/1.1';
        date_default_timezone_set('UTC');
        $date_string = (String)date('D, d M Y H:i:s')." GMT";
        $signedValue = hash_hmac('sha256', implode("\n", ['date: '.$date_string, $path_query]), $this->api_secret, true);
        $signature = base64_encode($signedValue);

        return sprintf('hmac username="%s", algorithm="hmac-sha256", headers="date request-line", signature="%s"', $this->api_key, $signature);
    }

    public function index(Request $request) {
        $default_path = '';
        // if($request->post('api_key'))
        //     $this->api_key = $request->post('api_key');
        // if($request->post('api_secret'))
        //     $this->api_secret = $request->post('api_secret');
        // $this->api_key = config('intranet.TALENTA.key');
        // $this->api_secret = config('intranet.TALENTA.secret');
        $company_code = ['3589', '3522', '1270', '3574', '3524', '3590'];
        if($request->get('company_code')){
            $company_code = [$request->get('company_code')];
            $this->api_key = config('intranet.TALENTA.'.$request->get('company_code').'.key');
            $this->api_secret = config('intranet.TALENTA.'.$request->get('company_code').'.secret');
        } else {
            return response()->json(['status'=>'failed', 'msg'=>'Please provide a company code'], 400);
        }
        
        $data = $this->getData($request, $company_code);
        return $data;
    }

    public function getData($request, $company_code=[]) { 
        // $data = [];
        // API EMPLOYEE BASE URI
        // $company_code = ['3524'];
        if(count($company_code) <= 0)
            return response()->json(['status'=>'no_data', 'msg'=>'No Data to insert, please try again']);

        for($i=0;$i<count($company_code);$i++){
            echo "COMPANY ".$company_code[$i]."<br>";
            # Deprecated URL V1
            // $this->path = 'https://data-api.talenta.co/v1/employee/all/'.$company_code[$i];
            $this->path = 'https://data-api.talenta.co/v2/employee';
            date_default_timezone_set('UTC');
            $date_string = (String)date('D, d M Y H:i:s')." GMT";
            $header = ['headers' => ['Authorization' => $this->hmacHandler($request, parse_url($this->path)), 'Date'=>$date_string]];
            $init_data = $this->initRequest($request, $this->path, $date_string, $header);

            if($init_data){
                DB::connection('dbintranet')
                ->table('INT_EMPLOYEE_TALENTA')
                ->whereIn('COMPANY_ID', $company_code)
                ->delete();

                // try{
                //     DB::connection('dbintranet')
                //     ->select("DBCC CHECKIDENT('dbo.INT_EMPLOYEE_TALENTA', RESEED, 0)");
                // } catch(\Exception $e){}

                $page_length = isset($init_data['data']['pagination']['last_page']) ? (int)$init_data['data']['pagination']['last_page'] : 0;
                for($start=0;$start<$page_length;$start++){
                    $page = (int)$start + 1;
                    $per_page = 10;
                    # Deprecated url V1
                    // $path = 'https://data-api.talenta.co/v1/employee/all/'.$company_code[$i].'?page='.$page.'&per-page='.$per_page;
                    $path = 'https://data-api.talenta.co/v2/employee?page='.$page.'&per-page='.$per_page;
                    // Set header
                    date_default_timezone_set('UTC');
                    $date_string = (String)date('D, d M Y H:i:s')." GMT";
                    $header = ['headers' => ['Authorization' => $this->hmacHandler($request, parse_url($path)), 'Date'=>$date_string], 'delay'=>1000];

                    $data_employee = $this->initRequest($request, $path, $date_string, $header);
                    if($data_employee){
                        $cek_data_employee = isset($data_employee['data']['employees']) ? $data_employee['data']['employees'] : [];
                        $data_insert = collect($cek_data_employee)->map(function($item, $key){
                            $employee['USER_ID'] = isset($item['user_id']) ? $item['user_id'] : null;
                            $employee['EMPLOYEE_ID'] = isset($item['employment']['employee_id']) ? $item['employment']['employee_id'] : null;
                            $employee['ORGANIZATION_ID'] = isset($item['employment']['organization_id']) ? $item['employment']['organization_id'] : null;
                            $employee['ORGANIZATION_NAME'] = isset($item['employment']['organization_name']) ? $item['employment']['organization_name'] : null;
                            $employee['JOB_POSITION'] = isset($item['employment']['job_position']) ? $item['employment']['job_position'] : null;
                            $employee['JOB_LEVEL'] = isset($item['employment']['job_level']) ? $item['employment']['job_level'] : null;
                            $employee['EMPLOYMENT_STATUS'] = isset($item['employment']['employment_status']) ? $item['employment']['employment_status'] : null;
                            $employee['COMPANY_ID'] = isset($item['employment']['company_id']) ? $item['employment']['company_id'] : null;
                            $employee['BRANCH_ID'] = isset($item['employment']['branch_id']) ? $item['employment']['branch_id'] : null;
                            $employee['BRANCH_NAME'] = isset($item['employment']['branch']) ? $item['employment']['branch'] : null;
                            $employee['JOIN_DATE'] = isset($item['employment']['join_date']) ? !empty($item['employment']['join_date']) ? date('Y-m-d',strtotime($item['employment']['join_date'])) : null : null;
                            $employee['LENGTH_OF_SERVICE'] = isset($item['employment']['length_of_service']) ? $item['employment']['length_of_service'] : null;
                            $employee['GRADE'] = isset($item['employment']['grade']) ? $item['employment']['grade'] : null;
                            $employee['CLASS'] = isset($item['employment']['class']) ? $item['employment']['class'] : null;
                            $employee['APPROVAL_LINE'] = isset($item['employment']['approval_line']) ? $item['employment']['approval_line'] : null;
                            $employee['STATUS'] = isset($item['employment']['status']) ? $item['employment']['status'] : null;
                            $employee['RESIGN_DATE'] = isset($item['employment']['resign_date']) ? !empty($item['employment']['resign_date']) ? date('Y-m-d', strtotime($item['employment']['resign_date'])) : null : null;
                            $employee['COST_CENTER'] = null;
                            $employee['SAP_COST_CENTER_ID'] = isset($item['payroll_info']['cost_center_name']) ? $item['payroll_info']['cost_center_name'] : null;
                            $employee['WORK_EMAIL'] = null;
                            $employee['MIDJOB_TITLE_ID'] = null;

                            $is_custom_field = isset($item['custom_field']) ? true : false;
                            if($is_custom_field){
                                $custom_field = collect($item['custom_field'])->map(function($item, $key) use (&$employee){
                                    $field_name = isset($item['field_name']) ? $item['field_name'] : '';
                                    if($field_name){
                                        if(preg_match('/^Cost Center$/i', $field_name)){
                                            $employee['COST_CENTER'] = isset($item['value']) ? trim((String)$item['value']) : null;
                                        }
                                        // else if(preg_match('/^Sap Cost Center$/i', $field_name)){
                                        //     $employee['SAP_COST_CENTER_ID'] = isset($item['value']) ? trim((String)$item['value']) : null;
                                        // }
                                        else if(preg_match('/^Mid Job Title$/i', $field_name)){
                                            $employee['MIDJOB_TITLE_ID'] = isset($item['value']) ? substr(trim((String)$item['value']), 0, 9) : null;
                                        }
                                        else if(preg_match('/^Work Email$/i', $field_name)){
                                            $employee['WORK_EMAIL'] = isset($item['value']) ? trim((String)$item['value']) : null;
                                        }
                                    }

                                });
                            }

                            $employee['EMERGENCY_CONTACT'] = isset($item['family']['emergency_contacts']['phone_number']) ? $item['family']['emergency_contacts']['phone_number'] : null;
                            $employee['FIRST_NAME'] = isset($item['personal']['first_name']) ? $item['personal']['first_name'] : null;
                            $employee['LAST_NAME'] = isset($item['personal']['last_name']) ? $item['personal']['last_name'] : null;
                            $employee['EMAIL'] = isset($item['personal']['email']) ? $item['personal']['email'] : null;
                            $employee['AVATAR'] = isset($item['personal']['avatar']) ? $item['personal']['avatar'] : null;
                            $employee['IDENTITY_TYPE'] = isset($item['personal']['identity_type']) ? $item['personal']['identity_type'] : null;
                            $employee['IDENTITY_NUMBER'] = isset($item['personal']['identity_number']) ? $item['personal']['identity_number'] : null;
                            $employee['EXPIRED_DATE_IDENTITY'] = isset($item['personal']['expired_date_identity']) ? !empty($item['personal']['expired_date_identity']) ? date('Y-m-d', strtotime($item['personal']['expired_date_identity'])): null : null;
                            $employee['POSTAL_CODE'] = isset($item['personal']['postal_code']) ? $item['personal']['postal_code'] : null;
                            $employee['ADDRESS'] = isset($item['personal']['address']) ? $item['personal']['address'] : null;
                            $employee['CURRENT_ADDRESS'] = isset($item['personal']['current_address']) ? $item['personal']['current_address'] : null;
                            $employee['BIRTH_PLACE'] = isset($item['personal']['birth_place']) ? $item['personal']['birth_place'] : null;
                            $employee['BIRTH_DATE'] = isset($item['personal']['birth_date']) ? !empty($item['personal']['birth_date']) ? date('Y-m-d', strtotime($item['personal']['birth_date'])) : null : null;
                            $employee['PHONE'] = isset($item['personal']['phone']) ? $item['personal']['phone'] : null;
                            $employee['MOBILE_PHONE'] = isset($item['personal']['mobile_phone']) ? $item['personal']['mobile_phone'] : null;
                            $employee['GENDER'] = isset($item['personal']['gender']) ? $item['personal']['gender'] : null;
                            $employee['MARITAL_STATUS'] = isset($item['personal']['marital_status']) ? $item['personal']['marital_status'] : null;
                            $employee['BLOOD_TYPE'] = isset($item['personal']['blood_type']) ? $item['personal']['blood_type'] : null;
                            $employee['RELIGION'] = isset($item['personal']['religion']) ? $item['personal']['religion'] : null;
                            date_default_timezone_set('Asia/Jakarta');
                            $employee['LAST_UPDATE'] = date('Y-m-d H:i:s');

                            return $employee;
                        })->toArray();

                        try {
                            $berhasil = $this->insertData($data_insert);
                            if(!$berhasil){
                                break;
                            }
                        } catch(\Exception $e){
                            break;
                            echo "Gagal insert Data | ".$e->getMessage();
                        }
                    }
                }
                // End For loop data karyawan
            echo "Berhasil input data";
            }
        }
    }
}
