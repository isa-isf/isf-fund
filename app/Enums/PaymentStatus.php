<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class PaymentStatus
 * @package App\Enums
 * @method static \App\Enums\PaymentStatus CREATED()
 * @method static \App\Enums\PaymentStatus PAID()
 * @method static \App\Enums\PaymentStatus FAILED()
 */
class PaymentStatus extends Enum
{
    private const CREATED = 100;
    private const PAID = 101;
    private const FAILED = 999;
}
