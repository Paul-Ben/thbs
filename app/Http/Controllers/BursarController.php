<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFeePayment;
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
        
        $transactions = Transaction::with(['paymentable.application.programme'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('bursar.transactions.index', compact('authUser', 'transactions'));
    }

    public function showTransaction(Transaction $transaction): View
    {
        $authUser = Auth::user();
        
        $transaction->load(['paymentable.application.programme']);
        
        return view('bursar.transactions.show', compact('transaction', 'authUser'));
    }

    public function reconcileTransaction(Transaction $transaction)
    {
        $transaction->update(['is_reconciled' => true]);
        
        return redirect()->route('bursar.transaction.show', $transaction)
            ->with('success', 'Transaction has been reconciled successfully.');
    }
}