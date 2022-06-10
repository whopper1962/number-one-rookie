<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
{

    private $auth;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //ユーザの権限チェック
	      if(auth()->user()->role === 'admin'){
	         $this->auth = true;
	      }else{
	         $this->auth = false;
	      }

	      //ユーザの権限がadminだった場合はアクセスを許可
	      if($this->auth === true){
	         return $next($request);
	      }
	      return back();
    }

 
}
