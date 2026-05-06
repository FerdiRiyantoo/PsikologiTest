<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TestSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('test_session_id')) {
            return redirect()->route('enter.code')
                ->with('error', 'Masukkan kode akses terlebih dahulu.');
        }
        return $next($request);
    }
}