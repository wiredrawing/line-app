<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LineMemberMessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)->view([
            // HTMLメールが受信できる場合はこちらのテンプレートが使用される
            "templates.email.line_member_message.html",
            // HTMLメールを受信できない端末の場合はこちらの平文テンプレートが使用される
            "templates.email.line_member_message.plain",
        ], [
            "notifiable" => $notifiable,
        ])
            ->subject("LINEログインが成功しました.")
            ->cc("akifumi.senbiki.1209@ymobile.ne.jp")
            ->from("noreply@wire-drawing.co.jp");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
