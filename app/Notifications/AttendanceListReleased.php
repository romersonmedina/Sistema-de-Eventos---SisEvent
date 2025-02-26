<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceListReleased extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toMail($notifiable){

        return (new MailMessage)
                    ->subject('Lista de Presença Liberada')
                    ->greeting('Olá!')
                    ->line('A lista de presença para o evento "' . $notifiable->event->title . '" foi liberada.')
                    ->action('Marcar Presença', url('/events/' . $notifiable->event->id . '/mark-attendance'))
                    ->line('Clique no link acima para assinar a lista e marcar sua presença no evento.');
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
}
