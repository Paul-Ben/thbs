<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = auth()->user();
            $redirect = match ($user->userRole) {
                'Superadmin' => route('superadmin.dashboard'),
                'Department Admin' => route('department.dashboard'),
                'Admission Officer' => route('admissions.dashboard'),
                'Bursar' => route('bursar.dashboard'),
                'IT Admin' => route('it.dashboard'),
                'Student' => route('student.dashboard'),
                default => '/dashboard',
            };

            ToastMagic::success('Success!', 'Login Successful!');

            return redirect()->intended($redirect);

        } catch (\Exception $e) {
           
            ToastMagic::error('Error!', 'Invalid credentials! Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        ToastMagic::info('Success!', 'Logout successful!');

        return redirect('/login');

       
    }
}
