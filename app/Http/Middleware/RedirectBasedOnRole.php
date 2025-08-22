<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('Superadmin')) {
                return redirect()->intended('/superadmin/dashboard');
            } elseif ($user->hasRole('Department Admin')) {
                return redirect()->intended('/department/dashboard');
            } elseif ($user->hasRole('Admissions Officer')) {
                return redirect()->intended('/admissions/dashboard');
            } elseif ($user->hasRole('Bursar')) {
                return redirect()->intended('/bursar/dashboard');
            } elseif ($user->hasRole('IT Admin')) {
                return redirect()->intended('/itadmin/dashboard');
            } elseif ($user->hasRole('Student')) {
                return redirect()->intended('/student/dashboard');
            }
        }
        return $next($request);
    }
}
