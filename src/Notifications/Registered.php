<?php

namespace Viviniko\Customer\Notifications;

use Viviniko\Mail\Contracts\MailService;
use Illuminate\Notifications\Notification;

class Registered extends Notification {

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return app(MailService::class)->make('	user.register', array_merge($notifiable->toArray(), [
            'login_url' => route('login'),
        ]))->to($notifiable->email);
    }

}
