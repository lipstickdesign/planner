<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNo extends BaseResetPassword
{
    /**
     * Norsk, Vivu/FLIK-profilert e-post for nullstilling av passord.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expire = config(
            'auth.passwords.'.config('auth.defaults.passwords').'.expire',
            60
        );

        return (new MailMessage)
            ->subject('Tilbakestill passordet ditt – Vivu Planner')
            ->view('emails.reset-password', [
                'url' => $url,
                'expire' => $expire,
                'name' => $notifiable->name ?? null,
            ]);
    }
}
