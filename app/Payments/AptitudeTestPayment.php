<?php

namespace App\Payments;

use App\Contracts\PaymentTypeInterface;
use App\Models\Application;
use App\Models\AptitudeTestPayment as AptitudeTestPaymentModel;
use App\Models\AptitudeTestFee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class AptitudeTestPayment implements PaymentTypeInterface
{
    public function validate(Request $request): void
    {
        $request->validate([
            'application_number' => 'required|string|exists:applications,application_number',
        ]);
    }

    public function buildPaymentData(array $data): array
    {
        // If this is already processed payment data, return it as-is
        if (isset($data['amount']) && isset($data['meta'])) {
            return $data;
        }

        if (!isset($data['application_number'])) {
            throw new Exception('Application number is missing from request data. Available keys: ' . implode(', ', array_keys($data)));
        }

        $application = Application::with('programme')
            ->where('application_number', $data['application_number'])
            ->firstOrFail();

        // Check if aptitude test payment already exists for this application
        $existingPayment = AptitudeTestPaymentModel::where('application_id', $application->id)
            ->where('status', 'successful')
            ->first();

        if ($existingPayment) {
            throw ValidationException::withMessages([
                'application_number' => 'Aptitude test payment has already been made for this application.'
            ]);
        }

        $aptitudeTestFee = AptitudeTestFee::where('is_active', true)->firstOrFail();

        return [
            'amount' => $aptitudeTestFee->amount,
            'title' => 'THBS Aptitude Test Fee - ' . $application->programme->name,
            'description' => "Aptitude test fee payment for {$application->programme->name} - {$application->applicant_surname} {$application->applicant_othernames}",
            'email' => $application->email,
            'surname' => $application->applicant_surname,
            'othernames' => $application->applicant_othernames,
            'phone' => $application->phone ?? 'N/A',
            'meta' => [
                'payment_type' => 'aptitude_test_fee',
                'application_id' => $application->id,
                'programme_id' => $application->programme_id,
                'programme_name' => $application->programme->name,
                'surname' => $application->applicant_surname,
                'othernames' => $application->applicant_othernames,
                'email' => $application->email,
                'phone' => $application->phone ?? 'N/A',
            ]
        ];
    }

    public function handleSuccess(array $paymentResult)
    {
        return redirect()->route('application.landing')
            ->with('success', 'Aptitude Test Payment successful!');
    }

    public function persistVerifiedPayment(array $data, string $txRef, array $meta): array
    {
        $application = Application::findOrFail($meta['application_id']);

        $aptitudeTestPayment = AptitudeTestPaymentModel::updateOrCreate(
            ['reference' => $txRef],
            [
                'application_id' => $application->id,
                'amount' => $data['amount'] ?? 0,
                'currency' => $data['currency'] ?? 'NGN',
                'payment_method' => 'card',
                'transaction_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'failed',
                'payment_date' => now(),
                'description' => 'Aptitude test fee payment for ' . ($meta['surname'] ?? '') . ' ' . ($meta['othernames'] ?? ''),
                'metadata' => $data
            ]
        );

        $aptitudeTestPayment->transactions()->create([
            'type' => 'Aptitude test fee payment',
            'status' => $data['status'] ?? 'failed',
            'amount' => $data['amount'] ?? 0,
            'currency_code' => $data['currency'] ?? 'NGN',
            'is_reconciled' => false,
        ]);

        return [
            'status' => $data['status'] ?? 'failed',
            'payment_model' => $aptitudeTestPayment,
            'application' => $application,
        ];
    }
}
