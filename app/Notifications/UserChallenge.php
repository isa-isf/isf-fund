<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class UserChallenge extends Notification implements ShouldQueue
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
