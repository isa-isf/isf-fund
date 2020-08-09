<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class DonationTypes
 * @package App\Enums
 * @method static \App\Enums\DonationType MONTHLY()
 * @method static \App\Enums\DonationType ONE_TIME()
 */
class DonationType extends Enum
{
    private const MONTHLY = 'monthly';
    private const ONE_TIME = 'one-time';
}
