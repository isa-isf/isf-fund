<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static \App\Enums\LoginResult SUCCESS()
 * @method static \App\Enums\LoginResult CHALLENGING()
 * @method static \App\Enums\LoginResult FAILED_PASSWORD()
 * @method static \App\Enums\LoginResult FAILED_UNKNOWN_USER()
 * @method static \App\Enums\LoginResult FAILED_CHALLENGE()
 */
final class LoginResult extends Enum
{
    private const SUCCESS = 'success';
    private const CHALLENGING = 'challenging';
    private const FAILED_PASSWORD = 'failed:password';
    private const FAILED_UNKNOWN_USER = 'failed:unknown-user';
    private const FAILED_CHALLENGE = 'failed:password';
}
