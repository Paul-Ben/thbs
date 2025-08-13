<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Application;
use App\Models\ApplicationSession;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function initializePayment(array $data): array
    {
        if (empty($data['email']) || empty($data['surname'])) {
            Log::error('Invalid payment data', ['data' => $data]);
            throw new Exception('Invalid payment data');
        }

        $flutterwaveKey = config('services.flutterwave.secret_key');
        if (empty($flutterwaveKey)) {
            Log::error('Flutterwave secret key is missing in configuration.');
            throw new Exception('Flutterwave initialization failed.');
        }

        $baseUrl = config('services.flutterwave.base_url');
        $txRef = 'THBS-' . strtoupper(Str::random(8)) . '-' . time();

        $payload = [
            'tx_ref' => $txRef,
            'amount' => 5000.00,
            'currency' => 'NGN',
            'redirect_url' => route('payment.callback'),
            'customer' => [
                'email' => $data['email'],
                'name' => $data['surname'] . ' ' . ($data['othernames'] ?? ''),
                'phone_number' => $data['phone'] ?? 'N/A'
            ],
            'customizations' => [
                'title' => 'THBS Application Fee',
                'description' => 'Application fee payment for ' . $data['surname'] . ' ' . ($data['othernames'] ?? '')
            ],
            'meta' => [
                'surname' => $data['surname'],
                'othernames' => $data['othernames'] ?? '',
                'email' => $data['email'],
                'phone' => $data['phone'] ?? 'N/A'
            ]
        ];

        $endpoint = rtrim($baseUrl, '/') . '/v3/payments';
        $response = Http::withToken($flutterwaveKey)->post($endpoint, $payload);

        if ($response->failed()) {
            throw new Exception('Flutterwave initialization failed: ' . $response->body());
        }

        $responseData = $response->json();
        Log::info('Flutterwave payment initialized successfully', [
            'tx_ref' => $txRef,
            'status' => $responseData['status'] ?? 'unknown',
            'response_structure' => $responseData
        ]);

        return [
            'status' => $responseData['status'] ?? 'unknown',
            'link' => $responseData['data']['link'] ?? null,
            'tx_ref' => $txRef
        ];
    }

    public function verifyAndLogPayment(string $transactionId): Payment
    {

        $baseUrl = config('services.flutterwave.base_url');
        $verifyUrl = rtrim($baseUrl, '/') . "/v3/transactions/{$transactionId}/verify";

        $response = Http::withToken(config('services.flutterwave.secret_key'))->get($verifyUrl);
        $json = $response->json();

        if ($response->failed() || ($json['status'] ?? null) !== 'success') {
            throw new Exception('Payment verification failed');
        }

        $data = $json['data'] ?? [];
        $txRef = $data['tx_ref'] ?? null;

        if (!$txRef) {
            Log::error('Missing tx_ref in verification response', ['data' => $data]);
            throw new Exception('Verification response missing tx_ref');
        }

        $meta = $data['meta'] ?? [];

        $application_session = ApplicationSession::where('is_current', true)->first();

        $payment = DB::transaction(function () use ($data, $txRef, $meta, $application_session) {
            $application = Application::create([
                'applicant_surname' => $meta['surname'],
                'applicant_othernames' => $meta['othernames'],
                'email' => $meta['email'],
                'phone' => $meta['phone'],
                'payment_reference' => $txRef,
                'application_session_id' => $application_session->id,
                'is_filled' => 0,
            ]);

            return Payment::updateOrCreate(
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
        });

        if ($payment->status === 'successful') {
            $this->notificationService->sendPaymentSuccessfulNotification($payment);
        }

        return $payment;

    }

}