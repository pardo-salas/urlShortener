<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckSessionValues
{
    
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('id')) {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
