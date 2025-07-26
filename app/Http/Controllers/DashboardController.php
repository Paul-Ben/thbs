<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $authUser = Auth::user();
          if ($authUser->userRole == "Superadmin") {
            return view('superadmin.dashboard', compact('authUser'))->with('success', 'Login Successful');
          } else if ($authUser->userRole == "Admission Officer") {
            return view('admission_officer.dashboard', compact('authUser'));
          
        }
        // return view('dashboard');
    }
}
 
}
