<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function initialize(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'surname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'othernames' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'nullable|string|regex:/^[0-9+\-\s\(\)]+$/|max:20',
            'programme_id' => 'required|exists:programmes,id',
        ], [
            'surname.regex' => 'Surname must contain only letters and spaces.',
            'othernames.regex' => 'Other names must contain only letters and spaces.',
            'phone.regex' => 'Phone number must contain only numbers.',
            'surname.required' => 'Surname is required.',
            'othernames.required' => 'Other names are required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'programme_id.required' => 'Please select a programme.',
            'programme_id.exists' => 'Selected programme is invalid.',
        ]);

        if (preg_match('/[0-9]/', $request->surname)) {
            return redirect()->route('application.landing')
                ->withErrors(['surname' => 'Surname cannot contain numbers.']);
        }

        if (preg_match('/[0-9]/', $request->othernames)) {
            return redirect()->route('application.landing')
                ->withErrors(['othernames' => 'Other names cannot contain numbers.']);
        }

        if ($request->phone && !preg_match('/^[0-9+\-\s\(\)]+$/', $request->phone)) {
            return redirect()->route('application.landing')
                ->withErrors(['phone' => 'Phone number contains invalid characters.']);
        }

        try {
            $result = $this->paymentService->initializePayment($request->all());

            if ($result['status'] === 'success' && !empty($result['link'])) {
                return redirect($result['link']);
            }

            return redirect()->route('application.landing')
                ->with('error', 'Failed to initialize payment. Please try again.');

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return redirect()->route('application.landing')
                ->with('error', 'An error occurred while initializing payment.');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        // Log::info('Flutterwave callback received', $request->all());

        try {
            if ($request->transaction_id) {
                $payment = $this->paymentService->verifyAndLogPayment($request->transaction_id);

                if ($payment->status === 'successful') {
                    return redirect()->route('application.create', $payment->reference)
                        ->with('success', 'Payment successful! You can now proceed with your application.');
                }

                return redirect()->route('application.landing')
                    ->with('error', 'Payment verification failed');
            }

            return redirect()->route('application.landing')
                ->with('error', 'Missing transaction ID in callback');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('application.landing')
                ->with('error', 'An error occurred during payment processing');
        }
    }


}