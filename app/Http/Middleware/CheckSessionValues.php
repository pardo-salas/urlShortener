<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class CheckSessionValues
{
    
    public function handle(Request $request, Closure $next)
    {
        $value = $request->cookie('id');
        if (!$value) {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
