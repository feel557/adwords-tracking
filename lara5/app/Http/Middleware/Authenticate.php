<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

/**
* The Guard implementation.
*
* @var Guard
*/
protected $auth;

/**
* Create a new filter instance.
*
* @param  Guard  $auth
* @return void
*/
public function __construct(Guard $auth)
{
$this->auth = $auth;
}

/**
* Handle an incoming request.
*
* @param  \Illuminate\Http\Request  $request
* @param  \Closure  $next
* @return mixed
*/
public function handle($request, Closure $next)
{

if($request->user()){

if( $request->user()->user_type == 21 ){
return $next($request);
}

return redirect('/');
}


return redirect('/');

}

}
