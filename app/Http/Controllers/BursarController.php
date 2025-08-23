<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFeePayment;
use App\Models\SchoolFeePayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BursarController extends Controller
{
  
    public function payments(): View
    {
        $authUser = Auth::user();
        
        $payments = ApplicationFeePayment::with(['application.programme'])
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('bursar.applications.index', compact('authUser', 'payments'));
    }

    public function showPayment(ApplicationFeePayment $payment): View
    {
        $authUser = Auth::user();
        
        $payment->load(['application.programme']);
        
        return view('bursar.applications.show', compact('payment', 'authUser'));
    }

    public function transactions(): View
    {
        $authUser = Auth::user();
        
        $transactions = Transaction::with('paymentable')
            ->orderBy('created_at', 'desc')
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
        
        return view('bursar.transactions.index', compact('authUser', 'transactions'));
    }

    public function showTransaction(Transaction $transaction): View
    {
        $authUser = Auth::user();
        
        // Load specific relationships based on payment type
        if ($transaction->paymentable_type === 'App\\Models\\ApplicationFeePayment') {
            $transaction->load('paymentable.application.programme');
        } elseif ($transaction->paymentable_type === 'App\\Models\\SchoolFeePayment') {
            $transaction->load('paymentable.student', 'paymentable.schoolFee');
        } elseif ($transaction->paymentable_type === 'App\\Models\\AptitudeTestPayment') {
            $transaction->load('paymentable.application.programme');
        }
        
        return view('bursar.transactions.show', compact('transaction', 'authUser'));
    }

    public function schoolFeePayments(): View
    {
        $authUser = Auth::user();
        
        $payments = SchoolFeePayment::with(['student.programme', 'schoolFee.schoolSession', 'schoolFee.semester'])
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('bursar.school-fees.payments', compact('authUser', 'payments'));
    }

    public function showSchoolFeePayment(SchoolFeePayment $payment): View
    {
        $authUser = Auth::user();
        
        $payment->load(['student.programme', 'schoolFee.schoolSession', 'schoolFee.semester']);
        
        return view('bursar.school-fees.payment-show', compact('payment', 'authUser'));
    }

    public function reconcileTransaction(Transaction $transaction)
    {
        $transaction->update(['is_reconciled' => true]);
        
        return redirect()->route('bursar.transaction.show', $transaction)
            ->with('success', 'Transaction has been reconciled successfully.');
    }
}