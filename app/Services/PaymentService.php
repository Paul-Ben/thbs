<?php

namespace App\Services;

use App\Support\PaymentTypeResolver;
use App\Services\NotificationService;
use App\Constants\PaymentStatus;
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

    public function initializePayment(array $data, string $paymentType, array $paymentConfig): array
    {
        $paymentHandler = PaymentTypeResolver::resolve($paymentType);

        $paymentData = $paymentHandler->buildPaymentData($data);

        $flutterwaveKey = config('services.flutterwave.secret_key');
        if (empty($flutterwaveKey)) {
            throw new Exception('Flutterwave secret key is missing.');
        }

        $baseUrl = config('services.flutterwave.base_url');
        $txRef = 'THBS-' . strtoupper(Str::random(8)) . '-' . time();

        $payload = [
            'tx_ref' => $txRef,
            'amount' => $paymentData['amount'],
            'currency' => 'NGN',
            'redirect_url' => route('payment.callback'),
            'customer' => [
                'email' => $data['email'],
                'name' => $data['surname'] . ' ' . ($data['othernames'] ?? ''),
                'phone_number' => $data['phone'] ?? 'N/A'
            ],
            'customizations' => [
                'title' => $paymentData['title'],
                'description' => $paymentData['description']
            ],
            'meta' => $paymentData['meta']
        ];

        $endpoint = rtrim($baseUrl, '/') . '/v3/payments';
        $response = Http::withToken($flutterwaveKey)->post($endpoint, $payload);

        if ($response->failed()) {
            throw new Exception('Flutterwave initialization failed: ' . $response->body());
        }

        $responseData = $response->json();

        return [
            'status' => $responseData['status'] ?? PaymentStatus::FAILED,
            'link' => $responseData['data']['link'] ?? null,
            'tx_ref' => $txRef
        ];
    }

    public function verifyAndLogPayment(string $transactionId): array
    {
        $baseUrl = config('services.flutterwave.base_url');
        $verifyUrl = rtrim($baseUrl, '/') . "/v3/transactions/{$transactionId}/verify";

        $response = Http::withToken(config('services.flutterwave.secret_key'))->get($verifyUrl);
        $json = $response->json();

        if (!in_array($json['status'], ['success', 'successful'])) {
            Log::error('Payment verification failed', [
                'transaction_id' => $transactionId,
                'received_status' => $json['status'] ?? 'null',
                'full_response' => $json
            ]);
            throw new Exception('Payment verification failed');
        }

        $data = $json['data'] ?? [];
        $txRef = $data['tx_ref'] ?? null;

        if (!$txRef) {
            throw new Exception('Verification response missing tx_ref');
        }

        $meta = $data['meta'] ?? [];
        $paymentType = $meta['payment_type'];

        $paymentHandler = PaymentTypeResolver::resolve($paymentType);

        $paymentResult = DB::transaction(
            fn() =>
            $paymentHandler->persistVerifiedPayment($data, $txRef, $meta)
        );

        if ($paymentResult['status'] === PaymentStatus::SUCCESSFUL) {
            $this->notificationService->sendPaymentSuccessfulNotification($paymentResult['payment_model']);
        }

        return [
            'status' => $paymentResult['status'],
            'payment_type' => $paymentType,
            'reference' => $txRef,
            'payment_model' => $paymentResult['payment_model']
        ];
    }
}
