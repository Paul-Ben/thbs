<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Traits\HasRoles;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

      

        // // Check if user has the required role passed as middleware parameter
        // $roles = explode('|', $request->route()->middleware()[1] ?? '');

        // if (!in_array($user->role, $roles)) {
        //     abort(403, 'Unauthorized');
        // }

            return $next($request);
        }
    }

