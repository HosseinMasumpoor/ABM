<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use URL;

class EmailChangeNotification extends Notification
{
    use Queueable;

    public $userId;
    /**
     * Create a new notification instance.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', $this->verifyRoute($notifiable))
        //     ->line('Thank you for using our application!');

        return (new MailMessage)
            ->view('emails.verify-email', ['url' => $this->verifyRoute($notifiable)]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function verifyRoute(AnonymousNotifiable $notifiable)
    {

        // Make verify URL
        $url = URL::temporarySignedRoute('user.email.change.verify', 60 * 60, [
            'user' => $this->userId,
            'email' => $notifiable->routes['mail']
        ]);

        // Change domain to SPA domain
        $apiRoute = route('user.email.change.verify');
        $SPARoute = str_replace($apiRoute, env('SPA_EMAIL_VERIFICATION_URL'), $url);

        return $SPARoute;
    }
}
