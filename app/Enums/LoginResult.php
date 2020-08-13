<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

final class LoginResult extends Enum
{
    private const SUCCESS = 'success';
    private const FAILED_PASSWORD = 'failed:password';
    private const FAILED_UNKOWN_USER = 'failed:unknown-user';
    private const FAILED_CHALLENGE = 'failed:password';
}
