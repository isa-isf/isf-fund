<?php

namespace App\Channels;

use App\Models\User;
use Aws\Sns\SnsClient;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

final class AwsSnsChannel
{
    private $client;

    public function __construct(SnsClient $client)
    {
        $this->client = $client;
    }

    /**
     *
     * @param \Illuminate\Notifications\Notifiable|\App\Models\User $notifable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('sms', $notification);
        $content = $notification->toSms($notifiable);

        if (empty($to)) {
            throw new \RuntimeException('No recipient');
        }
        if (empty($content)) {
            throw new \RuntimeException('No content');
        }

        if (app()->isLocal()) {
            Log::info("[SMS: {$to}] {$content}");
            return;
        }

        return $this->client->publish([
            'Message' => $content,
            'PhoneNumber' => $to,
        ]);
    }
}
