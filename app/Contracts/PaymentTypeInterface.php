<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentTypeInterface
{
    public function validate(Request $request): void;

    public function buildPaymentData(array $data): array;

    public function handleSuccess(array $paymentResult);

    public function persistVerifiedPayment(array $data, string $txRef, array $meta): array;
}
