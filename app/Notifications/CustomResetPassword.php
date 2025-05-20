<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Reset Password - ' . now()->format('Y-m-d H:i:s')) // ⬅ subjek unik
        ->greeting('Halo!')
        ->line('Kami menerima permintaan untuk mengatur ulang password Anda.')
        ->action('Reset Password', url('/reset-password?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset()))
        ->line('Jika Anda tidak meminta ini, abaikan saja email ini.');
    }
}
