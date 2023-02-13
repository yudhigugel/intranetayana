<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Traits\IntranetTrait;
class CheckPermission
{
    use IntranetTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $user_login = Session::get('user_data');
        if($user_login){
            $is_superuser = isset($user_login->IS_SUPERUSER) ? $user_login->IS_SUPERUSER : 0;
            if(!$is_superuser){
                $permission_menu = isset($request->route()->action['permission']) ? $request->route()->action['permission'] : [];
                $permission_user = Session::get('permission_menu');
                if($permission_user){
                    $new_permission = $permission_user->filter(function($item, $key) use ($permission_menu){
                        return in_array($item, $permission_menu);
                    })->toArray();
                } else{
                    $new_permission = [];
                }

                if(count($new_permission) == 0){
                    if($request->ajax() || self::wantsJson($request))
                        return response()->json(['msg'=>"You don't have enough permission to do such task, operation terminated",'type'=>'error'], 200);
                    else
                        return response()->view('pages.exception.unauthorized');
                }
            }
        } else {
            if($request->ajax() || self::wantsJson($request))
                return response()->json(['msg'=>'Cannot read any user data logged-in, please try again or refresh page','type'=>'error'], 410);
            else 
                return redirect('/login');
        }
        // Finally return request as usual if requirement passed
        return $next($request);
    }
}
