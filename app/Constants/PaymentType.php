<?php

namespace App\Constants;

class PaymentType
{
    public const APPLICATION_FEE = 'application_fee';
    public const APTITUDE_TEST_FEE = 'aptitude_test_fee';

    public const ALL = [
        self::APPLICATION_FEE,
        self::APTITUDE_TEST_FEE,
    ];
}
