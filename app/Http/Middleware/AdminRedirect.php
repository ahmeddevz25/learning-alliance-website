<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirect
{
    public function handle($request, Closure $next)
    {
        // Check 1: Agar user sirf /admin hit karta hai (ya admin/ par ho)
        if ($request->is('admin') || $request->is('admin/')) {
            // Agar logged in hai, toh dashboard par bhej do
            if (Auth::check()) {
                return redirect()->route('dashboard');
            } else {
                // Agar logged in nahi hai, toh login page par bhej do
                return redirect()->route('login');
            }
        }

        // Check 2: Agar user admin/login page par hai aur already logged in hai
        if ($request->is('admin/login') && Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Baaqi routes ke liye aage badh jao (next middleware ya controller)
        return $next($request);
    }
}
