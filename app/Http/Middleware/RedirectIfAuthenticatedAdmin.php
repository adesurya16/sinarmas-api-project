<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAuthenticatedAdmin
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
        $login = session('login', false);

        if($login) {
          return redirect('/home');
        }

        return $next($request);
    }
}
