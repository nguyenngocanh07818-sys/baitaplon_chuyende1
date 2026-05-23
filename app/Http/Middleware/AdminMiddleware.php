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
            return redirect()->route('login.show')->with('error', 'Bạn cần đăng nhập.');
        }
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập.');
        }
        return $next($request);
    }
}
