<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
               $recentTransactions = ApplicationFeePayment::with(['application.programme'])
       ->orderBy('created_at', 'desc')
       ->limit(5)
       ->get();
   

        $totalRevenue = ApplicationFeePayment::where('status', 'successful')->sum('amount');
        $applicationRevenue = ApplicationFeePayment::where('status', 'successful')
            ->whereHas('application', function($query) {
                $query->whereNotNull('programme_id');
            })
            ->sum('amount');
        
        $schoolChargeRevenue = 0;
        
        $totalTransactions = ApplicationFeePayment::count();
        if (Auth::check()) {
            $authUser = Auth::user();
          if ($authUser->userRole == "Superadmin") {
            return view('superadmin.dashboard', compact('authUser'))->with('success', 'Login Successful');
          } else if ($authUser->userRole == "Admission Officer") {
            return view('admission_officer.dashboard', compact('authUser'));
          } else if ($authUser->userRole == "Bursar") {
            return view('bursar.dashboard', compact(
                'authUser', 
                'recentTransactions', 
                'totalRevenue', 
                'applicationRevenue', 
                'schoolChargeRevenue',
                'totalTransactions'
            ));
        }
        // return view('dashboard');
    }
}
 
}
