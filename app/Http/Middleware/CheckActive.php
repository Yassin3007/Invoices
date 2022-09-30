<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckActive
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
        $user= User::find($request->email);
        if (! $user->Status == 'مفعل'){
            return 'غير مفعل' ;

        }
        return $next($request);


    }
}
