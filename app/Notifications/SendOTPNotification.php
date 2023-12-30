<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTPNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $code)
    {
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
            ->view('emails.OTPCode', ['code' => $this->code]);

        // ->line('کد ورود شما :')
        // ->line($this->code)
        // // ->action('Notification Action', url('/'))
        // ->line('درخواستی بابت ورود به سیستم از سوی شما دریافت کرده‌ایم. امنیت حساب کاربری شما برایمان اهمیت دارد پس درصورتی‌که شما این درخواست را نداده‌اید، این ایمیل را نادیده بگیرید.
        // از همراهی‌تان خوشحالیم.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code
        ];
    }
}
