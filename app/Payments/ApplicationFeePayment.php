<?php

namespace App\Payments;

use App\Contracts\PaymentTypeInterface;
use App\Models\Programme;
use App\Models\Application;
use App\Models\ApplicationSession;
use App\Models\ApplicationFeePayment as ApplicationFeePaymentModel;
use Illuminate\Http\Request;
use Exception;

class ApplicationFeePayment implements PaymentTypeInterface
{
    public function validate(Request $request): void
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'surname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'othernames' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'nullable|string|regex:/^[0-9+\-\s\(\)]+$/|max:20',
            'programme_id' => 'required|exists:programmes,id',
        ]);
    }

    public function buildPaymentData(array $data): array
    {
        // If this is already processed payment data, return it as-is
        if (isset($data['amount']) && isset($data['meta'])) {
            return $data;
        }

        if (!isset($data['programme_id'])) {
            throw new Exception('Programme ID is missing from request data. Available keys: ' . implode(', ', array_keys($data)));
        }

        $programme = Programme::with('applicationFees')->findOrFail($data['programme_id']);
        $applicationFee = $programme->applicationFees->firstOrFail();

        return [
            'amount' => $applicationFee->amount,
            'title' => 'THBS Application Fee - ' . $programme->name,
            'description' => "Application fee payment for {$programme->name} - {$data['surname']} " . ($data['othernames'] ?? ''),
            'email' => $data['email'],
            'surname' => $data['surname'],
            'othernames' => $data['othernames'] ?? '',
            'phone' => $data['phone'] ?? 'N/A',
            'meta' => [
                'payment_type' => 'application_fee',
                'programme_id' => $programme->id,
                'programme_name' => $programme->name,
                'surname' => $data['surname'],
                'othernames' => $data['othernames'] ?? '',
                'email' => $data['email'],
                'phone' => $data['phone'] ?? 'N/A',
            ]
        ];
    }

    public function handleSuccess(array $paymentResult)
    {
        return redirect()->route('application.create', $paymentResult['reference'])
            ->with('success', 'Payment successful! You can now proceed with your application.');
    }

    public function persistVerifiedPayment(array $data, string $txRef, array $meta): array
    {
        $applicationSession = ApplicationSession::where('is_current', true)->firstOrFail();

        $application = Application::create([
            'applicant_surname' => $meta['surname'],
            'applicant_othernames' => $meta['othernames'],
            'email' => $meta['email'],
            'phone' => $meta['phone'],
            'payment_reference' => $txRef,
            'application_session_id' => $applicationSession->id,
            'is_filled' => 0,
            'programme_id' => $meta['programme_id'],
        ]);

        $applicationFeePayment = ApplicationFeePaymentModel::updateOrCreate(
            ['reference' => $txRef],
            [
                'amount' => $data['amount'] ?? 0,
                'currency' => $data['currency'] ?? 'NGN',
                'payment_method' => 'card',
                'transaction_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'failed',
                'payment_date' => now(),
                'description' => 'Application fee payment for ' . ($meta['surname'] ?? '') . ' ' . ($meta['othernames'] ?? ''),
                'metadata' => $data
            ]
        );

        $applicationFeePayment->transactions()->create([
            'type' => 'Application fee payment',
            'status' => $data['status'] ?? 'failed',
            'amount' => $data['amount'] ?? 0,
            'currency_code' => $data['currency'] ?? 'NGN',
            'is_reconciled' => false,
        ]);

        return [
            'status' => $data['status'] ?? 'failed',
            'payment_model' => $applicationFeePayment,
            'application' => $application,
        ];
    }
}
