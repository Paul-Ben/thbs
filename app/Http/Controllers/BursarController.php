<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BursarController extends Controller
{
    /**
     * Display all successful payments.
     */
    public function payments(): View
    {
        $authUser = Auth::user();
        
        $payments = Payment::with(['application.programme'])
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('bursar.applications', compact('authUser', 'payments'));
    }

    /**
     * Display payment details.
     */
    public function showPayment(Payment $payment): View
    {
        $authUser = Auth::user();
        
        $payment->load(['application.programme']);
        
        return view('bursar.application-details', compact('payment', 'authUser'));
    }
}