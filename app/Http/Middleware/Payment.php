<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Payment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $check_uri = explode('/', url()->current());
            // Make pointer array to the end of array
            end($check_uri);
            $key = key($check_uri);
            
            // If there's no payload reject request
            if(!isset($check_uri[$key])){
                abort(403);
            }
            $decryptString = Crypt::decryptString($check_uri[$key]);
            try {
                $payload = json_decode($decryptString);
                $CAANO = 0;
                $expired = 0;
                if(isset($payload->CAANO) && isset($payload->expired) && isset($payload->signature)){
                    // Check expired againts expired token
                    $now = strtotime(now());
                    $expired = $payload->expired;
                    if($expired < $now){
                        $request->session()->forget($signature);
                        $request->session()->flash('invalid_payload', 'Your credentials has expired or invalid');
                        abort(403);
                    }

                    $CAANO = $payload->CAANO;
                    $signature = $payload->signature;
                    if(!$request->session()->has($signature))
                        $request->session()->put($signature, ['__inv'=>$CAANO, '__expired'=>$expired]);
                }
                else {
                    $request->session()->flash('invalid_payload', 'Unable to retrive data from payload, please try again');
                    abort(403);
                }
            } catch(\Exception $e){
                throw new DecryptException('Broken Payload');
            }

            // Finally return request
            $request->attributes->add(['payment_data' => $request->session()->get($signature)]);
            return $next($request);
        } catch(DecryptException $e){
            $request->session()->flash('invalid_payload', 'Payload is invalid or expired, cannot make any operations');
            abort(403);
        } catch(\Exception $e){
            abort(500);
        }
    }
}
