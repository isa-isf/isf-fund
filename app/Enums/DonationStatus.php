<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static \App\Enums\DonationSTATUS CREATED()
 * @method static \App\Enums\DonationSTATUS ACTIVE()
 * @method static \App\Enums\DonationSTATUS INACTIVE()
 */
class DonationStatus extends Enum
{
    private const CREATED = 'created';
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';
}
