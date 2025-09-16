<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirect
{
    public function handle($request, Closure $next)
    {
        // Agar admin login page pe hai
        if ($request->is('admin/login')) {
            if (Auth::check()) {
                // Agar already login hai → dashboard bhej do
                return redirect()->route('dashboard');
            }
            return $next($request); // warna login page dikhao
        }

        // Agar koi aur admin route access ho raha hai (jaise dashboard)
        if ($request->is('admin/*')) {
            if (! Auth::check()) {
                // Agar login nahi hai → login page bhej do
                return redirect()->route('login');
            }
        }

        return $next($request);
    }

}
