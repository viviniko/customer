<?php

namespace Viviniko\Customer\Notifications;

use Viviniko\Mail\Contracts\MailService;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return app(MailService::class)->make('user.reset.password', array_merge($notifiable->toArray(), [
            'username' => $notifiable->name,
            'token' => $this->token,
            'url' => route('password.reset', $this->token),
        ]))->to($notifiable->email);
    }

}
