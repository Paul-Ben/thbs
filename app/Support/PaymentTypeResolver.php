<?php

namespace App\Support;

use App\Contracts\PaymentTypeInterface;
use App\Payments\ApplicationFeePayment;
use App\Payments\AptitudeTestPayment;
use App\Payments\SchoolFeePayment;
use InvalidArgumentException;

class PaymentTypeResolver
{
    protected static array $map = [
        'application_fee' => ApplicationFeePayment::class,
        'aptitude_test_fee' => AptitudeTestPayment::class,
        'school_fee' => SchoolFeePayment::class,
    ];

    public static function resolve(string $paymentType): PaymentTypeInterface
    {
        if (!isset(self::$map[$paymentType])) {
            throw new InvalidArgumentException("Unsupported payment type: {$paymentType}");
        }

        return app(self::$map[$paymentType]);
    }
}
