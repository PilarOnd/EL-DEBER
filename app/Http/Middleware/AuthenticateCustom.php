<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthenticateCustom
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('usuario')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
} 