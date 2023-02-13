<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Traits\IntranetTrait;

class AuthApi
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
        if (Session::get('logged_in')!==1) {
            if(self::wantsJson($request) || $request->ajax()){
                return response()->json(['message'=>'Page Expired, please re-login'], 419);
            }
            return redirect('/login');
        }
        else{
            $user_login = Session::has('user_data');
            if($user_login){
                $is_superuser = isset($user_login->IS_SUPERUSER) ? $user_login->IS_SUPERUSER : 0;
                if(!$is_superuser){
                    if(Session::has('access_menu')){
                        $roles = isset($request->route()->action['permission']) ? $request->route()->action['permission'] : [];
                        $roles = collect($roles);
                        
                        $menu_available = Session::get('access_menu');
                        $now_path = "/".$request->path();
                        // dd($now_path, $menu_available);

                        if(!in_array($now_path, $menu_available)){
                            // Exclude request berupa ajax ataupun axios dari vue
                            if(!$request->ajax() && !self::wantsJson($request)){
                                // Jika tidak ada hak akses menu dan bukan merupakan ajax, cek permission 
                                // untuk menu add, create, update, delete
                                $cek_permission = $roles->filter(function($item) use ($menu_available) {
                                    $child_menu_related_to_parent = isset(explode('_', $item, 2)[1]) ? explode('_', $item, 2)[1] : false; 
                                    return in_array($child_menu_related_to_parent, $menu_available);
                                    // return Session::get('permission_menu')->has($item);
                                });
                                
                                if(isset($cek_permission) && count($cek_permission)){
                                    return $next($request);
                                }
                                // abort(401, "You have no access of this menu");
                                return response()->view('pages.exception.unauthorized');
                            }
                        }
                        // End check menu permission
                    } else {
                        return response()->view('pages.exception.unauthorized');
                    }
                }
            } else {
                return response()->view('pages.exception.unauthorized');
            }
        }
        return $next($request);
    }
}
