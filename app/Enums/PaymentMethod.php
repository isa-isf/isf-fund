<?php

declare(strict_types=1);

namespace App\Enums;


use MyCLabs\Enum\Enum;

/**
 * @method static \App\Enums\PaymentMethod CreditCard()
 * @method static \App\Enums\PaymentMethod WebATM()
 * @method static \App\Enums\PaymentMethod ATM()
 * @method static \App\Enums\PaymentMethod CVS()
 */
final class PaymentMethod extends Enum
{
    private const CreditCard = 'CreditCard';
    private const WebATM = 'WebATM';
    private const ATM = 'ATM';
    private const CVS = 'CVS';
}
