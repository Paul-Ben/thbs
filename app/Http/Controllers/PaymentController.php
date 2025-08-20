<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Support\PaymentTypeResolver;
use App\Constants\PaymentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function initialize(Request $request): RedirectResponse
    {
        $paymentType = $request->payment_type;
        $paymentHandler = PaymentTypeResolver::resolve($paymentType);

        // Validate using the handler
        $paymentHandler->validate($request);

        try {
            $paymentData = $paymentHandler->buildPaymentData($request->all());

            $result = $this->paymentService->initializePayment(
                $paymentData,
                $paymentType,
                $paymentData
            );

            if (in_array($result['status'], [PaymentStatus::SUCCESS, PaymentStatus::SUCCESSFUL]) && !empty($result['link'])) {
                return redirect($result['link']);
            }

            return back()->with('error', 'Failed to initialize payment. Please try again.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while initializing payment.');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            if (!$request->transaction_id) {
                return redirect()->route('application.landing')->with('error', 'Missing transaction ID in callback');
            }

            $paymentResult = $this->paymentService->verifyAndLogPayment($request->transaction_id);
            $paymentHandler = PaymentTypeResolver::resolve($paymentResult['payment_type']);

            if (in_array($paymentResult['status'], [PaymentStatus::SUCCESS, PaymentStatus::SUCCESSFUL])) {
                return $paymentHandler->handleSuccess($paymentResult);
            }

            return redirect()->route('application.landing')->with('error', 'Payment verification failed');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('application.landing')->with('error', 'An error occurred during payment processing');
        }
    }
}
