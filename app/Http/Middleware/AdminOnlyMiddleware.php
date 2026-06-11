<?php
// app/Http/Middleware/AdminOnlyMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Доступ только для администраторов.');
        }
        
        return $next($request);
    }
}