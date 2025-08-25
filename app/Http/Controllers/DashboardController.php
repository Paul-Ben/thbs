<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFeePayment;
use App\Models\AptitudeTestPayment;
use App\Models\SchoolFeePayment;
use App\Models\Transaction;
use App\Models\Application;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       $recentTransactions = Transaction::with('paymentable')
       ->orderBy('created_at', 'desc')
       ->limit(5)
       ->get()
       ->map(function($transaction) {
           // Load specific relationships based on payment type
           if ($transaction->paymentable_type === 'App\\Models\\ApplicationFeePayment') {
               $transaction->load('paymentable.application.programme');
           } elseif ($transaction->paymentable_type === 'App\\Models\\SchoolFeePayment') {
               $transaction->load('paymentable.student', 'paymentable.schoolFee');
           } elseif ($transaction->paymentable_type === 'App\\Models\\AptitudeTestPayment') {
               $transaction->load('paymentable.application.programme');
           }
           return $transaction;
       });
   

        // $totalRevenue = ApplicationFeePayment::where('status', 'successful')->sum('amount');

        $applicationRevenue = ApplicationFeePayment::where('status', 'successful')
            ->whereHas('application', function($query) {
                $query->whereNotNull('programme_id');
            })
            ->sum('amount');

        $aptitudeTestRevenue = AptitudeTestPayment::where('status', 'successful')
            ->sum('amount');

        $schoolFeeRevenue = SchoolFeePayment::where('status', 'successful')
            ->sum('amount');

        $totalRevenue = $applicationRevenue + $aptitudeTestRevenue + $schoolFeeRevenue;
        
        $schoolChargeRevenue = $schoolFeeRevenue;
        
        $totalTransactions = Transaction::count();
        if (Auth::check()) {
            $authUser = Auth::user();
          if ($authUser->userRole == "Superadmin") {
            return view('superadmin.dashboard', compact('authUser'))->with('success', 'Login Successful');
          } else if ($authUser->userRole == "Admission Officer") {
            $applicationsCount = Application::count();
            $admittedCount = Admission::where('status', 'approved')->count();
            return view('admission_officer.dashboard', compact('authUser', 'applicationsCount', 'admittedCount'));
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
