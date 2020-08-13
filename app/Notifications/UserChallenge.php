<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class UserChallenge extends Notification
{
    use Queueable;

    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['sms'];
    }

    public function toSms($notifiable)
    {
        return '[ISF] 簡訊驗證碼 ' . $this->code . '，15 分鐘內有效';
    }
}
