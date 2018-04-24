<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckLogin
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
        $session=Session::all();
        if(isset($session["roleid"])&&isset($session["username"])&&$session["isLogin"]||$request->path()=="login"||$request->path()=="login/checkLogin"){
            return $next($request);
        }else{
            return redirect("login");
        }
    }

    public function terminate($request, $response)
    {
        //这里是响应后调用的方法
        //dd($request->url());
        //dd($request->path());
        //dd($request->attributes->all());
    }

}
