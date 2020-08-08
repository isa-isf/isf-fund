<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class UserPasswordSetup extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \App\Models\User $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user)
    {
        $expires = now()->addHours(12);

        return (new MailMessage)
            ->subject('請立即設定密碼')
            ->line('已建立帳號，請立即使用下列連結設定密碼')
            ->action(
                '設定密碼',
                URL::temporarySignedRoute(
                    'password-setup',
                    now()->addHours(12),
                    ['user' => $user->hashid],
                ),
            )
            ->line('本連結有效期限至 ' . $expires->toDateTimeString());
    }
}
