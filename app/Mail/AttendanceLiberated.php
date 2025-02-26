<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceLiberated extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $eventTitle;
    public $eventLink;

    /**
     * Criar uma nova instância do Mailable.
     */
    public function __construct($event, $user)
    {
    $this->event = $event;
    $this->user = $user;
    $this->eventTitle = $event->title;
    $this->eventLink = url("/dashboard?event_id={$event->id}&user_id={$user->id}");
    }


    /**
     * Criar a mensagem do e-mail.
     */
    public function build()
    {
        return $this->subject('Lista de Presença Liberada!')
                    ->view('emails.attendance_liberated')
                    ->with([
                        'eventTitle' => $this->eventTitle,
                        'eventLink' => $this->eventLink,
                        'user' => $this->user // Passando o usuário para o Blade
                    ]);
    }
}
