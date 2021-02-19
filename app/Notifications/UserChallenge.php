<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
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
        return ['nexmo'];
    }

    public function toNexmo($notifiable)
    {
        $host = parse_url(url(''), PHP_URL_HOST);

        return (new NexmoMessage())
            ->content($this->code . ' 是你的 ' . config('app.name') . ' 驗證碼，15 分鐘內有效。' . "\n\n@{$host} #{$this->code}")
            ->unicode();
    }
}
