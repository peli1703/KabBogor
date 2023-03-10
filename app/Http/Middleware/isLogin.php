<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ngecek di auth ada data user yang login
        // kalau ada, masuk ke if terus next proses
        // kalau ga ada, masuk ke elsa, menuju halaman login
        
        if (Auth::check()){
            return $next($request);
        } else{
            return redirect()->route('login');
        }
    }
}
