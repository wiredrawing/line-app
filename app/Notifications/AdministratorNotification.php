<?php

namespace App\Notifications;

use App\Models\Administrator;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdministratorNotification extends Notification
{
    use Queueable;

    private $administrator = null;
    private $token = null;

    /**
     * Create a new notification instance.
     *
     * @param Administrator $administrator
     * @param string $token
     */
    public function __construct(Administrator $administrator, string $token)
    {
        // print_r($administrator->toArray());
        $this->administrator = $administrator;
        $this->token = $token;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $password_reset_form_url = route("admin.password.reset", [
            "token" => $this->token,
            "email" => $this->administrator->email,
        ]);
        return (new MailMessage)
            ->line('------> customize -----> The introduction to the notification.')
            ->action('Notification Action', $password_reset_form_url)
            ->line('Thank you for using our application!');
    }
}
