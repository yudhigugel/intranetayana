<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use App\Models\HumanResource\RoleMenu as DataRoleMenu;
use App\Models\HumanResource\UserPermission as UserHasPermission;
use App\Models\HumanResource\MasterMenu as DataMenu;
use App\Models\HumanResource\MenuAccessRole as DataAccessRole;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    private $menu = [];
    private $url = [];
    private $role = [];
    private $permission = [];
    private $notification = [];
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        if(config('app.env') === 'production' || config('app.env') === 'staging') {
            \URL::forceScheme('https');
        }

         /**
         * User Login Detail
         */
        $this->app->singleton('user_login', function () {
            $user_login = Session::get('user_data');
            $get_all_menu_and_permission = $this->app->get('get_all_menu');
            if ($user_login) {
                if(!Session::has('access_menu'))
                    Session::put('access_menu', $this->url);
                else {
                    $menu_db = count($this->url);
                    $menu_session = count(Session::get('access_menu'));
                    // dd($this->role, $this->url, $this->menu);
                    // $this->url = $data_url;
                    // $this->menu = $data_menu;
                    // $this->permission
                    
                    /**
                     * If function below activated
                     * Every new menu added will be available
                     * as soon as page refresh (might be causing a performance hit)
                     * Suggest to re-login
                     */
                    // if($menu_db != $menu_session)
                    //     Session::put('access_menu', $this->app->get('get_all_menu'));
                }

                if(!Session::has('permission_menu'))
                    Session::put('permission_menu', $this->permission);
                return $user_login;
            }
            return [];
        });

        $this->app->singleton('reset_session_credential',function(){
            if(!empty(Session::get('user_data'))){
                $userModel= new UserModel();
                $id=Session::get('user_id');
                $data=EmployeeModel::where('EMPLOYEE_ID',$id)->get(); //dapetin emailnya
                $email_user=$data[0]->EMAIL;
                $result=$userModel->getUserByEmail($email_user); //dapetin recordnya

                Session::put('user_data', $result[0]);
                Session::put('user_id', $result[0]->EMPLOYEE_ID, config('intranet.session_exp'));
                // Session::put('company_code', $company_code, config('intranet.session_exp'));
                // Session::put('job_title', $job_title, config('intranet.session_exp'));
            }
            return[];
        });

        $this->app->singleton('get_permission', function(){
            $user_login = Session::get('user_data');
            if ($user_login) {
                $is_superuser = isset($user_login->IS_SUPERUSER) ? $user_login->IS_SUPERUSER : 0;
                // Jika bukan superuser validasi permission
                if(!$is_superuser){
                    $cek_permission_menu = DB::connection('dbintranet')
                    ->table('INT_ROLE_MENU_PERMISSION')
                    ->whereIn('INT_ROLE_MENU_PERMISSION.MENU_ID', $this->menu)
                    ->whereIn('INT_ROLE_MENU_PERMISSION.ROLE_ID', $this->role)
                    ->where('INT_ROLE_MENU.IS_ACTIVE', 1)
                    ->join('INT_ROLE_MENU', function($join){
                        $join->on('INT_ROLE_MENU_PERMISSION.ROLE_ID','=','INT_ROLE_MENU.ROLE_ID');
                    })
                    ->join('INT_ROLE_HAS_ACCESS', function($join){
                        $join->on('INT_ROLE_MENU_PERMISSION.ROLE_ID','=','INT_ROLE_HAS_ACCESS.ROLE_ID');
                    })
                    ->join('INT_MASTER_MENU', function($join){
                        $join->on('INT_ROLE_MENU_PERMISSION.MENU_ID','=','INT_MASTER_MENU.SEQ_ID');
                    })
                    ->join('INT_PERMISSIONS', function($join){
                        $join->on('INT_ROLE_MENU_PERMISSION.PERMISSION_ID','=','INT_PERMISSIONS.SEQ_ID');
                    })
                    ->select(DB::raw("DISTINCT CONCAT(LOWER(INT_PERMISSIONS.PERMISSION_NAME), '_', LOWER(INT_MASTER_MENU.PATH)) AS PERMISSION_NAME"))
                    ->get()->pluck('PERMISSION_NAME', 'PERMISSION_NAME')
                    ->toArray();
                }
                // Jika superuser, full akses
                else {
                    $cek_permission_menu = [];
                    $permission = DB::connection('dbintranet')
                    ->table('INT_PERMISSIONS')
                    ->select('PERMISSION_NAME')
                    ->distinct()->get()->pluck('PERMISSION_NAME')->toArray();
                    if($permission){
                        foreach ($permission as $key => $value) {
                            $data_permission = DB::connection('dbintranet')
                            ->table('INT_MASTER_MENU')
                            ->select(DB::raw("DISTINCT CONCAT(LOWER('$value'), '_', LOWER(INT_MASTER_MENU.PATH)) AS PERMISSION_NAME"))
                            ->get()->pluck('PERMISSION_NAME', 'PERMISSION_NAME')
                            ->toArray();
                            array_push($cek_permission_menu, $data_permission);
                        }
                        $cek_permission_menu = call_user_func_array("array_merge", $cek_permission_menu);
                    }
                }
                return collect($cek_permission_menu);
            }
            return [];
        });

        $this->app->singleton('get_menu_access', function() {
            $data_menu = DataMenu::whereIn('SEQ_ID', $this->menu)
            ->orderBy('SEQ_ID', 'ASC')
            ->get()->toArray();

            $data_new_menu = collect($data_menu)->map(function($item, $key) use ($data_menu){
                $parent = [];
                if($item['IS_PARENT_MENU'] == 1 && $item['PARENT_ID'] == 0){
                    $parent = array(
                        "menu_id"=>$item['SEQ_ID'],
                        "menu_name"=>$item['MENU_NAME'],
                        "route"=>$item['PATH'],
                        "icon"=>$item['ICON'],
                        "position"=>1,
                        "parent_id"=>$item['PARENT_ID'],
                        "is_parent_menu"=>$item['IS_PARENT_MENU'],
                        "visible"=>"Y",
                        "status"=>$item['IS_ACTIVE'],
                        "target_blank"=>'',
                        "created_by"=>'',
                        "created_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_CREATED'])),
                        "updated_by"=>'',
                        "updated_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_MODIFIED'])),
                        "type"=>"frontend",
                        "method"=>["GET", "POST"],
                        "code"=>$item['ROUTE_NAME'],
                        "child"=>[],
                        "sort"=>$item['MENU_SORT']
                    );
                    /**
                     * Find first child of main menu
                     * Better use filter array
                     * rather than query
                     */
                    // $child1 = $item->findChild(1, $item->SEQ_ID, $this->menu) ?? [];
                    $child1 = collect($data_menu)->filter(function($obj_menu, $key) use ($item){
                        return $obj_menu['IS_PARENT_MENU'] == 1 && $obj_menu['PARENT_ID'] == $item['SEQ_ID'];
                    });
                    if($child1){
                        $child1 = $child1->map(function($item, $key) use ($data_menu){
                            $child_1 = array(
                                "menu_id"=>$item['SEQ_ID'],
                                "menu_name"=>$item['MENU_NAME'],
                                "route"=>$item['PATH'],
                                "icon"=>$item['ICON'],
                                "position"=>1,
                                "parent_id"=>$item['PARENT_ID'],
                                "is_parent_menu"=>$item['IS_PARENT_MENU'],
                                "visible"=>"Y",
                                "status"=>$item['IS_ACTIVE'],
                                "target_blank"=>'',
                                "created_by"=>'',
                                "created_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_CREATED'])),
                                "updated_by"=>'',
                                "updated_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_MODIFIED'])),
                                "type"=>"frontend",
                                "method"=>["GET", "POST"],
                                "code"=>$item['ROUTE_NAME'],
                                "sort"=>$item['MENU_SORT']
                            );

                            // $child2 = $item->findChild(0, $item->SEQ_ID, $this->menu) ?? [];
                            $child2 = collect($data_menu)->filter(function($obj_menu, $key) use ($item){
                                return $obj_menu['IS_PARENT_MENU'] == 0 && $obj_menu['PARENT_ID'] == $item['SEQ_ID'];
                            });
                            if($child2){
                                $child2 = $child2->map(function($item, $key){
                                    $child_2 = array(
                                        "menu_id"=>$item['SEQ_ID'],
                                        "menu_name"=>$item['MENU_NAME'],
                                        "route"=>$item['PATH'],
                                        "icon"=>$item['ICON'],
                                        "position"=>1,
                                        "parent_id"=>$item['PARENT_ID'],
                                        "is_parent_menu"=>$item['IS_PARENT_MENU'],
                                        "visible"=>"Y",
                                        "status"=>$item['IS_ACTIVE'],
                                        "target_blank"=>'',
                                        "created_by"=>'',
                                        "created_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_CREATED'])),
                                        "updated_by"=>'',
                                        "updated_date"=>date('Y-m-d H:i:s', strtotime($item['DATE_MODIFIED'])),
                                        "type"=>"frontend",
                                        "method"=>["GET", "POST"],
                                        "code"=>$item['ROUTE_NAME'],
                                        "sort"=>$item['MENU_SORT']
                                    );
                                    return $child_2;
                                });

                                if($child2->toArray()){
                                    $child_1['child_route'] = ["#"];
                                    $child_1['child'] = $child2->toArray();
                                }
                            }

                            return $child_1;
                        });
                    }

                    // insert menu to parent
                    if($child1->toArray()){
                        $parent['child_route'] = ["#"];
                        $parent['child'] = $child1->toArray();
                    }
                }
                if($parent)
                    return $parent;
            })->reject(function ($value) {
                return $value === null;
            });
            $data_new_menu = $data_new_menu->sortBy('sort');
            return json_encode($data_new_menu->toArray()) ?? [];
        });

        $this->app->singleton('user_login_menu', function () {
            $user_login = Session::get('user_data');
            if ($user_login) {
                $emp_id = $user_login->SEQ_ID;
                $is_superuser = isset($user_login->IS_SUPERUSER) ? $user_login->IS_SUPERUSER : 0;
                if(!$is_superuser){
                    $cek_user_detail = DB::connection('dbintranet')
                    ->table('VIEW_EMPLOYEE_ACCESS')
                    ->where('SEQ_ID', $emp_id)
                    ->orderBy('SEQ_ID', 'ASC')
                    ->get()
                    ->first();
                    if($cek_user_detail){
                        // Prepare detail user / employee
                        $get_access_menu = [
                            'COMPANY CODE'=>$cek_user_detail->COMPANY_CODE,
                            'PLANT'=>$cek_user_detail->SAP_PLANT_ID,
                            'EMPLOYEE'=>$cek_user_detail->SEQ_ID,
                            'MIDJOB'=>$cek_user_detail->MIDJOB_TITLE_ID,
                            'COST CENTER'=>$cek_user_detail->SAP_COST_CENTER_ID,
                            'TERRITORY'=>$cek_user_detail->TERRITORY_ID,
                            'DEPARTMENT'=>$cek_user_detail->DEPARTMENT_SEQ_ID,
                            'DIVISION'=>$cek_user_detail->DIVISION_SEQ_ID,
                            'GENERAL'=>0,
                            'PUBLIC'=>0
                        ];

                        // Cek Akses yang tersedia / yang sudah di assign
                        $data_master=DataAccessRole::join('INT_MASTER_MENU_ACCESS', function($join){
                            $join->on('INT_ROLE_HAS_ACCESS.MENU_ACCESS_ID','=','INT_MASTER_MENU_ACCESS.SEQ_ID');
                        })->join('INT_MASTER_ROLE', function($join){
                            $join->on('INT_ROLE_HAS_ACCESS.ROLE_ID','=','INT_MASTER_ROLE.SEQ_ID');
                        })->select('INT_ROLE_HAS_ACCESS.SEQ_ID', 'INT_ROLE_HAS_ACCESS.REFER_TO_ID', 'INT_ROLE_HAS_ACCESS.MENU_ACCESS_ID', 'INT_MASTER_MENU_ACCESS.MENU_ACCESS_NAME', 'INT_MASTER_ROLE.ROLE_NAME', 'INT_ROLE_HAS_ACCESS.ROLE_ID', 'INT_ROLE_HAS_ACCESS.IS_ACTIVE')
                        ->get();
                        $data_access_role = $data_master->pluck('MENU_ACCESS_NAME', 'MENU_ACCESS_ID')->toArray();
                        // $data_role = array_unique($data_master->pluck('ROLE_ID')->toArray());
                        $data_role = [];
                        $data_menu = [];
                        $data_url = [];
                        foreach($data_access_role as $key => $access){
                            if(in_array(strtoupper($access), array_keys($get_access_menu))){
                                $get_menu = DB::connection('dbintranet')
                                ->select("SELECT DISTINCT rha.ROLE_ID, rm.MENU_ID, mm.*, rm.IS_ACTIVE AS IS_ACTIVE_ROLEMENU, rha.IS_ACTIVE AS IS_ACTIVE_ACCESS, mm.IS_ACTIVE AS IS_ACTIVE_MENU FROM dbo.INT_ROLE_HAS_ACCESS rha
                                    INNER JOIN dbo.INT_ROLE_MENU rm ON rha.ROLE_ID = rm.ROLE_ID
                                    INNER JOIN dbo.INT_MASTER_MENU mm ON rm.MENU_ID = mm.SEQ_ID
                                    WHERE rha.MENU_ACCESS_ID = :menu_access_id AND rha.REFER_TO_ID = :refer_to
                                    AND rha.IS_ACTIVE = 1 AND rm.IS_ACTIVE = 1 AND mm.IS_ACTIVE = 1"
                                , ['menu_access_id'=>$key, 'refer_to'=>isset($get_access_menu[strtoupper($access)]) ? $get_access_menu[strtoupper($access)] : '']);
                                if(count($get_menu)){
                                    $data_menu_retrieved = collect($get_menu)->pluck('SEQ_ID');
                                    $data_url_retrieved = array_unique(collect($get_menu)->pluck('PATH')->toArray());
                                    $data_role_retrieved = array_unique(collect($get_menu)->pluck('ROLE_ID')->toArray());
                                    foreach($data_menu_retrieved as $value){
                                        if(!in_array($value, $data_menu, true)){
                                            array_push($data_menu, (int)$value);
                                        }
                                    }
                                    foreach($data_url_retrieved as $value){
                                        if(!in_array($value, $data_url, true)){
                                            array_push($data_url, $value);
                                        }
                                    }
                                    foreach($data_role_retrieved as $value){
                                        if(!in_array($value, $data_role, true)){
                                            array_push($data_role, $value);
                                        }
                                    }

                                }
                            }

                        }
                        sort($data_menu, SORT_NUMERIC);
                        $this->role = $data_role;
                        $this->url = $data_url;
                        $this->menu = $data_menu;
                        $this->permission = $this->app->get('get_permission');
                        return $this->app->get('get_menu_access') ?? [];
                    }
                } else {
                    $data_menu = [];
                    $data_url = [];
                    $get_all_menu = DB::connection('dbintranet')
                    ->table('dbo.INT_MASTER_MENU')
                    ->where('IS_ACTIVE', 1)
                    ->select('SEQ_ID', 'PATH')
                    ->get();

                    $new_menu = $get_all_menu->map(function($item, $key) use (&$data_menu, &$data_url){
                        if(!in_array($item->SEQ_ID, $data_menu, true)){
                            array_push($data_menu, (int)$item->SEQ_ID);
                        }
                        if(!in_array($item->PATH, $data_url, true)){
                            array_push($data_url, $item->PATH);
                        }
                    });
                    sort($data_menu, SORT_NUMERIC);
                    $this->role = [];
                    $this->url = $data_url;
                    $this->menu = $data_menu;
                    $this->permission = $this->app->get('get_permission');
                    return $this->app->get('get_menu_access') ?? [];
                }

            }
            return [];
        });

        $this->app->singleton('get_all_menu', function(){
            $data_menu = $this->app->get('user_login_menu');
            return $this->url;
        });

        $this->app->singleton('get_all_permission', function(){
            $data_permission = $this->app->get('user_login_menu');
            return $this->permission;
        });

        /**
         * Side Menu
         */
        $this->app->singleton('side_menu', function () {
            // $user_login = file_get_contents(__DIR__.'/../menus.json');
            $instance = $this;
            $user_login = $instance->app->get('user_login_menu');
            if ($user_login) {
                $child1 = collect(json_decode($user_login,true));
                $child1->map(function ($item1) {
                    $item1['child_position'] = 1;
                    $item1['child_route'] = [];
                    $item1['child_route'][] = $item1['route'];
                    if (isset($item1->child)) {
                        $child2 = collect($item1->child);
                        $child2->map(function ($item2) use ($item1) {
                            $item2['child_position'] = 2;
                            $item1['child_route'][] = $item2['route'];
                            $item2['child_route'] = [];
                            $item1['child_route'][] = $item2['route'];
                            $item2['child_route'][] = $item2['route'];
                            if (isset($item2->child)) {
                                $child3 = collect($item2->child);
                                $child3->map(function ($item3) use ($item1, $item2) {
                                    $item3['child_position'] = 3;
                                    $item1['child_route'][] = $item3['route'];
                                    $item3['child_route'] = [];
                                    $item1['child_route'][] = $item3['route'];
                                    $item2['child_route'][] = $item3['route'];
                                    $item3['child_route'][] = $item3['route'];
                                    if (isset($item3->child)) {
                                        $child4 = collect($item3->child);
                                        $child4->map(function ($item4) use ($item1, $item2, $item3) {
                                            $item4['child_position'] = 4;
                                            $item1['child_route'][] = $item4['route'];
                                            $item2['child_route'][] = $item4['route'];
                                            $item3['child_route'][] = $item4['route'];
                                            $item4['child_route'][] = $item4['route'];
                                        });
                                    }
                                });
                            }
                        });
                        $item1->child = $child2;
                    }
                });
                return $child1 ?? [];
            }
            return [];
        });

        $this->app->singleton('user_notification', function (){
            $id=Session::get('user_id');

            if(!empty($id)){
                $user_notif_new=DB::connection('dbintranet')
                ->select("SELECT * FROM TBL_NOTIFICATIONS WHERE NOTIF_EMPLOYEE_ID = '".$id."' AND NOTIF_ISREAD=0  ORDER BY NOTIF_DATE_CREATED DESC");
                $user_notif_old=DB::connection('dbintranet')
                ->select("SELECT TOP 50 * FROM TBL_NOTIFICATIONS WHERE NOTIF_EMPLOYEE_ID = '".$id."' AND NOTIF_ISREAD=1 ORDER BY NOTIF_DATE_CREATED DESC");

                $total=(int) (count($user_notif_new) + count($user_notif_old));


                $this->notification=array(
                    'total'=>$total,
                    'new'=>$user_notif_new,
                    'old'=>$user_notif_old
                );
            }

            return $this->notification;
        });

    }
}
