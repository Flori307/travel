<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Проверка на блокировку
        if (isset($user->is_blocked) && $user->is_blocked) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Ваш аккаунт заблокирован');
        }
        
        // Проверка роли (админ или модератор)
        if (!in_array($user->role, ['admin', 'moderator'])) {
            abort(403, 'У вас нет доступа к этой странице.');
        }
        
        return $next($request);
    }
}