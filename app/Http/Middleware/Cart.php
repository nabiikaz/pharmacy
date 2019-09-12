<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Closure;

class Cart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        //test if the client_session is not set then set it .
        if(!isset($_COOKIE["client_session"])){
            $client_session = Hash::make(now().rand(0,123456789));
            setcookie("client_session",$client_session, time() + (86400 * 30), "/");
        }else{
            $client_session = $_COOKIE["client_session"];
        }

        
        //this Cache with the key of $client_session holds the ids of the medicines in the cart
        if(!Cache::has($client_session))
            Cache::put($client_session,[],(86400 * 30));

        
        
        
        

        return $next($request);
    }
}
